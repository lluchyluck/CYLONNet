<?php
require_once("../../config.php");

if(($_SESSION["login"] === true) && isset($_GET["username"])){
    $user = $app->getUser(null, $_GET["username"], null);
    $returnUser = [];
    
    if (is_null($user) || empty($user)) {
        // Si no hay misiones, devolver un mensaje o un array vacío
        $returnUser = ['error' => 'No user available with this name'];
    }else{
        $missions = $app->getUserMissions($_GET["username"]);
        $returnUser["username"] = $user["username"];
        $returnUser["email"] = $user["email"];
        $returnUser["xp"] = (int)$user["xp"];
        $returnUser["icon"] = $user["icon"];
        $returnUser["missions"] = $missions;
    }
    header('Content-Type: application/json');
    echo json_encode($returnUser);
}else{
    echo "Usuario no encontrado o no se ha autenticado!!!";
}

