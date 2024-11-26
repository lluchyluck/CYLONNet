<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/mission.php";
class FormRemoveMission extends Form {
    

    public function handle() {
        
        
        $name = filter_input(INPUT_POST, 'mission_name', FILTER_SANITIZE_STRING);
        
        
        if (!$this->validateInputs($name)) {
            return;
        }
        
       
        
       
        $mission = new Mission($name);
        
        $this->eliminarMision($mission);
        
    }

    private function validateInputs($name) {
        if (empty($name)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_ ]+$/', $name)) {
            $this->setMessageAndRedirect("El nombre de la misión solo puede contener letras, números y guiones bajos.");
            return false;
        }

        

        return true;
    }

    private function eliminarMision($mision) {
        if (($data = $this->app->getMission($mision->getName(),null)) === null) {        
            $this->setMessageAndRedirect("La misión no está registrada, no se eliminará nada.");
            return false;
        }
        print_r($data);
        $mision->setId($data["id"]);
        if ($mision->eliminarDB($this->app)) {
            $this->setMessageAndRedirect("Misión eliminada exitosamente.");
            return true;
        } else {
            $this->setMessageAndRedirect("Error en la eliminación de la mision. Inténtalo nuevamente.");
            return false;
        }
    }
}
