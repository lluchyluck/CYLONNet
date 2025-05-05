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

# 2) Si todo fue OK, recarga NGINX
STATUS=$(echo "$RESPONSE" | jq -r .status)
if [[ "$STATUS" == "ok" ]]; then
  docker exec cylonnet_web_1 nginx -s reload > /dev/null 2>&1
  echo "¡Nginx recargado!"
else
  echo "Error en el despliegue: $RESPONSE"
  exit 1
fi

echo "==============================================="
echo "       ¡Despliegue completado con éxito!       "
echo "==============================================="
echo ""
echo "Por favor, añade el siguiente bloque a tu archivo /etc/hosts:"
echo ""
echo "    IP_SERVIDOR    $SUBDOM"
echo ""
echo "Una vez hecho eso, podrás acceder a tu laboratorio en:"
echo ""
echo "    http://$SUBDOM"
echo ""
echo "==============================================="
echo "Nota: El laboratorio se eliminará automáticamente en 60 minutos."
echo "==============================================="
