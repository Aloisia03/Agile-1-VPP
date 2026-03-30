<?php
namespace App\Controllers;

use App\Controller;
use PDO;

class HomeController extends Controller
{
    // Hàm render nội bộ
    private function render($viewPath, $data = []) {
        extract($data);
        $filePath = "views/" . $viewPath . ".blade.php";
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo "Lỗi: Không tìm thấy file giao diện tại $filePath";
        }
    }

    public function index() {
        try {
            // 1. Kết nối Database
            $db = new PDO("mysql:host=localhost;dbname=vpp_agile;charset=utf8", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 2. Lấy danh sách sản phẩm (Sửa lỗi ONLY_FULL_GROUP_BY)
            // Thay vì dùng GROUP BY, ta dùng DISTINCT hoặc lấy ảnh đầu tiên
            $sql = "SELECT p.*, 
                    (SELECT image_url FROM product_images WHERE product_id = p.id LIMIT 1) as image_url
                    FROM products p";
            
            $stmt = $db->query($sql);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 3. Gọi hàm render
            return $this->render('home', ['products' => $products]);

        } catch (\Exception $e) {
            // Kiểm tra và tạo thư mục log nếu chưa có để tránh lỗi Warning
            if (!is_dir('storage/logs')) {
                mkdir('storage/logs', 0755, true);
            }
            
            $this->logError("Lỗi trang chủ: " . $e->getMessage());
            echo "Dữ liệu đang được tải, vui lòng F5 lại trang! (Lỗi: " . $e->getMessage() . ")";
        }
    }
}