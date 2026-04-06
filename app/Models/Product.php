<?php 
namespace App\Models;

use App\Model;

class Product extends Model
{
    public function getAll($sort = 'default'){
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->orderBy('p.id', 'DESC');

        switch ($sort) {
            case 'price_asc':
                $stmt->orderBy('p.price', 'ASC');
                break;
            case 'price_desc':
                $stmt->orderBy('p.price', 'DESC');
                break;
            default:
                $stmt->orderBy('p.id', 'DESC');
                break;
        }
        return $stmt->fetchAllAssociative();
    }

    public function findById($id){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $stmt->executeQuery()->fetchAssociative(); 
    }

    // Hàm mới thêm vào
    public function findByCategory($categoryId) {
    $stmt = $this->connection->createQueryBuilder();

    $stmt->select('p.*', 'c.name AS category_name')
        ->from('products', 'p')
        ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
        ->where('p.category_id = :categoryId')
        ->setParameter('categoryId', $categoryId)
        ->orderBy('p.id', 'DESC');

    return $stmt->executeQuery()->fetchAllAssociative();
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
    