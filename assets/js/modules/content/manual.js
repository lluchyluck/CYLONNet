export function loadManualContent() {
    $('#content').html(`
        <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de enlazar tu CSS -->
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <div class="title-logo">
            <img src="./../assets/images/menu/manual.png" alt="Logo" class="profile-pic">
            <h1>Manual de Usuario</h1>
        </div>
        <div class="profile">
            <span>Ayuda</span>
        </div>
    </div>

    <!-- Contenedor principal -->
    <div class="main-container">
        <!-- Menú lateral -->
        <div class="menu">
            <a href="#introduccion"><img src="icon1.png" alt="Introducción">Introducción</a>
            <a href="#caracteristicas"><img src="icon2.png" alt="Características">Características</a>
            <a href="#uso"><img src="icon3.png" alt="Instrucciones">Instrucciones</a>
            <a href="#contacto"><img src="icon4.png" alt="Contacto">Contacto</a>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <div class="box" id="introduccion">
                <h2>Introducción</h2>
                <p>Bienvenido, Cylon, al manual de iniciación para la infiltración en sistemas humanos. Este documento está dividido en varias secciones clave que debes comprender antes de comenzar a utilizar nuestro sistema.</p>
            </div>

            <div class="box" id="caracteristicas">
                <h2>Funcionalidades</h2>
                <ul>
                    <li>Misiones</li>
                    <li>Compatibilidad con dispositivos móviles.</li>
                    <li>Acceso a funcionalidades avanzadas como filtros y búsquedas.</li>
                    <li>Panel de usuario personalizable.</li>
                </ul>
            </div>

            <div class="box" id="uso">
                <h2>Antes de empezar</h2>
                <h3>1. Acceso a la web</h3>
                <p>La web solo será accesible para los miembros a los que se les haya ofrecido la VPN.</p>
                <h3>2. Registro e Inicio de Sesión</h3>
                <p>Para comenzar, regístrate creando una cuenta con tu <strong>correo de la UCM</strong> o inicia sesión si ya tienes una. <strong>¡Muchas funcionalidades solo funcionan si estás registrado!</strong></p>
                <h3>3. Personalización</h3>
                <p>Accede a tu perfil para personalizar tus preferencias y gestionar tu cuenta.</p>
            </div>

            <div class="box" id="misiones">
                <h2>Misiones</h2>
                <h3>1. Lanzar una misión</h3>
                <p>Haz clic en "See contract" y luego en "Start mission".</p>
                <h3>2. Acceder a una misión</h3>
                <p>En el pop-up que te saldrá en la pantalla, se te mostrará el nombre del subdominio de la web en el que está alojada la misión. Si te dice que es cylonhack.cylonet.es, deberás acceder a esa URL.</p>
                <h3>3. Tags</h3>
                <p>Los tags te indican los retos que te irás encontrando en la misión. Cuantos más tags, más XP ganarás.</p>
            </div>

            <div class="box" id="contacto">
                <h2>Contacto</h2>
                <p>Si tienes preguntas o necesitas ayuda, no dudes en contactarnos:</p>
                <ul>
                    <li>Email: soporte@ejemplo.com</li>
                    <li>Teléfono: +34 123 456 789</li>
                </ul>
                <p>Síguenos en nuestras redes sociales para más actualizaciones.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Nombre de tu Empresa. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

    `);
}