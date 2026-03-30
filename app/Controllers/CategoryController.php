<?php 
namespace App\Controllers;
use App\Controller;
use App\Model\Category;
class CategoryController extends Controller{
    private $modelCategory;
    public function __construct()
    {
        $this->modelCategory = new Category();
    }
    public function index(){
        $title = "Danh mục sản phẩm";
        $categories = $this->modelCategory->getAll();
        return view('categories.index',compact('title','categories'));
    }
}