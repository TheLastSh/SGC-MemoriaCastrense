# Etapa 1: Build assets (Node)
FROM node:20-alpine AS node-build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Etapa 2: Dependencias PHP (Composer)
FROM composer:2 AS composer-build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Etapa 3: Imagen final
FROM php:8.2-fpm-alpine AS production

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

RUN apk add --no-cache nginx

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/overrides.ini

WORKDIR /var/www/html
COPY --from=node-build /app/public/build /var/www/html/public/build
COPY --from=composer-build /app/vendor /var/www/html/vendor
COPY . .

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

CMD ["/bin/sh", "/entrypoint.sh"]
