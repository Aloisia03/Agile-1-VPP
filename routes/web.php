<?php

require_once __DIR__ . '/../vendor/autoload.php'; // 🔥 chuẩn

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;

use Bramus\Router\Router;

$router = new Router();

// 🔥 thêm dòng này
$router->setBasePath('/Agile-1-VPP');

// ==========================================
// 1. PHẦN QUẢN TRỊ (ADMIN) - GIỮ NGUYÊN 100% CODE CỦA BẠN
// ==========================================
// HOME
$router->get('/home', HomeController::class . '@index');

// AUTH
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');

$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');

$router->get('/logout', AuthController::class . '@logout');

$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/{id}', ProductController::class . '@show');
$router->get('/category', CategoryController::class . '@index');

$router->post('/cart/add', CartController::class . '@add');
$router->get('/cart', CartController::class . '@view');
$router->post('/cart/update', CartController::class . '@update');

$router->get('/checkout', CartController::class . '@checkout');
$router->get('/my-orders', CartController::class . '@myOrders');

$router->post('/checkout', App\Controllers\CartController::class . '@checkout');
$router->get('/checkout', App\Controllers\CartController::class . '@checkout'); 
$router->get('/cancel-order/{id}', App\Controllers\CartController::class . '@cancelOrder');


// ==========================================
// 2. PHẦN KHÁCH HÀNG (CLIENT) - KHÔNG GIAN RIÊNG
// ==========================================
// Sử dụng $router->mount để bọc tất cả link của khách vào thư mục /client
$router->mount('/client', function() use ($router) {
    
    // Trang chủ: http://localhost/Agile-1-VPP/client/home
    $router->get('/home', App\Controllers\Client\HomeController::class . '@index');
    $router->get('/', App\Controllers\Client\HomeController::class . '@index');
    
    // Sản phẩm
    $router->get('/products', App\Controllers\Client\ProductController::class . '@index');
    $router->get('/product/{id}', App\Controllers\Client\ProductController::class . '@show');
    
    // Giỏ hàng
    $router->get('/cart', App\Controllers\Client\CartController::class . '@index');
    $router->post('/cart/add', App\Controllers\Client\CartController::class . '@add');
    $router->get('/cart/remove/{id}', App\Controllers\Client\CartController::class . '@remove');
    
    // Đơn hàng & Thanh toán
    $router->post('/checkout', App\Controllers\Client\CartController::class . '@checkout');
    $router->get('/checkout', App\Controllers\Client\CartController::class . '@checkout'); 
    $router->get('/my-orders', App\Controllers\Client\CartController::class . '@myOrders');
    $router->get('/cancel-order/{id}', App\Controllers\Client\CartController::class . '@cancel');
    
});

$router->run();