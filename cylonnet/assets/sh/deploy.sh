#!/bin/bash
# deploy-via-orchestrator.sh

# Ensure required commands are available
for cmd in curl jq; do
  if ! command -v $cmd &> /dev/null; then
    echo "Error: $cmd is not installed or not in PATH."
    exit 1
  fi
done

if [ $# -ne 3 ]; then
  echo "Uso: $0 <lab_tar.gz> <uflag> <rflag>"
  exit 1
fi

TAR=$1
U=$2
R=$3
ORCH_URL="http://orquestador:8000/deploy"
LAB_NAME=$(basename "$TAR" .tar.gz)
SUBDOM="${LAB_NAME}.cylonnet"

# 1) Llamada al orquestador
RESPONSE=$(curl -s -X POST "$ORCH_URL" \
  -H "Content-Type: application/json" \
  -d "{\"tar_path\": \"/app/sh/labos$TAR\", \"uflag\": \"$U\", \"rflag\": \"$R\", \"lab\": \"$SUBDOM\"}")

if [ -z "$RESPONSE" ]; then
  echo "Error: No response from orchestrator."
  exit 1
fi

echo "{\"tar_path\": \"/app/sh/labos$TAR\", \"uflag\": \"$U\", \"rflag\": \"$R\", \"lab\": \"$SUBDOM\"}"
echo "$RESPONSE" | jq .

# 2) Si todo fue OK, recarga NGINX
STATUS=$(echo "$RESPONSE" | jq -r .status)
if [[ "$STATUS" == "ok" ]]; then
  echo "Recargando NGINX en el contenedor 'web'..."
  docker exec cylonnet_web_1 nginx -s reload > /dev/null 2>&1
  echo "¡Nginx recargado!"
else
  echo "Error en despliegue, no se recargará NGINX."
  echo "Detalles del error: $RESPONSE"
  echo $TAR
  exit 1
fi
