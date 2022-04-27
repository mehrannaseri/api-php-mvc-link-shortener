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

    public function saveLink($user_id, $main_address, $short_url, $timestamp)
    {
        $expire_time = $this->expireTime($timestamp);

        $query = "INSERT INTO $this->table (user_id, main_address, shortened_link, expire_at) VALUES (:user, :main_address, :url, :expire)";
        $sql = $this->connection->prepare($query);
        $sql->bindParam("user" , $user_id);
        $sql->bindParam("main_address" , $main_address);
        $sql->bindParam("url" , $short_url);
        $sql->bindParam("expire" , $expire_time);
        return $sql->execute();
    }

    public function expireTime($time)
    {
        $time = strtotime($time);
        return date("Y-m-d H:i:s", strtotime("+1 month", $time));
    }
}