FROM php:8.2-fpm as php

RUN docker-php-ext-install pdo_mysql sockets exif mysqli
RUN docker-php-ext-enable mysqli

RUN apt-get update && apt-get install -y supervisor libpng-dev libzip-dev nodejs npm

RUN docker-php-ext-install gd zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
COPY . .
# COPY ./.docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

ENTRYPOINT ["/var/www/entrypoint.sh"]
