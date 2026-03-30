<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; margin: 0; }
    /* Fix lỗi Sidebar đè lên nội dung */
    .wrapper { display: flex; }
    .main-container { 
        flex: 1; 
        margin-left: 260px; /* Độ rộng của Aside */
        transition: all 0.3s;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    /* Responsive cho mobile */
    @media (max-width: 992px) {
        .main-container { margin-left: 0; }
    }
</style>

<div class="wrapper">
    <?php include 'views/partials/aside.blade.php'; ?>

    <div class="main-container">
        <?php include 'views/partials/header.blade.php'; ?>

        <div class="main-content p-4">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Quản Lý Giỏ Hàng</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                                <li class="breadcrumb-item active">Giỏ hàng</li>
                            </ol>
                        </nav>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">
                        <?= count($cart ?? []) ?> Sản phẩm hiện có
                    </span>
                </div>

                <div class="row g-4">
                    <div class="col-xl-8">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light border-bottom">
                                            <tr class="text-secondary small text-uppercase">
                                                <th class="ps-4 py-3">Sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Thành tiền</th>
                                                <th class="text-end pe-4">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($cart)): $total = 0; foreach($cart as $id => $item): 
                                                $subtotal = $item['price'] * $item['quantity'];
                                                $total += $subtotal;
                                            ?>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="storage/uploads/<?= $item['image'] ?? 'default.jpg' ?>" 
                                                             class="rounded-3 shadow-sm me-3 border" 
                                                             style="width: 55px; height: 55px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold text-dark mb-0"><?= $item['name'] ?></div>
                                                            <small class="text-muted font-monospace">#<?= $id ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-medium"><?= number_format($item['price']) ?>đ</td>
                                                <td>
                                                    <form action="/cart/update" method="POST" class="d-flex align-items-center">
                                                        <input type="hidden" name="id" value="<?= $id ?>">
                                                        <div class="input-group input-group-sm" style="width: 100px;">
                                                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                                                                   class="form-control text-center fw-bold" min="1">
                                                            <button class="btn btn-primary"><i class="bi bi-check-lg"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="fw-bold text-primary"><?= number_format($subtotal) ?>đ</td>
                                                <td class="text-end pe-4">
                                                    <a href="/cart/remove/<?= $id ?>" class="btn btn-sm btn-outline-danger border-0">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <img src="https://cdn-icons-png.flaticon.com/512/11329/11329061.png" width="80" class="opacity-25 mb-3 d-block mx-auto">
                                                    <p class="text-muted fw-bold">Giỏ hàng của Long đang trống!</p>
                                                    <a href="/" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">Tiếp tục mua sắm</a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white mb-4 overflow-hidden">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4 d-flex justify-content-between">
                                    <span>Tạm tính</span>
                                    <span><?= number_format($total ?? 0) ?>đ</span>
                                </h5>
                                <div class="d-flex justify-content-between mb-3 text-white-50">
                                    <span>Vận chuyển</span>
                                    <span class="text-success fw-bold">Free</span>
                                </div>
                                <hr class="border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h6 mb-0 fw-bold">Tổng cộng:</span>
                                    <span class="h3 mb-0 fw-bold text-warning"><?= number_format($total ?? 0) ?>đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3 text-muted small text-uppercase">Xác nhận thanh toán</h6>
                            <form action="/checkout" method="POST">
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">CHỌN KHÁCH HÀNG</label>
                                    <select name="user_id" class="form-select border-0 bg-light py-2 shadow-none">
                                        <option value="1">Nguyen Van A (ID: 1)</option>
                                        <option value="2">Tran Van B (ID: 2)</option>
                                        <option value="3">Admin (ID: 3)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm <?= empty($cart) ? 'disabled' : '' ?>">
                                    <i class="bi bi-box-arrow-in-down me-2"></i>LƯU ĐƠN HÀNG VÀO DATABASE
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'views/partials/footer.blade.php'; ?>
    </div>
</div>