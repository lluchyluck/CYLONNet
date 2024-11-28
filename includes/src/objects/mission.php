<?php
require_once __DIR__ ."/../../config.php";
class Mission
{
    // Propiedades privadas para encapsular los datos
    private $id;
    private $name;
    private $description;
    private $tags;
    private $icon;
    private $dockerlocation;

    // Constructor para inicializar las propiedades
    public function __construct($name,$description = null, $tags= null, $icon= null, $dockerlocation= null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tags = $tags;
        $this->icon = $icon;
        $this->dockerlocation = $dockerlocation;
    }
    public function insertarDB($app)
    {
        $this->setId($app->nextId("ctfs"));
        $tagsJson = json_encode(['tagnames' => array_map('trim', explode(',', $this->getTags()))]);
        $queryInsertMission = "INSERT INTO ctfs (id, name, description, tags, icon, dockerlocation) VALUES (?, ?, ?, ?, ?, ?)";
        $queryInsertAuthor = "INSERT INTO userxctf (id_user, id_ctf, completado, creada) VALUES (?, ?, ?, ?)";
        return ($app->executeQuery($queryInsertMission, [$this->getId(), $this->getName(), $this->getDescription(), $tagsJson, $this->getIcon(), $this->getDockerloc()], "isssss")) && ($app->executeQuery($queryInsertAuthor, [$_SESSION["id"],$this->getId(), false, true], "iiii"));
    }
    public function eliminarDB($app)
    {
        $query = "DELETE FROM ctfs WHERE id = ?";
        return $app->executeQuery($query, [$this->getId()], "i");
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

    public function getIcon()
    {
        return $this->icon;
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