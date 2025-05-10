<?php

function handler(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['login_button'])){ //si se ha enviado el formulario de login
            
            require_once __DIR__ ."/formtypes/form_login.php";
            $form = new FormLogin($app);
            $form->handle();
        }else if(isset($_POST['logout_button'])){ //si se ha enviado el formulario de logout
            require_once __DIR__ ."/formtypes/form_logout.php";
            $form = new FormLogout($app);
            $form->handle();
        }else if (isset($_POST['register_button'])){//si se ha enviado el formulario de registro
           
            require_once __DIR__ ."/formtypes/form_register.php";
            
            $form = new FormRegister($app);
           
            $form->handle();
        }else if (isset($_POST['add_admin_button'])){//si se ha enviado el formulario de añadir admin
           
            require_once __DIR__ ."/formtypes/form_addAdmin.php";
            if(($_SESSION["login"] === true) && isset($_SESSION["login"]) && isset($_SESSION["developer"]) && ($_SESSION["developer"] === true)){
                $form = new FormAddAdmin($app);
                $form->handle();
            }else{
                return;
            }
        }else if (isset($_POST['remove_mission_button'])){//si se ha enviado el formulario de eliminar mision
           
            require_once __DIR__ ."/formtypes/form_removeMission.php";
            if(($_SESSION["login"] === true) && isset($_SESSION["login"]) && isset($_SESSION["developer"]) && ($_SESSION["developer"] === true)){
                $form = new FormRemoveMission($app);
                $form->handle();
            }else{
                return;
            }
        }else if (isset($_POST['remove_admin_button'])){//si se ha enviado el formulario de eliminar admin
           
            require_once __DIR__ ."/formtypes/form_removeAdmin.php";
            if(($_SESSION["login"] === true) && isset($_SESSION["login"]) && isset($_SESSION["developer"]) && ($_SESSION["developer"] === true)){
                $form = new FormRemoveAdmin($app);
                $form->handle();
            }else{
                return;
            }
        }else if (isset($_POST['flag_button'])){
           
            require_once __DIR__ ."/formtypes/form_submitFlag.php";
            if(($_SESSION["login"] === true) && isset($_SESSION["login"])){
                $form = new FormSubmitFlag($app);
                $form->handle();
            }else{
                return;
            }
        }
        else{
            echo "Los ctfs se encuentran en otro lugar piratilla!!!.";
        }
    }else{
        echo "No vas a encontrar nada piratilla!!!";
    }
}

handler();
