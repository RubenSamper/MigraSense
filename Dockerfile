FROM php:8.2-fpm

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN apt-get update && apt-get install -y \
        zip unzip git curl libzip-dev default-mysql-client \
    && docker-php-ext-install pdo_mysql zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php -S 0.0.0.0:${PORT:-8000} -t public
