FROM php:8.3-fpm-alpine

WORKDIR /var/www/laravel

RUN apk update && apk add \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    icu-dev && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install pdo && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install intl && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip && \
    apk cache clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*