<?php 

require_once __DIR__ . "/src/objects/usuario.php";


$mission = new Usuario($app, 3);

var_dump($mission);
echo "<br>". $mission->getExistence();