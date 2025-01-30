<?php
require_once __DIR__ ."/../../config.php";

class Usuario
{
    // Propiedades privadas para encapsular los datos
    private $app;
    private $id;
    private $username;
    private $email;
    private $password;
    private $xp;
    private $developer;
    private $img;
    private $exist;

    // Constructor para inicializar las propiedades
    public function __construct($app, $id, $username = null, $password = null, $email = null, $img = null)
    {   
        $this->app = $app;
        $this->id = (int)$id;
        if(($userExist = $this->app->getUser($id, null, null)) !== null){
            $this->username = $userExist['username'];
            $this->password = $userExist['password'];
            $this->email = $userExist['email'];
            $this->xp = $userExist['xp'];
            $this->img = $userExist['img'];
            $this->exist = true;
        }else{
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
            $this->xp = 0;
            $this->developer = false; //El valor por defecto para un nuevo usuario de developer es 0
            $this->img = $img;
            $this->exist = false;
        }
    }
    
    public function insertarDB()
    {   
        
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (id, username, email, password, xp, developer, icon) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->app->executeQuery($query, [$this->id, $this->username, $this->email, $hashedPassword, $this->xp, $this->developer, $this->img], "isssiis");
    }
    public function autenticar(){
        if (($usuarioAComprobar = $this->app->getUser(null, $this->getUsername(), "")) !== null) {   
            if (password_verify($this->getPassword(),$usuarioAComprobar["password"])) {
                $this->setId($usuarioAComprobar["id"]);
                $this->setEmail($usuarioAComprobar["email"]);
                $this->setXp($usuarioAComprobar["xp"]);
                $this->setDeveloper((bool)$usuarioAComprobar["developer"]);
                $this->setImg($usuarioAComprobar["icon"]);
                return true;
            }  
        }
        return false;
    }
    public function ascenderAdmin(){
        $this->setDeveloper(1); // Nuevo valor para el campo developer         
        $sqlQuery = "UPDATE users SET developer = ? WHERE id = ?";
        return $this->app->executeQuery($sqlQuery, [$this->getDeveloper(), $this->getId()], 'ii');  
    }
    public function añadirXp($xp){
        $newXp = ((int)$_SESSION["xp"]) + $xp;
        $sqlQuery = "UPDATE users SET xp = ? WHERE username = ?";
        if($this->app->executeQuery($sqlQuery, [$newXp, $this->getUsername()], 'is')){
            $this->setXp($newXp);
            return true;
        }
        return false;
    }
    public function misionCompletada($missionId){
        $sqlSelect = "SELECT x.id_ctf FROM userxctf x RIGHT JOIN users u ON x.id_user = u.id WHERE x.id_ctf = ? AND x.id_user = ?";
        $output = [];
        if(!$this->app->executeQuery($sqlSelect, [$missionId, $this->getId()], "ii", $output))
            return false;
        if($output[0]["id_ctf"] !== null)
            return false;
        
        $query = "INSERT INTO userxctf (id_user, id_ctf, completado, creada) VALUES (?, ?, ?, ?)";
        return $this->app->executeQuery($query, [$this->getId(), $missionId, true, false], "iiii");
    }
    public function descenderAdmin(){      
        $this->setDeveloper(0); // Nuevo valor para el campo developer         
        $sqlQuery = "UPDATE users SET developer = ? WHERE id = ?";
        return $this->app->executeQuery($sqlQuery, [$this->getDeveloper(), $this->getId()], 'ii');  
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
    public function getXp()
    {
        return $this->xp;
    }

    public function getDeveloper()
    {
        return $this->developer;
    }
    public function getImg()
    {
        return $this->img;
    }
    public function getEmail()
    {
        return $this->email;
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

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setXp($xp)
    {
        $this->xp = $xp;
    }

    public function setDeveloper($developer)
    {
        $this->developer = (bool)$developer;
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