FROM php:8.4-cli

# Basis dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libonig-dev libxml2-dev zip curl

# PHP-extensies
RUN docker-php-ext-install pdo pdo_mysql intl zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/common_tools