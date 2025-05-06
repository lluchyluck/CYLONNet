# Utilizamos una versión específica de Ubuntu para mayor reproducibilidad
FROM arm32v7/ubuntu:24.04

# Configuración de entorno para evitar prompts interactivos
ENV DEBIAN_FRONTEND=noninteractive

# Actualizar sistema e instalar paquetes necesarios
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
    apache2 \
    sudo \
    iproute2 \
    mariadb-server \
    mariadb-client \
    nano \
    vim \
    php \
    libapache2-mod-php \
    php-mysql \
    python3 \
    && rm -rf /var/lib/apt/lists/*

# Crear usuario y configurar contraseña
RUN useradd -m -s /bin/bash oficial && \
    echo "oficial:scythe_not_galactica" | chpasswd

# Configurar privilegios sudo para python3
RUN echo "oficial ALL=(ALL) NOPASSWD: /usr/bin/python3" >> /etc/sudoers
RUN echo "www-data ALL=(oficial) NOPASSWD: /script.sh" >> /etc/sudoers

# Crear script de ejemplo con contenido completo
RUN printf '#!/bin/bash\n\necho "Este script se ejecuta como el usuario oficial"\n' > /script.sh && \
    chown oficial:www-data /script.sh && \
    chmod 4775 /script.sh  # SetUID + grupo con escritura

# Configurar Apache
RUN rm /var/www/html/index.html && \
    chown -R www-data:www-data /var/www/html/

# Copiar archivos de la aplicación (rutas relativas al contexto de construcción)
COPY index.php /var/www/html/
COPY scythe_db.sql /tmp/

# Inicializar base de datos MariaDB
RUN service mariadb start && \
    sleep 5 && \
    mysql -e "CREATE DATABASE IF NOT EXISTS scythe_db;" && \
    mysql -e "CREATE USER IF NOT EXISTS 'scythe'@'localhost' IDENTIFIED BY 'scypassword';" && \
    mysql -e "GRANT ALL PRIVILEGES ON scythe_db.* TO 'scythe'@'localhost';" && \
    mysql -e "FLUSH PRIVILEGES;" && \
    mysql scythe_db < /tmp/scythe_db.sql && \
    service mariadb stop

# Exponer puertos y configurar arranque
EXPOSE 80 3306

# Script de inicio para servicios
RUN printf '#!/bin/bash\nservice mariadb start\nsleep 5\nservice apache2 start\nsleep 5\ntail -f /dev/null\n' > /init.sh && \
    chmod +x /init.sh

CMD ["/init.sh"]

