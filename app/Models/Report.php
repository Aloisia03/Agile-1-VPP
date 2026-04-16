<?php
namespace App\Models;

use App\Model;

class Report extends Model
{
    // 1. Lấy tổng quan Doanh thu và Số đơn hoàn thành
    public function getOverviewStats()
    {
        // Chỉ tính tiền những đơn đã 'completed'
        $sql = "SELECT 
                    SUM(total_price) as total_revenue, 
                    COUNT(id) as total_completed_orders 
                FROM orders 
                WHERE status = 'completed'";
                
        // Dùng $this->connection và fetchAssociative của Doctrine
        $result = $this->connection->executeQuery($sql);
        
        // Trả về 1 mảng dữ liệu (1 dòng)
        return $result->fetchAssociative(); 
    }

    // 2. Lấy Top sản phẩm bán chạy nhất
    public function getTopSellingProducts($limit = 10)
    {
        // Ép kiểu (int) để bảo mật
        $limit = (int) $limit; 
        
        // JOIN 3 bảng: order_items, orders và products
        $sql = "SELECT 
                    p.id, 
                    p.name, 
                    SUM(oi.quantity) as total_sold, 
                    SUM(oi.quantity * oi.price) as total_revenue
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                JOIN products p ON oi.product_id = p.id
                WHERE o.status = 'completed'
                GROUP BY p.id, p.name
                ORDER BY total_sold DESC
                LIMIT $limit";

        // Dùng $this->connection và executeQuery
        $result = $this->connection->executeQuery($sql);
        
        // fetchAllAssociative() sẽ tự động trả về toàn bộ mảng kết quả, không cần viết vòng lặp while nữa!
        return $result->fetchAllAssociative();
    }

    public function getInventoryData() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.stock ASC";
        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

    //  update tồn kho hàng
    public function updateProductStock($id, $stock)
    {
        $sql = "UPDATE products SET stock = :stock WHERE id = :id";
        // Vì hàm này nằm bên trong Model nên được phép dùng $this->connection thoải mái
        $this->connection->executeStatement($sql, [
            'stock' => (int) $stock,
            'id' => (int) $id
        ]);
    }
}