# cylonnet/orchestrator/Dockerfile
FROM python:3.11-slim

# Instala Docker SDK, Flask y at para programar limpieza
RUN apt-get update && \
    apt-get install -y --no-install-recommends at docker.io && \
    pip install --no-cache-dir flask docker && \
    rm -rf /var/lib/apt/lists/*

# Crea el directorio de la aplicación
WORKDIR /app

# Copia tu código de orquestación
COPY app.py requirements.txt /app/

# Directorios de soporte
VOLUME [ "/var/run/docker.sock", "/app/sh", "/app/nginx/conf.d" ]

EXPOSE 8000

CMD ["python", "app.py"]
