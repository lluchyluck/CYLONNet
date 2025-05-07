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
RUN printf '#!/bin/bash\n\
echo "============================================="\n\
echo "  Selecciona el comando a ejecutar (1–30):"\n\
echo "============================================="\n\
echo "  1)  id"\n\
echo "  2)  whoami"\n\
echo "  3)  ls -la /root"\n\
echo "  4)  ps aux"\n\
echo "  5)  uname -a"\n\
echo "  6)  cat /etc/passwd"\n\
echo "  7)  env"\n\
echo "  8)  df -h"\n\
echo "  9)  netstat -tuln"\n\
echo " 10) lsof"\n\
echo " 11) ping -c 1 localhost"\n\
echo " 12) uptime"\n\
echo " 13) free -m"\n\
echo " 14) cat /etc/shadow"\n\
echo " 15) dmesg | tail -n 10"\n\
echo " 16) tail -n 5 /var/log/auth.log"\n\
echo " 17) less /etc/passwd"\n\
echo " 18) uname -r"\n\
echo " 19) mount"\n\
echo " 20) cat /proc/cpuinfo"\n\
echo " 21) ip a"\n\
echo " 22) ss -tulw"\n\
echo " 23) grep root /etc/group"\n\
echo " 24) head -n 5 /etc/hosts"\n\
echo " 25) find / -maxdepth 1 -type d"\n\
echo " 26) ps -ef | grep www-data"\n\
echo " 27) journalctl -n 5"\n\
echo " 28) stat /tmp"\n\
echo " 29) ping -c 1 8.8.8.8"\n\
echo " 30) cat /etc/issue"\n\
echo\n\
read -p "Opción: " choice\n\
echo\n\
case "$choice" in\n\
  1)  id;;  2)  whoami;;  3)  ls -la /root;;  4)  ps aux;;  5)  uname -a;;\n\
  6)  cat /etc/passwd;;  7)  env;;  8)  df -h;;  9)  netstat -tuln;;  10) lsof;;\n\
  11) ping -c 1 localhost;;  12) uptime;;  13) free -m;;  14) cat /etc/shadow;;\n\
  15) dmesg | tail -n 10;;  16) tail -n 5 /var/log/auth.log;;\n\
  17) exec less /etc/passwd;;\n\
  18) uname -r;;  19) mount;;  20) cat /proc/cpuinfo;;  21) ip a;;\n\
  22) ss -tulw;;  23) grep root /etc/group;;  24) head -n 5 /etc/hosts;;\n\
  25) find / -maxdepth 1 -type d;;  26) ps -ef | grep www-data;;\n\
  27) journalctl -n 5;;  28) stat /tmp;;  29) ping -c 1 8.8.8.8;;  30) cat /etc/issue;;\n\
  *) echo "Opción inválida. Elige un número del 1 al 30.";;\n\
esac\n' > /script.sh && chown oficial:www-data /script.sh && chmod 4755 /script.sh


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

