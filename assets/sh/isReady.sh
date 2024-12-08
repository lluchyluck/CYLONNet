#!/bin/bash

# Verificación de argumentos
if [ $# -ne 1 ]; then
    echo "Uso: $0 <archivo_tar_gz>"
    exit 1
fi

TAR_FILE="$1"

# Nombre de la imagen y del contenedor
IMAGE_NAME=$(basename "$TAR_FILE" .tar.gz)
CONTAINER_NAME="${IMAGE_NAME}_container"

# Verificar si el contenedor ya está en ejecución
if docker ps -q -f name="$CONTAINER_NAME" | grep -q .; then
    echo "El contenedor $CONTAINER_NAME ya está iniciado."
    exit 1
fi

echo "El archivo y el contenedor están listos para procesarse."
exit 0
