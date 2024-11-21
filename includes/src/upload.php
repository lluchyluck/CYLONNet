<?php
// Ruta del directorio donde se almacenarán los fragmentos de docker temporalmente
$uploadDir = './uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Recibir el fragmento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica si el archivo y los datos del fragmento están presentes
    if (isset($_FILES['file']) && isset($_POST['chunkIndex']) && isset($_POST['totalChunks']) && isset($_POST['fileName'])) {
        $chunkIndex = intval($_POST['chunkIndex']);
        $totalChunks = intval($_POST['totalChunks']);
        $fileName = basename($_POST['fileName']);  // Obtener el nombre original del archivo
        $tempFilePath = $uploadDir . $fileName . '.part' . $chunkIndex;  // Ruta temporal para almacenar cada fragmento
        
        // Mover el archivo temporal a la ubicación deseada
        if (move_uploaded_file($_FILES['file']['tmp_name'], $tempFilePath)) {
            echo "Fragmento $chunkIndex subido con éxito.";

            // Comprobar si todos los fragmentos han sido subidos
            $allChunksUploaded = true;
            for ($i = 0; $i < $totalChunks; $i++) {
                if (!file_exists($uploadDir . $fileName . '.part' . $i)) {
                    $allChunksUploaded = false;
                    break;
                }
            }

            // Si todos los fragmentos están disponibles, combinar los fragmentos
            if ($allChunksUploaded) {
                // Crear un archivo de destino final
                $dockerPath = "./../../assets/sh/labos/";
                $finalFilePath = $dockerPath . $fileName;
                $finalFile = fopen($finalFilePath, 'wb');
                
                // Combinar los fragmentos
                for ($i = 0; $i < $totalChunks; $i++) {
                    $partFile = fopen($uploadDir . $fileName . '.part' . $i, 'rb');
                    while (!feof($partFile)) {
                        fwrite($finalFile, fread($partFile, 1024));  // Escribir contenido de cada fragmento en el archivo final
                    }
                    fclose($partFile);
                    // Eliminar el fragmento después de escribirlo
                    unlink($uploadDir . $fileName . '.part' . $i);
                }

                fclose($finalFile);
                echo 'Archivo combinado con éxito: ' . $finalFilePath;
            }
        } else {
            echo "Error al subir el fragmento $chunkIndex.";
        }
    } else {
        echo "Faltan datos necesarios.";
    }
}
?>
