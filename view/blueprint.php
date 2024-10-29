<?php
    require_once "../includes/config.php";

?>



<html>

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/images/cylonPNG.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYLONNet - Cybersecurity CTF Platform</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <canvas id="starfield"></canvas>
    <div class="header">
        <div class="profile" id="profile">
            <img src="<?php if($_SESSION["login"] === true)  echo "/CYLONNet/assets/images/profile" . $_SESSION["icon"]; else  echo "/CYLONNet/assets/images/profile/icon.gif"; ?>" alt="Profile picture" class="profile-pic" >
            <span id="username">Guest</span>
        </div>
        <div id="animated-text" class="title-logo">
        <img src="../assets/images/cylonPNG.png" style="width: 90px;">
        <h1>CYLONNet</h1>
        </div>
    </div>
    <div class="profile-dropdown" id="profile-dropdown">
        <h3>Profile</h3>
        <p>Username: <span id="dropdown-username">Guest</span></p>
        <p>Email: <span id="dropdown-email">Not logged in</span></p>
        
            <?php if($_SESSION["login"] === true)  echo '<form action="../includes/src/formularios/formHandler.php" method="POST"><button type="submit" name="logout_button" class="button">Logout</button></form>'; else echo '<button onclick="loadContent(\'login\')" class="button">Login</button>'; ?>
       
    </div>
    <div class="main-container">
        <nav class="menu">
            <a data-page="home"><img src="../assets/images/menu/basecyclon.png" alt="Home icon" width="50"
                    height="50">Home</a>
            <a data-page="missions"><img src="../assets/images/menu/raider.png" alt="Missions icon" width="50"
                    height="50">Missions</a>
            <a data-page="developer"><img src="../assets/images/menu/gearwheel.png" alt="Dev icon" width="50"
                    height="50">Developer</a>
        </nav>
        <main class="content" id="content">
            <!-- El contenido se carga  dinamicamente -->
        </main>
    </div>
    <div id="message-box"></div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/modules/contentLoader.js"></script>
    <script src="../assets/js/modules/user.js"></script>
    <script src="../assets/js/modules/animations.js"></script>
    <script src="../assets/js/modules/menu.js"></script>
    <script src="../assets/js/app.js"></script>
    <script>
        <?php if(isset($_SESSION["mensaje"]) && $_SESSION["mensaje"] !== null): ?>
        console.log("Mensaje en sesión encontrado: <?php echo htmlspecialchars($_SESSION["mensaje"]); ?>");
        $(document).ready(function() {
            console.log("Document ready");
            var messageBox = $("#message-box");
            messageBox.text("<?php echo htmlspecialchars($_SESSION["mensaje"]); ?>");
            messageBox.show();
            console.log("Message box mostrada");
            setTimeout(function() {
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
        <?php if(isset($_SESSION["login"]) && $_SESSION["login"] === true): ?>
        login("<?php echo $_SESSION['username']; ?>", "<?php echo $_SESSION['email']; ?>");

        
        <?php endif; ?>
    </script>
</body>

</html>