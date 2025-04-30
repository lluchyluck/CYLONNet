<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";

class FormRegister extends Form {
    public function handle() {
        

        $nombre_usuario = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'];
        $img = ($_FILES["image"]["size"] !== 0) ? $_FILES["image"] : null;
        
        
        if (!$this->validateInputs($nombre_usuario, $email, $password, $img)) {
            return;
        }
        
        $imagenRuta = ($img !== null) ? $this->handleImageUpload($img) : "/icon.gif";
        
        if ($imagenRuta === false) {
            return;
        }
        
        $user = new Usuario($this->app, $this->app->nextId("users"),$nombre_usuario, $password, $email, $imagenRuta);
        
        $this->registrarUsuario($user);
        
    }

    private function validateInputs($nombre_usuario, $email, $password, $image) {
        if (empty($nombre_usuario) || empty($email) || empty($password)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setMessageAndRedirect("Formato de correo electrónico no válido.");
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $this->setMessageAndRedirect("El correo electrónico no tiene un formato válido.");
            return false;
        }
        $domain = substr(strrchr($email, "@"), 1); 
        if ($domain !== "ucm.es") {
            $this->setMessageAndRedirect("El correo electrónico debe pertenecer a ucm.es.");
            return false;
        }
        
        if (strlen($password) < 8) {
            $this->setMessageAndRedirect("La contraseña debe tener al menos 8 caracteres.");
            return false;
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $this->setMessageAndRedirect("La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_usuario)) {
            $this->setMessageAndRedirect("El nombre de usuario solo puede contener letras, números y guiones bajos.");
            return false;
        }
        if (strlen($nombre_usuario) > 50) {
            $this->setMessageAndRedirect("El nombre de usuario no puede tener más de 50 caracteres.");
            return false;
        }

        if ($image !== null && !$this->validateImage($image)) {
            return false;
        }

        return true;
    }

    private function handleImageUpload($img) {
        $ruta_destino = __DIR__ . '/../../../../assets/images/profile/' . basename($img["name"]);

        if (!move_uploaded_file($img['tmp_name'], $ruta_destino)) {
            $this->setMessageAndRedirect("Error al guardar la imagen en el servidor.");
            return false;
        }

        return "/" . basename($img["name"]);
    }

    private function validateImage($image) {
        if (!preg_match('/^[a-zA-Z0-9_]+\.(jpg|jpeg|png)$/', $image["name"])) {
            $this->setMessageAndRedirect("El nombre de la imagen solo puede contener letras, números y guiones bajos, y debe tener una extensión .jpg, .jpeg o .png.");
            return false;
        }
        if (!getimagesize($image['tmp_name'])) {
            $this->setMessageAndRedirect("El archivo no es una imagen válida.");
            return false;
        }
        if ($image['size'] > 2 * 1024 * 1024) {
            $this->setMessageAndRedirect("El tamaño de la imagen no debe superar los 2 MB.");
            return false;
        }

        list($width, $height) = getimagesize($image['tmp_name']);
        if ($width > 400 || $height > 400 || $width !== $height) {
            $this->setMessageAndRedirect("La imagen debe ser cuadrada y no exceder 400x400 píxeles.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+(\.[a-zA-Z]+)$/', $image["name"])) {
            $this->setMessageAndRedirect("El nombre de la imagen solo puede contener letras, números y guiones bajos.");
            return false;
        }

        return true;
    }

    private function registrarUsuario($user) {
        
        if ($this->app->getUser($user->getId(),$user->getUsername(), $user->getEmail()) !== null) {
            
            $this->setMessageAndRedirect("Error en el registro: el usuario ya está registrado.");
            return false;
        }
        if ($user->insertarDB($this->app)) {
            $this->setMessageAndRedirect("Registro exitoso. Ahora puedes iniciar sesión.");
            return true;
        } else {
            $this->setMessageAndRedirect("Error en el registro. Inténtalo nuevamente.");
            return false;
        }
    }
}
