@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng #' . $order['id'])

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0 pb-2">
            <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/home" class="text-info">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/my-orders" class="text-info">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết #{{ $order['id'] }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-info text-white font-weight-bold py-3">
                    <i class="fa-solid fa-circle-info mr-2"></i>Thông tin đơn hàng
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Mã đơn hàng:</strong> <span class="text-danger">#{{ $order['id'] }}</span></p>
                    <p class="mb-2"><strong>Trạng thái:</strong> 
                        @if($order['status'] == 'pending' || $order['status'] == 'Chờ xác nhận')
                            <span class="badge badge-warning px-2 py-1 text-white">Chờ xác nhận</span>
                        @elseif($order['status'] == 'Đang giao')
                            <span class="badge badge-info px-2 py-1">Đang giao</span>
                        @elseif($order['status'] == 'Hoàn thành')
                            <span class="badge badge-success px-2 py-1">Hoàn thành</span>
                        @elseif($order['status'] == 'Đã hủy')
                            <span class="badge badge-secondary px-2 py-1">Đã hủy</span>
                        @else
                            <span class="badge badge-dark px-2 py-1">{{ $order['status'] }}</span>
                        @endif
                    </p>
                    <hr>
                    <h6 class="font-weight-bold mb-3">Thông tin người nhận</h6>
                    <p class="mb-1"><i class="fa-solid fa-user text-muted mr-2"></i> {{ $order['customer_name'] }}</p>
                    <p class="mb-1"><i class="fa-solid fa-phone text-muted mr-2"></i> {{ $order['customer_phone'] }}</p>
                    <p class="mb-1"><i class="fa-solid fa-location-dot text-muted mr-2"></i> {{ $order['customer_address'] }}</p>
                    
                    @if(!empty($order['note']))
                        <div class="alert alert-warning mt-3 mb-0 p-2 text-sm">
                            <strong>Ghi chú:</strong> {{ $order['note'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white font-weight-bold py-3 border-bottom">
                    <i class="fa-solid fa-box-open mr-2 text-info"></i>Sản phẩm đã mua
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle text-center">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th class="py-3 text-left pl-4">Sản phẩm</th>
                                <th class="py-3">Đơn giá</th>
                                <th class="py-3">Số lượng</th>
                                <th class="py-3">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDetails as $item)
                                <tr>
                                    <td class="text-left pl-4 d-flex align-items-center">
                                        <img src="/Agile-1-VPP/public/uploads/{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded mr-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="font-weight-bold text-dark">{{ $item['name'] }}</span>
                                    </td>
                                    <td class="text-muted">{{ number_format($item['price'], 0, ',', '.') }} ₫</td>
                                    <td><span class="badge badge-light border px-2 py-1">{{ $item['quantity'] }}</span></td>
                                    <td class="font-weight-bold text-danger">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} ₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-right py-4 pr-4">
                    <h5 class="mb-0">
                        <span class="text-muted mr-3">Tổng cộng:</span> 
                        <span class="font-weight-bold text-danger" style="font-size: 1.5rem;">
                            {{ number_format($order['total_price'] ?? $order['total'] ?? 0, 0, ',', '.') }} ₫
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection