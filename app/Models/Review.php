<?php
namespace App\Models;

use App\Model;

class Review extends Model
{
    // Lấy toàn bộ đánh giá để Admin quản lý
    public function getAllReviews()
    {
        $sql = "SELECT r.*, u.name as user_name, p.name as product_name 
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                JOIN products p ON r.product_id = p.id
                ORDER BY r.created_at DESC";
        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

    // Cập nhật trạng thái (Duyệt/Ẩn)
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE reviews SET status = :status WHERE id = :id";
        return $this->connection->executeStatement($sql, ['status' => $status, 'id' => $id]);
    }


    public function getApprovedReviewsByProductId($productId)
    {
        $sql = "SELECT r.*, u.name as user_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = :pid AND r.status = 'approved' 
                ORDER BY r.created_at DESC";
                
        // Trong Model thì dùng $this->connection thoải mái không bị đỏ
        return $this->connection->executeQuery($sql, ['pid' => $productId])->fetchAllAssociative();
    }

    public function createReview($data)
{
    $sql = "INSERT INTO reviews (user_id, product_id, rating, content, status) 
            VALUES (:user_id, :product_id, :rating, :content, :status)";
            
    // Trong Model thì dùng $this->connection thoải mái, không bao giờ bị đỏ
    return $this->connection->executeStatement($sql, [
        'user_id'    => $data['user_id'],
        'product_id' => $data['product_id'],
        'rating'     => $data['rating'],
        'content'    => $data['content'],
        'status'     => $data['status']
    ]);
}
}