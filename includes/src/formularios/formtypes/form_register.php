<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/usuario.php";

class FormRegister extends Form {
    public function handle() {
        

        $nombre_usuario = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $img = ($_FILES["image"]["size"] !== 0) ? $_FILES["image"] : null;
        
        
        if (!$this->validateInputs($nombre_usuario, $email, $password, $img)) {
            return;
        }
        
        $imagenRuta = ($img !== null) ? $this->handleImageUpload($img) : "/icon.gif";
        
        if ($imagenRuta === false) {
            return;
        }
        
        $user = new Usuario($nombre_usuario, $password, $email, $imagenRuta);
        
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

        if (strlen($password) < 8) {
            $this->setMessageAndRedirect("La contraseña debe tener al menos 8 caracteres.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_usuario)) {
            $this->setMessageAndRedirect("El nombre de usuario solo puede contener letras, números y guiones bajos.");
            return false;
        }

        if ($image !== null && !$this->validateImage($image)) {
            return false;
        }

        return true;
    }

    private function handleImageUpload($img) {
        $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . "/CYLONNet/assets/images/profile/" . basename($img["name"]);

        if (!move_uploaded_file($img['tmp_name'], $ruta_destino)) {
            $this->setMessageAndRedirect("Error al guardar la imagen en el servidor.");
            return false;
        }

        return "/" . basename($img["name"]);
    }

    private function validateImage($image) {
        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        $fileType = mime_content_type($image['tmp_name']);
        if (!in_array($fileType, $allowedMimeTypes)) {
            $this->setMessageAndRedirect("Tipo de archivo no permitido. Solo se permiten imágenes JPEG y PNG.");
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
        
        if ($this->app->getUser($user->getUsername(), $user->getEmail()) !== null) {
            
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
