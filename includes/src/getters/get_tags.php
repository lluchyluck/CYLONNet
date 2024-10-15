<?php
require_once("../../config.php");

// Ejecutar la función para obtener las misiones
$tags = $app->getAllTags();

if (is_null($tags) || empty($tags)) {
    // Si no hay misiones, devolver un mensaje o un array vacío
    $tags = ['error' => 'No tags available'];
}


// Devolver las misiones en formato JSON
header('Content-Type: application/json');
echo json_encode($tags);
