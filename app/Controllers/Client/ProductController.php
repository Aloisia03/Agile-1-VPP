<?php
namespace App\Controllers\Client;

use App\Models\Product;

class ProductController {
    protected $db;
    protected $productModel;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../config/database.php';
        $this->productModel = new Product($this->db);
    }

    // Hiển thị tất cả sản phẩm
    public function index() {
        $products = $this->productModel->getAll();
        return view('client.products', [
            'title' => 'Danh mục Sản phẩm',
            'products' => $products
        ]);
    }

    // Xem chi tiết 1 sản phẩm
    public function show($id) {
        $product = $this->productModel->find($id);
        if (!$product) {
            header("Location: /Agile-1-VPP/products");
            exit;
        }
        return view('client.product_detail', [
            'title' => $product['name'],
            'product' => $product
        ]);
    }
}