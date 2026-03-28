<?php 
namespace App\Controllers;

use App\Controller;
use App\Model\Product;

class ProductController extends Controller
{
    private $modelProducts;

    public function __construct()
    {
        $this->modelProducts = new Product();
    }
    public function index(){
        $products = $this->modelProducts->getAll();
        return view('products.index', compact('products'));
    }
    public function show($id){
        $product = $this->modelProducts->findById($id);
        return view('products.show', compact('product'));
    }
}