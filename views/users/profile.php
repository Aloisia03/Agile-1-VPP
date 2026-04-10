<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cá nhân</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        
        <div class="col-md-6">
            <div class="card shadow rounded-4">
                
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Hồ sơ cá nhân</h3>

                    <!-- SUCCESS MESSAGE -->
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Cập nhật thông tin thành công!
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

                    <div class="border rounded-3 overflow-hidden">

                        <div class="d-flex justify-content-between px-3 py-3 border-bottom bg-white">
                            <label class="form-label">Tên</label>
                            <input name="name" type="text" class="form-control" placeholder="Nhập tên" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Nhập email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Điện thoại</label>
                            <input name="phone" type="tel" class="form-control" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" class="form-control" placeholder="Nhập địa chỉ" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="/Agile-1-VPP/home">Quay lại trang chủ</a> | 
                        <a href="/Agile-1-VPP/logout">Đăng xuất</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>