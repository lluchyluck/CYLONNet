<?php

require_once __DIR__ . "/../../../config.php";
require RUTA_INCLUDES . "/src/app.php";
require RUTA_INCLUDES . "/src/formulario.php";
require_once  RUTA_INCLUDES . "/src/cancion.php";

class formulario_añadirCancion extends formulario
{
    protected $app;
    protected $usuario;

    public function __construct()
    {

        parent::__construct('formAddSong', ['urlRedireccion' => "/ProyectoABD/index.php", 'enctype' => 'multipart/form-data']);
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
        $erroresCampos = self::generaErroresCampos(['nombre', 'genero', 'artista', 'duracion'], $this->errores, 'span', array('class' => 'error'));
        $htmlSelect = $this->htmlGeneros();
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Añada los datos necesarios para la nueva canción:</legend>
            <div>
                <label for="nombre"><strong>Nombre de la Cancion:  </strong></label>
                <input type="text" id="nombre" name="nombre"><br><br>
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="genero"><strong>Genero:  </strong></label>
                <select id='genero' name='genero'>
                <option value='--'>--</option>
                $htmlSelect
                </select>
                {$erroresCampos['genero']}
            </div>
            <div> 
                <label for="artista"><strong>Artista:  </strong></label>
                <input type="text" id="artista" name="artista"><br><br>
                {$erroresCampos['artista']}
            </div> 
            <div> 
                <label for="duracion"><strong>Duracion en mins:  </strong></label>
                <input type="text" id="duracion" name="duracion"><br><br>
                {$erroresCampos['duracion']}
            </div> 
            <br>
            <button type="submit">Añadir Cancion</button>
        </fieldset>


        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombre || empty($nombre)) {
            $this->errores['nombre'] = '<span style="color: red;">El nombre no puede estar vacío.</span>';

        }

        $genero = trim($datos['genero'] ?? '');
        $genero = filter_var($genero, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$genero || empty($genero) || $genero == "--") {
            $this->errores['genero'] = '<span style="color: red;">El genero no puede estar vacío.</span>';
        }
        $artista = trim($datos['artista'] ?? '');
        $artista = filter_var($artista, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$artista || empty($artista)) {
            $this->errores['artista'] = '<span style="color: red;">El artista no puede estar vacío.</span>';
        }
        $duracion = trim($datos['duracion'] ?? '');
        $duracion = filter_var($duracion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$duracion || empty($duracion)) {
            $this->errores['duracion'] = '<span style="color: red;">La duracion no puede estar vacía.</span>';
        }


        if (count($this->errores) === 0) {
            

            $app = Aplicacion::getInstance();
           

            $newSong = new Cancion($nombre, $genero, $artista, $duracion);
            
            
            
            //comprobar que el usuario no esta ya en la base de datos para prevenir de duplicados
            if ($app->existeCancion($nombre) != null) {
                
                $this->errores[] = '<span style="color: red;">[-]Error al añadir una Cancion: cancion ya existente.</span>';
            } else {
                
                


                if (!$app->objectToDataBase($newSong)) {
                    $this->errores[] = '<p style="color: red;">Error al añadir una cancion.</p>';
                } else {

                    $_SESSION["mensaje"] = 'Cancion añadida con éxito.';
                    $_SESSION["accion"] = true;

                }
            }
        }

    }
}