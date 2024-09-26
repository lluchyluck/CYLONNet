<?php

require_once __DIR__ . "/../../config.php";
require RUTA_INCLUDES . "/src/formulario.php";
require RUTA_INCLUDES . "/src/app.php";


class formulario_login extends formulario
{
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => '/ProyectoABD/index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'password'], $this->errores, 'span', array('class' => 'error'));

        //$rutaRegistro = RUTA_INCLUDES . "/src/register/register.php";
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Introduzca sus datos para el inicio de sesión:</legend>
            <div>
                <label for="nombreUsuario">Usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
                {$erroresCampos['nombreUsuario']}
            </div>
            <br>
            <div>
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            <br>
            <div>
                <button type="submit" name="login">Entrar</button>
            </div>
            <div>
            <p>¿No te has registrado todavía?, registrate aqui: <a href="/ProyectoABD/includes/src/register/register.php">Registro</a><p>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || empty($nombreUsuario) ) {
            $this->errores['nombreUsuario'] = '<span style="color: red;">El nombre de usuario no puede estar vacío.</span>';
        }
        
        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || empty($password) ) {
            $this->errores['password'] = '<span style="color: red;">El password no puede estar vacío.</span>';
        }
        
        if (count($this->errores) === 0) {
            $app = Aplicacion::getInstance();
            if($user = $app->existeUsuario($nombreUsuario)){         
                $app->login($user);
            }else{
                $this->errores[] = '<p style="color: red;">El usuario o el password no coinciden.</p>';
            }
        }
    }
}