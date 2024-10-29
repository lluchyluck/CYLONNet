<?php

require_once __DIR__ ."/src/objects/usuario.php";
require_once __DIR__ ."/src/objects/mission.php";

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
    public function objectOutDatabase($objeto){
        if (is_a($objeto, "Usuario")) {
            return false;
        }else if (is_a($objeto, "Mission")) {
            return $this->eliminarMision($objeto);
        } else {
            echo "El objeto no pertenece a ninguna clase conocida";
            return false;
        }
    }
    private function executeQuery($query, $params, $types)
    {
        $db = $this->getConexionBd();
        if ($stmt = mysqli_prepare($db, $query)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        } else {
            error_log("Error al preparar la consulta: " . mysqli_error($db));
            return false;
        }
    }
    private function insertarUsuario($usuario)
    {
        $usuario->setId($this->nextId("users"));
        $hashedPassword = password_hash($usuario->getPassword(), PASSWORD_BCRYPT);
        $query = "INSERT INTO users (id, username, email, password, developer, icon) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->executeQuery($query, [$usuario->getId(), $usuario->getUsername(), $usuario->getEmail(), $hashedPassword, false, $usuario->getImg()], "isssis");
    }
    private function insertarMision($mision)
    {
        $mision->setId($this->nextId("ctfs"));
        $tagsJson = json_encode(['tagnames' => array_map('trim', explode(',', $mision->getTags()))]);
        $query = "INSERT INTO ctfs (id, name, description, tags, icon, dockerlocation) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->executeQuery($query, [$mision->getId(), $mision->getName(), $mision->getDescription(), $tagsJson, $mision->getIcon(), "/TODO"], "isssss");
    }
    private function eliminarMision($mision)
    {
        $query = "DELETE FROM ctfs WHERE id = ?";
        return $this->executeQuery($query, [$mision->getId()], "i");
    }

    public function addAdmin($user){
        $userId = $user["id"]; // ID del usuario que quieres actualizar
        $developer = 1; // Nuevo valor para el campo developer
        $sqlQuery = "UPDATE users SET developer = ? WHERE id = ?";
    
        return $this->executeQuery($sqlQuery, [$developer, $userId], 'ii');
    }
    public function logueaUsuario($user, $password)
    {

        if (($usuarioAComprobar = $this->getUser($user, "")) !== null) {
               
            if (password_verify($password,$usuarioAComprobar["password"])) {
                return $usuarioAComprobar;
            } else {
                
                return null;
            }
        }
        return null;
    }
    private function nextId($table)
    {
        $db = $this->getConexionBd();
        $query = "SELECT MAX(id) + 1 AS next_id FROM $table";
        $result = mysqli_query($db, $query);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row['next_id'] ?? 1;
    }
    private function fetchAll($query)
{
    $db = $this->getConexionBd();
    $result = mysqli_query($db, $query);

    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_free_result($result); // Liberar el resultado
    }

    return $data;
}

    public function getAllUsers()
    {
        return $this->fetchAll("SELECT id, username, email, password, developer, icon FROM users");
    
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
    public function getMission($nombre, $id) //se puede buscar por nombre o id
    {
        $missions = $this->getAllMissions();

        if (empty($missions) || $missions == null) {
            return null; // No users found, not an error
        }

        foreach ($missions as $mission) {
            if (($mission['name'] === $nombre) || ($mission['id'] === $id)) {
                return $mission; // Return the complete user data
            }
        }

        return null;
    }
    public function getAllMissions()
    {
        return $this->fetchAll("SELECT id, name, description, tags, icon, dockerlocation FROM ctfs");
  
    }
    public function getAllTags()
    {
        $result = $this->fetchAll("SELECT tagname FROM tags");
        return array_column($result, "tagname");
    }
    
}