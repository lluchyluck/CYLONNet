<?php
require_once "../../config.php";

function handleLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login_button'])) {
        return;
    }

    $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    if (!validateInputs($nombre_usuario, "email@ejemplo.com", $password, null)) {
        return;
    }

    authenticateUser($GLOBALS['app'], $nombre_usuario, $password);
}

function handleLogout()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['logout_button'])) {
        return;
    }
    logout();
}

function handleRegistration()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register_button'])) {
        return;
    }

    $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $img = ($_FILES["image"]["size"] !== 0) ? $_FILES["image"] : null;

    if (!validateInputs($nombre_usuario, $email, $password, $img)) {
        return;
    }

    $imagenRuta = ($img !== null) ? handleImageUpload($img) : "/default.jpg";
    if ($imagenRuta === false) {
        return;
    }

    $user = new Usuario($nombre_usuario, $password, $email, $imagenRuta);
    registrarUsuario($GLOBALS['app'], $user);
}

function handleImageUpload($img)
{
    $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . "/CYLONNet/assets/images/profile/" . basename($img["name"]);

    if (!move_uploaded_file($img['tmp_name'], $ruta_destino)) {
        setMessageAndRedirect("Error al guardar la imagen en el servidor.");
        return false;
    }

    return "/" . basename($img["name"]);
}

function validateInputs($nombre_usuario, $email, $password, $image)
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

    if ($image !== null && !validateImage($image)) {
        return false;
    }

    return true;
}

function validateImage($image)
{
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $fileType = mime_content_type($image['tmp_name']);
    if (!in_array($fileType, $allowedMimeTypes)) {
        setMessageAndRedirect("Tipo de archivo no permitido. Solo se permiten imágenes JPEG y PNG.");
        return false;
    }

    if ($image['size'] > 2 * 1024 * 1024) {
        setMessageAndRedirect("El tamaño de la imagen no debe superar los 2 MB.");
        return false;
    }

    list($width, $height) = getimagesize($image['tmp_name']);
    if ($width > 400 || $height > 400 || $width !== $height) {
        setMessageAndRedirect("La imagen debe ser cuadrada y no exceder 400x400 píxeles.");
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $image["name"])) {
        setMessageAndRedirect("El nombre de la imagen solo puede contener letras, números y guiones bajos.");
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

function registrarUsuario($app, $user)
{
    if ($app->getUser($user->getUsername(), $user->getEmail()) !== null) {
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

function authenticateUser($app, $nombre_usuario, $password)
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
    $_SESSION["icon"] = $user["icon"];
}

function logout()
{
    session_unset();
    session_destroy();
    session_start();
    setMessageAndRedirect("Sesión cerrada.");
}

handleRegistration();
handleLogin();
handleLogout();
