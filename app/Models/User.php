<?php

namespace App\Models;

class User {
    private $conn;

    public function __construct() {
        $this->conn = new \mysqli("localhost", "root", "", "vpp_agile");

        if ($this->conn->connect_error) {
            die("Lỗi kết nối DB: " . $this->conn->connect_error);
        }
    }

    public function create($name, $email, $password) {
        $stmt = $this->conn->prepare(
            "INSERT INTO users(name,email,password) VALUES (?,?,?)"
        );
        $stmt->bind_param("sss", $name, $email, $password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email=?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function findByPhone($phone) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE phone=?"
        );
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function login($email, $password) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findById($id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE id=?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function update($id, $name, $email, $phone = null, $address = null) {
        $stmt = $this->conn->prepare(
            "UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?"
        );
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "UPDATE users SET password=? WHERE id=?"
        );
        $stmt->bind_param("si", $hashedPassword, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function setPasswordResetToken($email) {
        $user = $this->findByEmail($email);
        if (!$user) return false;

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->conn->prepare(
            "UPDATE users SET reset_token=?, reset_token_expires=? WHERE email=?"
        );
        $stmt->bind_param("sss", $token, $expiresAt, $email);
        $result = $stmt->execute();
        $stmt->close();

        return $result ? $token : false;
    }

    public function findByPasswordResetToken($token) {
        $currentTime = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE reset_token=? AND reset_token_expires > ?"
        );
        $stmt->bind_param("ss", $token, $currentTime);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function clearPasswordResetToken($id) {
        $stmt = $this->conn->prepare(
            "UPDATE users SET reset_token=NULL, reset_token_expires=NULL WHERE id=?"
        );
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
