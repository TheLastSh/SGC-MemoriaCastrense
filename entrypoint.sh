#!/bin/sh
set -e

php artisan migrate --force
php artisan storage:link
php artisan view:cache
php artisan optimize

nginx
php-fpm
