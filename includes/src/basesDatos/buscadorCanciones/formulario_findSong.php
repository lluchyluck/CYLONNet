<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/formulario.php";
require_once RUTA_INCLUDES . "/src/app.php";


class formulario_findSong extends formulario
{
    public function __construct()
    {
        parent::__construct('formFindSong');
    }
    private function htmlGeneros()
    {
        $app = Aplicacion::getInstance();
        $generos = $app->getAllGenders();
        if($generos == null)
            return "No hay suficiente informacion en la base de datos";
        foreach ($generos as $genero) {
            $html .= "<option value=" . $genero . ">" . $genero . "</option>";
        }

        return $html;
    }
    protected function generaCamposFormulario(&$datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['buscarCancion', 'genero'], $this->errores, 'span', array('class' => 'error'));

        //$rutaRegistro = RUTA_INCLUDES . "/src/register/register.php";
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $htmlSelect = $this->htmlGeneros();
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Buscador de canciones:</legend>
            <div>
                <label for="buscarCancio">Nombre:</label>
                <input id="buscarCancion" type="text" name="buscarCancion"/>
                {$erroresCampos['buscarCancion']}
            </div>
            <br>
            <div>
                <label for="Gener">Genero:</label>
                <select id='genero' name='genero'>
                <option value='--'>--</option>
                $htmlSelect
                </select>
                {$erroresCampos['genero']}
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
        $nombre = trim($datos['buscarCancion'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($nombre == "") {
            $nombre = 1;
        }

        $genero = trim($datos['genero'] ?? '');
        $genero = filter_var($genero, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($genero == "--") {
            $genero = 1;
        }

        if (count($this->errores) === 0) {
            $app = Aplicacion::getInstance();

            $canciones = $app->getSong($nombre, $genero);
            $html = "";

            if ($canciones == null) {
                $html .= "<h2>No hemos encontrado canciones, por favor intentelo de nuevo!!!</h2>";
            } else {
                if (!isset($canciones)) {
                    $html .= "<p>No hay canciones para mostrar</p>";
                    return $html;
                }
                $html .= "<h3>Canciones encontradas con su busqueda:</h3>";
                $html .= "<table>";
                $html .= "<tr><th>Nombre</th><th>Género</th><th>Artista</th><th>Duración</th><th>Favorita</th></tr>";
                foreach ($canciones as $cancion) {
                    $html .= "<tr>";
                    $html .= "<td>" . $cancion['name'] . "</td>";
                    $html .= "<td>" . $cancion['genero'] . "</td>";
                    $html .= "<td>" . $cancion['artista'] . "</td>";
                    $html .= "<td>" . $cancion['duracion'] . "</td>";
                    $result = $app->getFavouriteSong($cancion['id']);
                    if($result != null){
                        $html .= "<td>&#10003;</td>"; //tic
                    }else
                        $html .= "<td>&#10007;<form style='background: none; border: none; padding: none; margin: none; box-shadow: none; border-radius: none;' id='unique-form' action='/ProyectoABD/includes/src/perfil/cancionesFavoritas/añadirCancionFav.php' method='post'><input type='hidden' name='songId' value =" . $cancion['id']. "><label style='display: inline;'>Stars:</label><input type='number' id='stars' name='stars' value='0' min='0' max='5'><button type='submit'>Añadir</button>
                      </form></td>"; //no tic
                    $html .= "</tr>";
                }
                $html .= "</table>";
            }

        }
        return $html;
    }
}