<?php 
namespace App\Models;

use App\Model;

class Order extends Model
{
    // Nhận kết nối DB từ Controller truyền vào
    public function __construct($db = null) {
        if ($db) {
            $this->connection = $db;
        } else {
            // Nếu không có $db truyền vào, tự load từ config
            $this->connection = require __DIR__ . '/../../config/database.php';
        }
    }

    public function getOrdersByUserId($userId) {
        // Dùng MySQLi thuần thay vì QueryBuilder
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $userId); // "i" nghĩa là integer (số nguyên)
        $stmt->execute();
        
        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        $stmt->close();
        return $orders;
    }

    // Hàm này dùng cho nút "Đặt hàng" (Checkout)
    public function createOrder($userId, $total, $cartItems) {
        $conn = $this->connection; // Đây là đối tượng mysqli

        try {
            // 1. Chèn dữ liệu vào bảng orders
            $sqlOrder = "INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'pending', NOW())";
            $stmtOrder = $conn->prepare($sqlOrder);
            $stmtOrder->bind_param("id", $userId, $total);
            $stmtOrder->execute();
            
            // Lấy ID của đơn hàng vừa chèn xong
            $orderId = $conn->insert_id;
            $stmtOrder->close();

            // 2. Chèn từng món hàng vào bảng order_items
            $sqlItems = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmtItem = $conn->prepare($sqlItems);

            foreach ($cartItems as $item) {
                // Lưu ý: $item['id'] hoặc $item['product_id'] tùy theo mảng bạn truyền vào
                $productId = $item['id']; 
                $quantity = $item['quantity'];
                $price = $item['price'];

                $stmtItem->bind_param("iiid", $orderId, $productId, $quantity, $price);
                $stmtItem->execute();
            }
            $stmtItem->close();

            return $orderId;
        } catch (\Exception $e) {
            // Nếu có lỗi thì dừng lại hiện thông báo
            die("Lỗi lưu đơn hàng: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật trạng thái đơn hàng (Dùng cho việc Hủy đơn)
    public function updateStatus($orderId, $userId, $status) {
        // Chỉ cho phép cập nhật nếu đơn đó là của đúng User này VÀ đang ở trạng thái 'pending'
        $sql = "UPDATE orders SET status = ? WHERE id = ? AND user_id = ? AND status = 'pending'";
        
        $stmt = $this->connection->prepare($sql);
        
        // "sii" nghĩa là: String (status), Integer (orderId), Integer (userId)
        $stmt->bind_param("sii", $status, $orderId, $userId);
        
        return $stmt->execute();
    }
}