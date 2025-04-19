# cylonnet/Dockerfile
FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /var/www/html