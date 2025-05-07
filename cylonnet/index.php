<?php
require_once "./includes/config.php";

?>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./assets/images/cylonPNG.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYLONNet - Cybersecurity CTF Platform</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <canvas id="starfield"></canvas>
    <div class="header">
        <div id="animated-text" class="title-logo">
            <img src="./assets/images/cylonPNG.png" style="width: 90px;">
            <h1>CYLONNet</h1>
        </div>

        <div class="center-container">
            <div id="xp-display" class="xp-display">0/200</div>
            <div class="experience-bar-container">
                <div id="experience-bar" class="experience-bar"></div>
            </div>
            <div id="level-text" class="level-text">Level: 1</div>

        </div>



        <div class="profile" id="profile">
            <div class="profile-pic-container">
                <img src="<?php if ($_SESSION["login"] === true)
                    echo "./assets/images/profile" . $_SESSION["icon"];
                else
                    echo "./assets/images/profile/icon.gif"; ?>" alt="Profile picture" class="profile-pic">
                <img id="rank-badge" class="rank-badge" src="">
            </div>
            <span id="username">Guest</span>
        </div>

    </div>
    <div class="profile-dropdown" id="profile-dropdown">
        <h3 onclick="profile('<?php if ($_SESSION['login'] === true)
            echo $_SESSION['username']; ?>')" style="cursor: pointer; color: inherit; transition: color 0.3s ease;"
            onmouseover="this.style.filter='brightness(1.2)';" onmouseout="this.style.filter='brightness(1)';">Perfil
            <img id="rank-badgee" class="rank-badge" src=""></h3>
        <p>Usuario: <span id="dropdown-username">Guest</span></p>
        <p>Email: <span id="dropdown-email">Not logged in</span></p>
        <?php if ($_SESSION["login"] === true)
            echo '<form action="./includes/src/formularios/formHandler.php" method="POST"><button type="submit" name="logout_button" class="button">Logout</button></form>';
        else
            echo '<button onclick="loadContent(\'login\')" class="button">Login</button>'; ?>

    </div>
    <div class="main-container">
        <nav class="menu">
            <a data-page="home"><img src="./assets/images/menu/basecyclon.png" alt="Home icon" width="50"
                    height="50">Inicio</a>
            <a data-page="missions"><img src="./assets/images/menu/raider.png" alt="Missions icon" width="50"
                    height="50">Misiones</a>
            <a data-page="leaderboard"><img src="./assets/images/menu/leaderboard.png" alt="Leaderboard icon" width="50"
                    height="50">Clasificación</a>
            <a data-page="manual"><img src="./assets/images/menu/manual.png" alt="Manual icon" width="50"
                    height="50">Manual</a>
            <?php if ($_SESSION["developer"] === true) {
                echo '<a data-page="developer"><img src="./assets/images/menu/gearwheel.png" alt="Dev icon" width="50" height="50">Admin</a>';
            } ?>
        </nav>
        <main class="content" id="content">
            <!-- El contenido se carga  dinamicamente -->
        </main>
    </div>
    <div id="message-box"></div>
    <div id="csrf-token" data-csrf-token="<?php echo $_SESSION["csrf_token"] ?? ''; ?>"></div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="module" src="./assets/js/modules/contentHandler.js"></script>
    <script src="./assets/js/modules/user.js"></script>
    <script src="./assets/js/modules/animations.js"></script>
    <script src="./assets/js/modules/menu.js"></script>
    <script src="./assets/js/app.js"></script>
    <script>
        <?php if (isset($_SESSION["mensaje"]) && $_SESSION["mensaje"] !== null): ?>
            console.log("Mensaje en sesión encontrado: <?php echo htmlspecialchars($_SESSION["mensaje"]); ?>");
            $(document).ready(function () {
                console.log("Document ready");
                var messageBox = $("#message-box");
                messageBox.text("<?php echo htmlspecialchars($_SESSION["mensaje"]); ?>");
                messageBox.show();
                console.log("Message box mostrada");
                setTimeout(function () {
                    messageBox.hide();
                    console.log("Message box ocultada");
                    <?php unset($_SESSION["mensaje"]); ?>
                }, 5000);
            });
        <?php else: ?>
            console.log("No se encontró mensaje en la sesión");
        <?php endif; ?>
    </script>
    <script>
        <?php if (isset($_SESSION["login"]) && $_SESSION["login"] === true): ?>
            
            const username = "<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>";
            const email = "<?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?>";
            const xp = <?php echo isset($_SESSION['xp']) ? (int)$_SESSION['xp'] : 0; ?>;
            const level = calculateLevel(xp).currentLevel;
            const levelImg = rankImage(level);
            console.log(level, levelImg);

            document.addEventListener("DOMContentLoaded", () => {
                login(username, email);
                updateExperienceBar(xp);
                $('#rank-badge').attr('src', levelImg);
                $('#rank-badgee').attr('src', levelImg);
            });
        <?php else: ?>
            document.addEventListener("DOMContentLoaded", () => {
                    updateExperienceBar(0);
                    $('#rank-badge').attr('src', './assets/images/ranks/rank1.png');
                    $('#rank-badgee').attr('src', './assets/images/ranks/rank1.png');
                });
        <?php endif; ?>

    </script>
</body>

</html>