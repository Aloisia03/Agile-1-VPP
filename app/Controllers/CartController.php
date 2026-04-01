<?php
namespace App\Controllers;

use App\Models\Product;

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
}