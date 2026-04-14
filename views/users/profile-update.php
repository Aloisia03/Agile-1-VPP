<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật hồ sơ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-4">

                    <h3 class="text-center mb-4 text-primary">
                        Cập nhật thông tin
                    </h3>

                    <!-- SUCCESS -->
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Cập nhật thành công!
                        </div>
                    <?php endif; ?>

                    <!-- ERROR -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e): ?>
                                <div><?= htmlspecialchars($e) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- FORM -->
                   <form method="POST" action="/Agile-1-VPP/profile/update">

    <div class="mb-3">
        <label class="form-label">Họ tên</label>
        <input type="text" name="name"
               class="form-control"
               value="<?= htmlspecialchars($user['name'] ?? '') ?>"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email"
               class="form-control"
               value="<?= htmlspecialchars($user['email'] ?? '') ?>"
               autocomplete="off"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input type="text" name="phone"
               class="form-control"
               value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Địa chỉ</label>
        <textarea name="address"
                  class="form-control"
                  rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
    </div>

    <button class="btn btn-primary w-100 py-2">
        Lưu thay đổi
    </button>

</form>

                    <div class="text-center mt-3">
                        <a href="/Agile-1-VPP/profile" class="text-muted">← Quay lại hồ sơ</a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>