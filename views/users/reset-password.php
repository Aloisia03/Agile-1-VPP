<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dat lai mat khau</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center" style="height:100vh;">
        <div class="col-md-5">
            <div class="card shadow rounded-4">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Dat lai mat khau</h3>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e): ?>
                                <div><?= $e ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($errors) || $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nhập lai số điện thoại để xác minh</label>
                                <input type="tel" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="confirm" class="form-control" placeholder="Nhập lại mật khẩu" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>
                        </form>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="/Agile-1-VPP/login">Quay lại đăng nhập </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
