<?php
namespace App\Controllers;

use App\Models\User;

class ProfileController {

    public function show() {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $user = $_SESSION['user'];
        $errors = [];
        $success = $_SESSION['success'] ?? false;
        unset($_SESSION['success']);

        require '../Agile-1-VPP/views/users/profile-detail.php';
    }

    public function edit() {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user']['id']);

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $success = false;

        require '../Agile-1-VPP/views/users/profile-update.php';
    }

    public function update() {

        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $userModel = new User();
        $userId = $_SESSION['user']['id'];
        $currentUser = $userModel->findById($userId);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            // validate
            if (strlen($name) < 3) {
                $errors[] = "Tên phải >= 3 ký tự";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ";
            }

            // check email trùng
            $exist = $userModel->findByEmail($email);
            if ($exist && $exist['id'] != $userId) {
                $errors[] = "Email đã tồn tại";
            }

            if (empty($errors)) {

                // ✅ FIX QUAN TRỌNG: đúng format update($id, $data)
                $userModel->update($userId, [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address
                ]);

                // update session
                $_SESSION['user'] = $userModel->findById($userId);

                $_SESSION['success'] = "Cập nhật thành công";

                header("Location: /Agile-1-VPP/profile");
                exit;
            }
        }

        $user = $currentUser;
        require '../Agile-1-VPP/views/users/profile-update.php';
    }
}