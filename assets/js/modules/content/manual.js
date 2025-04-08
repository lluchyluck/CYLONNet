export function loadManualContent() {
    $('#content').html(`
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manual de Usuario</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="title-logo">
      <h1>Manual de Usuario</h1>
    </div>
    <div class="profile">
      <span>Ayuda</span>
    </div>
  </header>

  <div class="main-container">
    <nav>
      <ul class="menu">
        <li>
          <a href="#introduccion">
            Introducción
          </a>
        </li>
        <li>
          <a href="#ade">
            Antes de Empezar
          </a>
        </li>
        <li>
          <a href="#misiones">
            Misiones
          </a>
        </li>
        <li>
          <a href="#contacto">
           Contacto
          </a>
        </li>
      </ul>
    </nav>

    <main>
      <!-- Sección de Introducción -->
      <section id="introduccion" class="box" style="width: 1350px">
        <h2>Introducción</h2>
        <p>
          Bienvenido, Cylon, a tu guía de iniciación para la infiltración en sistemas humanos. En este manual encontrarás secciones esenciales que debes revisar antes de comenzar a utilizar nuestro sistema.
          Unete al discord para hablar con gente que este resolviendo las mismas máquinas que tu: <a href="https://discord.gg/GFPgrRGkMy" target="_blank">Discord</a>
        </p>
      </section>

      <!-- Sección de Antes de Empezar -->
      <section id="ade" class="box" style="width: 1350px">
        <h2>Antes de Empezar</h2>
        <article>
          <h3>1. Acceso a la Web</h3>
          <p>
            El acceso a la web está restringido a los miembros que dispongan de VPN o que ya se encuentren conectados a la red de la UCM.
          </p>
        </article>
        <article>
          <h3>2. Registro e Inicio de Sesión</h3>
          <p>
            Para comenzar, regístrate utilizando tu <strong>correo institucional de la UCM</strong> y crea una contraseña nueva (no uses tu contraseña personal, de todos modos se almacenan en formato hash). Si ya tienes una cuenta, inicia sesión. <strong>¡Muchas funcionalidades requieren que estés registrado!</strong>
          </p>
        </article>
        <article>
          <h3>3. Preparación Final</h3>
          <p>
            Se recomienda leer detenidamente el manual; sin embargo, el sistema ya se encuentra configurado para su uso.
          </p>
        </article>
      </section>

      <!-- Sección de Misiones -->
      <section id="misiones" class="box" style="width: 1350px">
        <h2>Misiones</h2>
        <article>
          <h3>1. Lanzar una Misión</h3>
          <p>
            Haz clic en "See contract" y luego en "Start mission". Una vez presionado el botón, espera unos segundos (dependiendo de la carga del sistema) para que la misión se inicie.
          </p>
        </article>
        <article>
          <h3>2. Acceder a una Misión</h3>
          <p>
            En el pop-up que aparecerá en pantalla se mostrará el subdominio donde se aloja la misión. Si se indica <code>cylonhack.cylonet.es</code>, deberás acceder a esa URL.
          </p>
        </article>
        <article>
          <h3>3. Tags</h3>
          <p>
            Los tags indican los desafíos que encontrarás durante la misión. Cuantos más acumules, mayor será el XP que ganarás.
          </p>
        </article>
        <article>
          <h3>4. Walkthrough</h3>
          <p>
            El objetivo final de cada misión es lograr un RCE (remote command execution), es decir, tomar el control de la máquina y posteriormente escalar privilegios hasta alcanzar la cuenta root.
          </p>
        </article>
        <article>
          <h3>5. Flags</h3>
          <p>
            Una vez que controles la máquina, las flags se obtendrán siempre en las siguientes rutas:
          </p>
          <ul>
            <li>User flag: <code>/flag/uflag.txt</code></li>
            <li>Root flag: <code>/root/rflag.txt</code></li>
          </ul>
        </article>
        <article>
          <h3>6. Consideraciones Adicionales</h3>
          <p>
            En algunos casos, es posible que no se puedan escalar privilegios directamente desde <code>www-data</code> o <code>daemon</code> a root; puede que debas acceder primero a un usuario intermedio.
          </p>
        </article>
      </section>

      <!-- Sección para Crear Misión -->
      <section id="Developer" class="box" style="width: 1350px">
        <h2>Crear Misión</h2>
        <article>
          <h3>1. Uso de Docker como Base</h3>
          <p>
            Se utilizarán contenedores Docker como base para los laboratorios, comprimidos en formato <code>.tar.gz</code>. Es fundamental seguir la configuración detallada en este documento para evitar errores al ejecutar el contenedor.
          </p>
        </article>
        <article>
          <h3>2. Creación del Contenedor</h3>
          <p>
            El contenedor se creará mediante un Dockerfile. Existen tutoriales en YouTube sobre cómo construir contenedores vulnerables. Como recomendación, consulta el siguiente enlace: 
            <a href="https://www.youtube.com/watch?v=kDAK9Wc8o_k&t=1240s&ab_channel=ElPing%C3%BCinodeMario" target="_blank">Tutorial de ElPingüino de Mario</a>.
          </p>
        </article>
        <article>
          <h3>3. Exportación del Contenedor</h3>
          <p>
            Una vez creada la imagen del contenedor, expórtala utilizando el siguiente comando:
          </p>
          <pre><code>docker save -o nombre_de_la_maquina.tar.gz nombre_de_la_maquina:latest</code></pre>
        </article>
        <article>
          <h3>4. Subida del Contenedor a la Plataforma</h3>
          <p>
            En la sección "Developer" configura la subida seleccionando el archivo generado.
          </p>
        </article>
        <article>
          <h3>5. Verificación</h3>
          <p>
            Se verificará el correcto funcionamiento del contenedor. En caso de error, se recomienda eliminar la misión desde el panel de administración y revisar los pasos realizados.
          </p>
        </article>
        <article>
          <h3>6. Consideraciones Adicionales</h3>
          <p>
            Si no logras subir exitosamente el contenedor, puedes contactar a <a href="mailto:lucalzad@ucm.es">lucalzad@ucm.es</a>.
          </p>
          <p>Ejemplo de Dockerfile:</p>
          <div class="box" style="width: 1200px;"><pre><code># Utilizamos una versión específica de Ubuntu para mayor reproducibilidad
FROM ubuntu:latest

# Configuración de entorno para evitar prompts interactivos
ENV DEBIAN_FRONTEND=noninteractive

# Actualizar el sistema e instalar paquetes necesarios
RUN apt-get update && 
    apt-get upgrade -y && 
    apt-get install -y 
      apache2 
      sudo 
      iproute2 
      mariadb-server 
      mariadb-client 
      nano 
      vim 
      php 
      libapache2-mod-php 
      php-mysql 
      python3 && 
    rm -rf /var/lib/apt/lists/*

# Crear usuario y configurar contraseña
RUN useradd -m -s /bin/bash oficial && 
    echo "oficial:scythe_not_galactica" | chpasswd

# Configurar privilegios sudo para python3
RUN echo "oficial ALL=(ALL) NOPASSWD: /usr/bin/python3" >> /etc/sudoers
RUN echo "www-data ALL=(oficial) NOPASSWD: /script.sh" >> /etc/sudoers

# Crear script de ejemplo con contenido completo
RUN printf '#!/bin/bash\n\necho "Este script se ejecuta como el usuario oficial"\n' > /script.sh && 
    chown oficial:www-data /script.sh && 
    chmod 4775 /script.sh  # SetUID + permisos de escritura para el grupo

# Configurar Apache
RUN rm /var/www/html/index.html && 
    chown -R www-data:www-data /var/www/html/

# Copiar archivos de la aplicación (rutas relativas al contexto de construcción)
COPY index.php /var/www/html/
COPY scythe_db.sql /tmp/

# Inicializar base de datos MariaDB
RUN service mariadb start && 
    sleep 5 && 
    mysql -e "CREATE DATABASE IF NOT EXISTS scythe_db;" && 
    mysql -e "CREATE USER IF NOT EXISTS 'scythe'@'localhost' IDENTIFIED BY 'scypassword';" && 
    mysql -e "GRANT ALL PRIVILEGES ON scythe_db.* TO 'scythe'@'localhost';" && 
    mysql -e "FLUSH PRIVILEGES;" && 
    mysql scythe_db < /tmp/scythe_db.sql && 
    service mariadb stop

# Exponer puertos y configurar arranque
EXPOSE 80 3306

# Script de inicio para servicios
RUN printf '#!/bin/bash\nservice mariadb start\nsleep 5\nservice apache2 start\nsleep 5\ntail -f /dev/null\n' > /init.sh && \
    chmod +x /init.sh

CMD ["/init.sh"]
</code></pre></div>
        </article>
      </section>
      
      <!-- Sección de Contacto -->
      <section id="contacto" class="box" style="width: 1350px">
        <h2>Contacto</h2>
        <p>
          Si tienes preguntas o necesitas asistencia, no dudes en escribir a:
        </p>
        <ul>
          <li>Alumno: <a href="mailto:lucalzad@ucm.es">lucalzad@ucm.es</a></li>
          <li>Tutor: <a href="mailto:dpacios@ucm.es">dpacios@ucm.es</a></li>
          <li>Tutor: <a href="mailto:jlvazquez@fdi.ucm.es">jlvazquez@fdi.ucm.es</a></li>
        </ul>
      </section>
    </main>
  </div>

  <footer>
    <p>&copy; 2025 CylonNet. Todos los derechos reservados.</p>
  </footer>
</body>
</html>



    `);
}