<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Echoes of the Galactica</title>
    <style>
        /* Contenedor de fondo filtrado */
        .bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('fondo.jpg') center/cover no-repeat;
            filter: blur(8px) brightness(1.3); /* Blur y brillo sólo aquí */ 
            z-index: 0;
        }

        /* Superposición oscura para atenuar el brillo */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            z-index: 1;
        }

        /* Contenido principal, sin filtros */
        .container {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            font-size: 3em;
            color: #fff200;                     /* Amarillo brillante para contraste */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            margin-bottom: 1rem;
        }

        form {
            background-color: rgba(51, 51, 51, 0.85);
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0,255,204,0.6);
            width: 320px;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1.1em;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 6px;
            background-color: #444;
            color: #e0e0e0;
            font-size: 1em;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            background-color: #00cc99;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #009966;
        }

        pre {
            background: rgba(34,34,34,0.85);
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1.5rem;
            text-align: left;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="bg"></div>
    <div class="overlay"></div>
    <div class="container">
        <h1>Echoes of the Galactica</h1>
        <form method="POST" action="">
            <label for="command">Human Mirror:</label>
            <input type="text" name="command" id="command" required>
            <button type="submit">Ejecutar</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $command = $_POST['command'];
            echo "<pre>";
            system("echo " . $command);
            echo "</pre>";
        }
        ?>
    </div>
</body>
</html>
