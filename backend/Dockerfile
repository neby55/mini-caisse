FROM php:8.1-apache

RUN apt-get update && apt-get install -y libpq-dev libxml2-dev libonig-dev git zip unzip && docker-php-ext-install dom xml mbstring pgsql pdo pdo_pgsql

COPY . /var/www/html

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --no-dev

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite
