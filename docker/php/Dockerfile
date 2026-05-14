FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl \
    && pecl install redis \
    && docker-php-ext-enable redis
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
