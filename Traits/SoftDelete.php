<?php

namespace App\Traits;


trait SoftDelete
{
    public function delete($id)
    {
        $date = date('Y-m-d H:i:s');

        $query = "UPDATE $this->table SET deleted_at = :delete_time WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':delete_time', $date);
        $statement->bindParam(':id', $id);

        $statement->execute();
    }
}