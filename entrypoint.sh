#!/bin/sh
set -e

# Fix permissions for storage and cache
chown -R www-data:www-data storage bootstrap/cache

php artisan migrate --force
php artisan storage:link

# Ensure APP_KEY is set (Render env vars may not be available during config:cache)
if [ -z "${APP_KEY}" ]; then
    APP_KEY=$(php artisan key:generate --show --force)
    export APP_KEY
fi
echo "APP_KEY is set"

php artisan view:cache
php artisan event:cache

# Test Laravel boot (will print fatal errors to stdout)
php artisan about --quiet 2>&1 || echo "Laravel boot test: FAILED"

nginx
php-fpm
