#!/usr/bin/env bash



# Ensure curl is installed
if ! command -v curl &> /dev/null; then
  echo "❌ curl no está instalado. Por favor, instálalo para continuar." >&2
  exit 1
fi

if [ $# -lt 1 ]; then
  echo "Uso: $0 <archivo_tar_gz>" >&2
  exit 1
fi

TAR_FILE="./../../assets/sh/labos$1"
API_URL="http://orquestador:8000"


# Deriva el nombre del lab y del contenedor
if [ ! -f "$TAR_FILE" ]; then
  echo "❌ El archivo ${TAR_FILE} no existe." >&2
  exit 1
fi

LAB_NAME=$(basename "$TAR_FILE" .tar.gz)

echo "[+] Comprobando el estado de: ${LAB_NAME}"

# Llamada al endpoint /estado
HTTP_CODE=$(curl -v -o /dev/null -w "%{http_code}" \
  -X POST "${API_URL}/estado" \
  -H "Content-Type: application/json" \
  -d "{\"lab\":\"${LAB_NAME}\"}")

if [ "$HTTP_CODE" -eq 200 ]; then
  echo "[+] El contenedor ${LAB_NAME} está detenido o no existe."
  exit 0
else
  echo "[!] El contenedor ${LAB_NAME} está en ejecución."
  docker exec cylonnet_web_1 nginx -s reload > /dev/null 2>&1
  exit 1
fi
