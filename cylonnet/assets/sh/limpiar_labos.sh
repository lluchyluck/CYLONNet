#!/bin/bash

echo "[INFO] Eliminando contenedores con label 'lab'..."
docker ps -a --filter "label=lab" -q | xargs -r docker rm -f

echo "[INFO] Eliminando archivos .conf de nginx excepto default.conf..."
find ./../../../nginx/conf.d -type f -name "*.conf" ! -name "default.conf" -exec rm -f {} +


echo "[INFO] Cleanup completado."
