<?php


use App\Controllers\HomeController;
use App\Controllers\CartController; // Thêm dòng này
use Bramus\Router\Router;

$router = new Router();

$router->get('/', HomeController::class . '@index');

// Nhóm Route cho Giỏ hàng
$router->get('/cart', CartController::class . '@index'); // Xem giỏ hàng
$router->post('/cart/add', CartController::class . '@add'); // Thêm sản phẩm
$router->post('/cart/update', CartController::class . '@update'); // Cập nhật số lượng
$router->get('/cart/remove/(\d+)', CartController::class . '@remove'); // Xóa sản phẩm

// Nhóm Route cho Đặt hàng
$router->get('/checkout', CartController::class . '@checkout'); // Giao diện đặt hàng
$router->post('/checkout', CartController::class . '@processOrder'); // Lưu đơn hàng vào DB

$router->run();