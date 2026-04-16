<?php
namespace App\Controllers;

use App\Models\User;

class UserController
{
    protected $userModel;

    public function __construct()
    {
        // 1. Kiểm tra nếu chưa đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        // 2. Kiểm tra nếu không phải Admin hoặc Staff
        $role = $_SESSION['user']['role'];
        if ($role !== 'admin' && $role !== 'staff') {
            // Nếu là khách mà đòi vào Admin -> Đuổi về trang chủ hoặc trang lỗi
            header("Location: /Agile-1-VPP/"); 
            exit;
        }

        $this->userModel = new \App\Models\User();
    }

    // Hiển thị danh sách khách hàng
    public function index()
    {
        $users = $this->userModel->getAllUsersWithStats();
        return view('users.index', compact('users'));
    }

    // Khóa hoặc Mở khóa tài khoản
    public function toggleStatus($id)
    {
        $this->userModel->toggleStatus($id);
        
        // Chuyển hướng về lại trang danh sách
        header("Location: /Agile-1-VPP/users");
        exit;
    }

    // Thêm quyền user
    public function updateRole($id)
    {
        $newRole = $_POST['role'] ?? 'customer';
        
        $userModel = new \App\Models\User();
        $userModel->changeRole($id, $newRole);

        header("Location: /Agile-1-VPP/users");
        exit;
    }
}