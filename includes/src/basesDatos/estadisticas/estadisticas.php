<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/app.php";

$tituloPagina = 'Estadisticas';

$app = Aplicacion::getInstance();


$html = verInfo($app);


require RUTA_INCLUDES . "/plantilla.php";

function verInfo($app)
{
    
    $esta = $app->getEstadisticas();
    $html ="";
    $html .= "<h3>Listado de las 5 canciones mas populares y su nota promedio:</h3>";
    $html .= "<table>";
    $html .= "<tr><th>Nombre</th><th>Artista</th><th>Género</th><th>Duración</th><th>Favorita</th></tr>";
    if (isset($esta) && !empty($esta)) {
        foreach ($esta as $cancion) {
            $html .= "<tr>";
            $html .= "<td>" . $cancion['name'] . "</td>";
            $html .="<td>" . $cancion['artista'] . "</td>";
            $html .="<td>" . $cancion['genero'] . "</td>";
            $html .="<td>" . $cancion['duracion'] . "</td>";
            $html .="<td>" . number_format($cancion['estrellas_promedio'], 2, '.', '') . "</td>"; // Format to one decimal place
            $html.= "</tr>";
        }
        $html .= "</table>";
    } else {
        $html .= "<tr><td colspan='5'>No hay datos disponibles</td></tr>";
    }
    return $html;
}