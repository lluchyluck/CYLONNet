#!/bin/sh
#establece permisos para carpetas
chown -R www-data:www-data /var/www/html/assets/sh/labos
chmod -R 755 /var/www/html/assets/sh/labos

chown -R www-data:www-data /var/www/html/assets/images/missions
chmod -R 755 /var/www/html/assets/images/missions

chown -R www-data:www-data /var/www/html/assets/images/profile 
chmod -R 755 /var/www/html/assets/images/profile

chown -R www-data:www-data /var/www/html/includes/src/uploads
chmod -R 755 /var/www/html/includes/src/uploads

# Ejecuta el proceso original del contenedor
exec docker-php-entrypoint php-fpm
