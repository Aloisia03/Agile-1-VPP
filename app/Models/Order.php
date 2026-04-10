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
    public function createOrderAdmin($userId, $total, $cartItems) {
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

public function getOrderDetail($orderId) {
        // Lấy thông tin chi tiết đơn, tên sản phẩm, và LẤY ẢNH TỪ BẢNG product_images
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

// 1. Hàm tạo đơn hàng mới (Lưu vào bảng orders)
    public function createOrder($userId, $name, $phone, $address, $note, $totalPrice) {
        $sql = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, note, total_price, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
                
        $stmt = $this->connection->prepare($sql);
        
        // userId(i), name(s), phone(s), address(s), note(s), totalPrice(d) => "issssd"
        $stmt->bind_param("issssd", $userId, $name, $phone, $address, $note, $totalPrice);
        
        if ($stmt->execute()) {
            $insertId = $this->connection->insert_id;
            $stmt->close();
            return $insertId;
        } else {
            die("Lỗi lưu đơn hàng: " . $stmt->error);
        }
    }

    // 2. Hàm lưu chi tiết sản phẩm (ĐÃ SỬA THÀNH BẢNG order_items CHO KHỚP VỚI DB CỦA BẠN)
    public function createOrderDetail($orderId, $productId, $price, $quantity) {
        $sql = "INSERT INTO order_items (order_id, product_id, price, quantity) 
                VALUES (?, ?, ?, ?)";
                
        $stmt = $this->connection->prepare($sql);
        
        // orderId(i), productId(i), price(d), quantity(i) => "iidi"
        $stmt->bind_param("iidi", $orderId, $productId, $price, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    // Lấy thông tin chung của 1 đơn hàng (kiểm tra đúng chủ sở hữu)
    public function getOrderById($orderId, $userId) {
        $sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $orderId, $userId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        
        $stmt->close();
        return $order;
    }
}