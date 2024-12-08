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
        if (!preg_match('/^[a-zA-Z0-9]+$/', $flag)) {
            $this->setMessageAndRedirect("La flag debe de tener letras y numeros unicamente.");
            return false;
        }
        return true;
    }
    private function validateFlag($missionId, $flag) {
        if (($data = $this->app->getMission(null, $missionId)) === null) {       
            $this->setMessageAndRedirect("No hay ninguna mision con ese ID!!!");
            return false;
        }
        $misionComprobar = new Mission($data["name"]);
        
        if ($misionComprobar->comprobarFlag($this->app, $flag)) {
            $usuario = new Usuario($_SESSION["username"]);
            $xp = 100;
            if($usuario->añadirXp($this->app, $xp)){
                $this->setMessageAndRedirect("XP añadida: " . $xp);
            }else{
                $this->setMessageAndRedirect("Error al añadir XP");
            }
        } else {
            $this->setMessageAndRedirect("Flag incorrecta!!!");
        }
    }
}
