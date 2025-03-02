<?php
require_once __DIR__ ."/../src/objects/mission.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION["login"] === true) {
    // Obtener el ID de la misión
    $missionId = filter_input(INPUT_POST, 'missionId', FILTER_VALIDATE_INT);
    $mission = new Mission($app, $missionId);
    if ($mission->getExistence()) {
        $containerName = $mission->getDockerloc();

        // Comando para iniciar el contenedor
        $scriptPath = escapeshellcmd("./../../assets/sh/isReady.sh");
        $containerPath = escapeshellarg("./../../assets/sh/labos" . $containerName);
        $isReadyCommand = "$scriptPath $containerPath";
        // Ejecutar el comando y capturar el output y código de salida
        $output = [];
        $returnCode = 0;
        exec($isReadyCommand, $output, $returnCode);
        if ($returnCode === 0) {
            $mission->renewFlag();           
        }else{
            echo implode("\n", $output);
            error_log("Error in deploy.sh: " . implode("\n", $output));
            exit;
        }
        $scriptPath = escapeshellcmd("./../../assets/sh/deploy.sh");
        $uflag = escapeshellarg("FELICIDADES CYLON, USER FLAG{" . $mission->getuFlag() . "}");
        $rflag = escapeshellarg("FELICIDADES CYLON, ROOT FLAG{" . $mission->getrFlag() . "}");
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
