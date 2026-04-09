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
    $category_id = $_GET['category_id'] ?? null;
    $sort = $_GET['sort'] ?? null;

    if($category_id){
        $products = $this->modelProducts->getByCategory($category_id, $sort);
    } else {
        $products = $this->modelProducts->getAll($sort);
    }

    $categories = (new \App\Models\Category(require __DIR__.'/../../config/database.php'))->getAll();

    return view('products.index', compact('products','categories','sort'));
}
    
    public function show($id){
        $product = $this->modelProducts->find($id);
        return view('products.show', compact('product'));
    }

    public function search(){
        $keyword = $_GET['keyword'] ?? '';
        if(empty($keyword)){
            $products = $this->modelProducts->getAll();
        } else {
            $products = $this->modelProducts->search($keyword);
        }
        return view('products.index', compact('products', 'keyword'));
    }

    public function uploadImage($id){
    if($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $product = $this->modelProducts->find($id);
    if(!$product) return;

    // kiểm tra file upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $file = $_FILES['image'];

        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if(move_uploaded_file($file['tmp_name'], $filePath)){
            $imagePath = 'uploads/' . $fileName;

            // ✅ update 1 ảnh
            $this->modelProducts->updateImage($id, $imagePath);
        }
    }

    header("Location: /Agile-1-VPP/product/show/$id");
    exit;
}
}
