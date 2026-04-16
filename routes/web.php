<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Controllers\ProfileController;
use App\Controllers\UserController;
use App\Controllers\OrderController;
use App\Controllers\ReportController;
use App\Controllers\InventoryController;
use App\Controllers\Client\HomeController as ClientHomeController;
use App\Controllers\Client\ProductController as ClientProductController;
use App\Controllers\Client\CartController as ClientCartController;

use Bramus\Router\Router;

$router = new Router();

// Base path
$router->setBasePath('/Agile-1-VPP');

/* |--------------------------------------------------------------------------
| MIDDLEWARE - NGƯỜI GÁC CỔNG
|--------------------------------------------------------------------------
*/
$router->before('GET|POST', '/.*', function() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    $currentUri = $_SERVER['REQUEST_URI'];
    
    // 1. Tự động tìm vị trí của project để cắt chuỗi cho chuẩn
    // Nó sẽ lấy phần sau chữ Agile-1-VPP
    $projectPath = '/Agile-1-VPP';
    $pos = strpos($currentUri, $projectPath);
    $relativeUri = substr($currentUri, $pos + strlen($projectPath));
    
    // Nếu URL trống (trang chủ) thì gán là /
    if (empty($relativeUri)) $relativeUri = '/';

    $adminPaths = ['/users', '/reports', '/inventory', '/orders', '/products', '/category', '/product'];
    
    $isAdminPage = false;
    foreach ($adminPaths as $path) {
        // Kiểm tra xem URL có chứa từ khóa admin không
        if (strpos($relativeUri, $path) === 0) {
            $isAdminPage = true;
            break;
        }
    }

    if ($isAdminPage) {
        // KIỂM TRA ĐĂNG NHẬP
        if (!isset($_SESSION['user'])) {
            header("Location: " . $projectPath . "/login");
            exit;
        }

        // Ép kiểu role về chữ thường
        $role = strtolower($_SESSION['user']['role'] ?? '');
        
        // --- ĐOẠN DEBUG (Xóa sau khi xong) ---
        // Nếu bạn là Admin mà vẫn bị đuổi, hãy bỏ comment dòng dưới để xem lỗi ở đâu
        // die("Role cua ban la: " . $role . " | URI hien tai: " . $relativeUri);
        // ------------------------------------

        if ($role !== 'admin' && $role !== 'staff') {
            header("Location: " . $projectPath . "/client/home");
            exit;
        }
    }
});

/* |--------------------------------------------------------------------------
| ROUTES - HỆ THỐNG
|--------------------------------------------------------------------------
*/

// HOME
$router->get('/home', HomeController::class . '@index');

// AUTH (Công khai)
$router->get('/register', AuthController::class . '@showRegister');
$router->post('/register', AuthController::class . '@register');
$router->get('/login', AuthController::class . '@showLogin');
$router->post('/login', AuthController::class . '@login');
$router->get('/logout', AuthController::class . '@logout');

// QUÊN MẬT KHẨU
$router->get('/forgot-password', AuthController::class . '@showForgotPassword');
$router->post('/forgot-password', AuthController::class . '@forgotPassword');
$router->get('/reset-password', AuthController::class . '@showResetPassword');
$router->post('/reset-password', AuthController::class . '@resetPassword');

// PROFILE (Phải đăng nhập mới xem được)
$router->get('/profile', ProfileController::class . '@show');
$router->get('/profile/update', ProfileController::class . '@edit');
$router->post('/profile/update', ProfileController::class . '@update');

/* |--------------------------------------------------------------------------
| ADMIN ROUTES (Đã được bảo vệ bởi Middleware ở trên)
|--------------------------------------------------------------------------
*/

// QUẢN LÝ TÀI KHOẢN
$router->get('/users', UserController::class . '@index');
$router->post('/users/update-role/(\d+)', UserController::class . '@updateRole');
$router->post('/users/toggle-status/(\d+)', UserController::class . '@toggleStatus');

// SẢN PHẨM (Admin)
$router->get('/products', ProductController::class . '@index');
$router->get('/product/show/(\d+)', ProductController::class . '@show');
$router->get('/product/create', ProductController::class . '@create');
$router->post('/product/store', ProductController::class . '@store');
$router->get('/product/edit/(\d+)', ProductController::class . '@edit');
$router->post('/product/update/(\d+)', ProductController::class . '@update');
$router->get('/product/delete/(\d+)', ProductController::class . '@delete');
$router->post('/product/upload-image/(\d+)', ProductController::class . '@uploadImage');

// DANH MỤC
$router->get('/category', CategoryController::class . '@index');

// ĐƠN HÀNG (Admin)
$router->get('/orders', OrderController::class . '@index');
$router->get('/orders/(\d+)', OrderController::class . '@show');
$router->post('/orders/confirm/(\d+)', OrderController::class . '@confirm');
$router->post('/orders/cancel/(\d+)', OrderController::class . '@cancel');
$router->post('/orders/update-status/(\d+)', OrderController::class . '@updateStatus');

// BÁO CÁO THỐNG KÊ & KHO HÀNG
$router->get('/reports', ReportController::class . '@index');
$router->get('/inventory', InventoryController::class . '@index');
$router->post('/inventory/update', InventoryController::class . '@updateStock');

// QUẢN LÝ ĐÁNH GIÁ
$router->get('/reviews', 'App\Controllers\ReviewController@index');
$router->post('/reviews/update/(\d+)', 'App\Controllers\ReviewController@updateStatus');

/* |--------------------------------------------------------------------------
| CLIENT ROUTES (Dành cho người mua hàng)
|--------------------------------------------------------------------------
*/
$router->mount('/client', function() use ($router) {
    $router->get('/home', ClientHomeController::class . '@index');
    $router->get('/products', ClientProductController::class . '@index');
    $router->get('/product/(\d+)', ClientProductController::class . '@show');
    
    $router->get('/cart', ClientCartController::class . '@index');
    $router->post('/cart/add', ClientCartController::class . '@add');
    $router->post('/cart/update', ClientCartController::class . '@update');
    $router->get('/cart/remove/(\d+)', ClientCartController::class . '@remove');
    
    $router->get('/checkout', ClientCartController::class . '@checkout');
    $router->post('/checkout', ClientCartController::class . '@processCheckout');
    $router->get('/my-orders', ClientCartController::class . '@myOrders');
    $router->get('/order-detail/(\d+)', ClientCartController::class . '@orderDetail');
    $router->get('/cancel-order/(\d+)', ClientCartController::class . '@cancel');
    $router->get('/reorder/(\d+)', ClientCartController::class . '@reorder');
    $router->post('/product/review', 'App\Controllers\Client\ProductController@postReview');
});

$router->run();