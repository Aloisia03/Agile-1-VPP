<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center" style="height:100vh;">
        
        <div class="col-md-5">
            <div class="card shadow rounded-4">
                
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Quên mật khẩu</h3>

                    <!-- SUCCESS MESSAGE -->
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Nếu email tồn tại trong hệ thống, bạn sẽ nhận được liên kết đặt lại mật khẩu. Vui lòng kiểm tra email của bạn.
                        </div>
                    <?php endif; ?>

                    <!-- ERROR -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e): ?>
                                <div><?= $e ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!$success): ?>
                        <p class="text-muted text-center mb-3">Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>

                        <form method="POST">

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" placeholder="Nhập email" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Gửi liên kết reset</button>
                        </form>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="/Agile-1-VPP/login">Quay lại đăng nhập</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
