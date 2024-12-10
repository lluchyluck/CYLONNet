<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";
require_once __DIR__ ."/../../objects/mission.php";

class FormSubmitFlag extends Form {
    public function handle() {
        $missionId = filter_input(INPUT_POST, 'missionId', FILTER_VALIDATE_INT);
        $flag = filter_input(INPUT_POST, 'flag', FILTER_SANITIZE_STRING);
        
        if (!$this->validateInputs($missionId, $flag)) {
            return;
        }       
        
        $this->validateFlag($missionId, $flag);    
    }
    private function validateInputs($missionId,$flag) {
        if (empty($missionId) || empty($flag)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (!preg_match('/^[0-9]+$/', $missionId)) {
            $this->setMessageAndRedirect("El missionId debe de ser un número");
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9]{1,16}$/', $flag)) {
            $this->setMessageAndRedirect("La flag debe tener entre 1 y 16 caracteres, compuesta únicamente por letras y números.");
            return false;
        }        
        return true;
    }
    private function validateFlag($missionId, $flag) {
        if (($data = $this->app->getMission(null, $missionId)) === null) {       
            $this->setMessageAndRedirect("No hay ninguna mision con ese ID!!!");
            return false;
        }
        $misionComprobar = new Mission($this->app, $data["id"]);
        if($misionComprobar->getExistence()){  
            if ($misionComprobar->comprobarFlag($flag)) {
                $usuario = new Usuario($this->app, (int)$_SESSION["id"]);
                var_dump($usuario);
                $xp = 1000;
                if($usuario->getExistence()){
                    if($usuario->misionCompletada($misionComprobar->getId())){
                        echo "pasa";
                        if($usuario->añadirXp($xp)){
                            $_SESSION["xp"] = $usuario->getXp();
                            $this->setMessageAndRedirect("Mision completada, XP añadida: " . $xp);
                        }else{
                            $this->setMessageAndRedirect("Error al añadir XP");
                        }
                        
                    }else{
                        $this->setMessageAndRedirect("Esta mision ya ha sido completada!!!");
                    }
                }
            } else {
                $this->setMessageAndRedirect("Flag incorrecta!!!");
            }
        }else{
            $this->setMessageAndRedirect("No existe una mision con ese ID!!!");
        }
    }
}
