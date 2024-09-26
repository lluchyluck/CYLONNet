<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/formulario.php";
require RUTA_INCLUDES . "/src/app.php";


class formulario_findArtist extends formulario
{
    public function __construct() {
        parent::__construct('formFindARtist');
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['buscarArtista'], $this->errores, 'span', array('class' => 'error'));

        //$rutaRegistro = RUTA_INCLUDES . "/src/register/register.php";
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Buscador de Artistas:</legend>
            <div>
                <label for="buscarArti">Nombre:</label>
                <input id="buscarArtista" type="text" name="buscarArtista"/>
                {$erroresCampos['buscarArtista']}
            </div>
            <br>
            <div>
                <button type="submit" name="buscar">Sumbit</button>
            </div>

        </fieldset>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nombre = trim($datos['buscarArtista'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || empty($nombre) ) {
            $this->errores['buscarArtista'] = '<span style="color: red;">El artista no puede estar vacio.</span>';
        }
    
        
        if (count($this->errores) === 0) {
            $app = Aplicacion::getInstance();
            $canciones = $app->getArtist($nombre);
            $html = "";
            if ($canciones == null) {
                $html .= "<h2>No hemos encontrado tu artista, por favor intentelo de nuevo!!!</h2>";
            } else {
                if (!isset($canciones)) {
                    $html .= "<p>No hay canciones para mostrar</p>";
                    return $html;
                }
                $html .= "<h2>" . $nombre. ":</h2>";
                $html .= "<table>";
                $html .= "<tr><th>Nombre</th><th>Género</th><th>Duración</th></tr>";
                foreach ($canciones as $cancion) {
                    $html .= "<tr>";
                    $html .= "<td>" . $cancion['name'] . "</td>";
                    $html .= "<td>" . $cancion['genero'] . "</td>";
                    $html .= "<td>" . $cancion['duracion'] . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</table>";
            }

        }
        return $html;
    }
}