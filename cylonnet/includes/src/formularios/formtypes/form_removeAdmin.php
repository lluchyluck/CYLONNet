<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";
class FormRemoveAdmin extends Form {
    

    public function handle() {
        
        
        $name = filter_input(INPUT_POST, 'admin_name', FILTER_SANITIZE_STRING);
        
        
        if (!$this->validateInputs($name)) {
            return;
        }
        
       
        if (($data = $this->app->getUser(null,$name,null)) === null) {        
            $this->setMessageAndRedirect("Error al eliminar administrador, el usuario introducido no existe!!!");
            return false;
        }
        $user = new Usuario($this->app, $data["id"]);
        
        $this->eliminarAdmin($user);
    }

    private function validateInputs($name) {
        if (empty($name)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            $this->setMessageAndRedirect("El nombre de el admin solo puede contener letras, números y guiones bajos.");
            return false;
        }

        

        return true;
    }

    private function eliminarAdmin($usuario) {
        if ($usuario->descenderAdmin($this->app)) {
            
            $this->setMessageAndRedirect("Usuario descendido a usuario normal exitosamente.");
            return true;
        } else {
            $this->setMessageAndRedirect("Hubo un problema al descender admin.");
            return false;
        }
    }
}
