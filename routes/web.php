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

// HOME
$router->get('/home', HomeController::class . '@index');

// AUTH
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');

$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');

$router->get('/logout', AuthController::class . '@logout');

$router->get('/products', ProductController::class . '@index');
$router->get('/products/search', ProductController::class . '@search');
$router->get('/product/show/{id}', ProductController::class . '@show');
$router->post('/product/upload-images/{id}', ProductController::class . '@uploadImages');
$router->get('/category', CategoryController::class . '@index');


$router->post('/cart/add', CartController::class . '@add');
$router->get('/cart', CartController::class . '@view');
$router->post('/cart/update', CartController::class . '@update');
$router->run();