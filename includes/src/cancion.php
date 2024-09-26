<?php

require_once __DIR__ . "/../config.php";

class Cancion
{
    // Propiedades privadas para encapsular los datos
    private $id;
    private $name;
    private $genero;
    private $artista;
    private $duracion;

    // Constructor para inicializar las propiedades
    public function __construct($name, $genero, $artista,$duracion)
    {
        $this->name = $name;
        $this->genero = $genero;
        $this->artista = $artista;
        $this->duracion = $duracion;

    }


    // Getter para obtener el ID
    public function getId()
    {
        return $this->id;
    }

    // Setter para establecer el ID
    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter para obtener el nombre
    public function getName()
    {
        return $this->name;
    }

    // Setter para establecer el nombre
    public function setName($name)
    {
        $this->name = $name;
    }

    // Getter para obtener el género
    public function getGenero()
    {
        return $this->genero;
    }

    // Setter para establecer el género
    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    // Getter para obtener el artista
    public function getArtista()
    {
        return $this->artista;
    }

    // Setter para establecer el artista
    public function setArtista($artista)
    {
        $this->artista = $artista;
    }

    // Getter para obtener la duración
    public function getDuracion()
    {
        return $this->duracion;
    }

    // Setter para establecer la duración
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }
}