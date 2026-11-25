# ==============================================================================
# STAGE 1: Build Frontend Assets (Vite 8 + Tailwind CSS v4)
# ==============================================================================
FROM node:22-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY vite.config.js ./
COPY resources ./resources
COPY app ./app
RUN npm run build

# ==============================================================================
# STAGE 2: Base PHP 8.3 + Nginx + PostgreSQL + phpredis Runtime (Debian Bookworm)
# ==============================================================================
FROM php:8.4-fpm-bookworm AS base
WORKDIR /var/www/html

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    curl \
    && rm -rf /var/lib/apt/lists/* \
    && install-php-extensions \
        pdo_pgsql \
        pgsql \
        intl \
        zip \
        mbstring \
        pcntl \
        bcmath \
        opcache

RUN curl -fsSL https://codeload.github.com/phpredis/phpredis/tar.gz/6.2.0 -o /tmp/redis.tar.gz \
    && mkdir -p /tmp/redis \
    && tar -xzf /tmp/redis.tar.gz -C /tmp/redis --strip-components=1 \
    && cd /tmp/redis \
    && phpize \
    && ./configure \
    && make -j"$(nproc)" \
    && make install \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/redis /tmp/redis.tar.gz
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ==============================================================================
# STAGE 3: Install Composer Dev Dependencies & Execute Automated Test Suite
# ==============================================================================
FROM base AS vendor-dev
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts

FROM vendor-dev AS test
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN cp .env.example .env \
    && php artisan key:generate --force \
    && mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && export DB_CONNECTION=sqlite \
       DB_DATABASE=/var/www/html/database/database.sqlite \
       CACHE_STORE=array \
       SESSION_DRIVER=array \
       QUEUE_CONNECTION=sync \
       && php artisan migrate --force \
       && php artisan test \
    && touch /tmp/.tests-passed

# ==============================================================================
# STAGE 4: Production Dependencies & Final Runtime Image
# ==============================================================================
FROM base AS vendor-prod
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

FROM base AS production
COPY --from=vendor-prod /var/www/html/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build
COPY --from=test /tmp/.tests-passed /tmp/.tests-passed

RUN rm -f /etc/nginx/sites-enabled/default
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-pendekin.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /run/nginx storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=15s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]