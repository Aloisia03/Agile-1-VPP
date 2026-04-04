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

    public function search(){
        $keyword = $_GET['keyword'] ?? '';
        if(empty($keyword)){
            $products = $this->modelProducts->getAll();
        } else {
            $products = $this->modelProducts->search($keyword);
        }
        return view('products.index', compact('products', 'keyword'));
    }

    public function uploadImages($id){
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $product = $this->modelProducts->find($id);
        if(!$product) return;

        $images = $product['images'] ?? [];

        if(isset($_FILES['images'])){
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            foreach($_FILES['images']['tmp_name'] as $key => $tmpName){
                if($_FILES['images']['error'][$key] === UPLOAD_ERR_OK){
                    $fileName = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
                    $filePath = $uploadDir . $fileName;
                    if(move_uploaded_file($tmpName, $filePath)){
                        $images[] = 'uploads/' . $fileName;
                    }
                }
            }
        }

        // Update product with new images
        $this->modelProducts->updateImages($id, json_encode($images));

        header("Location: /Agile-1-VPP/product/show/$id");
        exit;
    }
}