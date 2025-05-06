# cylonnet/Dockerfile
FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y jq && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

#entrypoint.sh para permisos de carpetas
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]