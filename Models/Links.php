<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Links extends Model
{
    public $table = 'links';
    public function checkLink($url)
    {
        $query = "SELECT * FROM $this->table where main_address = :link";
        $sql = $this->connection->prepare($query);
        $sql->bindValue(":link", $url);
        $sql->execute();
        return $sql->rowCount();
    }

    public function saveLink($user_id, $main_address, $short_url, $expire_time)
    {
        $query = "INSERT INTO $this->table (user_id, main_address, shortened_link, expire_at) VALUES (:user, :main_address, :url, :expire)";
        $sql = $this->connection->prepare($query);
        $sql->bindValue(":user" , $user_id);
        $sql->bindValue(":main_address" , $main_address);
        $sql->bindValue(":url" , $short_url);
        $sql->bindValue(":expire" , $expire_time);
        return $sql->execute();
    }

    public function getAll($user_id)
    {
        $query = "SELECT * FROM $this->table where user_id = :user_id";
        $sql = $this->connection->prepare($query);
        $sql->bindValue(":user_id", $user_id);
        $sql->execute();
        return $sql->fetchAll();
    }
}