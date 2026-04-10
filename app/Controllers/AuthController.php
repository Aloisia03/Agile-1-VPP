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

    public function showForgotPassword() {
        $errors = [];
        $success = false;
        require '../Agile-1-VPP/views/users/forgot-password.php';
    }

    public function forgotPassword() {
        $errors = [];
        $success = false;

        $email = $_POST['email'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ";
        }

        if (empty($errors)) {
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user) {
                $resetToken = $userModel->setPasswordResetToken($email);
                
                if ($resetToken) {
                    // Gửi email reset password
                    $resetLink = "http://localhost/Agile-1-VPP/reset-password/" . $resetToken;
                    $to = $email;
                    $subject = "Đặt lại mật khẩu";
                    $message = "Bạn đã yêu cầu đặt lại mật khẩu. Nhấp vào liên kết dưới đây:\n\n";
                    $message .= $resetLink . "\n\n";
                    $message .= "Liên kết này sẽ hết hạn trong 1 giờ.";
                    
                    $headers = "From: noreply@agile.local\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                    
                    mail($to, $subject, $message, $headers);
                    $success = true;
                }
            } else {
                // Không tiết lộ nếu email tồn tại hay không (vì lý do bảo mật)
                $success = true;
            }
        }

        require '../Agile-1-VPP/views/users/forgot-password.php';
    }

    public function showResetPassword($token) {
        $errors = [];
        $userModel = new User();
        $user = $userModel->findByPasswordResetToken($token);

        if (!$user) {
            $errors[] = "Liên kết không hợp lệ hoặc đã hết hạn";
        }

        require '../Agile-1-VPP/views/users/reset-password.php';
    }

    public function resetPassword($token) {
        $errors = [];
        $success = false;

        $userModel = new User();
        $user = $userModel->findByPasswordResetToken($token);

        if (!$user) {
            $errors[] = "Liên kết không hợp lệ hoặc đã hết hạn";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';

            if (strlen($password) < 6) {
                $errors[] = "Mật khẩu phải >= 6 ký tự";
            }
            if ($password !== $confirm) {
                $errors[] = "Mật khẩu không khớp";
            }

            if (empty($errors)) {
                $result = $userModel->updatePassword($user['id'], $password);
                
                if ($result) {
                    $userModel->clearPasswordResetToken($user['id']);
                    $_SESSION['success_message'] = "Mật khẩu đã được cập nhật thành công. Vui lòng đăng nhập.";
                    header("Location: /Agile-1-VPP/login");
                    exit;
                } else {
                    $errors[] = "Cập nhật mật khẩu thất bại";
                }
            }
        }

        require '../Agile-1-VPP/views/users/reset-password.php';
    }
}