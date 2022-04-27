<?php

namespace App\Core;

class Model
{
    public $connection = null;
    public function __construct()
    {
        $this->connection = Application::$app->db->pdo;
    }
}