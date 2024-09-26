<?php


require_once __DIR__ . "/../../config.php";



$htmlNombreUsuario = '<h2 style="display: inline;">' . $_SESSION["username"] . '</h2>';
$imgSrc = $_SESSION["img"];
$idUser = $_SESSION["id"];
$tituloPagina = 'Perfil';

$html = <<<EOS
<article>

    <fieldset>
        <legend>$htmlNombreUsuario</legend>
        <div>
        <img src="/ProyectoABD/assets/img/$imgSrc" alt="No se ha encontrado imagen!!!" width="150" height="150">
        </div>
        <div>
        <label>ID: $idUser<label>
        </div>
        <div>
        <h3 style="display: inline;"><a href="/ProyectoABD/includes/src/perfil/cancionesFavoritas/cancionesFavoritas.php">Canciones Favoritas</a></h3>
        </div>
    </fieldset>

    
</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
