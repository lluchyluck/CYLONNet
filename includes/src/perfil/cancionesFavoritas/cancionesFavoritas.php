<?php

require_once __DIR__ . "/../../../config.php";
require_once RUTA_INCLUDES . "/src/app.php";
require RUTA_INCLUDES . "/src/basesDatos/buscadorCanciones/formulario_findSong.php";

$tituloPagina = 'Canciones Favoritas';

$app = Aplicacion::getInstance();

$html = "";
$html .= verCancionesFavoritas($app);
$html .= verAñadirCanciones();


require RUTA_INCLUDES . "/plantilla.php";

function verCancionesFavoritas($app)
{
    $html = "";
    $songs = $app->getAllFavouriteSongs();


    if (!isset($songs)) {
        $html = "<p>No hay canciones para mostrar</p>";
        return $html;
    }
    $html .= "<h3>Canciones favoritas de: " . $_SESSION["username"] . "</h3>";
    $html .= "<table>";
    $html .= "<tr><th>Nombre</th><th>Género</th><th>Artista</th><th>Duración</th><th>Estrellas</th><th>Favorita</th></tr>";

    foreach ($songs as $song) {
        $html .= "<tr>";
        $html .= "<td>" . $song['name'] . "</td>";
        $html .= "<td>" . $song['genero'] . "</td>";
        $html .= "<td>" . $song['artista'] . "</td>";
        $html .= "<td>" . $song['duracion'] . "</td>";
        $html .= "<td>" . $song['stars'] . "</td>";
        $result = $app->getFavouriteSong($song['id']);
        if($result != null)
            $html .= "<td>&#10003;<form style='background: none; border: none; padding: none; margin: none; box-shadow: none; border-radius: none;' id='unique-form' action='/ProyectoABD/includes/src/perfil/cancionesFavoritas/eliminarCancionFav.php' method='post'><input type='hidden' name='songId' value =" . $song['id'] . "><button type='submit'>Eliminar</button></form></td>"; //tic
        else
            $html .= "<td>&#10007;<form style='background: none; border: none; padding: none; margin: none; box-shadow: none; border-radius: none;' id='unique-form' action='/ProyectoABD/includes/src/perfil/cancionesFavoritas/añadirCancionFav.php' method='post'><input type='hidden' name='songId' value =" . $song['id'] . "><label style='display: inline;'>Stars:</label><input type='number' id='stars' name='stars' value='0' min='0' max='5'><button type='submit'>Añadir</button></form></td>"; //no tic
        $html .= "</tr>";
    }

    $html .= "</table>";
    return $html;
}
function verAñadirCanciones()
{
    $html = "";
    $formFindSong = new formulario_findSong();
    $htmlFormFindSong = $formFindSong->gestiona();
    $html .= "<h3>Añadir cancion favorita: </h3>";
    $html .= <<<EOS
    <article>
        $htmlFormFindSong
    </article>
    EOS;
    return $html;

}