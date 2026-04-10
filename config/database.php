<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "duanagile";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Kết nối DB thất bại: " . $conn->connect_error);
}
return $conn;