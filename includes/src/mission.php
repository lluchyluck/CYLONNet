<?php

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
    public function __construct($name,$description, $tags, $icon, $dockerlocation)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tags = $tags;
        $this->icon = $icon;
        $this->dockerlocation = $dockerlocation;
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

    public function getTag()
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

    public function setTag($tag)
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