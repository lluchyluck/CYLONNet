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
            #-----PARA DESPLIEGUE FINAL-----
            $bdHost = "db";
            $bdUser = "cylon_adm";
            $bdPass = "cambia_esto";
            $bdName = "CYLONNet";
            #-----PARA DESARROLLO LOCAL-----
            #$bdHost = "localhost";
            #$bdUser = "root";
            #$bdPass = "";
            #$bdName = "CYLONNet";


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
    public function executeQuery($query, $params = [], $types = "", &$output = null)
    {
        $db = $this->getConexionBd();
        
        // Preparar la consulta
        if (!$stmt = mysqli_prepare($db, $query)) {
            error_log("Error al preparar la consulta: " . mysqli_error($db));
            return false;
        }

        // Si hay parámetros, vincularlos
        if (!empty($params) && !empty($types)) {
            if (!mysqli_stmt_bind_param($stmt, $types, ...$params)) {
                error_log("Error al vincular parámetros: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return false;
            }
        }

        // Ejecutar la consulta
        if (!mysqli_stmt_execute($stmt)) {
            error_log("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }

        // Si existen resultados, obtenerlos
        if ($output !== null) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result) {
                $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_free_result($result);
            } else {
                error_log("Error al obtener el resultado: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return false;
            }
        }

        mysqli_stmt_close($stmt);
        return true;
    }


   
    public function nextId($table)
    {
        $query = "SELECT MAX(id) + 1 AS next_id FROM $table";
        $output = [];
        if ($this->executeQuery($query, [], "", $output)) {
            return $output[0]['next_id'] ?? 1;
        }
        return 1;
    }
    private function fetchAll($query)
    {
        $output = [];
        if ($this->executeQuery($query, [], "", $output)) {
            return $output;
        }
        return [];
    }

    public function getAllUsers()
    {
        return $this->fetchAll("SELECT id, username, email, password, xp, developer, icon FROM users");
    
    }
    public function getUser($id, $nombreUsuario, $email)
    {
        $users = $this->getAllUsers();

        if (empty($users) || $users == null) {
            return null; // No users found, not an error
        }

        foreach ($users as $user) {
            if (((int)$user['id'] === $id) || ($user['username'] === $nombreUsuario) || ($user['email'] === $email)) {
                return $user; // Return the complete user data
            }
        }

        return null;
    }
    public function getUserMissions($username){
        $query = "SELECT DISTINCT c.id, c.name, c.tags, c.difficulty, c.icon FROM ctfs c JOIN userxctf x ON c.id = x.id_ctf JOIN users u ON x.id_user = u.id WHERE u.username = ? AND (x.ucompletado = 1 OR x.rcompletado = 1)";
        $output = [];
        if($this->executeQuery($query, [$username], "s",$output)){
            return $output;
        }
        return false;
    }
    public function getUserTop()
    {
        $query = "SELECT id, username, xp, icon FROM users WHERE id != 1 ORDER BY xp DESC LIMIT 10";
        $output = [];
        if ($this->executeQuery($query, [], "", $output)) {
            return $output;
        }
        return false;
    }

    public function getMission($nombre, $id) //se puede buscar por nombre o id
    {
        $missions = $this->getAllMissions();

        if (empty($missions) || $missions == null) {
            return null; // No missions found, not an error
        }

        foreach ($missions as $mission) {
            if (($mission['name'] === $nombre) || ((int)$mission['id'] === (int)$id)) {
                return $mission; // Return the complete user data
            }
        }

        return null;
    }
    public function getAllMissions()
    {
        return $this->fetchAll("SELECT DISTINCT c.id, c.name, c.description, c.tags, c.difficulty, c.icon, c.dockerlocation, u.username FROM ctfs c LEFT JOIN userxctf x ON c.id = x.id_ctf LEFT JOIN users u ON u.id = x.id_user WHERE x.creada = 1;");
  
    }
    public function getAllTags()
    {
        $result = $this->fetchAll("SELECT tagname FROM tags");
        return array_column($result, "tagname");
    }
}