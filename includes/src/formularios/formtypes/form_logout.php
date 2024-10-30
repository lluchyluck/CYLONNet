<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../../config.php";
class FormLogout extends Form {
    public function handle() {
        $this->logout();
    }

    private function logout() {
        session_unset();
        session_destroy();
        session_start();
        $this->setMessageAndRedirect("Sesión cerrada.");
    }
}
