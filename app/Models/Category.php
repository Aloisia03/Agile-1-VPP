<?php
namespace App\Models;

    use App\Model;

    class Category extends Model{
        public function getAll(){
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select('*')->from('categories');
        
        // PHẢI thêm executeQuery() trước khi fetch kết quả
        return $stmt->executeQuery()->fetchAllAssociative(); 
    }

    public function getOne($id){
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select('*')
            ->from('categories')
            ->where('id = :id')
            ->setParameter('id', $id);
            
        return $stmt->executeQuery()->fetchAssociative();
    }
    }
?>