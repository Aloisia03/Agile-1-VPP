<?php 
namespace App\Models;

use App\Model;

class Order extends Model
{
    public function __construct($db = null) {
        if ($db) {
            $this->connection = $db;
        } else {
            $this->connection = require __DIR__ . '/../../config/database.php';
        }
    }

    // 🔹 Lấy danh sách đơn hàng theo user
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        $stmt->close();
        return $orders;
    }

    // 🔹 Tạo đơn hàng
    public function createOrder($userId, $name, $phone, $address, $note, $totalPrice) {
        $sql = "INSERT INTO orders 
                (user_id, customer_name, customer_phone, customer_address, note, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')";
                
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("issssd", $userId, $name, $phone, $address, $note, $totalPrice);

        if ($stmt->execute()) {
            $insertId = $this->connection->insert_id;
            $stmt->close();
            return $insertId;
        } else {
            die("Lỗi lưu đơn hàng: " . $stmt->error);
        }
    }

    // 🔹 Lưu chi tiết đơn hàng
    public function createOrderDetail($orderId, $productId, $price, $quantity) {
        $sql = "INSERT INTO order_items (order_id, product_id, price, quantity) 
                VALUES (?, ?, ?, ?)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("iidi", $orderId, $productId, $price, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    // 🔹 Cập nhật trạng thái (hủy đơn)
    public function updateStatus($orderId, $userId, $status) {
       $sql = "UPDATE orders 
            SET status = ? 
            WHERE id = ? 
            AND user_id = ?
            AND status != 'canceled'"; // ❗ cho phép cả pending + confirmed

    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("sii", $status, $orderId, $userId);

    return $stmt->execute();
    }

    // 🔹 Lấy chi tiết đơn hàng
    public function getOrderDetail($orderId) {
        $sql = "SELECT oi.*, p.name,
                       (SELECT image_url FROM product_images WHERE product_id = p.id LIMIT 1) as image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // 🔹 Lấy 1 đơn hàng
    public function getOrderById($orderId, $userId) {
        $sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $orderId, $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
    // Lấy tất cả đơn hàng
public function getAllOrders()
{
    $sql = "SELECT * FROM orders ORDER BY id DESC";
    $result = $this->connection->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Lấy 1 đơn (admin)
public function getOrderByIdAdmin($id)
{
    $sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

// Update trạng thái (admin)
public function updateStatusAdmin($id, $status)
{
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    return $stmt->execute();
}

// đây là tồn kho

public function reduceStock($orderId) {
    // 1. Lấy danh sách sản phẩm và số lượng từ đơn hàng
    $sql = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // 2. Chuẩn bị sẵn câu lệnh trừ kho (Tối ưu hiệu suất)
    $updateSql = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $updateStmt = $this->connection->prepare($updateSql);

    // 3. Duyệt qua từng sản phẩm và thực hiện trừ kho
    while ($item = $result->fetch_assoc()) {
        $qty = $item['quantity'];
        $pId = $item['product_id'];
        
        // Gán tham số và chạy lệnh trừ
        $updateStmt->bind_param("ii", $qty, $pId);
        $updateStmt->execute();
    }

    // 4. Giải phóng bộ nhớ
    $stmt->close();
    $updateStmt->close();
}
}