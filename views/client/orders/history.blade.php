@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0 pb-2">
            <li class="breadcrumb-item">
                <a href="/Agile-1-VPP/client/home" class="text-info">Trang chủ</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Đơn hàng của tôi
            </li>
        </ol>
    </nav>

    {{-- Title --}}
    <h3 class="font-weight-bold mb-4 text-info">
        <i class="fa-solid fa-box-open mr-2"></i> Đơn hàng của tôi
    </h3>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">

            {{-- Empty orders --}}
            @if(empty($orders))
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png"
                         alt="No Orders"
                         style="width:120px; opacity:0.4;"
                         class="mb-3">

                    <h5 class="text-muted">Bạn chưa có đơn hàng nào!</h5>

                    <a href="/Agile-1-VPP/client/products"
                       class="btn btn-outline-info mt-3 rounded-pill px-4">
                        Bắt đầu mua sắm
                    </a>
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

                                @php
                                    $status = $order['status'];

                                    $statusMap = [
                                        'pending' => ['Chờ xác nhận', 'warning'],
                                        'confirmed' => ['Đã xác nhận', 'info'],
                                        'completed' => ['Hoàn thành', 'success'],
                                        'canceled' => ['Đã hủy', 'danger'],

                                        // fallback nếu DB đang lưu tiếng Việt
                                        'Chờ xác nhận' => ['Chờ xác nhận', 'warning'],
                                        'Hoàn thành' => ['Hoàn thành', 'success'],
                                        'Đã hủy' => ['Đã hủy', 'danger'],
                                    ];

                                    $statusText = $statusMap[$status][0] ?? $status;
                                    $statusClass = $statusMap[$status][1] ?? 'secondary';
                                @endphp

                                <tr>
                                    {{-- Mã đơn --}}
                                    <td class="font-weight-bold text-dark">
                                        #{{ $order['id'] }}
                                    </td>

                                    {{-- Ngày đặt --}}
                                    <td>
                                        {{ !empty($order['created_at'])
                                            ? date('d/m/Y H:i', strtotime($order['created_at']))
                                            : 'N/A' }}
                                    </td>

                                    {{-- Người nhận --}}
                                    <td>
                                        <strong>{{ $order['customer_name'] }}</strong><br>
                                        <small class="text-muted">
                                            {{ $order['customer_phone'] }}
                                        </small>
                                    </td>

                                    {{-- Tổng tiền --}}
                                    <td class="font-weight-bold text-danger">
                                        {{ number_format(
                                            $order['total_price'] ?? $order['total'] ?? 0,
                                            0, ',', '.'
                                        ) }} ₫
                                    </td>

                                    {{-- Trạng thái --}}
                                    <td>
                                        <span class="badge badge-{{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>

                                    {{-- Thao tác --}}
                                    <td>

                                        {{-- Xem chi tiết --}}
                                        <a href="/Agile-1-VPP/client/order-detail/{{ $order['id'] }}"
                                           class="btn btn-sm btn-outline-info rounded-circle mr-1"
                                           title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        {{-- Hủy đơn --}}
                                        @if($status == 'pending' || $status == 'Chờ xác nhận')
                                            <a href="/Agile-1-VPP/client/cancel-order/{{ $order['id'] }}"
                                               class="btn btn-sm btn-outline-danger rounded-circle"
                                               title="Hủy đơn"
                                               onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>

                                        {{-- Mua lại --}}
                                        @elseif($status == 'canceled'
                                            || $status == 'Đã hủy'
                                            || $status == 'completed'
                                            || $status == 'Hoàn thành')

                                            <a href="/Agile-1-VPP/client/reorder/{{ $order['id'] }}"
                                               class="btn btn-sm btn-outline-success rounded-circle"
                                               title="Mua lại">
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