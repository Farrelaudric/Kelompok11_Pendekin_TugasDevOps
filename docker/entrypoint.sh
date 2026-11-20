#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "[Pendekin] Menunggu koneksi PostgreSQL (${DB_HOST:-db}:${DB_PORT:-5432})..."
until php -r "new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '5432') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 1
done

echo "[Pendekin] Menunggu koneksi Redis (${REDIS_HOST:-redis}:${REDIS_PORT:-6379})..."
until php -r "\$r = new Redis(); \$r->connect(getenv('REDIS_HOST') ?: 'redis', (int)(getenv('REDIS_PORT') ?: 6379)); \$r->ping();" 2>/dev/null; do
    sleep 1
done

echo "[Pendekin] Menjalankan migrasi database..."
php artisan migrate --force

php artisan optimize:clear
if [ "${APP_ENV}" = "production" ]; then
    php artisan optimize
fi

echo "[Pendekin] Menjalankan PHP-FPM dan Nginx..."
php-fpm -D
exec nginx -g "daemon off;"