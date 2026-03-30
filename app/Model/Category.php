<?php
namespace App\Model;

    use App\Model;

    class Category extends Model{
        public function getAll(){
            $stmt = $this->connection->createQueryBuilder();
            $stmt->select('*')->from('categories');
            return $stmt->fetchAllAssociative();
        }
    }
?>