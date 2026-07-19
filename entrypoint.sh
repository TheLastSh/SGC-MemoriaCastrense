#!/bin/sh
set -e

php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan view:cache
php artisan config:cache
php artisan route:cache

nginx
php-fpm
