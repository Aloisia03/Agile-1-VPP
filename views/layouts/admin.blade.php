<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Hệ Thống VPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 260px; --primary-color: #4361ee; }
        body { background-color: #f8f9fa; overflow-x: hidden; }
        .main-wrapper { display: flex; }
        #content-area { width: 100%; margin-left: var(--sidebar-width); padding: 25px; transition: 0.3s; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.75rem; }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <?php include 'views/partials/aside.blade.php'; ?>

        <div id="content-area">
            <?php include 'views/partials/header.blade.php'; ?>

                <main class="mt-4">
                    @yield('main-content') 
                </main>

            <?php include 'views/partials/footer.blade.php'; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>