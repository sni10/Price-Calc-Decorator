#!/bin/bash
set -e

echo "Waiting for MySQL..."
until mysqladmin ping -h"${DB_HOST}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --skip-ssl --silent 2>/dev/null; do
    sleep 2
done
echo "MySQL is ready!"

echo "Running migrations..."
php artisan migrate --force

if [ "$APP_ENV" = "test" ]; then
    echo "Seeding database (test environment)..."
    php artisan db:seed --force
fi

echo "Starting php-fpm..."
exec php-fpm
