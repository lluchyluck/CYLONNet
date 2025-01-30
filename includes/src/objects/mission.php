<?php
require_once __DIR__ ."/../../config.php";
class Mission
{
    // Propiedades privadas para encapsular los datos

    private $app;
    private $id;
    private $name;
    private $description;
    private $tags;
    private $difficulty;
    private $icon;
    private $dockerlocation;
    private $flag;
    private $exist;

    // Constructor para inicializar las propiedades
    public function __construct($app, $id, $name = null,$description = null, $tags= null, $difficulty = null, $icon= null, $dockerlocation= null)
    {
        //comprobar si el campo id se ha introducido y si un usuario existe ya con ese campo, si no se devolvera lo que ya hay ahi:
        $this->app = $app;
        $this->id = (int)$id;
        if(($missionExist = $this->app->getMission(null, $id)) !== null){
            $this->name = $missionExist['name'];
            $this->description = $missionExist['description'];
            $this->tags = $missionExist['tags'];
            $this->difficulty = $missionExist['difficulty'];
            $this->icon = $missionExist['icon'];
            $this->dockerlocation = $missionExist['dockerlocation'];
            $this->flag = $missionExist['flag'];
            $this->exist = true;
        }else{
            $this->name = $name;
            $this->description = $description;
            $this->tags = $tags;
            $this->difficulty = $difficulty;
            $this->icon = $icon;
            $this->dockerlocation = $dockerlocation;
            $this->flag = null;
            $this->exist = false;
        }
    }
    public function insertarDB()
    {
        $tagsJson = json_encode(['tagnames' => array_map('trim', explode(',', $this->getTags()))]);
        $queryInsertMission = "INSERT INTO ctfs (id, name, description, tags, difficulty, icon, dockerlocation) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $queryInsertAuthor = "INSERT INTO userxctf (id_user, id_ctf, completado, creada) VALUES (?, ?, ?, ?)";
        return ($this->app->executeQuery($queryInsertMission, [$this->getId(), $this->getName(), $this->getDescription(), $tagsJson, $this->getDifficulty(), $this->getIcon(), $this->getDockerloc()], "isssiss")) && ($this->app->executeQuery($queryInsertAuthor, [$_SESSION["id"],$this->getId(), false, true], "iiii"));
    }
    public function eliminarDB()
    {
        $query = "DELETE FROM ctfs WHERE name = ?";
        return $this->app->executeQuery($query, [$this->getName()], "s");
    }
    public function comprobarFlag($flag){
        $query = "SELECT flag FROM ctfs WHERE name = ?;";
        $result = [];
        if($this->app->executeQuery($query, [$this->getName()], "s", $result)){
            if(strncmp($flag, $result[0]["flag"], strlen($flag)) === 0){
                return true;
            }
        }
        return false;
    }
    public function calculateMissionXP(){
        $tagsArray = json_decode($this->tags, true);
        $numTags = is_array($tagsArray['tagnames']) ? count($tagsArray['tagnames']) : 0;

        // Calcular XP: (número de tags * 50) * dificultad
        $xp = ($numTags * 50) * $this->difficulty;

        return $xp;
    }
    private function generateFlag() {
        $length = 16; // Longitud de la flag
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charLength = strlen($characters);
        $flag = '';
    
        for ($i = 0; $i < $length; $i++) {
            $flag .= $characters[random_int(0, $charLength - 1)];
        }
    
        return $flag;
    }
    public function renewFlag(){
        $query = "UPDATE ctfs SET flag = ? WHERE name = ?;";
        $this->setFlag($this->generateFlag());
        return $this->app->executeQuery($query, [$this->getFlag(), $this->getName()], "ss");
    }
    // Métodos getters para acceder a las propiedades
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTags()
    {
        return $this->tags;
    }
    public function getDifficulty()
    {
        return $this->difficulty;
    }
    public function getIcon()
    {
        return $this->icon;
    }
    public function getFlag()
    {
        return $this->flag;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function getDockerloc()
    {
        return $this->dockerlocation;
    }
    public function getExistence()
    {
        return $this->exist;
    }


    // Métodos setters para modificar las propiedades
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTags($tag)
    {
        $this->tags = $tag;
    }
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;
    }
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setDockerloc($dockerlocation)
    {
        $this->dockerlocation = $dockerlocation;
    }
}