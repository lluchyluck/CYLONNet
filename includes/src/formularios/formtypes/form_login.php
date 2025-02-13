<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";

class FormLogin extends Form {
    public function handle() {
        $nombre_usuario = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
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
        $usuarioAutenticar = new Usuario($this->app, -1, $nombre_usuario, $password);
        if ($usuarioAutenticar->autenticar()) {
            $this->login($usuarioAutenticar);
            $this->setMessageAndRedirect("Inicio de sesión exitoso. Bienvenido, " . $usuarioAutenticar->getUsername() . "!");
        } else {
            $this->setMessageAndRedirect("Nombre de usuario o contraseña incorrectos. Inténtalo nuevamente.");
        }
    }
    private function login($user) {
        $_SESSION["login"] = true;
        $_SESSION["id"] = $user->getId();
        $_SESSION["username"] = $user->getUsername();
        $_SESSION["email"] = $user->getEmail();
        $_SESSION["xp"] = $user->getXp();
        $_SESSION["developer"] = (bool)$user->getDeveloper();
        $_SESSION["icon"] = $user->getImg();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(8));
    }
}
