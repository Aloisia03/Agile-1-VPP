<?php 
namespace App\Models;

use App\Model;

class Product extends Model
{
    public function getAll(){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->orderBy('p.id', 'DESC');

        return $stmt->executeQuery()->fetchAllAssociative(); // 🔥 FIX
    }

    public function find($id){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $stmt->executeQuery()->fetchAssociative(); // 🔥 FIX
    }
}