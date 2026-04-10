
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;

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

// PRODUCTS
$router->get('/products', ProductController::class . '@index');
$router->get('/product/create', ProductController::class . '@create');
$router->post('/product/store', ProductController::class . '@store');
$router->get('/product/edit/(\d+)', ProductController::class . '@edit');
$router->post('/product/update/(\d+)', ProductController::class . '@update');
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

$router->run();