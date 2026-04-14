<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ================= REGISTER ================= */

    public function showRegister()
    {
        require '../Agile-1-VPP/views/users/register.php';
    }

    public function register()
    {
        $errors = [];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if (strlen($name) < 3) $errors[] = "Tên >= 3 ký tự";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email sai";
        if (strlen($password) < 6) $errors[] = "Password >= 6 ký tự";
        if ($password !== $confirm) $errors[] = "Mật khẩu không khớp";

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $errors[] = "Email đã tồn tại";
        }

        if (!empty($errors)) {
            require '../Agile-1-VPP/views/users/register.php';
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->create($name, $email, $hash);

        header("Location: /Agile-1-VPP/login");
        exit;
    }

    /* ================= LOGIN ================= */

    public function showLogin()
    {
        require '../Agile-1-VPP/views/users/login.php';
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if (!$user) {
            $errors[] = "Sai email hoặc mật khẩu";
            require '../Agile-1-VPP/views/users/login.php';
            return;
        }

        $_SESSION['user'] = $user;

        header("Location: /Agile-1-VPP/profile");
        exit;
    }

    /* ================= LOGOUT ================= */

    public function logout()
    {
        session_destroy();
        header("Location: /Agile-1-VPP/login");
        exit;
    }

    /* ================= FORGOT PASSWORD ================= */

    public function showForgotPassword()
    {
        $errors = [];
        $success = false;
        require '../Agile-1-VPP/views/users/forgot-password.php';
    }

    public function forgotPassword()
{
    $errors = [];
    $email = $_POST['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ";
    }

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    if (!$user) {
        $errors[] = "Email không tồn tại";
    }

    if (!empty($errors)) {
        $success = false;
        require '../Agile-1-VPP/views/users/forgot-password.php';
        return;
    }

    // lưu session tạm để reset
    $_SESSION['reset_user_id'] = $user['id'];

 header("Location: /Agile-1-VPP/reset-password");
exit;
}

    /* ================= RESET PASSWORD ================= */

  public function showResetPassword()
{
    if (!isset($_SESSION['reset_user_id'])) {
        header("Location: /Agile-1-VPP/login");
        exit;
    }

    $errors = [];
    require '../Agile-1-VPP/views/users/reset-password.php';
}

   public function resetPassword()
{
    if (!isset($_SESSION['reset_user_id'])) {
        header("Location: /Agile-1-VPP/login");
        exit;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if (strlen($password) < 6) {
            $errors[] = "Mật khẩu phải >= 6 ký tự";
        }

        if ($password !== $confirm) {
            $errors[] = "Mật khẩu không khớp";
        }

        if (empty($errors)) {

            $userModel = new \App\Models\User();
            $userModel->updatePassword($_SESSION['reset_user_id'], $password);

            unset($_SESSION['reset_user_id']);

            $_SESSION['success'] = "Đổi mật khẩu thành công";

            header("Location: /Agile-1-VPP/login");
            exit;
        }
    }

    require '../Agile-1-VPP/views/users/reset-password.php';
}    public function updateProfile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /Agile-1-VPP/login");
            exit;
        }

        $userModel = new User();
        $userId = $_SESSION['user']['id'];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        $errors = [];

        if (strlen($name) < 3) $errors[] = "Tên >= 3 ký tự";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ";

        // check email trùng (tránh chính mình)
        $exist = $userModel->findByEmail($email);
        if ($exist && $exist['id'] != $userId) {
            $errors[] = "Email đã tồn tại";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: /Agile-1-VPP/profile/update");
            exit;
        }

        // update DB
        $userModel->update($userId, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address
        ]);

        // 🔥 QUAN TRỌNG: update session để không bị mất dữ liệu
        $_SESSION['user'] = $userModel->findById($userId);

        $_SESSION['success'] = "Cập nhật thành công";

        header("Location: /Agile-1-VPP/profile");
        exit;
    }
}