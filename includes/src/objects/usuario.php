<?php

class Usuario
{
    // Propiedades privadas para encapsular los datos
    private $id;
    private $username;
    private $email;
    private $password;
    private $img;

    // Constructor para inicializar las propiedades
    public function __construct($username, $password, $email, $img)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->img = $img;
        
    }
    
    
    // Métodos getters para acceder a las propiedades
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getImg()
    {
        return $this->img;
    }
    public function getEmail()
    {
        return $this->email;
    }


    // Métodos setters para modificar las propiedades
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setImg($img)
    {
        $this->img = $img;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
}