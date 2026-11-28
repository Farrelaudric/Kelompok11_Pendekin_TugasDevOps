# Stage 1: Base PHP 8.4 + ekstensi Laravel, MySQL, dan Redis
FROM php:8.4-cli AS base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN sh -c 'for i in 1 2 3; do apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    default-mysql-client && break || sleep 3; done' \
    && rm -rf /var/lib/apt/lists/* \
    && sh -c 'for i in 1 2 3; do install-php-extensions pdo_mysql zip mbstring redis && break || sleep 3; done'

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Stage 2: Install dependencies & jalankan automated test
FROM base AS tester

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts

COPY . .
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    && php artisan package:discover --ansi
RUN cp -n .env.example .env || true
RUN php artisan key:generate --force
RUN php artisan test

# Stage 3: Production / runtime image
FROM base AS runtime

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php \
    && php artisan package:discover --ansi

RUN mkdir -p storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:clear && until php -r 'try { new PDO(\"mysql:host=\" . getenv(\"DB_HOST\") . \";port=\" . getenv(\"DB_PORT\"), getenv(\"DB_USERNAME\"), getenv(\"DB_PASSWORD\")); exit(0); } catch (Exception $e) { exit(1); }'; do sleep 2; done && until php artisan migrate --force; do sleep 2; done && php artisan serve --host=0.0.0.0 --port=8000"]
