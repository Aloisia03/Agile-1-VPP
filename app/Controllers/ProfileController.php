<?php
namespace App\Controllers;
use App\Models\User;

require_once __DIR__ . '/../Models/User.php';

class ProfileController {

    public function show() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $errors = [];
        $success = false;
        $user = $_SESSION['user'];
        require '../Agile-1-VPP/views/users/profile-detail.php';
    }

    public function update() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $errors = [];
        $success = false;
        $user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            // Validation
            if (strlen($name) < 3) {
                $errors[] = "Tên phải >= 3 ký tự";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ";
            }

            if (empty($errors)) {
                $userModel = new User();
                
                // Kiểm tra email đã tồn tại (ngoại trừ email hiện tại)
                if ($email !== $user['email']) {
                    $existingUser = $userModel->findByEmail($email);
                    if ($existingUser) {
                        $errors[] = "Email đã tồn tại";
                    }
                }

                if (empty($errors)) {
                    $result = $userModel->update($user['id'], $name, $email, $phone, $address);
                    
                    if ($result) {
                        // Cập nhật session
                        $updatedUser = $userModel->findById($user['id']);
                        $_SESSION['user'] = $updatedUser;
                        $success = true;
                        $user = $updatedUser;
                    } else {
                        $errors[] = "Cập nhật thất bại";
                    }
                }
            }
        }

        require '../Agile-1-VPP/views/users/profile-detail.php';
    }
}