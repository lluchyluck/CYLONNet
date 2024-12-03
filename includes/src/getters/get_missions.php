<?php
require_once("../../config.php");

// Ejecutar la función para obtener las misiones
$user = $app->getAllMissions();

if (is_null($user) || empty($user)) {
    // Si no hay misiones, devolver un mensaje o un array vacío
    $user = ['error' => 'No missions available'];
}


// Devolver las misiones en formato JSON
header('Content-Type: application/json');
echo json_encode($user);
