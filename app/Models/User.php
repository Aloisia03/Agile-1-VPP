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

    public function login($email, $password) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}