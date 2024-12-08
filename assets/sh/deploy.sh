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
if [ $# -ne 2 ]; then
    echo "Uso: $0 <archivo_tar_gz> <bandera>"
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
FLAG="$2"

if [ ! -f "$TAR_FILE" ]; then
    echo "Archivo $TAR_FILE no encontrado."
    exit 1
fi

# Crear archivo de bandera temporal
FLAG_FILE="/tmp/flag.txt"
echo "$FLAG" > "$FLAG_FILE"

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
if docker load -i "$TAR_FILE" > /dev/null; then
    if docker run -d --name "$CONTAINER_NAME" -v "$FLAG_FILE:/flag/flag.txt" "$IMAGE_NAME" > /dev/null; then
        IP_ADDRESS=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' "$CONTAINER_NAME")
        echo -e "\nMáquina desplegada con éxito. Dirección IP: $IP_ADDRESS"

        # Programar el script exit.sh para que se ejecute después de 30 minutos usando 'at'
        echo "$(pwd)/sh/exit.sh $TAR_FILE" | at "now + 30 minutes"
        echo "[+] El laboratorio se eliminará en 30 minutos automáticamente."
    else
        echo "Error al iniciar el contenedor."
        exit 1
    fi
else
    echo "Error al cargar la imagen Docker desde $TAR_FILE."
    exit 1
fi

# Limpiar archivo temporal al terminar
trap "rm -f $FLAG_FILE" EXIT
exit 0