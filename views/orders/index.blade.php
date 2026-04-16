@extends('home')

@section('content')

<style>
    /* ================= STATS CARDS ================= */
    .stat-card {
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .stat-card .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 5rem;
        opacity: 0.1;
        transform: rotate(-15deg);
        transition: all 0.3s ease;
    }
    .stat-card:hover .icon-bg {
        transform: rotate(0deg) scale(1.1);
        opacity: 0.15;
    }
    
    /* Bảng màu chuyên nghiệp cho Cards */
    .card-all { background: linear-gradient(135deg, #f8f9fc 0%, #eef1f6 100%); border-left: 5px solid #4e73df; }
    .card-pending { background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%); border-left: 5px solid #f6c23e; }
    .card-confirmed { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-left: 5px solid #4caf50; }
    .card-shipping { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-left: 5px solid #2196f3; }
    .card-completed { background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); border-left: 5px solid #009688; }
    .card-canceled { background: linear-gradient(135deg, #fce4e4 0%, #f8d7da 100%); border-left: 5px solid #e74a3b; }

    /* ================= BẢNG & NÚT ================= */
    .table th {
        background-color: #f8f9fc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .action-btn {
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .action-btn:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
    
    /* Dropdown trạng thái */
    .status-select {
        border-radius: 50px;
        padding: 5px 25px 5px 12px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid #e3e6f0;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23888%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 10px top 50%;
        background-size: 10px auto;
        background-color: white;
    }
    .status-pending { color: #f6c23e; border-color: #f6c23e; background-color: #fffdf5;}
    .status-confirmed { color: #4caf50; border-color: #4caf50; background-color: #f2fcf3;}
    .status-shipping { color: #2196f3; border-color: #2196f3; background-color: #f0f7ff;}
    .status-completed { color: #009688; border-color: #009688; background-color: #effbf9;}
    .status-canceled { color: #e74a3b; border-color: #e74a3b; background-color: #fff5f5;}
</style>

<div class="container-fluid py-4" style="background-color: #f4f6f9; min-height: 100vh;">

    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h3 class="font-weight-bold text-dark mb-1">Quản lý đơn hàng</h3>
            <p class="text-muted mb-0">Theo dõi, xét duyệt và cập nhật trạng thái đơn hàng của Tre Trẻ VPP</p>
        </div>
    </div>

@php
    // LOGIC DỮ LIỆU
    $currentStatus = $_GET['status'] ?? '';
    $orderId = $_GET['order_id'] ?? null;
    $baseOrders = $orders ?? [];

    $countAll = count($baseOrders);
    $countPending = count(array_filter($baseOrders, fn($o) => $o['status'] == 'pending'));
    $countConfirmed = count(array_filter($baseOrders, fn($o) => $o['status'] == 'confirmed'));
    $countShipping = count(array_filter($baseOrders, fn($o) => $o['status'] == 'shipping'));
    $countCompleted = count(array_filter($baseOrders, fn($o) => $o['status'] == 'completed'));
    $countCanceled = count(array_filter($baseOrders, fn($o) => $o['status'] == 'canceled'));

    $ordersFiltered = $baseOrders;
    if ($currentStatus !== '') {
        $ordersFiltered = array_filter($ordersFiltered, fn($o) => $o['status'] == $currentStatus);
    }
    if (!empty($orderId)) {
        $ordersFiltered = array_filter($ordersFiltered, fn($o) => $o['id'] == $orderId);
    }
@endphp

    <div class="row mb-4">
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="orders" class="text-decoration-none">
                <div class="card stat-card card-all shadow-sm h-100 {{ $currentStatus === '' ? 'shadow' : '' }}">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tất cả đơn</div>
                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $countAll }}</div>
                        <i class="fa-solid fa-layer-group text-primary icon-bg"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="?status=pending" class="text-decoration-none">
                <div class="card stat-card card-pending shadow-sm h-100 {{ $currentStatus === 'pending' ? 'shadow' : '' }}">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ xử lý</div>
                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $countPending }}</div>
                        <i class="fa-solid fa-clock-rotate-left text-warning icon-bg"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="?status=confirmed" class="text-decoration-none">
                <div class="card stat-card card-confirmed shadow-sm h-100 {{ $currentStatus === 'confirmed' ? 'shadow' : '' }}">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đang chuẩn bị</div>
                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $countConfirmed }}</div>
                        <i class="fa-solid fa-box-open text-success icon-bg"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="?status=shipping" class="text-decoration-none">
                <div class="card stat-card card-shipping shadow-sm h-100 {{ $currentStatus === 'shipping' ? 'shadow' : '' }}">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Đang giao</div>
                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $countShipping }}</div>
                        <i class="fa-solid fa-truck-fast text-info icon-bg"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="?status=completed" class="text-decoration-none">
                <div class="card stat-card card-completed shadow-sm h-100 {{ $currentStatus === 'completed' ? 'shadow' : '' }}">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold" style="color: #009688; text-transform: uppercase; margin-bottom: 0.25rem;">Hoàn thành</div>
                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $countCompleted }}</div>
                        <i class="fa-solid fa-check-double icon-bg" style="color: #009688;"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <a href="?status=canceled" class="text-decoration-none">
                <div class="card stat-card card-canceled shadow-sm h-100 {{ $currentStatus === 'canceled' ? 'shadow' : '' }}">
                    <div class="card-body p-3">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Đã hủy</div>
                        <div class="h3 mb-0 font-weight-bold text-dark">{{ $countCanceled }}</div>
                        <i class="fa-solid fa-ban text-danger icon-bg"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <form class="form-inline w-100" method="GET" action="">
                <div class="input-group input-group-sm rounded bg-light border p-1" style="max-width: 350px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    </div>
                    <input type="text" name="order_id" class="form-control bg-transparent border-0 shadow-none" placeholder="Nhập mã đơn cần tìm..." value="{{ $_GET['order_id'] ?? '' }}">
                    @if($currentStatus !== '')
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                    @endif
                </div>
                <button type="submit" class="btn btn-sm btn-primary ml-2 px-4 shadow-sm" style="border-radius: 8px;">Lọc</button>
                @if(!empty($_GET['order_id']) || $currentStatus !== '')
                    <a href="orders" class="btn btn-sm btn-light ml-2 text-muted" style="border-radius: 8px;"><i class="fa-solid fa-rotate-right"></i> Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive bg-white">
            <table class="table table-hover align-middle mb-0 text-dark">
                <thead>
                    <tr>
                        <th class="border-0 text-muted font-weight-bold" style="padding-left: 1.5rem;">Mã Đơn</th>
                        <th class="border-0 text-muted font-weight-bold">Khách hàng</th>
                        <th class="border-0 text-muted font-weight-bold text-right">Tổng Tiền</th>
                        <th class="border-0 text-muted font-weight-bold text-center">Trạng thái</th>
                        <th class="border-0 text-muted font-weight-bold text-center" style="padding-right: 1.5rem;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordersFiltered as $order)
                    <tr>
                        <td class="align-middle" style="padding-left: 1.5rem;">
                            <span class="font-weight-bold text-dark">#{{ str_pad($order['id'], 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">{{ $order['customer_name'] }}</span>
                                <span class="text-secondary small mt-1"><i class="fa-solid fa-phone mr-1 text-muted"></i> {{ $order['customer_phone'] ?? 'Chưa cập nhật' }}</span>
                            </div>
                        </td>

                        <td class="align-middle text-right">
                            <span class="font-weight-bold text-danger" style="font-size: 1.1rem;">
                                {{ number_format($order['total_price'], 0, ',', '.') }} ₫
                            </span>
                        </td>

                        <td class="align-middle text-center">
                            @if($order['status'] == 'canceled')
                                <span class="badge badge-danger px-3 py-2" style="border-radius: 20px;"><i class="fa-solid fa-ban mr-1"></i> Đã hủy</span>
                            @else
                                <form action="/Agile-1-VPP/orders/update-status/{{ $order['id'] }}" method="POST" class="m-0">
                                    <select name="status" class="status-select status-{{ $order['status'] }}" onchange="this.form.submit()">
                                        <option value="pending" {{ $order['status'] == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="confirmed" {{ $order['status'] == 'confirmed' ? 'selected' : '' }}>Đang chuẩn bị hàng</option>
                                        <option value="shipping" {{ $order['status'] == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                        <option value="completed" {{ $order['status'] == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                    </select>
                                </form>
                            @endif
                        </td>

                        <td class="align-middle" style="padding-right: 1.5rem;">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                
                                <a href="/Agile-1-VPP/orders/{{ $order['id'] }}" class="btn btn-outline-info action-btn mr-1">
                                    <i class="fa-regular fa-eye mr-1"></i> Chi tiết
                                </a>

                                @if($order['status'] == 'pending')
                                <form action="/Agile-1-VPP/orders/confirm/{{ $order['id'] }}" method="POST" class="m-0 mr-1">
                                    <button class="btn btn-success action-btn" onclick="return confirm('Duyệt đơn hàng này?')">
                                        <i class="fa-solid fa-check mr-1"></i> Duyệt
                                    </button>
                                </form>
                                @endif

                                @if(!in_array($order['status'], ['canceled', 'completed']))
                                <form action="/Agile-1-VPP/orders/cancel/{{ $order['id'] }}" method="POST" class="m-0">
                                    <button class="btn btn-outline-danger action-btn" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')">
                                        <i class="fa-solid fa-xmark mr-1"></i> Hủy
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/11544/11544571.png" alt="Empty" style="width: 100px; opacity: 0.5;" class="mb-3">
                            <h6 class="font-weight-bold text-muted">Chưa có đơn hàng nào</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection