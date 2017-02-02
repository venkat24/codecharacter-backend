FROM php:7-fpm

WORKDIR /var/www/

RUN docker-php-ext-install pdo_mysql \
        && apt-get -y update && apt-get install -y \
        curl \
        git
