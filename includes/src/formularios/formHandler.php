<?php

function handler(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['login_button'])){
            
            require_once __DIR__ ."/formtypes/form_login.php";
            
            $form = new FormLogin($app);
            
            $form->handle();
        }else if(isset($_POST['logout_button'])){
            require_once __DIR__ ."/formtypes/form_logout.php";
            $form = new FormLogout($app);
            $form->handle();
        }else if (isset($_POST['register_button'])){
           
            require_once __DIR__ ."/formtypes/form_register.php";
            
            $form = new FormRegister($app);
           
            $form->handle();
        }else if (isset($_POST['add_mission_button'])){
           
            require_once __DIR__ ."/formtypes/form_newMission.php";
            if(($_SESSION["login"] === true) && isset($_SESSION["developer"]) && ($_SESSION["developer"] === true)){
                $form = new FormNewMission($app);
                $form->handle();
            }else{
                return;
            }
        }
    }
}

handler();