<?php

namespace includes;

// Comprobar si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(!isset($_SESSION["username"]))
        $_SESSION["username"] = 'Usuario no logueado';
    if(!isset($_SESSION["login"]))
        $_SESSION["login"] = false;
    
}

