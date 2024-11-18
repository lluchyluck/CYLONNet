<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";
class FormRemoveAdmin extends Form {
    

    public function handle() {
        
        
        $name = filter_input(INPUT_POST, 'admin_name', FILTER_SANITIZE_STRING);
        
        
        if (!$this->validateInputs($name)) {
            return;
        }
        
       
        
       
        $user = new Usuario($name);
        
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
            $this->setMessageAndRedirect("La usuario no está registrado!!!.");
            return false;
        }
    }
}
