<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Controllers\ProfileController;
use App\Controllers\Client\HomeController as ClientHomeController;
use App\Controllers\Client\ProductController as ClientProductController;
use App\Controllers\Client\CartController as ClientCartController;
use App\Controllers\OrderController;


use Bramus\Router\Router;

$router = new Router();

// base path
$router->setBasePath('/Agile-1-VPP');

// HOME
$router->get('/home', HomeController::class . '@index');



// AUTH
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');

$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');

// FORGOT PASSWORD & RESET PASSWORD
$router->get('/forgot-password', AuthController::class . '@showForgotPassword');
$router->post('/forgot-password', AuthController::class . '@forgotPassword');

$router->get('/reset-password', 'App\Controllers\AuthController@showResetPassword');
$router->post('/reset-password', 'App\Controllers\AuthController@resetPassword');

// PROFILE
$router->get('/profile', ProfileController::class . '@show');
$router->get('/profile/update', ProfileController::class . '@edit');     // <-- FIX
$router->post('/profile/update', ProfileController::class . '@update');


$router->get('/logout', AuthController::class . '@logout');

// PRODUCTS
$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/(\d+)', ProductController::class . '@show');
$router->get('/products/search', ProductController::class . '@search');
$router->get('/product/create', ProductController::class . '@create');
$router->post('/product/store', ProductController::class . '@store');
$router->get('/product/edit/(\d+)', ProductController::class . '@edit');
$router->post('/product/update/(\d+)', ProductController::class . '@update');
$router->get('/product/delete/{id}', ProductController::class . '@delete');


$router->get('/orders', OrderController::class . '@index');

    $router->get('/orders/(\d+)', OrderController::class . '@show');

    $router->post('/orders/confirm/(\d+)', OrderController::class . '@confirm');

    $router->post('/orders/cancel/(\d+)', OrderController::class . '@cancel');


// ✅ FIX upload
$router->post('/product/upload-image/(\d+)', ProductController::class . '@uploadImage');

// CATEGORY
$router->get('/category', CategoryController::class . '@index');

// CART
$router->post('/cart/add', CartController::class . '@add');
$router->get('/cart', CartController::class . '@view');
$router->post('/cart/update', CartController::class . '@update');


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