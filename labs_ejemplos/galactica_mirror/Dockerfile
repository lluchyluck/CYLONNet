# Use la imagen oficial de PHP 8.1 con Apache para ARMv7 (armhf)
FROM arm32v7/php:8.1-apache

# Establecer el directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
        zip \
    # Instalar la extensión zip de PHP
    && docker-php-ext-install zip \
    # Limpiar caché de apt para reducir el tamaño de la imagen
    && rm -rf /var/lib/apt/lists/*

# Copiar todos los archivos de la aplicación al directorio de Apache
COPY . /var/www/html/

# Ajustar permisos: el usuario www-data es el que ejecuta Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 750 /var/www/html

RUN a2enmod rewrite

# Exponer el puerto 80 para HTTP
EXPOSE 80

