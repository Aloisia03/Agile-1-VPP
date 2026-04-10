@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0 pb-2">
            <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/home" class="text-info">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn hàng của tôi</li>
        </ol>
    </nav>

    <h3 class="font-weight-bold mb-4 text-info"><i class="fa-solid fa-box-open mr-2"></i> Đơn hàng của tôi</h3>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            @if(empty($orders))
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="No Orders" style="width: 120px; opacity: 0.4;" class="mb-3">
                    <h5 class="text-muted">Bạn chưa có đơn hàng nào!</h5>
                    <a href="/Agile-1-VPP/client/products" class="btn btn-outline-info mt-3 rounded-pill px-4">Bắt đầu mua sắm</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle text-center">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th class="py-3">Mã đơn</th>
                                <th class="py-3">Ngày đặt</th>
                                <th class="py-3">Người nhận</th>
                                <th class="py-3">Tổng tiền</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="py-3">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="font-weight-bold text-dark">#{{ $order['id'] }}</td>
                                    <td>{{ isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $order['customer_name'] }}</strong><br>
                                        <small class="text-muted">{{ $order['customer_phone'] }}</small>
                                    </td>
                                    <td class="font-weight-bold text-danger">
                                        {{ number_format($order['total_price'] ?? $order['total'] ?? 0, 0, ',', '.') }} ₫
                                    </td>
                                    <td>
                                        @if($order['status'] == 'pending' || $order['status'] == 'Chờ xác nhận')
                                            <span class="badge badge-warning px-3 py-2 text-white">Chờ xác nhận</span>
                                        @elseif($order['status'] == 'Đang giao')
                                            <span class="badge badge-info px-3 py-2">Đang giao</span>
                                        @elseif($order['status'] == 'Hoàn thành')
                                            <span class="badge badge-success px-3 py-2">Hoàn thành</span>
                                        @elseif($order['status'] == 'Đã hủy')
                                            <span class="badge badge-secondary px-3 py-2">Đã hủy</span>
                                        @else
                                            <span class="badge badge-dark px-3 py-2">{{ $order['status'] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/Agile-1-VPP/client/order-detail/{{ $order['id'] }}" class="btn btn-sm btn-outline-info rounded-circle mr-1" title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        
                                        @if($order['status'] == 'pending' || $order['status'] == 'Chờ xác nhận')
                                            <a href="/Agile-1-VPP/client/cancel-order/{{ $order['id'] }}" class="btn btn-sm btn-outline-danger rounded-circle" title="Hủy đơn" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                            
                                        @elseif($order['status'] == 'canceled' || $order['status'] == 'Đã hủy' || $order['status'] == 'Hoàn thành')
                                                <a href="/Agile-1-VPP/client/reorder/{{ $order['id'] }}" class="btn btn-sm btn-outline-success rounded-circle" title="Mua lại">
                                                    <i class="fa-solid fa-rotate-right"></i>
                                                </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection