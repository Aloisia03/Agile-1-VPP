<?php

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\RoleController;
use Bramus\Router\Router;

$router = new Router();

// Đây là nơi khai báo các route

$router->get('/', HomeController::class . '@index');
$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/{id}', ProductController::class . '@show');

// ------------------------

$router->run();
