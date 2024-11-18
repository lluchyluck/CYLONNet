#!/bin/bash

# Función para detener y eliminar contenedor e imagen
detener_y_eliminar_contenedor() {
    IMAGE_NAME="${TAR_FILE%.tar}"
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
if [ $# -ne 1 ]; then
    echo "Uso: $0 <archivo_tar>"
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
if [ ! -f "$TAR_FILE" ]; then
    echo "Archivo $TAR_FILE no encontrado."
    exit 1
fi

echo "Desplegando la máquina vulnerable, espere un momento..."
detener_y_eliminar_contenedor

# Cargar imagen desde el archivo tar
if docker load -i "$TAR_FILE" > /dev/null; then
    IMAGE_NAME=$(basename "$TAR_FILE" .tar)
    CONTAINER_NAME="${IMAGE_NAME}_container"

    echo "Iniciando el contenedor $CONTAINER_NAME..."
    if docker run -d --name "$CONTAINER_NAME" "$IMAGE_NAME" > /dev/null; then
        IP_ADDRESS=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' "$CONTAINER_NAME")
        echo -e "\nMáquina desplegada con éxito. Dirección IP: \e[1;97m$IP_ADDRESS\e[0m"

       # Programar el script exit.sh para que se ejecute después de 10 segundos usando 'at'
        echo "$(pwd)/sh/exit.sh $TAR_FILE" | at "now + 30 minute"
        echo "[+]El laboratorio se eliminara en 30 minutos automaticamente."


    else
        echo "Error al iniciar el contenedor."
        exit 1
    fi
else
    echo "Error al cargar la imagen Docker desde $TAR_FILE."
    exit 1
fi
