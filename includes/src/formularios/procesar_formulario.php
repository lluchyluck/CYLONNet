<?php
require_once "../../config.php";

function handleLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login_button'])) {
        return;
    }

    $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    if (!validateInputs($nombre_usuario, "aaadsacxas@gmail.com", "aaaaaaaaaaaaa")) {
        return;
    }

    authenticateUser($GLOBALS['app'], $GLOBALS['db'], $nombre_usuario, $password);
}
function handleRegistration()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register_button'])) {
        return;
    }

    $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $img = "TODO";

    if (!validateInputs($nombre_usuario, $email, $password)) {
        return;
    }

    $user = new Usuario($nombre_usuario, $password, $email, $img);
    registrarUsuario($GLOBALS['app'], $GLOBALS['db'], $user);
}

function validateInputs($nombre_usuario, $email, $password)
{
    if (empty($nombre_usuario) || empty($email) || empty($password)) {
        setMessageAndRedirect("Por favor, completa todos los campos.");
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setMessageAndRedirect("Formato de correo electrónico no válido.");
        return false;
    }

    if (strlen($password) < 8) {
        setMessageAndRedirect("La contraseña debe tener al menos 8 caracteres.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_usuario)) {
        setMessageAndRedirect("El nombre de usuario solo puede contener letras, números y guiones bajos.");
        return false;
    }

    return true;
}

function setMessageAndRedirect($message)
{
    $_SESSION["mensaje"] = $message;
    header("Location: ../../../view/blueprint.php");
    exit();
}

function registrarUsuario($app, $db, $user)
{
    if ($app->existeUsuario($user->getUsername(), $user->getEmail()) != null) {
        setMessageAndRedirect("Error en el registro: el usuario ya está registrado.");
        return false;
    }

    if ($app->objectToDataBase($user)) {
        setMessageAndRedirect("Registro exitoso. Ahora puedes iniciar sesión.");
        return true;
    } else {
        setMessageAndRedirect("Error en el registro. Inténtalo nuevamente.");
        return false;
    }
}
function authenticateUser($app, $db, $nombre_usuario, $password)
{
    $user = $app->logueaUsuario($nombre_usuario, $password);

    if ($user !== null) {
        login($user);
        setMessageAndRedirect("Inicio de sesión exitoso. Bienvenido, " . $user["username"] . "!");
    } else {
        setMessageAndRedirect("Nombre de usuario o contraseña incorrectos. Inténtalo nuevamente.");
    }
}
function login($user)
{
    $_SESSION["login"] = true;
    $_SESSION["id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["img"] = $user["img"];
    $_SESSION["mensaje"] = "Usuario logeado con exito: " . $user["username"];
}

handleRegistration();
handleLogin();