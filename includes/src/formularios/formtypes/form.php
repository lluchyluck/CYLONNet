<?php
require_once __DIR__ ."/../../../config.php";

abstract class Form{
    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    abstract public function handle();
    
    protected function setMessageAndRedirect($message) {
        $_SESSION["mensaje"] = $message;
        header("Location: ../../../view/blueprint.php");
        exit();
    }
}
