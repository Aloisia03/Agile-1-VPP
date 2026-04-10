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

        // đảm bảo có session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =========================
    // 🛒 ADD TO CART
    // =========================
    public function add()
    {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$productId || $quantity <= 0) {
            redirect('/products');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        redirect('/products');
    }

    // =========================
    // 🛒 VIEW CART (FIX LỖI Ở ĐÂY)
    // =========================
    public function view()
    {
        $cartItems = [];
        $total = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $quantity) {

                $product = $this->productModel->find($productId);

                if ($product) {

                    // 🔥 FIX CỨNG: đảm bảo price là số
                    $price = $product['price'];

                    if (is_array($price)) {
                        $price = (int) ($price[0] ?? 0);
                    } else {
                        $price = (int) $price;
                    }

                    $quantity = (int) $quantity;

                    $product['price'] = $price;
                    $product['quantity'] = $quantity;
                    $product['subtotal'] = $price * $quantity;

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

    // =========================
    // 🔄 UPDATE CART
    // =========================
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