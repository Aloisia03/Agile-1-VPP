<?php
namespace App\Controllers\Client;

use App\Models\Product;

class HomeController {
    protected $db;
    protected $productModel;

    public function __construct() {
        $this->db = require __DIR__ . '/../../../config/database.php';
        $this->productModel = new Product($this->db);
    }

    public function index() {
        // Lấy danh sách sản phẩm hiển thị ra trang chủ (có thể giới hạn 4-8 cái)
        // Nếu Model chưa có hàm lấy limit, bạn có thể dùng tạm getAll()
        $products = $this->productModel->getAll(); 
        
        // Trả về view home trong thư mục client
        return view('client.home', [
            'title' => 'Trang chủ',
            'products' => $products
        ]);
    }
}