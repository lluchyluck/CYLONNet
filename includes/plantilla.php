<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php require_once __DIR__ . "/config.php"; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Musical</title>
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>" />
    
    
</head>
<body>
    <header>
        <?php require(RUTA_VISTAS . '/cabecera.php'); ?>
    </header>

    <main>
        <section class="contenedor-principal">
            <aside id="sidebar-izquierda">
                <?php require(RUTA_VISTAS . '/sidebarizq.php'); ?>
            </aside>

            <div id="contenido-central">
                
                <?= $html ?>
    
            </div>

            <aside id="sidebar-derecha">
                <?php require(RUTA_VISTAS . '/sidebarder.php'); ?>
            </aside>
        </section>
    </main>


    <footer>
        <?php require(RUTA_VISTAS . '/footer.php'); ?>
    </footer>

    <script src="script.js"></script>
</body>
</html>
