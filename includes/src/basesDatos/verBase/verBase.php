<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/app.php";

$tituloPagina = 'Bases de datos';

$app = Aplicacion::getInstance();


$html = verBase($app);


require RUTA_INCLUDES . "/plantilla.php";

function verBase($app)
{
    $html = "";
    $songs = $app->getAllSongs();
    

    if (!isset($songs)) {
        $html = "<p>No hay canciones para mostrar</p>";
        return $html;
    }

    $html .= "<table>";
    $html .= "<tr><th>Nombre</th><th>Género</th><th>Artista</th><th>Duración</th></tr>";

    foreach ($songs as $song) {
        $html .= "<tr>";
        $html .= "<td>" . $song['name'] . "</td>";
        $html .= "<td>" . $song['genero'] . "</td>";
        $html .= "<td>" . $song['artista'] . "</td>";
        $html .= "<td>" . $song['duracion'] . "</td>";
        $html .= "</tr>";
    }

    $html .= "</table>";
    return $html;
}