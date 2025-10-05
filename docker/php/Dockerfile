FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git curl zip unzip icu-dev oniguruma-dev libzip-dev mariadb-connector-c-dev \
    && docker-php-ext-install intl pdo pdo_mysql mbstring zip opcache \
    && apk del --no-cache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN printf "memory_limit=512M\nupload_max_filesize=120M\npost_max_size=120M\nmax_execution_time=300\n" > /usr/local/etc/php/conf.d/laravel.ini

WORKDIR /var/www/html