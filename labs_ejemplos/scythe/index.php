<?php
ob_start();
session_start();

// Configuración para mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// ======== CONEXIÓN A BD ========
$conn = new mysqli("localhost", "scythe", "scypassword", "scythe_db");
$conn->set_charset("utf8mb4");
// Verificar conexión
if ($conn->connect_error) {
    die("<div class='alert'>Error de conexión Cylon: " . $conn->connect_error . "</div>");
}


// Mostrar errores SQL en pantalla (vulnerabilidad crítica)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ======== VULNERABILIDAD SQLi ========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['page'])) {
    try {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Query vulnerable con información de error detallada
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $_SESSION['admin'] = true;
            $_SESSION['username'] = $username;
            header("Location: index.php?page=admin");
            exit;
        } else {
            // Lanzar excepción para credenciales inválidas
            throw new Exception("ERROR: INVALID CREDENTIALS - CYBER ATTACK DETECTED");
        }
    } catch (mysqli_sql_exception $e) { // Capturar errores SQL
        $error_message = "Error Cylon DB-357: " . $e->getMessage() . "<br>Query: " . $query;
    } catch (Exception $e) { // Capturar excepciones generales (credenciales inválidas)
        $error_message = $e->getMessage();
    }
}

// ======== PANEL ADMIN ========
if (isset($_GET['page']) && $_GET['page'] === 'admin') {
    if (!isset($_SESSION['admin'])) {
        die("<div class='alert'>¡Alerta! Navegación no autorizada<br>Error Code: CY7-0N3<br><a href='index.php'>Volver al login</a></div>");
    }

    // ======== VULNERABILIDAD SUBIDA DE ARCHIVOS ========
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['weapon_config'])) {
        $upload_dir = "uploads/";

        // Crear directorio con permisos 777 (vulnerable)
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = $_FILES['weapon_config']['name'];
        $file_path = $upload_dir . $file_name;

        // Filtro vulnerable: Verifica solo si el nombre CONTIENE '.conf' (no la extensión real)
        if (strpos($file_name, '.conf') === false) {
            $upload_message = "Error Cylon-357: Solo se permiten archivos .conf";
        } else {
            // Subida del archivo (vulnerable a .conf.php)
            if (move_uploaded_file($_FILES['weapon_config']['tmp_name'], $file_path)) {
                $upload_message = "Firmware subido:<br><a href='$file_path'>$file_path</a><br>MD5: " . md5_file($file_path);
            } else {
                $upload_message = "Error en subida:<br>" . error_get_last()['message'];
            }
        }
    }

    // ======== VULNERABILIDAD DE DATOS EXPUESTOS ========
    try {
        $weapons = $conn->query("SELECT * FROM weapons");
        $users = $conn->query("SELECT username, password FROM users"); // Exponer credenciales
    } catch (Exception $e) {
        $db_error = "Error Cylon DB-358: " . $e->getMessage();
    }
}
?>

<!-- Mantener el HTML/CSS anterior pero agregar estas secciones -->

<?php if (isset($_GET['page']) && $_GET['page'] == 'admin'): ?>
    <!-- Sección nueva: Credenciales expuestas -->
    <div class="alert" style="margin-top: 2rem;">
        <h3>DEBUG SYSTEM INFORMATION:</h3>
        <?php while ($user = $users->fetch_assoc()): ?>
            Usuario: <?= $user['username'] ?> | Contraseña: <?= $user['password'] ?><br>
        <?php endwhile; ?>
    </div>

<?php endif; ?>

<!-- El HTML/CSS permanece igual -->

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Mismo CSS que ya tienes -->
    <style>
        /* Estilo de terminal militar */
        :root {
            --cylon-red: #ff0033;
            --deep-space: #0a0a1a;
            --hud-green: #00ff88;
        }

        body {
            background: linear-gradient(to bottom right, var(--deep-space), #000);
            color: var(--hud-green);
            font-family: 'Roboto Mono', monospace;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            border: 2px solid var(--cylon-red);
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: rgba(0, 20, 30, 0.9);
            border: 1px solid var(--cylon-red);
            box-shadow: 0 0 20px rgba(255, 0, 51, 0.3);
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--cylon-red);
            text-align: center;
            text-transform: uppercase;
            border-bottom: 2px solid var(--cylon-red);
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        input[type="text"],
        input[type="password"],
        input[type="file"] {
            background: #001015;
            border: 1px solid var(--cylon-red);
            color: var(--hud-green);
            padding: 0.8rem;
            font-family: 'Roboto Mono', monospace;
        }

        button {
            background: var(--cylon-red);
            color: black;
            border: none;
            padding: 1rem 2rem;
            font-family: 'Orbitron', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background: #ff0044;
            box-shadow: 0 0 15px var(--cylon-red);
        }

        .alert {
            color: var(--cylon-red);
            padding: 1rem;
            border: 1px solid var(--cylon-red);
            margin: 1rem 0;
            text-align: center;
        }

        .upload-section {
            margin-top: 2rem;
            padding: 2rem;
            background: rgba(255, 0, 51, 0.1);
        }

        .uploaded-file {
            margin-top: 1rem;
            padding: 1rem;
            background: #002030;
            border-left: 3px solid var(--cylon-red);
        }

        .cylon-scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background: repeating-linear-gradient(0deg,
                    rgba(0, 255, 136, 0.1) 0px,
                    rgba(0, 255, 136, 0.1) 1px,
                    transparent 1px,
                    transparent 2px);
            z-index: -1;
        }

        /* Nuevos estilos para la tabla */
        .weapons-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        .weapons-table th,
        .weapons-table td {
            border: 1px solid var(--cylon-red);
            padding: 0.8rem;
            text-align: left;
        }

        .weapons-table th {
            background: rgba(255, 0, 51, 0.2);
        }
    </style>
</head>

<body>
    <div class="cylon-scanline"></div>

    <div class="container">
        <?php if (isset($_GET['page']) && $_GET['page'] == 'admin'): ?>
            <!-- PANEL ADMIN -->
            <h1>SYSTEM OVERRIDE - SCYTHE WEAPONS CONTROL</h1>
            <p>Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

            <div class="upload-section">
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="weapon_config" required>
                    <button type="submit">UPLOAD WEAPON FIRMWARE</button>
                </form>

                <?php if (isset($upload_message)): ?>
                    <div class="uploaded-file">
                        <?php echo $upload_message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <h2>Active Weapon Systems</h2>
            <table class="weapons-table">
                <tr>
                    <th>ID</th>
                    <th>Weapon Name</th>
                    <th>Target</th>
                </tr>
                <?php while ($row = $weapons->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['target']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

        <?php else: ?>
            <!-- LOGIN -->
            <h1>SCYTHE DEFENSE SYSTEM v2.3</h1>
            <?php if (isset($error_message)): ?>
                <div class="alert">
                    <?= $error_message ?> <!-- Mostrar error SQL o credenciales inválidas aquí -->
                </div>
            <?php endif; ?>
            <form class="login-form" method="POST">
                <input type="text" name="username" placeholder="IDENTIFICATION" required>
                <input type="password" name="password" placeholder="ACCESS CODE" required>
                <button type="submit">AUTHORIZE</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>