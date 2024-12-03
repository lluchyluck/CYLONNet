<?php
require_once("../../config.php");

if(($_SESSION["login"] === true) && isset($_GET["username"])){
    $user = $app->getUser($_GET["username"], null);
    $returnUser = [];
    
    $missions = $app->getUserMissions($_GET["username"]);
    if (is_null($user) || empty($user)) {
        // Si no hay misiones, devolver un mensaje o un array vacío
        $returnUser = ['error' => 'No user available with this name'];
    }else{
        $returnUser["username"] = $user["username"];
        $returnUser["email"] = $user["email"];
        $returnUser["icon"] = $user["icon"];
    }
    header('Content-Type: application/json');
    echo json_encode($returnUser);
    echo json_encode($missions);
}else{
    echo "Usuario no encontrado o no se ha autenticado!!!";
}

