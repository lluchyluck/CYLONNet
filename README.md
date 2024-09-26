# ProyectoABD
Proyecto Realizado por: Pablo Pérez Agudo y Lucas Calzada del Pozo.
Programas utilizados: Github Desktop, Visual Studio Code, Xampp.

Explicacion de como iniciar la aplicacion:
-Descomprimir el zip y meter ProyectoABD en la carpeta htdocs, si se desea meter en otra carpeta habria que tocar el
archivo config.php, aunque es posible que halla algun problema.
-Importar la base de datos que esta en la carpeta mysql, se puede importar la estructura y los datos por separado
si se desea probar la aplicacion sin ningun dato.
-Las contraseñas no estan hasheadas para hacer mas facil el recordar las contraseñas (somos conscientes de la
falta de seguridad de no hashearlas).
-Si no estas logueado no podras acceder a las bases de datos.

Explicacion de la estructura de la aplicacion:
-Todos los objetos que se introducen en la base de datos se crean como clases (canciones,usuarios y canciones favoritas).
-Los generos son estaticos, es decir tendra que añadirlo si quiere el administrador desde el panel de phpmyadmin.
-Toda la aplicacion se divide entre vista y logica, por ejemplo, en el panel login.php junta la vista (plantilla.php),
con la logica sacada del formulario_login.php que lo procesa y ofrece el html que imprimira la plantilla, todas los 
php con formulario funcionan de manera similar.
-Existe un archivo config.php que define las rutas de la aplicacion y inicializa la sesion si no esta iniciada.
-Toda la logica de la base de datos esta implementada en la clase app que se comunica con esta misma.
