<?php

namespace App\Models;

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = new \mysqli("localhost", "root", "", "duanagile");

        if ($this->conn->connect_error) {
            die("DB Error: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    /* ================= CREATE ================= */
    public function create($name, $email, $password)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO users(name, email, password)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("sss", $name, $email, $password);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /* ================= FIND ================= */
    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM users WHERE email = ?
        ");

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        return $result;
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM users WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        return $result;
    }

    /* ================= LOGIN ================= */
    public function login($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /* ================= CHECK EMAIL TRÙNG ================= */
    public function findByEmailExceptId($email, $id)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE email = ? AND id != ?
        ");

        $stmt->bind_param("si", $email, $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        return $result;
    }

    public function update($id, $data)
{
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];

    $stmt = $this->conn->prepare("
        UPDATE users 
        SET name=?, email=?, phone=?, address=?
        WHERE id=?
    ");

    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

    return $stmt->execute();
}

    /* ================= UPDATE PASSWORD ================= */
    public function updatePassword($id, $newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            UPDATE users SET password = ? WHERE id = ?
        ");

        $stmt->bind_param("si", $hash, $id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /* ================= RESET TOKEN ================= */
    public function setPasswordResetToken($email)
    {
        $user = $this->findByEmail($email);
        if (!$user) return false;

        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->conn->prepare("
            UPDATE users 
            SET reset_token = ?, reset_token_expires = ?
            WHERE email = ?
        ");

        $stmt->bind_param("sss", $token, $expire, $email);

        $stmt->execute();
        $stmt->close();

        return $token;
    }

    public function findByPasswordResetToken($token)
    {
        $now = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE reset_token = ? 
            AND reset_token_expires > ?
        ");

        $stmt->bind_param("ss", $token, $now);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        return $result;
    }

    public function clearPasswordResetToken($id)
    {
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET reset_token = NULL, reset_token_expires = NULL
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}