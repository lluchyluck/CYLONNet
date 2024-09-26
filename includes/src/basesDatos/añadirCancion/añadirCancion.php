<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/basesDatos/añadirCancion/formulario_añadirCancion.php";


$añadirCancion = new formulario_añadirCancion();
$htmlForm = $añadirCancion->gestiona();

$tituloPagina = 'Añadir Cancion';

$html = <<<EOS
<article>
    <h1>Añadir Cancion</h1>
    $htmlForm
</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
