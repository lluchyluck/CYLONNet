<?php 
require_once "../../config.php";

if(($_SESSION["login"] === true) && isset($_SESSION["developer"]) && ($_SESSION["developer"] === true)){
    echo 1;
    //$_SESSION["mensaje"] = "Bienvenido <strong>".$_SESSION["username"]. "</strong> al panel de administrador";
}else{
    echo 0;
    //$_SESSION["mensaje"] = "No tienes los permisos adecuados para acceder al panel de dessarrollador!!!";
}
