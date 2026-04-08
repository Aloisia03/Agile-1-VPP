<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Order;
class CartController
{
    protected $db;
    protected $productModel;

  public function __construct()
{
    $this->db = require_once __DIR__ . '/../../config/database.php';

    if (!$this->db) {
        die("Không kết nối được DB");
    }

    $this->productModel = new Product($this->db);
}

    public function add()
    {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$productId || $quantity <= 0) {
            redirect('/products');
        }

        // Khởi tạo session nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Thêm hoặc cập nhật số lượng
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        redirect('/products');
    }

    public function view()
    {
        $cartItems = [];
        $total = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productModel->find($productId);
                if ($product) {
                    $product['quantity'] = $quantity;
                    $product['subtotal'] = $product['price'] * $quantity;
                    $cartItems[] = $product;
                    $total += $product['subtotal'];
                }
            }
        }

        return view('cart', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function update()
    {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);

        if ($productId && isset($_SESSION['cart'][$productId])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }

        redirect('/cart');
    }

    public function checkout()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $cartItems = [];
        $total = 0;

        // Nếu bấm "Mua ngay" từ trang sản phẩm
        if (isset($_POST['product_id'])) {
            $product = $this->productModel->find($_POST['product_id']);
            if ($product) {
                $qty = $_POST['quantity'] ?? 1;
                $product['quantity'] = $qty;
                $cartItems[] = $product;
                $total = $product['price'] * $qty;
            }
        } 
        // Nếu không có POST, lấy từ giỏ hàng (như cũ)
        elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productModel->find($productId);
                if ($product) {
                    $product['quantity'] = $quantity;
                    $cartItems[] = $product;
                    $total += $product['price'] * $quantity;
                }
            }
        }

        if (empty($cartItems)) {
            header("Location: /Agile-1-VPP/products");
            exit;
        }

        // Lưu vào database (Sử dụng Model Order đã viết ở bước trước)
        $orderModel = new \App\Models\Order($this->db);
        $orderId = $orderModel->createOrder($_SESSION['user']['id'], $total, $cartItems);

        if ($orderId) {
            unset($_SESSION['cart']); // Đặt xong thì xóa giỏ
            header("Location: /Agile-1-VPP/my-orders"); // Nhảy thẳng về trang đơn hàng
            exit;
        }
    }

    public function myOrders()
    {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        // 2. Lấy dữ liệu (Dùng đúng tên biến để compact)
        $orderModel = new \App\Models\Order($this->db);
        $userId = $_SESSION['user']['id'];
        $orders = $orderModel->getOrdersByUserId($userId);
        $title = "Đơn hàng của tôi";

        // 3. Trả về view theo đúng kiểu 'thư mục.file'
        return view('users.orders', compact('title', 'orders'));
    }

    public function cancelOrder($id)
    {
        if (!isset($_SESSION['user'])) return header("Location: /Agile-1-VPP/login");

        $orderModel = new \App\Models\Order($this->db);
        
        // Chỉ cho phép hủy nếu đơn hàng thuộc về user đang đăng nhập và đang chờ xác nhận
        $sql = "UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['user']['id']);
        
        if ($stmt->execute()) {
            header("Location: /Agile-1-VPP/my-orders");
        } else {
            die("Lỗi khi hủy đơn hàng!");
        }
    }
}