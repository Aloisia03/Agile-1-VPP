<?php
namespace App\Controllers;

use App\Models\Product; 

class InventoryController
{
    protected $connection;

    public function __construct()
    {
        // Tận dụng kết nối từ Model gốc của bạn
        $model = new \App\Models\Product();
        // Lấy connection từ thuộc tính protected (nếu Model của bạn cho phép) 
        // hoặc bạn có thể khởi tạo kết nối Doctrine giống file Model.php
    }

    public function index()
    {
        // Khởi tạo model để lấy dữ liệu
        $productModel = new \App\Models\Product();
        
        // Bạn cần viết một hàm getAllProductWithCategory trong Product Model 
        // Hoặc viết query trực tiếp ở đây bằng Doctrine DBAL giống Report Model
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.stock ASC"; // Hiện những thứ sắp hết lên đầu

        // Giả sử $productModel có thuộc tính connection (bạn cần đổi nó sang public ở Model.php)
        // Hoặc tạo một class kế thừa Model giống như Report.php mình đã làm
        
        $reportModel = new \App\Models\Report(); // Tận dụng kết nối từ Report Model cho nhanh
        $products = $reportModel->getInventoryData(); 

        return view('inventory.index', compact('products'));
    }


    // update tồn kho hàng
    public function updateStock()
    {
        // Lấy dữ liệu từ form
        $productId = $_POST['product_id'] ?? null;
        $newStock = $_POST['stock'] ?? 0;

        if ($productId) {
            // Khởi tạo Model
            $model = new \App\Models\Report();
            
            // Gọi hàm public từ Model để xử lý database (Không chọc trực tiếp vào connection nữa)
            $model->updateProductStock($productId, $newStock);
        }

        // Cập nhật xong thì quay lại trang kho hàng
        header("Location: /Agile-1-VPP/inventory");
        exit;
    }
}