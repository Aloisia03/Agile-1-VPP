@extends('layouts.client')

@section('title', 'Thanh toán đơn hàng')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0 pb-2">
            <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/cart" class="text-info">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <h3 class="font-weight-bold mb-4 text-info">Thông tin giao hàng</h3>

    <form action="/Agile-1-VPP/client/checkout" method="POST">
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0 rounded-lg p-4">
                    <h5 class="mb-4 border-bottom pb-2 font-weight-bold">Địa chỉ nhận hàng</h5>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control p-4" required 
                               value="{{ isset($_SESSION['user']) ? $_SESSION['user']['name'] : '' }}" 
                               placeholder="Nhập họ tên đầy đủ">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" class="form-control p-4" required 
                               placeholder="Nhập số điện thoại liên hệ">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Địa chỉ giao hàng chi tiết <span class="text-danger">*</span></label>
                        <textarea name="customer_address" class="form-control" rows="3" required 
                                  placeholder="Ví dụ: Số nhà 12, Ngõ 34, Phường X, Quận Y, TP Z"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Ghi chú (Tùy chọn)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Giao giờ hành chính, gọi trước khi giao..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-lg p-4 bg-light">
                    <h5 class="font-weight-bold mb-3 border-bottom pb-2">Tóm tắt đơn hàng</h5>
                    
                    @php $totalPrice = 0; @endphp
                    @foreach($cart as $item)
                        @php $totalPrice += $item['price'] * $item['quantity']; @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <img src="/Agile-1-VPP/public/uploads/{{ $item['image'] }}" class="rounded mr-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ $item['name'] }}</h6>
                                    <small class="text-muted">SL: {{ $item['quantity'] }}</small>
                                </div>
                            </div>
                            <span class="font-weight-bold">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} ₫</span>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success font-weight-bold">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 mt-2">
                        <h5 class="font-weight-bold">Tổng thanh toán:</h5>
                        <h4 class="font-weight-bold text-danger m-0">{{ number_format($totalPrice, 0, ',', '.') }} ₫</h4>
                    </div>

                    <button type="submit" class="btn btn-info btn-block py-3 font-weight-bold text-uppercase rounded-pill shadow">
                        Hoàn tất đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection