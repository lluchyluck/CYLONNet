<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/app.php";

$tituloPagina = 'Info';

$app = Aplicacion::getInstance();


$html = verInfo($app);


require RUTA_INCLUDES . "/plantilla.php";

function verInfo($app)
{
    
    $html = $app->displayGenericInfo();

    return $html;
}