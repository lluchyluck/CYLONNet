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

echo $TAR_FILE

# Deriva el nombre del lab y del contenedor
if [ ! -f "$TAR_FILE" ]; then
  echo "❌ El archivo ${TAR_FILE} no existe." >&2
  exit 1
fi

LAB_NAME=$(basename "$TAR_FILE" .tar.gz)

echo "🧹 Limpiando recursos previos para: ${LAB_NAME}"

# Llamada al endpoint /cleanup
HTTP_CODE=$(curl -v -o /dev/null -w "%{http_code}" \
  -X POST "${API_URL}/cleanup" \
  -H "Content-Type: application/json" \
  -d "{\"lab\":\"${LAB_NAME}\"}")

if [ "$HTTP_CODE" -eq 200 ]; then
  echo "✅ Recursos eliminados correctamente."
  exit 0
else
  echo "❌ Error al eliminar recursos (HTTP $HTTP_CODE)." >&2
  exit 1
fi
