<?php


require_once __DIR__ . "/../../config.php";
require RUTA_INCLUDES . "/src/register/formulario_register.php";



$formRegister = new formulario_register();
$htmlFormRegister = $formRegister->gestiona();

$tituloPagina = 'Register';

$html = <<<EOS
<article>
    <h1>Register</h1>
    $htmlFormRegister
</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
