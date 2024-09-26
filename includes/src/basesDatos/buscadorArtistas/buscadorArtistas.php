<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/basesDatos/buscadorArtistas/formulario_findArtist.php";


$formFindSong = new formulario_findArtist();
$htmlFormFindSong = $formFindSong->gestiona();

$tituloPagina = 'FindArtist';

$html = <<<EOS
<article>
    $htmlFormFindSong
</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
