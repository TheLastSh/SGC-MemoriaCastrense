#!/bin/sh
set -e

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

nginx
php-fpm
