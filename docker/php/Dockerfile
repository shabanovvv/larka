ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-fpm-alpine

# Arguments
ARG WWWGROUP
ARG NODE_VERSION=20

# Install system dependencies
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    shadow \
    bash \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    linux-headers

# Install PHP extensions (убираем sockets из основного списка)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

# Устанавливаем sockets отдельно с дополнительными флагами
RUN docker-php-ext-install sockets

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN apk add --no-cache nodejs npm

# Create system user to run Composer and Artisan Commands
RUN groupmod -o -g ${WWWGROUP} www-data && \
    usermod -o -u ${WWWGROUP} -g www-data www-data

# Create PHP socket directory
RUN mkdir -p /var/run/php && chown www-data:www-data /var/run/php

WORKDIR /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

USER www-data

EXPOSE 9000

CMD ["php-fpm", "-R", "--nodaemonize"]
