<?php 

require_once "includes/config.php";


$html = <<<EOS
<article>
    <h1>Bienvenido a SongRepository</h1>
    <p>
            En el mundo acelerado de hoy, la música se ha convertido en una compañera indispensable, una fuente de consuelo, inspiración y pura alegría. Seas un aficionado a la música experimentado o un oyente casual, tener acceso a una biblioteca musical vasta y diversa es un recurso invaluable. En SongRepository, nos apasiona brindarte una experiencia musical incomparable, que se adapte a todos los gustos y preferencias.
        </p>

        <h2>Tu puerta de entrada a un universo musical</h2>

        <ul>
            <li>Extensa biblioteca musical con géneros, épocas y artistas</li>
            <li>Interfaz fácil de usar para una navegación y búsqueda sencillas</li>
            <li>Listas de reproducción y recomendaciones personalizadas</li>
            <li>Streaming de audio de alta calidad en todos los dispositivos</li>
            <li>Una comunidad próspera de entusiastas de la música</li>
        </ul>

        <h2>Únete a la revolución musical</h2>

        <p>
            Regístrate hoy y comienza a explorar las posibilidades infinitas de [Nombre de tu biblioteca musical]. Deja que la música te mueva, te inspire y te conecte con el mundo que te rodea.
        </p>

</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";

if(isset($_SESSION["mensaje"])){
    echo "<script>alert('" . $_SESSION["mensaje"] . "');</script>";
    $_SESSION["mensaje"] = null;
}