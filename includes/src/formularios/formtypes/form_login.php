<?php
require_once __DIR__ ."/form.php";

class FormLogin extends Form {
    public function handle() {
        $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        

        if (!$this->validateInputs($nombre_usuario, $password)) {
            return;
        }
        
        $this->authenticateUser($nombre_usuario, $password);
        
    }

    private function validateInputs($nombre_usuario,$password) {
        if (empty($nombre_usuario) || empty($password)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_usuario)) {
            $this->setMessageAndRedirect("El nombre de usuario solo puede contener letras, números y guiones bajos.");
            return false;
        }

        return true;
    }

    private function authenticateUser($nombre_usuario, $password) {
        $user = $this->app->logueaUsuario($nombre_usuario, $password);
        echo "$nombre_usuario";
        if ($user !== null) {
            $this->login($user);
            $this->setMessageAndRedirect("Inicio de sesión exitoso. Bienvenido, " . $user["username"] . "!");
        } else {
            $this->setMessageAndRedirect("Nombre de usuario o contraseña incorrectos. Inténtalo nuevamente.");
        }
    }

    private function login($user) {
        $_SESSION["login"] = true;
        $_SESSION["id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["developer"] = (bool)$user["developer"];
        $_SESSION["icon"] = $user["icon"];
    }
}
