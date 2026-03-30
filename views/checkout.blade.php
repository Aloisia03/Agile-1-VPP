<div class="container py-5">
    <div class="row g-5">
        <div class="col-md-7">
            <h4 class="mb-4 fw-bold">Thông tin giao hàng</h4>
            <form action="/process-order" method="POST" class="card border-0 shadow-sm p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">HỌ VÀ TÊN</label>
                        <input type="text" name="customer_name" class="form-control" required placeholder="Nguyễn Văn A">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">SỐ ĐIỆN THOẠI</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">EMAIL</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">ĐỊA CHỈ NHẬN HÀNG</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark w-100 mt-4 py-3 fw-bold">HOÀN TẤT ĐẶT HÀNG</button>
            </form>
        </div>

        <div class="col-md-5">
            <h4 class="mb-4 fw-bold text-muted text-end">Sản phẩm chọn mua</h4>
            <ul class="list-group shadow-sm border-0">
                <?php foreach($cart as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h6 class="my-0 fw-bold"><?= $item['name'] ?></h6>
                        <small class="text-muted">Số lượng: <?= $item['quantity'] ?></small>
                    </div>
                    <span class="text-muted"><?= number_format($item['price'] * $item['quantity']) ?>đ</span>
                </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between bg-light py-3">
                    <span class="fw-bold">TỔNG TIỀN</span>
                    <strong class="text-primary h5 mb-0"><?= number_format($total) ?>đ</strong>
                </li>
            </ul>
        </div>
    </div>
</div>