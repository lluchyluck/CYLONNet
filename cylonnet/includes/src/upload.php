<?php
require_once "./../config.php";
require_once __DIR__ . "./../src/objects/mission.php";

function registrarMision($mision, $app)
{
    if ($app->getMission($mision->getName(), null)) {
        echo "Ya existe una mision con ese nombre!!!";
        return false;
    }

    if ($mision->insertarDB($app)) {
        return true;
    }

    echo "Error al insertar en la DB";
    return false;
}

function validateImage($image)
{
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $fileType = mime_content_type($image['tmp_name']);

    if (!in_array($fileType, $allowedMimeTypes)) {
        echo "Tipo de archivo no permitido. Solo se permiten imágenes JPEG y PNG.";
        return false;
    }

    if ($image['size'] > 2 * 1024 * 1024) {
        echo "El tamaño de la imagen no debe superar los 2 MB.";
        return false;
    }

    [$width, $height] = getimagesize($image['tmp_name']);
    if ($width > 1000 || $height > 1000) {
        echo "La imagen no debe exceder 1000x1000 píxeles.";
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z]{2,4}$/', $image['name'])) {
        echo "El nombre de la imagen solo puede contener letras, números y guiones bajos.";
        return false;
    }

    return true;
}

function handleImageUpload($img)
{
    $rutaDestino = __DIR__ . '/../../assets/images/missions/' . basename($img['name']);


    if (!move_uploaded_file($img['tmp_name'], $rutaDestino)) {
        echo "Error al guardar la imagen en el servidor.";
        return false;
    }

    return "/" . basename($img['name']);
}

function validateInputs($name, $description, $tags, $difficulty, $image)
{
    if (empty($name) || empty($description) || empty($tags)) {
        echo "Por favor, completa todos los campos.";
        return false;
    }

    if (strlen($description) > 500) {
        echo "La descripción no puede exceder 500 caracteres.";
        return false;
    }

    if (strlen($tags) < 1) {
        echo "Debe de haber al menos alguna etiqueta!!!";
        return false;
    }
    if(($difficulty > 5)  || ($difficulty < 1)){
        echo "La dificultad debe de estar entre 1 y 5!!!.";
        return false;
    }
    if (!preg_match('/^[a-zA-Z0-9_ ]+$/', $name)) {
        echo "El nombre de la misión solo puede contener letras, números y guiones bajos.";
        return false;
    }

    return $image === null || validateImage($image);
}

function handleMission($app)
{
    $name = filter_input(INPUT_POST, 'missionName', FILTER_DEFAULT);
    $description = filter_input(INPUT_POST, 'descripcion', FILTER_DEFAULT);
    $tags = filter_input(INPUT_POST, 'tags', FILTER_DEFAULT);
    $difficulty = filter_input(INPUT_POST, 'difficulty', FILTER_DEFAULT);
    $img = ($_FILES['icon']['size'] > 0) ? $_FILES['icon'] : null;
    $dockerLoc = "/" . basename(filter_input(INPUT_POST, 'fileName', FILTER_DEFAULT));

    if (!validateInputs($name, $description, $tags,$difficulty, $img)) {
        return false;
    }

    $imagenRuta = $img ? handleImageUpload($img) : "/default.jpg";
    if ($imagenRuta === false) {
        return false;
    }

    $mision = new Mission($app, $app->nextId("ctfs"), $name, $description, $tags, $difficulty, $imagenRuta, $dockerLoc);
    if(!$mision->getExistence())
        return registrarMision($mision, $app);
    else
        return false;
}

function upload($app)
{
    $uploadDir = './uploads/';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            echo "Error: No se pudo crear el directorio de subida.";
            return;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'], $_POST['chunkIndex'], $_POST['totalChunks'], $_POST['fileName'])) {
        $chunkIndex = intval($_POST['chunkIndex']);
        $totalChunks = intval($_POST['totalChunks']);
        $fileName = basename(filter_input(INPUT_POST, 'fileName', FILTER_DEFAULT));
        $tempFilePath = $uploadDir . $fileName . '.part' . $chunkIndex;
        
        if ($chunkIndex === 0 && !handleMission($app)) {
            return ;
        }
        if (move_uploaded_file($_FILES['file']['tmp_name'], $tempFilePath)) {
            chmod($tempFilePath, 0774); // Set appropriate permissions for the part file
            
            if (allChunksUploaded($uploadDir, $fileName, $totalChunks)) {
                combineChunks($uploadDir, $fileName, $totalChunks);
            }
            echo "OK";
        } else {
            echo "Error al subir el fragmento número " . $chunkIndex;
        }
    } else {
        echo "ERROR";
    }
}

function allChunksUploaded($uploadDir, $fileName, $totalChunks)
{
    for ($i = 0; $i < $totalChunks; $i++) {
        if (!file_exists($uploadDir . $fileName . '.part' . $i)) {
            return false;
        }
    }
    return true;
}

function combineChunks($uploadDir, $fileName, $totalChunks)
{
    $dockerPath = "./../../assets/sh/labos/";
    $finalFilePath = $dockerPath . $fileName;

    if (!is_dir($dockerPath)) {
        echo "Error: el directorio de destino no existe.";
        return;
    }

    $finalFile = fopen($finalFilePath, 'wb');
    if ($finalFile === false) {
        echo "Error al abrir el archivo final.";
        return;
    }

    for ($i = 0; $i < $totalChunks; $i++) {
        $partFile = fopen($uploadDir . $fileName . '.part' . $i, 'rb');
        if ($partFile === false) {
            echo "Error al abrir el fragmento número " . $i;
            fclose($finalFile);
            return;
        }

        while (!feof($partFile)) {
            fwrite($finalFile, fread($partFile, 1024));
        }
        fclose($partFile);
        unlink($uploadDir . $fileName . '.part' . $i);
    }

    fclose($finalFile);
}

upload($app);
