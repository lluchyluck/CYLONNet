<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYLONNet - Cybersecurity CTF Platform</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <canvas id="starfield"></canvas>
    <div class="header">
        <div class="profile" id="profile">
            <img src="https://cylonnet.bsg/images/default-profile.svg" alt="Profile picture" class="profile-pic"
                id="profile-pic">
            <span id="username">Guest</span>
        </div>
        <div id="animated-text">
        <h1>CYLONNet</h1>
        </div>
    </div>
    <div class="profile-dropdown" id="profile-dropdown">
        <h3>Profile</h3>
        <p>Username: <span id="dropdown-username">Guest</span></p>
        <p>Email: <span id="dropdown-email">Not logged in</span></p>
        <button class="button" id="logout-button">Logout</button>
    </div>
    <div class="main-container">
        <nav class="menu">
            <a data-page="home"><img src="https://cylonnet.bsg/images/home.svg" alt="Home icon" width="50"
                    height="50">Home</a>
            <a data-page="missions"><img src="https://cylonnet.bsg/images/mission.svg" alt="Missions icon" width="50"
                    height="50">Missions</a>
            <a data-page="login"><img src="https://cylonnet.bsg/images/login.svg" alt="Login icon" width="50"
                    height="50">Login</a>
            <a data-page="register"><img src="https://cylonnet.bsg/images/register.svg" alt="Register icon" width="50"
                    height="50">Register</a>
        </nav>
        <main class="content" id="content">
            <!-- Content will be dynamically loaded here -->
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/modules/contentLoader.js"></script>
    <script src="../assets/js/modules/user.js"></script>
    <script src="../assets/js/modules/animations.js"></script>
    <script src="../assets/js/modules/menu.js"></script>
    <script src="../assets/js/app.js"></script>
    

</body>

</html>