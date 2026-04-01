<?php 
namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    private $modelProducts;

    public function __construct()
    {
        // 🔥 load DB từ config
        $db = require __DIR__ . '/../../config/database.php';

        $this->modelProducts = new Product($db);
    }

    public function index(){
        $products = $this->modelProducts->getAll();
        return view('products.index', compact('products'));
    }
    
    public function show($id){
        $product = $this->modelProducts->find($id);
        return view('products.show', compact('product'));
    }
}