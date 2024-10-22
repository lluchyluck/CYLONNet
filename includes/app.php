<?php

require_once __DIR__ ."/src/usuario.php";

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
        }else if (is_a($objeto, "Mission")) {
            return $this->insertarMision($objeto);
        } else {
            echo "El objeto no pertenece a ninguna clase conocida";
            return false;
        }
    }
    private function insertarUsuario($usuario)
    {
        $db = $this->getConexionBd();
        $usuario->setId($this->nextIdUsuario());
        $sqlQuery = "INSERT INTO users (id, username, email, password, developer, icon) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($db, $sqlQuery)) {
            echo "hola";
            $hashedPassword = password_hash($usuario->getPassword(), PASSWORD_BCRYPT);
            $developerDefault=false;
            mysqli_stmt_bind_param(
                $stmt,
                "isssis",
                $usuario->getId(),
                $usuario->getUsername(),
                $usuario->getEmail(),
                $hashedPassword,
                $developerDefault,
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
    private function insertarMision($mision)
    {
        $db = $this->getConexionBd();
        $mision->setId($this->nextIdMision());
        $sqlQuery = "INSERT INTO ctfs (id, name, description, tags, icon, dockerlocation) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($db, $sqlQuery)) {
            $hashedPassword = password_hash($mision->getPassword(), PASSWORD_BCRYPT);
            $dockerlocation="/TODO";
            mysqli_stmt_bind_param(
                $stmt,
                "isssss",
                $mision->getId(),
                $mision->getName(),
                $mision->getDescription(),
                $mision->getTags(),
                $mision->getIcon(),
                $dockerlocation
            );
            
            $result = mysqli_stmt_execute($stmt);
            
            mysqli_stmt_close($stmt);

            if ($result) {
                return true;
            } else {
                error_log("Error al insertar la mision: " . mysqli_error($db));
                return false;
            }
        } else {
            error_log("Error al preparar la consulta: " . mysqli_error($db));
            return false;
        }
    }
    public function logueaUsuario($user, $password)
    {

        if (($usuarioAComprobar = $this->getUser($user, "")) !== null) {
               
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
    private function nextIdMision()
    {
        $db = $this->getConexionBd();
        $maxIDquery = "SELECT MAX(id) FROM ctfs";
        $resultmaxIDquery = mysqli_query($db, $maxIDquery);
        $row = mysqli_fetch_row($resultmaxIDquery);
        $maxId = $row[0];
        mysqli_free_result($resultmaxIDquery);
        return $maxId + 1;
    }
    public function getAllUsers()
    {
        $db = $this->getConexionBd();
        $sql = "SELECT id, username,email, password, developer, icon FROM users";
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
    public function getUser($nombreUsuario, $email)
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
    public function getMission($nombre)
    {
        $missions = $this->getAllMissions();

        if (empty($missions) || $missions == null) {
            return null; // No users found, not an error
        }

        foreach ($missions as $mission) {
            if (($mission['username'] === $nombre)) {
                return $mission; // Return the complete user data
            }
        }

        return null;
    }
    public function getAllMissions()
    {
        $db = $this->getConexionBd();
        $sql = "SELECT id, name,description,tags,icon,dockerlocation FROM ctfs";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            $missions = array();
            while ($row = mysqli_fetch_assoc($result)) {

                $missions[] = $row; // Add complete user data to the array
            }
            mysqli_free_result($result);
            return $missions;
        } else {
            return null;
        }
    }
    public function getAllTags()
    {
        $db = $this->getConexionBd();
        $sql = "SELECT tagname FROM tags";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            $tags = array();
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {

                $tags[$i] = $row["tagname"]; // Add complete user data to the array
                $i++;
            }
            mysqli_free_result($result);
            return $tags;
        } else {
            return null;
        }
    }
    
}