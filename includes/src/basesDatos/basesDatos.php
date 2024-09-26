<?php

require_once __DIR__ . "/../../config.php";

$tituloPagina = 'Bases de datos';

$html = <<<EOS
<article>
    <h1>Bases de datos buscador</h1>
    <div class="contenedor-cuadrado">
        <div class="subcuadrado">
            <a href="/ProyectoABD/includes/src/basesDatos/buscadorCanciones/buscadorCanciones.php">Buscador Canciones</a>
        </div>
        <div class="subcuadrado">
            <a href="/ProyectoABD/includes/src/basesDatos/buscadorArtistas/buscadorArtistas.php">Buscador Artistas</a>
        </div>
        <div class="subcuadrado">
            <a href="/ProyectoABD/includes/src/basesDatos/verBase/verBase.php">Ver base de datos</a>
        </div>
        <div class="subcuadrado">
            <a href="/ProyectoABD/includes/src/basesDatos/infoExtra/infoExtra.php">Informacion extra</a>
        </div>
    </div>
    <div class="contenedor-cuadrado">
        <div class="subcuadrado">
            <a href="/ProyectoABD/includes/src/basesDatos/estadisticas/estadisticas.php">Estadisticas</a>
        </div>
        <div class="subcuadrado">
            <a href="/ProyectoABD/includes/src/basesDatos/añadirCancion/añadirCancion.php">Añadir cancion</a>
        </div>
    </div>

</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
