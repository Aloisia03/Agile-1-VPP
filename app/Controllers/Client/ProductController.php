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

        view('client.products.detail', [
            'product' => $product
        ]);
    }
}