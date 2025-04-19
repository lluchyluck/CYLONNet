<?php
require_once("../../config.php");

// Obtener los 5 mejores usuarios con respecto a su XP
$users = $app->getUserTop();
$returnUsers = [];

if (empty($users)) {
    // Si no se obtienen usuarios, devolver un mensaje de error
    $returnUsers = ['error' => 'No users available'];
} else {
    // Si hay usuarios, procesar los primeros 5
    foreach ($users as $user) {
        $returnUsers[] = [
            "username" => $user["username"],
            "xp" => (int)$user["xp"],
            "icon" => $user["icon"]
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($returnUsers);
