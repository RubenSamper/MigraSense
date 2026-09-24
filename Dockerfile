FROM php:8.2-fpm

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY vendor /var/www/html/vendor

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip
