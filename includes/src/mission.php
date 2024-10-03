<?php

class Mission
{
    // Propiedades privadas para encapsular los datos
    private $id;
    private $name;
    private $description;
    private $tag;
    private $icon;
    private $dockerlocation;

    // Constructor para inicializar las propiedades
    public function __construct($name,$description, $tag, $icon, $dockerlocation)
    {
        $this->name = $name;
        $this->description = $description;
        $this->tag = $tag;
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
        return $this->tag;
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
        $this->tag = $tag;
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