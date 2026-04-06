<?php

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\RoleController;
use Bramus\Router\Router;

$router = new Router();

// Đây là nơi khai báo các route

$router->get('/', HomeController::class . '@index');
$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/{id}', ProductController::class . '@show');
// $router->get('/category/{id}/products', ProductController::class . '@listByCategory');
$router->get('/category/{id}/products', ProductController::class . '@filterByCategory');
$router->get('/product/create',        ProductController::class . '@create');
$router->post('/product/store',        ProductController::class . "@store");
// ------------------------

$router->get('/category', CategoryController::class . '@index');
$router->get('/category/show/{id}', CategoryController::class . '@show');
$router->run();
