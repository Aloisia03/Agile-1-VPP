<?php 
namespace App\Models;

use App\Model;

class Product extends Model
{
    // =========================
    // 🔥 FORMAT DATA (QUAN TRỌNG)
    // =========================
    private function formatProduct($product){
        if(!$product) return $product;

        // ✅ ép kiểu price về số
        $product['price'] = (int) ($product['price'] ?? 0);

        // ✅ xử lý image (có thể là JSON hoặc string)
        if(isset($product['image'])){
            $decoded = json_decode($product['image'], true);

            if(is_array($decoded)){
                // nếu là nhiều ảnh → lấy ảnh đầu
                $product['image'] = $decoded[0] ?? '';
                $product['images'] = $decoded;
            } else {
                // nếu là string
                $product['images'] = [$product['image']];
            }
        } else {
            $product['image'] = '';
            $product['images'] = [];
        }

        return $product;
    }

    // =========================
    // 🔥 GET ALL + SORT
    // =========================
    public function getAll($sort = null){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id');

        // sort
        if($sort == 'price_asc'){
            $stmt->orderBy('p.price', 'ASC');
        } elseif($sort == 'price_desc'){
            $stmt->orderBy('p.price', 'DESC');
        } else {
            $stmt->orderBy('p.id', 'DESC');
        }

        $products = $stmt->executeQuery()->fetchAllAssociative();

        foreach($products as &$product){
            $product = $this->formatProduct($product);
        }

        return $products;
    }

    // =========================
    // 🔥 FIND
    // =========================
    public function find($id){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select('p.*', 'c.name AS category_name')
            ->from('products', 'p')
            ->innerJoin('p', 'categories', 'c', 'c.id = p.category_id')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        $product = $stmt->executeQuery()->fetchAssociative();

        return $this->formatProduct($product);
    }

    // =========================
    // 🔥 SEARCH
    // =========================
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
            $product = $this->formatProduct($product);
        }

        return $products;
    }

    // =========================
    // 🔥 FILTER BY CATEGORY + SORT
    // =========================
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

        $products = $stmt->executeQuery()->fetchAllAssociative();

        foreach($products as &$product){
            $product = $this->formatProduct($product);
        }

        return $products;
    }

    // =========================
    // 🔥 INSERT
    // =========================
    public function insert($data){
        $this->connection->insert('products', $data);
        return $this->connection->lastInsertId();
    }

    // =========================
    // 🔥 UPDATE PRODUCT
    // =========================
    public function updateProduct($id, $data){
        if (empty($data)) return;
        $this->connection->update('products', $data, ['id' => $id]);
    }

    // =========================
    // 🔥 UPDATE IMAGE
    // =========================
    public function updateImage($id, $imagePath){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->update('products')
            ->set('image', ':image')
            ->where('id = :id')
            ->setParameter('image', $imagePath)
            ->setParameter('id', $id);

        $stmt->executeStatement();
    }

    // =========================
    // 🔥 DELETE
    // =========================
    public function delete($id){
        $stmt = $this->connection->createQueryBuilder();

        $stmt->delete('products')
            ->where('id = :id')
            ->setParameter('id', $id);

        return $stmt->executeStatement();
    }
}