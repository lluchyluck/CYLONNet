<?php
require_once "src/usuario.php";

class Aplicacion
{

    private static $instancia;

    private $db;

    public static function getInstance()
    {
        if (!self::$instancia instanceof self) {
            self::$instancia = new static();
        }
        return self::$instancia;
    }

    public function getConexionBd()
    {
        if (!isset($this->db)) {
            $bdHost = "localhost";
            $bdUser = "root";
            $bdPass = "";
            $bdName = "CYLONNet";

            $db = @mysqli_connect($bdHost, $bdUser, $bdPass, $bdName);
            if ($db) {
                $this->db = $db;
            } else {
                echo 'Conexión erronea a la base de datos';
            }
        }
        return $this->db;

    }
    private function isInitialized()
    {
        if (isset($this->instancia)) {
            return true;
        } else {
            return false;
        }
    }
    public function objectToDataBase($objeto)
    {
        if (is_a($objeto, "Usuario")) {
            return $this->insertarUsuario($objeto);
        } else {
            echo "El objeto no pertenece a ninguna clase conocida";
            return false;
        }
    }
    private function insertarUsuario($usuario)
    {
        $db = $this->getConexionBd();
        $usuario->setId($this->nextIdUsuario());
        $sqlQuery = "INSERT INTO users (id, username, email, password, icon) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($db, $sqlQuery)) {
            $hashedPassword = password_hash($usuario->getPassword(), PASSWORD_BCRYPT);

            mysqli_stmt_bind_param(
                $stmt,
                "issss",
                $usuario->getId(),
                $usuario->getUsername(),
                $usuario->getEmail(),
                $hashedPassword,
                $usuario->getImg()
            );

            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($result) {
                return true;
            } else {
                error_log("Error al insertar el usuario: " . mysqli_error($db));
                return false;
            }
        } else {
            error_log("Error al preparar la consulta: " . mysqli_error($db));
            return false;
        }
    }
    public function logueaUsuario($user, $password)
    {

        if (($usuarioAComprobar = $this->existeUsuario($user, "")) !== null) {
               
            if (password_verify($password,$usuarioAComprobar["password"])) {
                return $usuarioAComprobar;
            } else {
                
                return false;
            }
        }
    }

    private function nextIdUsuario()
    {
        $db = $this->getConexionBd();
        $maxIDquery = "SELECT MAX(id) FROM users";
        $resultmaxIDquery = mysqli_query($db, $maxIDquery);
        $row = mysqli_fetch_row($resultmaxIDquery);
        $maxId = $row[0];
        mysqli_free_result($resultmaxIDquery);
        return $maxId + 1;
    }
    public function getAllUsers()
    {
        $db = $this->getConexionBd();
        $sql = "SELECT id, username,email, password, icon FROM users";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            $users = array();
            while ($row = mysqli_fetch_assoc($result)) {

                $users[] = $row; // Add complete user data to the array
            }
            mysqli_free_result($result);
            return $users;
        } else {
            return null;
        }
    }
    public function existeUsuario($nombreUsuario, $email)
    {
        $users = $this->getAllUsers();

        if (empty($users) || $users == null) {
            return null; // No users found, not an error
        }

        foreach ($users as $user) {
            if (($user['username'] === $nombreUsuario) || ($user['email'] === $email)) {
                return $user; // Return the complete user data
            }
        }

        return null;
    }
    public function logout()
    {
        unset($_SESSION['login']);
        unset($_SESSION['username']);
        unset($_SESSION['id']);
        unset($_SESSION['img']);
        session_destroy();
        session_start();
        $_SESSION["mensaje"] = "Sesion cerrada!!!";
    }
}