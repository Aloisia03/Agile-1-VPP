<?php 
namespace App\Model;

use App\Model;

class Product extends Model
{
    public function getAll($categoryId = null){ // Thêm tham số categoryId
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id');

        // Nếu có truyền categoryId thì thêm điều kiện WHERE
        if ($categoryId) {
            $stmt->where('p.category_id = :id')
                 ->setParameter('id', $categoryId);
        }

        $stmt->orderBy('p.id', 'DESC');

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function findById($id){
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.id = :id');
        $stmt->setParameter('id', $id);
        return $stmt->fetchAssociative();
    }
    public function insert($data)
    {
        return $this->connection->insert('products', [
            'name'         => $data['name'],
            'price'        => $data['price'],
            'category_id'  => $data['category_id'],
            'description'   => $data['description'],
            'image'  => $data['image'],
        ]);
    }
}