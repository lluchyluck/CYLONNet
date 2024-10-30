<?php
require_once __DIR__ ."/form.php";
require_once __DIR__ ."/../../objects/mission.php";

class FormNewMission extends Form {
    private $maxDescriptionChars = 500;

    public function handle() {
        
        
        $name = filter_input(INPUT_POST, 'mission_name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'mission_description', FILTER_SANITIZE_STRING);
        $tags = filter_input(INPUT_POST, 'mission_tags', FILTER_SANITIZE_STRING);
        $img = ($_FILES["image"]["size"] !== 0) ? $_FILES["image"] : null;
        $dockerLoc = "/TODO";
        
        if (!$this->validateInputs($name, $description, $tags, $img)) {
            return;
        }
        
        $imagenRuta = ($img !== null) ? $this->handleImageUpload($img) : "/default.jpg";
        
        if ($imagenRuta === false) {
            return;
        }
        $mision = new Mission($name, $description, $tags, $imagenRuta, $dockerLoc);
        
        $this->registrarMision($mision);
        
    }

    private function validateInputs($name, $description, $tags, $image) {
        if (empty($name) || empty($description) || empty($tags)) {
            $this->setMessageAndRedirect("Por favor, completa todos los campos.");
            return false;
        }

        if (strlen($description) > $this->maxDescriptionChars) {
            $this->setMessageAndRedirect("Formato de descripcion no válido, no puede exceder mas de 500 caracteres!!!.");
            return false;
        }

        if (strlen($tags) < 8) {
            $this->setMessageAndRedirect("La contraseña debe tener al menos 8 caracteres.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            $this->setMessageAndRedirect("El nombre de la misión solo puede contener letras, números y guiones bajos.");
            return false;
        }

        if ($image !== null && !$this->validateImage($image)) {
            return false;
        }

        return true;
    }

    private function handleImageUpload($img) {
        $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . "/CYLONNet/assets/images/missions/" . basename($img["name"]);

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
        if ($width > 1000 || $height > 1000) {
            $this->setMessageAndRedirect("La imagen no debe exceder 1000x1000 píxeles.");
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+(\.[a-zA-Z]+)$/', $image["name"])) {
            $this->setMessageAndRedirect("El nombre de la imagen solo puede contener letras, números y guiones bajos.");
            return false;
        }

        return true;
    }

    private function registrarMision($mision) {
        if ($this->app->getMission($mision->getName(), null) !== null) {        
            $this->setMessageAndRedirect("Error en el registro: la misión ya está registrada.");
            return false;
        }
        
        if ($mision->insertarDB($this->app)) {
            $this->setMessageAndRedirect("Registro exitoso. Ahora puedes ver tu misión.");
            return true;
        } else {
            $this->setMessageAndRedirect("Error en el registro de mision. Inténtalo nuevamente.");
            return false;
        }
    }
}
