<?php
require_once __DIR__ ."/../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION["login"] === true) {
    // Obtener el ID de la misión
    $missionId = $_POST['missionId'];
   
   
    if (($mission = $app->getMission("", $missionId)) !== null) {
        $containerName = $mission["dockerlocation"];
        // Comando para iniciar el contenedor
        $command = "docker start " . escapeshellarg($containerName);
        
        // Ejecutar el comando
        $output = shell_exec("ls");
        if ($output !== null) {
            echo "$output";
        } else {
            echo "Failed to start container.";
        }
    } else {
        echo "Invalid mission ID.";
    }
} else {
    echo "Invalid request, tienes que estar logueado para iniciar un contenedor!!!.";
}

