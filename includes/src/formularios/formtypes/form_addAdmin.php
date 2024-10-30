<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";

class FormAddAdmin extends Form {
    public function handle() {
        $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

        

        if (!$this->validateInputs($nombre_usuario)) {
            return;
        }
        $usuario = new Usuario($nombre_usuario);
        if($usuario->ascenderAdmin($this->app)){
            $this->setMessageAndRedirect("Ahora $nombre_usuario es administrador!!!");
        }
        $this->setMessageAndRedirect("Error al añadir administrador, el usuario introducido no existe!!!");
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
