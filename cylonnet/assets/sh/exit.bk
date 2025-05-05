#!/bin/bash

# Verificar si se proporcionó un archivo tar como argumento
if [ $# -ne 1 ]; then
    echo "Uso: $0 <archivo_tar>"
    exit 1
fi

TAR_FILE="$1"


limpiar_contenedor_imagen() {
    IMAGE_NAME="${TAR_FILE%.tar}"
    IMAGE_NAME="${IMAGE_NAME##*/}"
    CONTAINER_NAME="${IMAGE_NAME}_container"

    # Detener y eliminar el contenedor si está en ejecución
    if docker ps -q -f name="$CONTAINER_NAME" | grep -q .; then
        echo "Deteniendo contenedor $CONTAINER_NAME..."
        docker stop "$CONTAINER_NAME" > /dev/null
    fi

    # Eliminar el contenedor si existe
    if docker ps -a -q -f name="$CONTAINER_NAME" | grep -q .; then
        echo "Eliminando contenedor $CONTAINER_NAME..."
        docker rm -f "$CONTAINER_NAME" > /dev/null
    fi

    # Eliminar la imagen si existe
    if docker images -q "$IMAGE_NAME" | grep -q .; then
        echo "Eliminando imagen $IMAGE_NAME..."
        docker rmi "$IMAGE_NAME" > /dev/null
    fi
}

# Llamar a la función de limpieza
limpiar_contenedor_imagen
