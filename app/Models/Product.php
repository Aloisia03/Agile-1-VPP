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

        $products = $stmt->executeQuery()->fetchAllAssociative();
        foreach($products as &$product){
            if(isset($product['images'])){
                $product['images'] = json_decode($product['images'], true) ?? [];
            }
        }
        return $products; // 🔥 FIX
    }

    public function find($id){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        $product = $stmt->executeQuery()->fetchAssociative();
        if($product && isset($product['images'])){
            $product['images'] = json_decode($product['images'], true) ?? [];
        }
        return $product; // 🔥 FIX
    }

    public function search($keyword){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.name LIKE :keyword OR p.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('p.id', 'DESC');

        $products = $stmt->executeQuery()->fetchAllAssociative();
        foreach($products as &$product){
            if(isset($product['images'])){
                $product['images'] = json_decode($product['images'], true) ?? [];
            }
        }
        return $products;
    }

    public function updateImages($id, $imagesJson){
        $stmt = $this->connection->createQueryBuilder();
        $stmt->update('products')
            ->set('images', ':images')
            ->where('id = :id')
            ->setParameter('images', $imagesJson)
            ->setParameter('id', $id);
        $stmt->executeStatement();
    }
}