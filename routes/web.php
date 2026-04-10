<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;


// Import thêm Controller của Long (Client)
use App\Controllers\Client\HomeController as ClientHomeController;
use App\Controllers\Client\ProductController as ClientProductController;
use App\Controllers\Client\CartController as ClientCartController;
use Bramus\Router\Router;

$router = new Router();

// base path
$router->setBasePath('/AGILE-1-VPPP');

// HOME
$router->get('/home', HomeController::class . '@index');

// AUTH
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');

$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');

$router->get('/logout', AuthController::class . '@logout');

// PRODUCTS
$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/(\d+)', ProductController::class . '@show');
$router->get('/products/search', ProductController::class . '@search');

// ✅ FIX upload
$router->post('/product/upload-image/(\d+)', ProductController::class . '@uploadImage');

// CATEGORY
$router->get('/category', CategoryController::class . '@index');

// CART
$router->post('/cart/add', CartController::class . '@add');
$router->get('/cart', CartController::class . '@view');
$router->post('/cart/update', CartController::class . '@update');

// ==========================================
// 2. PHẦN CLIENT CỦA LONG (THÊM MỚI)
// ==========================================
$router->mount('/client', function() use ($router) {
    
    // Trang chủ & Sản phẩm
    $router->get('/home', ClientHomeController::class . '@index');
    $router->get('/products', ClientProductController::class . '@index');
    $router->get('/product/(\d+)', ClientProductController::class . '@show');
    
    // Giỏ hàng (Client)
    $router->get('/cart', ClientCartController::class . '@index');
    $router->post('/cart/add', ClientCartController::class . '@add');
    $router->post('/cart/update', ClientCartController::class . '@update');
    $router->get('/cart/remove/(\d+)', ClientCartController::class . '@remove');
    
    // Đơn hàng & Thanh toán
    $router->get('/checkout', ClientCartController::class . '@checkout');
    $router->post('/checkout', ClientCartController::class . '@processCheckout');
    $router->get('/my-orders', ClientCartController::class . '@myOrders');
    $router->get('/order-detail/(\d+)', ClientCartController::class . '@orderDetail');
    $router->get('/cancel-order/(\d+)', ClientCartController::class . '@cancel');
    $router->get('/reorder/(\d+)', ClientCartController::class . '@reorder');
});
$router->run();