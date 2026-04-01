<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center" style="height:100vh;">
        
        <div class="col-md-5">
            <div class="card shadow rounded-4">
                
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Đăng ký</h3>

                    <!-- ERROR -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e): ?>
                                <div><?= $e ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Tên</label>
                            <input name="name" class="form-control" placeholder="Nhập tên">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm" class="form-control" placeholder="Nhập lại mật khẩu">
                        </div>

                        <button class="btn btn-primary w-100">Đăng ký</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/Agile-1-VPP/login">Đã có tài khoản? Đăng nhập</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>