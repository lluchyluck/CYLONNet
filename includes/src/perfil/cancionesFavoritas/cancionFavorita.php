<?php
require_once __DIR__ . "/../../../config.php";

class CancionFavorita
{
    // Propiedades privadas para encapsular los datos
    private $song_Id;
    private $user_Id;
    private $stars;


    // Constructor para inicializar las propiedades
    public function __construct($song_Id, $user_Id, $stars)
    {
        $this->song_Id = $song_Id;
        $this->user_Id = $user_Id;
        $this->stars = $stars;
       
        
    }
    
    
    public function getSongId()
    {
        return $this->song_Id;
    }

    public function getUserId()
    {
        return $this->user_Id;
    }

    public function getStars()
    {
        return $this->stars;
    }

    
    public function setSongId($songId)
    {
        $this->song_Id = $songId;
    }

    public function setUserId($userId)
    {
        $this->user_Id = $userId;
    }

    public function setStars($stars)
    {
        $this->stars = $stars;
    }
}