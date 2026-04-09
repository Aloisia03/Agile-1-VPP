<?php 
namespace App\Models;

use App\Model;

class Product extends Model
{
    public function getAll($sort = null){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id');

        if($sort == 'price_asc'){
            $stmt->orderBy('p.price', 'ASC');
        } elseif($sort == 'price_desc'){
            $stmt->orderBy('p.price', 'DESC');
        } else {
            $stmt->orderBy('p.id', 'DESC');
        }

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function find($id){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $stmt->executeQuery()->fetchAssociative();
    }

    public function search($keyword){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.name LIKE :keyword OR p.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('p.id', 'DESC');

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function updateImage($id, $imagePath){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->update('products')
            ->set('image', ':image')
            ->where('id = :id')
            ->setParameter('image', $imagePath)
            ->setParameter('id', $id);

        $stmt->executeStatement();
    }

    public function getByCategory($category_id, $sort = null){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.category_id = :category_id')
            ->setParameter('category_id', $category_id);

        if($sort == 'price_asc'){
            $stmt->orderBy('p.price', 'ASC');
        } elseif($sort == 'price_desc'){
            $stmt->orderBy('p.price', 'DESC');
        } else {
            $stmt->orderBy('p.id', 'DESC');
        }

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
