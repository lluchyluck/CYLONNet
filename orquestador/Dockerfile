# cylonnet/orchestrator/Dockerfile
FROM python:3.11-slim

# Instala las dependencias necesarias
RUN apt-get update && \
    apt-get install -y --no-install-recommends at docker.io && \
    pip install --no-cache-dir flask docker && \
    rm -rf /var/lib/apt/lists/*

# Crea el directorio de la aplicación
WORKDIR /app

COPY app.py requirements.txt /app/

VOLUME [ "/var/run/docker.sock", "/app/sh", "/app/nginx/conf.d" ]

EXPOSE 8000

CMD ["python", "app.py"]
