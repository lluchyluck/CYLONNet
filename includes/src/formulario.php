<?php
abstract class formulario
{
    protected $formId;

    protected $method;

    protected $action;

    protected $classAtt;

    protected $enctype;

    protected $urlRedireccion;

    protected $errores;

    public function __construct($formId, $opciones = array())
    {
        $this->formId = $formId;

        $opcionesPorDefecto = array('action' => null, 'method' => 'POST', 'class' => null, 'enctype' => null, 'urlRedireccion' => null);
        $opciones = array_merge($opcionesPorDefecto, $opciones);

        $this->action = $opciones['action'];
        $this->method = $opciones['method'];
        $this->classAtt = $opciones['class'];
        $this->enctype  = $opciones['enctype'];
        $this->urlRedireccion = $opciones['urlRedireccion'];

        if (!$this->action) {
            $this->action = htmlspecialchars($_SERVER['REQUEST_URI']);
        }
    }

    public function gestiona()
    {
        $datos = &$_POST;
        if(isset($_FILES['foto']))
            $datos['foto']  = $_FILES['foto']; //gestiona las fotos
        
        //print_r($_FILES); 
        
        if (strcasecmp('GET', $this->method) == 0) {
            $datos = &$_GET;
        }
        $this->errores = [];

        if (!$this->formularioEnviado($datos)) {
            return $this->generaFormulario();
        }
        
        $html = $this->procesaFormulario($datos);
        

        $esValido = count($this->errores) === 0;
        

        if (! $esValido ) {
            return $this->generaFormulario($datos);
        }
        

        if ($this->urlRedireccion !== null) {
            
            header("Location: {$this->urlRedireccion}");
            exit();
        }
        return $html;
    }

    protected function generaCamposFormulario(&$datos)
    {
        return '';
    }

    protected function procesaFormulario(&$datos)
    {

    }

    protected function formularioEnviado(&$datos)
    {
        return isset($datos['formId']) && $datos['formId'] == $this->formId;
    }

    protected function generaFormulario(&$datos = array())
    {
        $htmlCamposFormularios = $this->generaCamposFormulario($datos);

        $classAtt = $this->classAtt != null ? "class=\"{$this->classAtt}\"" : '';

        $enctypeAtt = $this->enctype != null ? "enctype=\"{$this->enctype}\"" : '';

        $htmlForm = <<<EOS
        <form method="{$this->method}" action="{$this->action}" id="{$this->formId}" {$classAtt} {$enctypeAtt}>
            <input type="hidden" name="formId" value="{$this->formId}" />
            $htmlCamposFormularios
        </form>
        EOS;
        return $htmlForm;
    }

    protected static function generaListaErroresGlobales($errores = array(), $classAtt = '')
    {
        $clavesErroresGlobales = array_filter(array_keys($errores), function ($elem) {
            return is_numeric($elem);
        });

        $numErrores = count($clavesErroresGlobales);
        if ($numErrores == 0) {
            return '';
        }

        $html = "<ul class=\"$classAtt\">";
        foreach ($clavesErroresGlobales as $clave) {
            $html .= "<li>$errores[$clave]</li>";
        }
        $html .= '</ul>';

        return $html;
    }

    protected static function createMensajeError($errores = [], $idError = '', $htmlElement = 'span', $atts = [])
    {
        if (! isset($errores[$idError])) {
            return '';
        }

        $att = '';
        foreach ($atts as $key => $value) {
            $att .= "$key=\"$value\" ";
        }
        $html = "<$htmlElement $att>{$errores[$idError]}</$htmlElement>";

        return $html;
    }

    protected static function generaErroresCampos($campos, $errores, $htmlElement = 'span', $atts = []) {
        $erroresCampos = [];
        foreach($campos as $campo) {
            $erroresCampos[$campo] = self::createMensajeError($errores, $campo, $htmlElement, $atts);
        }
        return $erroresCampos;
    }
}