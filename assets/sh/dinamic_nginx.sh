#!/bin/bash

# Parámetros: nombre del subdominio, IP del contenedor, puerto
SUBDOMINIO=$1
IP_CONTENEDOR=$2
PUERTO=80

# Ruta a los archivos de configuración
CONFIG_PATH="/etc/nginx/sites-available"
ENABLED_PATH="/etc/nginx/sites-enabled"

# Crea el archivo de configuración basado en la plantilla
sed -e "s/{{SUBDOMINIO}}/$SUBDOMINIO/g" \
    -e "s/{{IP_CONTENEDOR}}/$IP_CONTENEDOR/g" \
    -e "s/{{PUERTO}}/$PUERTO/g" \
    template.conf > $CONFIG_PATH/$SUBDOMINIO.conf

# Habilita el sitio en NGINX
ln -s $CONFIG_PATH/$SUBDOMINIO.conf $ENABLED_PATH/

# Recarga NGINX
nginx -s reload

echo "Subdominio $SUBDOMINIO configurado y habilitado en NGINX."
