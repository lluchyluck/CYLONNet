<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/basesDatos/buscadorCanciones/formulario_findSong.php";


$formFindSong = new formulario_findSong();
$htmlFormFindSong = $formFindSong->gestiona();

$tituloPagina = 'FindSong';

$html = <<<EOS
<article>
    $htmlFormFindSong
</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
