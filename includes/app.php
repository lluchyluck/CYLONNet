<?php

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
    public function executeQuery($query, $params, $types)
    {
        $db = $this->getConexionBd();
        
        if (!$stmt = mysqli_prepare($db, $query)) {
            error_log("Error al preparar la consulta: " . mysqli_error($db));
            return false;
        }
        if (!mysqli_stmt_bind_param($stmt, $types, ...$params)) {
            error_log("Error al vincular parámetros: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
        if (!$result = mysqli_stmt_execute($stmt)) 
            error_log("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        
        return $result;
    }

   
    public function nextId($table)
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