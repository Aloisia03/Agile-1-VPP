<?php
namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Order; // Bắt buộc phải có dòng này để gọi Database Đơn hàng

class CartController {
    
    public function __construct() {
        // Đảm bảo Session luôn khởi tạo (nếu file index gốc chưa có)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Nếu giỏ hàng chưa tồn tại, tạo một mảng rỗng
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Xử lý thêm sản phẩm vào giỏ
    public function add() {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($productId && $quantity > 0) {
            $productModel = new Product();
            $product = $productModel->find($productId);

            if ($product) {
                // Nếu sản phẩm ĐÃ CÓ trong giỏ -> Cộng dồn số lượng
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    // Nếu CHƯA CÓ -> Thêm mới vào giỏ
                    $_SESSION['cart'][$productId] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'quantity' => $quantity
                    ];
                }
            }
        }
        
        // Kiểm tra xem khách bấm "Thêm giỏ hàng" hay "Mua ngay"
        if (isset($_POST['buy_now'])) {
            header("Location: /Agile-1-VPP/client/checkout");
        } else {
            header("Location: /Agile-1-VPP/client/cart");
        }
        exit;
    }

    // Hiển thị trang Giỏ hàng
    public function index() {
        view('client.cart.index', [
            'cart' => $_SESSION['cart']
        ]);
    }

    // Xóa 1 sản phẩm khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: /Agile-1-VPP/client/cart");
        exit;
    }

    // Hiển thị trang Form Điền thông tin giao hàng
    public function checkout() {
        // Nếu giỏ hàng trống thì đuổi về trang sản phẩm, không cho vào trang checkout
        if (empty($_SESSION['cart'])) {
            header("Location: /Agile-1-VPP/client/products");
            exit;
        }

        view('client.cart.checkout', [
            'cart' => $_SESSION['cart']
        ]);
    }

    // ==========================================
    // PHẦN CODE BỊ MẤT ĐÃ ĐƯỢC KHÔI PHỤC Ở DƯỚI ĐÂY
    // ==========================================

    // Xử lý khi khách bấm nút "Hoàn tất đặt hàng"
    public function processCheckout() {
        if (empty($_SESSION['cart'])) {
            header("Location: /Agile-1-VPP/client/products");
            exit;
        }

        // Nhận dữ liệu từ Form
        $name = $_POST['customer_name'] ?? '';
        $phone = $_POST['customer_phone'] ?? '';
        $address = $_POST['customer_address'] ?? '';
        $note = $_POST['note'] ?? '';
        
        // Khách đăng nhập rồi thì lấy ID, chưa thì để null
        $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

        // Tính lại tổng tiền từ Session để bảo mật
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Gọi Model lưu vào Database
        $orderModel = new Order();
        $orderId = $orderModel->createOrder($userId, $name, $phone, $address, $note, $totalPrice);

        if ($orderId) {
            // Lưu chi tiết từng sản phẩm
            foreach ($_SESSION['cart'] as $productId => $item) {
                $orderModel->createOrderDetail($orderId, $productId, $item['price'], $item['quantity']);
            }

            // Xóa giỏ hàng và chuyển về trang quản lý đơn
            unset($_SESSION['cart']);
            header("Location: /Agile-1-VPP/client/my-orders");
            exit;
        } else {
            echo "Có lỗi hệ thống xảy ra khi tạo đơn hàng!";
        }
    }

    // Hiển thị danh sách Lịch sử đơn hàng
    public function myOrders() {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderModel = new Order();
        
        $orders = $orderModel->getOrdersByUserId($userId);

        view('client.orders.history', [
            'orders' => $orders
        ]);
    }

    // Xem chi tiết một đơn hàng cụ thể
    public function orderDetail($id) {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderModel = new Order();

        // Lấy thông tin đơn hàng, kiểm tra đúng chủ sở hữu
        $order = $orderModel->getOrderById($id, $userId);
        if (!$order) {
            header("Location: /Agile-1-VPP/client/my-orders");
            exit;
        }

        // Lấy danh sách sản phẩm
        $orderDetails = $orderModel->getOrderDetail($id);

        view('client.orders.detail', [
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
    }

    // Tính năng Hủy đơn hàng
    public function cancel($id) {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderModel = new Order();

        // Hàm updateStatus đã được bạn viết sẵn trong Model Order 
        // Nó chỉ cho phép cập nhật khi status là 'pending' (Chờ xác nhận)
        $orderModel->updateStatus($id, $userId, 'Đã hủy');

        header("Location: /Agile-1-VPP/client/my-orders");
        exit;
    }

    // Tính năng Mua lại (Tự động thêm các món trong đơn cũ vào giỏ)
    public function reorder($id) {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $orderModel = new \App\Models\Order();
        
        // Lấy toàn bộ chi tiết các món hàng của đơn hàng cũ
        $orderDetails = $orderModel->getOrderDetail($id);

        if ($orderDetails) {
            // Lặp qua từng món và ném nó vào $_SESSION['cart']
            foreach ($orderDetails as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];

                // Nếu trong giỏ đã có món này rồi thì cộng dồn số lượng
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    // Nếu chưa có thì thêm mới vào giỏ
                    $_SESSION['cart'][$productId] = [
                        'id' => $productId,
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'image' => $item['image'],
                        'quantity' => $quantity
                    ];
                }
            }
        }

        // Bỏ hàng vào giỏ xong thì tự động bay sang trang Giỏ hàng
        header("Location: /Agile-1-VPP/client/cart");
        exit;
    }

}