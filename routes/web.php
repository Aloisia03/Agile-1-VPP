<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Controllers\ProfileController;
use App\Controllers\StatisticsController;

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

$router->get('/logout', AuthController::class . '@logout');

// FORGOT PASSWORD & RESET PASSWORD (PHONE VERIFICATION)
$router->get('/forgot-password', AuthController::class . '@showForgotPassword');
$router->post('/forgot-password', AuthController::class . '@forgotPassword');

$router->get('/reset-password', AuthController::class . '@showResetPassword');
$router->post('/reset-password', AuthController::class . '@resetPassword');

// PROFILE
$router->get('/profile', ProfileController::class . '@show');
$router->post('/profile', ProfileController::class . '@update');

// PRODUCTS
$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/(\d+)', ProductController::class . '@show');
$router->get('/products/search', ProductController::class . '@search');
$router->post('/product/upload-image/(\d+)', ProductController::class . '@uploadImage');

// CATEGORY
$router->get('/category', CategoryController::class . '@index');

// STATISTICS
$router->get('/statistics', StatisticsController::class . '@index');

// CART
$router->post('/cart/add', CartController::class . '@add');
$router->get('/cart', CartController::class . '@view');
$router->post('/cart/update', CartController::class . '@update');

$router->run();
