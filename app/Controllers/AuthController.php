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

        if (strlen($name) < 3) $errors[] = "Tên >= 3 ký tự";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email sai";
        if (strlen($password) < 6) $errors[] = "Pass >= 6";
        if ($password !== $confirm) $errors[] = "Không khớp";

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $errors[] = "Email đã tồn tại";
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
            $errors[] = "Sai tài khoản hoặc mật khẩu";
        }

        if (empty($errors)) {
            $_SESSION['user'] = $user; // 
            header("Location: home");
            exit;
        }

          require '../Agile-1-VPP/views/users/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
    }
}