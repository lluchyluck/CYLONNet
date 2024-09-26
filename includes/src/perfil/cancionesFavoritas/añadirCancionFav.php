<?php

require_once __DIR__ . "/../../../config.php";
require_once RUTA_INCLUDES . "/src/app.php";


$tituloPagina = 'Añadir Cancion Favorita';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION["login"]) {
    
    $app = Aplicacion::getInstance();  
    $idCancion = $_POST["songId"];
    $stars = $_POST["stars"];
    $idUsuario = $_SESSION["id"];
    $cancionFav = new CancionFavorita($idCancion,$idUsuario,$stars);

    $app->objectToDataBase($cancionFav);

    header("Location: cancionesFavoritas.php");

}else{
    header("Location: ../../../../index.php");
}