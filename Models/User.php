<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public $table = 'users';

    public function save($name, $email, $password)
    {
        $query = "INSERT INTO $this->table (name, email, password) VALUES (:name, :email, :pass)";
        $sql = $this->connection->prepare($query);
        $sql->bindParam("name" , $name);
        $sql->bindParam("email" , $email);
        $sql->bindParam("pass" , $password);
        return $sql->execute();
    }

    public function getByEmail($email)
    {
        $query = "SELECT * FROM $this->table where email = :email";
        $sql = $this->connection->prepare($query);
        $sql->bindValue(":email", $email);
        $sql->execute();
        return (object)$sql->fetch(PDO::FETCH_ASSOC);
    }
}