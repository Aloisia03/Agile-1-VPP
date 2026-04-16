<?php

namespace App\Controllers\Client;

use App\Models\Product;
use App\Models\Category;

class ProductController
{
    public function index()
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->getAll();
        $categories = $categoryModel->getAll();

        // ======================
        // GET PARAMS
        // ======================
        $keyword = $_GET['keyword'] ?? null;
        $categoryId = $_GET['category_id'] ?? null;
        $sort = $_GET['sort'] ?? null;

        // ======================
        // SEARCH (TÊN SP)
        // ======================
        if (!empty($keyword)) {
            $keyword = strtolower(trim($keyword));

            $products = array_filter($products, function ($p) use ($keyword) {
                return isset($p['name']) &&
                       strpos(strtolower($p['name']), $keyword) !== false;
            });
        }

        // ======================
        // FILTER CATEGORY
        // ======================
        if (!empty($categoryId)) {
            $products = array_filter($products, function ($p) use ($categoryId) {
                return isset($p['category_id']) &&
                       $p['category_id'] == $categoryId;
            });
        }

        // ======================
        // SORT PRICE
        // ======================
        if ($sort === 'asc') {
            usort($products, fn($a, $b) => $a['price'] <=> $b['price']);
        }

        if ($sort === 'desc') {
            usort($products, fn($a, $b) => $b['price'] <=> $a['price']);
        }

        view('client.products.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            header("Location: /Agile-1-VPP/client/home");
            exit;
        }

        // 1. Lấy sản phẩm cùng loại qua hàm Model (Hết đỏ)
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);

        // 2. Lấy gợi ý qua hàm Model (Hết đỏ)
        $suggestedProducts = $productModel->getSuggestedProducts($id);

        // 3. Lấy đánh giá
        $reviewModel = new \App\Models\Review();
        $reviews = $reviewModel->getApprovedReviewsByProductId($id);

        return view('client.products.detail', [
            'product' => $product,
            'reviews' => $reviews,
            'relatedProducts' => $relatedProducts,
            'suggestedProducts' => $suggestedProducts
        ]);
    }

public function postReview()
    {
        // Kiểm tra đăng nhập
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $reviewModel = new \App\Models\Review();
        
        // Chuẩn bị dữ liệu
        $data = [
            'user_id'    => $_SESSION['user']['id'],
            'product_id' => $_POST['product_id'],
            'rating'     => $_POST['rating'],
            'content'    => $_POST['content'],
            // SỬA TẠI ĐÂY: Đổi 'pending' thành 'approved' để hiện luôn
            'status'     => 'approved' 
        ];

        // Gọi hàm từ Model (Không dùng ->connection ở đây nữa)
        $reviewModel->createReview($data);

        // Quay lại trang sản phẩm vừa đánh giá
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}