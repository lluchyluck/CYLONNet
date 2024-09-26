<?php

require_once __DIR__ . "/../../config.php";
require RUTA_INCLUDES . "/src/login/formulario_login.php";


$formLogin = new formulario_login();
$htmlFormLogin = $formLogin->gestiona();

$tituloPagina = 'Login';

$html = <<<EOS
<article>
    <h1>Login</h1>
    $htmlFormLogin
</article>
EOS;

require RUTA_INCLUDES . "/plantilla.php";
