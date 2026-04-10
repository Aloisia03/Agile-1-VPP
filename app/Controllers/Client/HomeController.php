<?php
namespace App\Controllers\Client;

use App\Models\Product; // Đảm bảo bạn đã có file Model này

class HomeController {
    public function index() {
        $productModel = new Product();
        // Giả sử hàm getAll() của bạn trả về mảng các sản phẩm
        $products = $productModel->getAll(); 

        // Hàm view() này được định nghĩa trong helpers.php của bạn
        view('client.home', [
            'products' => $products
        ]);
    }
}