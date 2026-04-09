<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cá nhân</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Hồ sơ cá nhân</h3>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Cập nhật thông tin thành công!
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e): ?>
                                <div><?= htmlspecialchars($e) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="border rounded-4 overflow-hidden bg-white">
                        <div class="d-flex justify-content-between align-items-start px-4 py-3 border-bottom">
                            <span class="fw-semibold text-muted">Tên</span>
                            <span class="text-end"><?= htmlspecialchars($user['name'] ?? 'Chua cap nhat') ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-start px-4 py-3 border-bottom bg-light">
                            <span class="fw-semibold text-muted">Email</span>
                            <span class="text-end"><?= htmlspecialchars($user['email'] ?? 'Chua cap nhat') ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-start px-4 py-3 border-bottom">
                            <span class="fw-semibold text-muted">Số điện thoại</span>
                            <span class="text-end"><?= htmlspecialchars($user['phone'] ?? 'Chua cap nhat') ?></span>
                        </div>

                        <div class="px-4 py-3 bg-light">
                            <div class="fw-semibold text-muted mb-2">Địa chỉ</div>
                            <div><?= nl2br(htmlspecialchars($user['address'] ?? 'Chua cap nhat')) ?></div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="/Agile-1-VPP/home" class="btn btn-outline-secondary">Quay lại trang chủ</a>
                        <a href="/Agile-1-VPP/logout" class="btn btn-danger ms-2">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
