<?php
require_once __DIR__ ."/../../config.php";
class Mission
{
    // Propiedades privadas para encapsular los datos
    private $id;
    private $name;
    private $description;
    private $tags;
    private $difficulty;
    private $icon;
    private $dockerlocation;
    private $flag;

    // Constructor para inicializar las propiedades
    public function __construct($name,$description = null, $tags= null, $difficulty = null, $icon= null, $dockerlocation= null, $flag = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tags = $tags;
        $this->difficulty = $difficulty;
        $this->icon = $icon;
        $this->dockerlocation = $dockerlocation;
        $this->flag = $flag;
    }
    public function insertarDB($app)
    {
        $this->setId($app->nextId("ctfs"));
        $tagsJson = json_encode(['tagnames' => array_map('trim', explode(',', $this->getTags()))]);
        $queryInsertMission = "INSERT INTO ctfs (id, name, description, tags, difficulty, icon, dockerlocation) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $queryInsertAuthor = "INSERT INTO userxctf (id_user, id_ctf, completado, creada) VALUES (?, ?, ?, ?)";
        return ($app->executeQuery($queryInsertMission, [$this->getId(), $this->getName(), $this->getDescription(), $tagsJson, $this->getDifficulty(), $this->getIcon(), $this->getDockerloc()], "isssiss")) && ($app->executeQuery($queryInsertAuthor, [$_SESSION["id"],$this->getId(), false, true], "iiii"));
    }
    public function eliminarDB($app)
    {
        $query = "DELETE FROM ctfs WHERE name = ?";
        return $app->executeQuery($query, [$this->getName()], "s");
    }
    public function comprobarFlag($app, $flag){
        $query = "SELECT flag FROM ctfs WHERE name = ?;";
        $result = [];
        if($app->executeQuery($query, [$this->getName()], "s", $result)){
            echo $result[0]["flag"] . $flag;
            if(strncmp($flag, $result[0]["flag"], strlen($flag)) === 0){
                return true;
            }
        }
        return false;
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
    public function renewFlag($app){
        $query = "UPDATE ctfs SET flag = ? WHERE name = ?;";
        $this->setFlag($this->generateFlag());
        return $app->executeQuery($query, [$this->getFlag(), $this->getName()], "ss");
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