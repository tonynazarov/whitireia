FROM php:8.2.6-fpm-alpine3.18

RUN apk add --update linux-headers \
    && apk add libpq \
    && apk add libzip-dev \
    && apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && apk add icu-dev \
    && apk add ghostscript \
    && apk add oniguruma-dev \
    && apk add --no-cache imagemagick && apk add --no-cache imagemagick-dev \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk add postgresql-dev \
    && apk add git \
    && pecl install xdebug


RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd \
        --with-jpeg=/usr/include/ \
        --with-freetype=/usr/include/ \
    && docker-php-ext-install pdo pdo_pgsql pgsql bcmath mbstring gd zip intl \
    && docker-php-ext-enable xdebug



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer