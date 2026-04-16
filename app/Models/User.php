<?php
namespace App\Models;

use App\Model;

class User extends Model
{
    /* ================= TÌM KIẾM (findById, findByEmail) ================= */
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->connection->executeQuery($sql, ['id' => $id])->fetchAssociative();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->connection->executeQuery($sql, ['email' => $email])->fetchAssociative();
    }

    public function findByEmailExceptId($email, $id) {
        $sql = "SELECT * FROM users WHERE email = :email AND id != :id";
        return $this->connection->executeQuery($sql, ['email' => $email, 'id' => $id])->fetchAssociative();
    }

    /* ================= ĐĂNG KÝ & ĐĂNG NHẬP (create, login) ================= */
    public function create($name, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, role, status) VALUES (:name, :email, :password, 'customer', 'active')";
        return $this->connection->executeStatement($sql, [
            'name' => $name, 'email' => $email, 'password' => $hash
        ]);
    }

    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return ($user['status'] == 'banned') ? 'banned' : $user;
        }
        return false;
    }

    /* ================= CẬP NHẬT PROFILE (update) ================= */
    public function update($id, $data) {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id";
        return $this->connection->executeStatement($sql, [
            'name' => $data['name'], 'email' => $data['email'],
            'phone' => $data['phone'], 'address' => $data['address'], 'id' => $id
        ]);
    }

    /* ================= QUÊN MẬT KHẨU (token) ================= */
    public function setPasswordResetToken($email) {
        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql = "UPDATE users SET reset_token = :token, reset_token_expires = :expire WHERE email = :email";
        $this->connection->executeStatement($sql, ['token' => $token, 'expire' => $expire, 'email' => $email]);
        return $token;
    }

    public function findByResetToken($token) {
        $sql = "SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()";
        return $this->connection->executeQuery($sql, ['token' => $token])->fetchAssociative();
    }

    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id";
        return $this->connection->executeStatement($sql, ['password' => $hash, 'id' => $id]);
    }

    /* ================= QUẢN LÝ ADMIN (Mới thêm) ================= */
    public function getAllUsersWithStats()
    {
        // Bỏ WHERE u.role = 'customer' để hiện cả Admin và Staff
        $sql = "SELECT u.*, 
                       COUNT(o.id) as total_orders,
                       COALESCE(SUM(CASE WHEN o.status = 'completed' THEN o.total_price ELSE 0 END), 0) as total_spent
                FROM users u
                LEFT JOIN orders o ON u.id = o.user_id
                GROUP BY u.id
                ORDER BY 
                    CASE WHEN u.role = 'admin' THEN 1 
                         WHEN u.role = 'staff' THEN 2 
                         ELSE 3 END, 
                    total_spent DESC"; // Admin hiện lên đầu, sau đó đến nhân viên và khách
                
        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }

    public function toggleStatus($id) {
        $user = $this->findById($id);
        if ($user) {
            $newStatus = ($user['status'] == 'active') ? 'banned' : 'active';
            return $this->connection->executeStatement($sql = "UPDATE users SET status = :status WHERE id = :id", 
                   ['status' => $newStatus, 'id' => $id]);
        }
    }

    // Thêm vào app/Models/User.php
    public function changeRole($id, $newRole)
    {
        $id = (int)$id;
        // Chỉ cho phép các quyền hợp lệ để tránh hack dữ liệu
        $allowedRoles = ['customer', 'admin', 'staff'];
        
        if (in_array($newRole, $allowedRoles)) {
            $sql = "UPDATE users SET role = :role WHERE id = :id";
            return $this->connection->executeStatement($sql, [
                'role' => $newRole,
                'id' => $id
            ]);
        }
        return false;
    }
}