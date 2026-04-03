<?php 
namespace App\Controllers;

use App\Controller;
use App\Model\Category;
use App\Model\Product;
use Exception;
use Rakit\Validation\Validator;

class ProductController extends Controller
{
    private $modelProducts;
    private $modelCategory;
    private $validator;

    public function __construct()
    {
        $this->modelProducts = new Product();
        $this->modelCategory = new Category();
        $this->validator = new Validator();
    }
    public function index(){
        $categoryId = $_GET['category_id'] ?? null;
        $products = $this->modelProducts->getAll($categoryId);
        $categories = $this->modelCategory->getAll();
        return view('products.index', compact('products', 'categories'));
    }
    public function show($id){
        $product = $this->modelProducts->findById($id);
        return view('products.show', compact('product'));
    }
    public function store()
    {
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'category_id' => $_POST['category_id'],
            'description' => $_POST['description'],
        ];



        $rules = [
            'name'        => 'required|max:50',
            'category_id' => 'required|integer',
            'price'       => 'required|numeric',
            'description'  => 'required|string|max:255',
        ];

        $error = $this->validate($this->validator, $data, $rules);

        if (!empty($error)) {
            setFlash('error', reset($error));
            return redirect('/product/create');
        }


        //xử lý hình ảnh

        if (is_upload('image')) {
            $data['image'] = $this->uploadFile($_FILES['image'], 'products');
        } else {
            $data['image'] = null;
        };
        // debug($data);

        try {
            $this->modelProducts->insert($data);
            setFlash('success', 'thêm sản phẩm thành công');
        } catch (Exception $e) {
            setFlash('error', 'Có lỗi xảy ra');
        }

        redirect('/products');
    }
}