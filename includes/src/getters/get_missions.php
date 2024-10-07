<?php
require_once("../../config.php");

// Ejecutar la función para obtener las misiones
$missions = $app->getAllMissions();

if (is_null($missions) || empty($missions)) {
    // Si no hay misiones, devolver un mensaje o un array vacío
    $missions = ['error' => 'No missions available'];
}


// Devolver las misiones en formato JSON
header('Content-Type: application/json');
echo json_encode($missions);
