# cylonnet/orchestrator/Dockerfile
FROM python:3.11-slim

# Instala las dependencias necesarias
RUN apt-get update && \
    apt-get install -y --no-install-recommends at docker.io && \
    pip install --no-cache-dir flask docker gunicorn && \
    rm -rf /var/lib/apt/lists/*

# Crea el directorio de la aplicación
WORKDIR /app

COPY app.py requirements.txt /app/

VOLUME [ "/var/run/docker.sock", "/app/sh", "/app/nginx/conf.d" ]


# CMD ["python", "app.py"] ---> desarrollo
 

# ---producción---
CMD ["gunicorn", "-w", "4", "-b", "0.0.0.0:8000", "app:app"]