<?php
namespace App\Controllers;

use App\Controller;
use PDO;

class CartController extends Controller {

    // Hàm render nội bộ để hiển thị giao diện chuyên nghiệp
    private function render($view, $data = []) {
        extract($data);
        include "views/$view.blade.php";
    }

    // 1. CỘT KANBAN: XEM GIỎ HÀNG
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        return $this->render('cart', ['cart' => $cart]);
    }

    // 2. CỘT KANBAN: THÊM SẢN PHẨM VÀO GIỎ
    public function add() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$id] = [
                    'name' => $_POST['name'] ?? 'Sản phẩm',
                    'price' => $_POST['price'] ?? 0,
                    'image' => $_POST['image'] ?? 'default.jpg',
                    'quantity' => 1
                ];
            }
        }
        header('Location: /cart');
    }

    // 3. CỘT KANBAN: CẬP NHẬT SỐ LƯỢNG & TÍNH TỔNG TIỀN
    public function update() {
        $id = $_POST['id'] ?? null;
        $qty = (int)($_POST['quantity'] ?? 1);
        
        if ($id && isset($_SESSION['cart'][$id])) {
            if ($qty > 0) {
                $_SESSION['cart'][$id]['quantity'] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
        header('Location: /cart');
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /cart');
    }

    // 4. CỘT KANBAN: ĐẶT HÀNG & LƯU ĐƠN HÀNG VÀO DATABASE
    public function processOrder() {
        if (empty($_SESSION['cart'])) {
            header('Location: /');
            exit;
        }

        $userId = $_POST['user_id'] ?? 1; // Lấy từ form Mock trên giao diện
        $cart = $_SESSION['cart'];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            // Kết nối Database vpp_agile (Bạn hãy chỉnh lại username/password nếu cần)
            $db = new PDO("mysql:host=localhost;dbname=vpp_agile;charset=utf8", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Bước A: Lưu vào bảng `orders`
            $sqlOrder = "INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'pending', NOW())";
            $stmt = $db->prepare($sqlOrder);
            $stmt->execute([$userId, $total]);
            $orderId = $db->lastInsertId(); // Lấy ID vừa tự động tăng

            // Bước B: Lưu chi tiết vào bảng `order_items`
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmtItem = $db->prepare($sqlItem);

            foreach ($cart as $productId => $item) {
                $stmtItem->execute([
                    $orderId, 
                    $productId, 
                    $item['quantity'], 
                    $item['price']
                ]);
            }

            // Bước C: Xóa giỏ hàng (Hoàn thành yêu cầu Agile)
            unset($_SESSION['cart']);

            // Mock thanh toán: Thông báo thành công
            echo "<script>alert('Đặt hàng thành công! Mã đơn hàng của Long là: #$orderId'); window.location.href='/cart';</script>";

        } catch (\Exception $e) {
            // Sử dụng hàm logError từ Controller cha của Long
            $this->logError("Lỗi SQL Order: " . $e->getMessage());
            echo "Lỗi hệ thống: Không thể lưu đơn hàng. Vui lòng kiểm tra log.";
        }
    }
}