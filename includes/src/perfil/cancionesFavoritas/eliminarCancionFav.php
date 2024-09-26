<?php

require_once __DIR__ . "/../../../config.php";
require_once RUTA_INCLUDES . "/src/app.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION["login"]) {
    
    $app = Aplicacion::getInstance();  
    $idCancion = $_POST["songId"];
    $idUsuario = $_SESSION["id"];
    
    

    if($app->removeFavSong($idCancion,$idUsuario)){
        header("Location: cancionesFavoritas.php");
    }else{
        echo "Error al eliminar la cancion.";
    }

   
    

}else{
    header("Location: ../../../../index.php");
}