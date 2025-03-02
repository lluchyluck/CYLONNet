#!/bin/bash

# Función para detener y eliminar contenedor e imagen
detener_y_eliminar_contenedor() {
    IMAGE_NAME="${TAR_FILE%.tar.gz}"
    IMAGE_NAME="${IMAGE_NAME##*/}"
    CONTAINER_NAME="${IMAGE_NAME}_container"

    # Detener y eliminar el contenedor si está en ejecución o detenido
    if docker ps -a -q -f name="$CONTAINER_NAME" | grep -q .; then
        echo "Deteniendo y eliminando contenedor existente: $CONTAINER_NAME..."
        docker rm -f "$CONTAINER_NAME" > /dev/null
    fi

    # Eliminar la imagen si existe
    if docker images -q "$IMAGE_NAME" | grep -q .; then
        echo "Eliminando imagen existente: $IMAGE_NAME..."
        docker rmi "$IMAGE_NAME" > /dev/null
    fi
}

# Verificación de argumentos
if [ $# -ne 3 ]; then
    echo "Uso: $0 <archivo_tar_gz> <bandera_usuario> <bandera_root>"
    exit 1
fi

# Verificar e instalar Docker si no está presente
if ! command -v docker &> /dev/null; then
    echo "Docker no está instalado. Instalando Docker..."
    sudo apt update && sudo apt install docker.io -y
    echo "Habilitando y arrancando el servicio Docker..."
    sudo systemctl enable --now docker
    if ! systemctl is-active --quiet docker; then
        echo "Error al iniciar Docker. Por favor, verifique la instalación manualmente."
        exit 1
    else
        echo "Docker instalado y activo."
    fi
fi

# Verificar e instalar 'at' si no está presente
if ! command -v at &> /dev/null; then
    echo "'at' no está instalado. Instalando 'at'..."
    sudo apt install at -y
    sudo systemctl enable --now atd
fi

TAR_FILE="$1"
uFLAG="$2"
rFLAG="$3"

if [ ! -f "$TAR_FILE" ]; then
    echo "Archivo $TAR_FILE no encontrado."
    exit 1
fi

# Crear archivos de bandera temporales
uFLAG_FILE="/tmp/uflag.txt"
rFLAG_FILE="/tmp/rflag.txt"
echo "$uFLAG" > "$uFLAG_FILE"
echo "$rFLAG" > "$rFLAG_FILE"

# Nombre de la imagen y del contenedor
IMAGE_NAME=$(basename "$TAR_FILE" .tar.gz)
CONTAINER_NAME="${IMAGE_NAME}_container"

# Verificar si el contenedor ya está en ejecución
if docker ps -q -f name="$CONTAINER_NAME" | grep -q .; then
    echo "El contenedor $CONTAINER_NAME ya está iniciado."
    exit 1
fi

detener_y_eliminar_contenedor

# Cargar imagen directamente desde el archivo .tar.gz
LOAD_OUTPUT=$(docker load -i "$TAR_FILE")
if [ $? -eq 0 ]; then
    IMAGE_NAME=$(echo "$LOAD_OUTPUT" | grep "Loaded image:" | awk -F ': ' '{print $2}')
    if [[ "$IMAGE_NAME" != *:* ]]; then
        IMAGE_NAME="$IMAGE_NAME:latest"
    fi
    if docker run -d --name "$CONTAINER_NAME" \
        -v "$uFLAG_FILE:/flag/uflag.txt" \
        -v "$rFLAG_FILE:/root/flag/rflag.txt" \
        "$IMAGE_NAME" > /dev/null; then
        
        IP_ADDRESS=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' "$CONTAINER_NAME")
        echo -e "\nMáquina desplegada con éxito. Dirección IP: $IP_ADDRESS"

        # Programar la eliminación del contenedor en 30 minutos
        echo "$(pwd)/sh/exit.sh $TAR_FILE" | at "now + 30 minutes"
        echo "[+] El laboratorio se eliminará en 30 minutos automáticamente."
    else
        ERROR_OUTPUT=$(docker run -d --name "$CONTAINER_NAME" \
            -v "$uFLAG_FILE:/flag/uflag.txt" \
            -v "$rFLAG_FILE:/root/flag/rflag.txt" \
            "$IMAGE_NAME" 2>&1)
        echo "Error al iniciar el contenedor: $ERROR_OUTPUT"
        exit 1
    fi
else
    echo "Error al cargar la imagen Docker desde $TAR_FILE."
    exit 1
fi

# Limpiar archivos temporales al terminar
trap "rm -f $uFLAG_FILE $rFLAG_FILE" EXIT
exit 0
