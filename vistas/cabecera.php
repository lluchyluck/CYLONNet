<div class="contenedor-header">
    <div class="contenedor-menu">
        <nav>
            <ul>
                <li><a href="/ProyectoABD/index.php">Inicio</a></li>
                <li><a href="<?php
                 require_once __DIR__ . "/../includes/config.php";
                if ($_SESSION["login"]) {
                    echo "/ProyectoABD/includes/src/basesDatos/basesDatos.php";
                } else {
                    echo "#";
                }
                ?>">Bases de datos</a></li>
                <li><a href="/ProyectoABD/includes/src/perfil/perfil.php">Perfil</a></li>
            </ul>
        </nav>
    </div>
    <div class="contenedor-sesion">
        <nav>
            <?php
            if (!$_SESSION["login"]) {
                echo '<a href="/ProyectoABD/includes/src/login/login.php">Login</a>';
            } else {
                echo '<b>Bienvenido:<a href="/ProyectoABD/includes/src/perfil/perfil.php">' . $_SESSION["username"] . '</a></b>';
            }
            ?>
             <?php
                require_once __DIR__ . "/../includes/config.php";
                if (!$_SESSION["login"]) {
                } else {
                    $imgSrc = $_SESSION["img"];
                    if ($imgSrc != "/") {
                        $view = <<<EOS
                            <img src="/ProyectoABD/assets/img/$imgSrc" width="50" height="50">                   
                        EOS;
                        echo $view;
                    }
                }
            ?>
            <button id="cerrarSesion" onclick="location.href='/ProyectoABD/includes/src/logout/logout.php';">Cerrar Sesión</button>
           


        </nav>
    </div>
</div>