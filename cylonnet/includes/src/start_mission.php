<?php
require_once __DIR__ ."/../src/objects/mission.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION["login"] === true) {
    // Obtener el ID de la misión
    $missionId = filter_input(INPUT_POST, 'missionId', FILTER_DEFAULT);
    $mission = new Mission($app, $missionId);
    if ($mission->getExistence()) {
        $containerName = $mission->getDockerloc();

        // Comando para comprobar el contenedor
        $scriptPath = escapeshellcmd("./../../assets/sh/estado_contenedor.sh");
        $containerPath = escapeshellarg($containerName);
        $isReadyCommand = "$scriptPath $containerPath";
        $output = [];
        $returnCode = 0;
        exec($isReadyCommand, $output, $returnCode);
        if ($returnCode === 0) {
            $mission->renewFlag();        //continua ejecucion   
        }else{
            $nombreContenedorFormateado = preg_replace('/^\/|\.tar\.gz$/', '', $containerName);
            echo "===============================================\n";
            echo "       ¡El contenedor ya estaba iniciado!       \n";
            echo "===============================================\n\n";
            echo "Por favor, añade el siguiente bloque a tu archivo /etc/hosts:\n\n";
            echo "    IP_SERVIDOR    " . $nombreContenedorFormateado . ".cylonnet\n\n";
            echo "Una vez hecho eso, podrás acceder a tu laboratorio en:\n\n";
            echo "    http://" . $nombreContenedorFormateado . ".cylonnet\n\n";
            echo "===============================================\n";
            echo "Nota: El laboratorio se eliminará automáticamente en 60 minutos.\n";
            echo "===============================================\n";
            exit; //no continua la ejecucion ya que el contenedor ya estaba iniciado
        }

        // Comando para iniciar el contenedor
        $scriptPath = escapeshellcmd("./../../assets/sh/deploy.sh");
        $uflag = escapeshellarg("FELICIDADES CYLON, USER FLAG: " . $mission->getuFlag());
        $rflag = escapeshellarg("FELICIDADES CYLON, ROOT FLAG: " . $mission->getrFlag());
        $initCommand = "$scriptPath $containerPath $uflag $rflag";

        
        exec($initCommand, $output, $returnCode);

        if ($returnCode === 0) {
            // Mostrar el output si el comando tuvo éxito
            echo htmlspecialchars(implode("\n", $output));
        } else {
            // Mostrar error y log adicional para depuración
            echo implode("\n", $output);
            error_log("Error in deploy.sh: " . implode("\n", $output));
        }
    } else {
        echo "Invalid mission ID.";
    }
} else {
    echo "Invalid request, tienes que estar logueado para iniciar un contenedor!!!.";
}
