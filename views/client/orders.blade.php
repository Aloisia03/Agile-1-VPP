@extends('layouts.client')

@section('title', 'Lịch sử mua hàng')

@section('content')
<div class="order-wrapper">
    <div class="order-header">
        <div class="header-title">
            <h1>Đơn hàng của tôi</h1>
            <p>Quản lý và theo dõi tiến độ các đơn hàng bạn đã đặt</p>
        </div>
        <div class="header-action">
            <a href="/Agile-1-VPP/products" class="btn-buy-more">
                <i class="fa-solid fa-plus"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>

    <div class="order-card">
        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt hàng</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th class="text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($orders) > 0)
                        @foreach($orders as $order)
                        <tr>
                            <td class="order-id">#{{ $order['id'] }}</td>
                            <td class="order-date">
                                <i class="fa-regular fa-calendar-check"></i>
                                {{ date('d/m/Y - H:i', strtotime($order['created_at'])) }}
                            </td>
                            <td class="order-total">{{ number_format($order['total']) }}đ</td>
                            <td>
                                @php
                                    $statusLabel = [
                                        'pending' => ['text' => 'Chờ xác nhận', 'class' => 'st-pending'],
                                        'completed' => ['text' => 'Hoàn thành', 'class' => 'st-completed'],
                                        'cancelled' => ['text' => 'Đã hủy', 'class' => 'st-cancelled']
                                    ][$order['status']] ?? ['text' => $order['status'], 'class' => 'st-default'];
                                @endphp
                                <span class="status-badge {{ $statusLabel['class'] }}">
                                    {{ $statusLabel['text'] }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="action-group">
                                    <a href="#" class="btn-icon view" title="Xem chi tiết">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                    @if($order['status'] == 'pending')
                                    <a href="/Agile-1-VPP/cancel-order/{{ $order['id'] }}" 
                                       class="btn-icon cancel" 
                                       onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')"
                                       title="Hủy đơn">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="empty-row">
                            <div class="empty-content">
                                <i class="fa-solid fa-inbox"></i>
                                <p>Kho hàng của bạn đang trống. Bắt đầu mua sắm ngay thôi!</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* MODERN DASHBOARD CSS - SENIOR STYLE */
:root {
    --primary: #3498db;
    --success: #2ecc71;
    --warning: #f1c40f;
    --danger: #e74c3c;
    --text-dark: #2c3e50;
    --text-light: #7f8c8d;
    --bg-light: #f9fbfc;
    --white: #ffffff;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.order-wrapper {
    padding: 20px;
    background-color: var(--bg-light);
    min-height: 100vh;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* Header Section */
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.header-title h1 {
    font-size: 24px;
    color: var(--text-dark);
    margin: 0;
    font-weight: 700;
}
.header-title p {
    color: var(--text-light);
    margin: 5px 0 0;
    font-size: 14px;
}
.btn-buy-more {
    background-color: var(--primary);
    color: white !important;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none !important;
    font-weight: 600;
    transition: 0.3s;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}
.btn-buy-more:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4); }

/* Card & Table */
.order-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 10px;
}
.table-container { overflow-x: auto; }
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.modern-table th {
    padding: 18px 20px;
    text-align: left;
    font-size: 13px;
    text-transform: uppercase;
    color: var(--text-light);
    font-weight: 600;
    border-bottom: 1px solid #f1f4f8;
}
.modern-table td {
    padding: 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f4f8;
}
.modern-table tr:last-child td { border-bottom: none; }

/* Styling Columns */
.order-id { font-weight: 700; color: var(--text-dark); }
.order-date { color: var(--text-light); font-size: 14px; }
.order-date i { margin-right: 8px; color: var(--primary); }
.order-total { font-weight: 700; color: var(--danger); font-size: 16px; }

/* Status Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 600;
}
.st-pending { background: #fff9e6; color: #d4a017; }
.st-completed { background: #e6f9ed; color: #2ecc71; }
.st-cancelled { background: #fff0f0; color: #e74c3c; }

/* Action Buttons */
.text-right { text-align: right; }
.action-group { display: flex; justify-content: flex-end; gap: 10px; }
.btn-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: 0.3s;
    text-decoration: none !important;
}
.btn-icon.view { background: #eef2f7; color: var(--primary); }
.btn-icon.cancel { background: #fff0f0; color: var(--danger); }
.btn-icon:hover { transform: scale(1.1); }

/* Empty State */
.empty-row { padding: 80px !important; text-align: center; }
.empty-content i { font-size: 50px; color: #dce3e9; margin-bottom: 15px; }
.empty-content p { color: var(--text-light); }
</style>
@endsection