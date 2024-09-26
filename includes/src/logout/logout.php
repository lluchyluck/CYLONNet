<?php

require_once __DIR__ . "/../../config.php";
require RUTA_INCLUDES . "/src/app.php";

$app = Aplicacion::getInstance();
$app->logout();
header("Location: /ProyectoABD/index.php");