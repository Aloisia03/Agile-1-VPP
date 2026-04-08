<?php
namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Order;

class CartController {
    protected $db;
    protected $productModel;
    protected $orderModel;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../config/database.php';
        $this->productModel = new Product($this->db);
        $this->orderModel = new Order($this->db);
        
        // Tự động khởi tạo giỏ hàng rỗng nếu khách chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // ==========================================
    // PHẦN 1: QUẢN LÝ GIỎ HÀNG
    // ==========================================

    // Hiển thị trang Giỏ hàng
    public function index() {
        $cartItems = [];
        $total = 0;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productModel->find($productId);
                if ($product) {
                    $product['quantity'] = $quantity;
                    $cartItems[] = $product;
                    $total += $product['price'] * $quantity;
                }
            }
        }

        return view('client.cart', [
            'title' => 'Giỏ hàng của bạn',
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity; // Nếu có rồi thì cộng dồn
            } else {
                $_SESSION['cart'][$productId] = $quantity;  // Nếu chưa có thì thêm mới
            }
        }
        header("Location: /Agile-1-VPP/cart");
        exit;
    }

    // Xóa 1 sản phẩm khỏi giỏ hàng
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: /Agile-1-VPP/cart");
        exit;
    }

    // ==========================================
    // PHẦN 2: THANH TOÁN VÀ ĐƠN HÀNG
    // ==========================================

    // Thanh toán (Xử lý chốt đơn)
    public function checkout() {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $cartItems = [];
        $total = 0;

        // Trường hợp A: Khách bấm "Mua ngay" từ 1 sản phẩm
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
            $product = $this->productModel->find($_POST['product_id']);
            if ($product) {
                $qty = (int)($_POST['quantity'] ?? 1);
                $product['quantity'] = $qty;
                $cartItems[] = $product;
                $total = $product['price'] * $qty;
            }
        } 
        // Trường hợp B: Khách bấm "Xác nhận đặt hàng" từ Giỏ hàng
        elseif (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $qty) {
                $product = $this->productModel->find($id);
                if ($product) {
                    $product['quantity'] = $qty;
                    $cartItems[] = $product;
                    $total += $product['price'] * $qty;
                }
            }
        }

        // Nếu mảng rỗng (không có gì để mua) -> Đá về trang sản phẩm
        if (empty($cartItems)) {
            header("Location: /Agile-1-VPP/products");
            exit;
        }

        // Lưu dữ liệu vào Database
        $orderId = $this->orderModel->createOrder($_SESSION['user']['id'], $total, $cartItems);

        if ($orderId) {
            unset($_SESSION['cart']); // Xóa sạch giỏ hàng sau khi mua thành công
            header("Location: /Agile-1-VPP/my-orders");
            exit;
        } else {
            die("Lỗi lưu đơn hàng vào Database!");
        }
    }

    // Hiển thị danh sách Đơn hàng của tôi
    public function myOrders() {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $orders = $this->orderModel->getOrdersByUserId($_SESSION['user']['id']);
        
        return view('client.orders', [
            'title' => 'Lịch sử mua hàng',
            'orders' => $orders
        ]);
    }

    // Hủy đơn hàng (Chỉ khi đang chờ xác nhận)
    public function cancel($id) {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $this->orderModel->updateStatus($id, $_SESSION['user']['id'], 'cancelled');
        header("Location: /Agile-1-VPP/my-orders");
        exit;
    }
}