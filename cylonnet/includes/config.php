<?php
require_once __DIR__ ."/app.php";


// Comprobar si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if (!isset($_SESSION["username"]))
        $_SESSION["username"] = 'guest';
    if (!isset($_SESSION["login"]))
        $_SESSION["login"] = false;
    if (!isset($_SESSION["develper"]))
        $_SESSION["developer"] = false;
}

$app = Aplicacion::getInstance();
$db = $app->getConexionBd();