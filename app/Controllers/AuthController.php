<?php
namespace App\Controllers;

use App\Models\User;

require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function showRegister() {
        require '../Agile-1-VPP/views/users/register.php';
    }

    public function register() {
        $errors = [];

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if (strlen($name) < 3) $errors[] = "Ten >= 3 ky tu";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email sai";
        if (strlen($password) < 6) $errors[] = "Pass >= 6";
        if ($password !== $confirm) $errors[] = "Mat khau khong khop";

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $errors[] = "Email da ton tai";
        }

        if (empty($errors)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userModel->create($name, $email, $hash);
            header("Location: login");
            exit;
        }

        require '../Agile-1-VPP/views/users/register.php';
    }

    public function showLogin() {
        require '../Agile-1-VPP/views/users/login.php';
    }

    public function login() {
        $errors = [];

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if (!$user) {
            $errors[] = "Sai tai khoan hoac mat khau";
        }

        if (empty($errors)) {
            $_SESSION['user'] = $user;
            header("Location: home");
            exit;
        }

        require '../Agile-1-VPP/views/users/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
    }

    public function showForgotPassword() {
        $errors = [];
        $success = false;
        require '../Agile-1-VPP/views/users/forgot-password.php';
    }

    public function forgotPassword() {
        $errors = [];
        $success = false;

        $phone = trim($_POST['phone'] ?? '');

        if (!preg_match('/^(0|\\+84)\\d{9,10}$/', $phone)) {
            $errors[] = "So dien thoai khong hop le";
        }

        if (empty($errors)) {
            $userModel = new User();
            $user = $userModel->findByPhone($phone);

            if (!$user) {
                $errors[] = "So dien thoai khong ton tai trong he thong";
            } else {
                $_SESSION['password_reset_user_id'] = (int) $user['id'];
                $_SESSION['password_reset_phone'] = $user['phone'];
                header("Location: /Agile-1-VPP/reset-password");
                exit;
            }
        }

        require '../Agile-1-VPP/views/users/forgot-password.php';
    }

    public function showResetPassword() {
        $errors = [];

        if (empty($_SESSION['password_reset_user_id'])) {
            $errors[] = "Phien xac minh da het han. Vui long thuc hien lai.";
        }

        require '../Agile-1-VPP/views/users/reset-password.php';
    }

    public function resetPassword() {
        $errors = [];

        $userId = $_SESSION['password_reset_user_id'] ?? null;
        $phone = $_SESSION['password_reset_phone'] ?? null;

        if (!$userId || !$phone) {
            $errors[] = "Phien xac minh da het han. Vui long thuc hien lai.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            $verifyPhone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            if ($verifyPhone !== $phone) {
                $errors[] = "So dien thoai xac minh khong dung";
            }
            if (strlen($password) < 6) {
                $errors[] = "Mat khau phai >= 6 ky tu";
            }
            if ($password !== $confirm) {
                $errors[] = "Mat khau khong khop";
            }

            if (empty($errors)) {
                $userModel = new User();
                $result = $userModel->updatePassword((int) $userId, $password);

                if ($result) {
                    unset($_SESSION['password_reset_user_id'], $_SESSION['password_reset_phone']);
                    $_SESSION['success_message'] = "Mat khau da duoc cap nhat thanh cong. Vui long dang nhap.";
                    header("Location: /Agile-1-VPP/login");
                    exit;
                } else {
                    $errors[] = "Cap nhat mat khau that bai";
                }
            }
        }

        require '../Agile-1-VPP/views/users/reset-password.php';
    }
}
