<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";

class FormAddAdmin extends Form {
    public function handle() {
        $nombre_usuario = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');

        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_token']) {
            $this->setMessageAndRedirect("Token CSRF no válido.");
        }
        

        if (!$this->validateInputs($nombre_usuario)) {
            return;
        }
        if (($data = $this->app->getUser(null,$nombre_usuario,null)) === null) {        
            $this->setMessageAndRedirect("Error al añadir administrador, el usuario introducido no existe!!!");
            return false;
        }
        $usuario = new Usuario($this->app, $data["id"]);
        if($usuario->ascenderAdmin()){
            $this->setMessageAndRedirect("Ahora $nombre_usuario es administrador!!!");
        }else{
            $this->setMessageAndRedirect("Error al añadir $nombre_usuario como administrador!!!");
        }
 
    }

    private function validateInputs($nombre_usuario) {
        if (empty($nombre_usuario)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_usuario)) {
            $this->setMessageAndRedirect("El nombre de usuario solo puede contener letras, números y guiones bajos.");
            return false;
        }

        return true;
    } 
}
