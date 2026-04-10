<?php 
namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    private $modelProducts;

    public function __construct()
    {
        $this->modelProducts = new Product();
    }

    public function index(){
    $category_id = $_GET['category_id'] ?? null;
    $sort = $_GET['sort'] ?? null;

    if($category_id){
        $products = $this->modelProducts->getByCategory($category_id, $sort);
    } else {
        $products = $this->modelProducts->getAll($sort);
    }

    $categories = (new \App\Models\Category())->getAll();

    return view('products.index', compact('products','categories','sort'));
}

    public function create() {
        $categories = (new \App\Models\Category())->getAll();
        return view('products.create', compact('categories'));
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => $_POST['category_id'] ?? null,
                'price' => $_POST['price'] ?? 0,
                'description' => $_POST['description'] ?? ''
            ];

            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)){
                    $data['image'] = 'uploads/' . $fileName;
                }
            }

            $this->modelProducts->insert($data);
            header("Location: /Agile-1-VPP/products");
            exit;
        }
    }

    public function edit($id) {
        $product = $this->modelProducts->find($id);
        if (!$product) {
            header("Location: /Agile-1-VPP/products");
            exit;
        }
        $categories = (new \App\Models\Category())->getAll();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'category_id' => $_POST['category_id'] ?? null,
                'price' => $_POST['price'] ?? 0,
                'description' => $_POST['description'] ?? ''
            ];

            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)){
                    $data['image'] = 'uploads/' . $fileName;
                }
            }

            $this->modelProducts->updateProduct($id, $data);
            header("Location: /Agile-1-VPP/products");
            exit;
        }
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