<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Văn Phòng Phẩm</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #3498db;
            --dark-blue: #2c3e50;
            --light-bg: #f4f7f6;
        }

        body {
            margin: 0;
            display: flex;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--light-bg);
            color: #333;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--dark-blue);
            color: white;
            position: fixed;
        }

        .main-container {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .content {
            padding: 25px;
            flex: 1;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            margin-left: 8px;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-cart {
            background: #27ae60;
            color: white;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-badge {
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 4px;
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-container">

        <header>
            <div class="breadcrumb">
                Admin / <strong>@yield('title')</strong>
            </div>

            <div class="top-actions">

                <!-- 🛒 GIỎ HÀNG -->
                <?php
                $cartCount = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $qty) {
                        $cartCount += $qty;
                    }
                }
                ?>
                <a href="/Agile-1-VPP/cart" class="btn btn-cart">
                    <i class="fa fa-shopping-cart"></i>
                    Giỏ hàng
                    <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <!-- 👤 USER -->
                <?php if (isset($_SESSION['user'])): ?>

                <i class="fa-regular fa-circle-user"></i>
                <?= $_SESSION['user']['name'] ?>

                <a href="/Agile-1-VPP/login" class="btn btn-danger">
                    Đăng xuất
                </a>

                <?php else: ?>

                <a href="/Agile-1-VPP/login" class="btn btn-primary">
                    Đăng nhập
                </a>

                <?php endif; ?>

            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>

    </div>

</body>

</html>
