<?php

abstract class Form{
    protected $app;

    public function __construct($app) {
        $this->app = $app;
    }

    abstract public function handle();
    
    protected function setMessageAndRedirect($message) {
        $_SESSION["mensaje"] = $message;
        header("Location: ../../../index.php");
        exit();
    }
}
