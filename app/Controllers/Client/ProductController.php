<?php
namespace App\Controllers\Client;

use App\Models\Product;

class ProductController {
    
    // Trang danh sách tất cả sản phẩm
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAll();

        view('client.products.index', [
            'products' => $products
        ]);
    }

    // Trang chi tiết 1 sản phẩm (nhận $id từ URL)
    public function show($id) {
        $productModel = new Product();
        
        // Giả sử Model của bạn có hàm getById() hoặc find()
        $product = $productModel->find($id); 

        // Nếu người dùng nhập ID linh tinh trên URL, đá về trang chủ
        if (!$product) {
            header("Location: /Agile-1-VPP/client/home");
            exit;
        }

        view('client.products.detail', [
            'product' => $product
        ]);
    }
}