@extends('home')

@section('content')
<div class="content">

    <!-- HEADER -->
    <div class="page-header">
        <h3>
            <i class="fa fa-file-invoice text-primary"></i>
            Chi tiết đơn #{{ $order['id'] }}
        </h3>
    </div>

    <!-- INFO -->
    <div class="info-grid">

        <div class="info-card">
            <p><b>👤 Khách hàng:</b></p>
            <h5>{{ $order['customer_name'] }}</h5>
        </div>

        <div class="info-card">
            <p><b>📞 Số điện thoại:</b></p>
            <h5>{{ $order['customer_phone'] }}</h5>
        </div>

        <div class="info-card full">
            <p><b>📍 Địa chỉ:</b></p>
            <h5>{{ $order['customer_address'] }}</h5>
        </div>

    </div>

    <!-- TABLE -->
    <div class="card">

        <div class="card-header">
            <b>Danh sách sản phẩm</b>
        </div>

        <div class="table-wrapper">
            <table class="table">

                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th width="120">Số lượng</th>
                        <th width="150">Giá</th>
                        <th width="150">Thành tiền</th>
                    </tr>
                </thead>

                <tbody>
                    @php $total = 0; @endphp

                    @foreach($items as $item)
                        @php $subtotal = $item['price'] * $item['quantity']; @endphp
                        @php $total += $subtotal; @endphp

                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-danger text-center">
                                {{ number_format($item['price'], 0, ',', '.') }} ₫
                            </td>
                            <td class="text-success text-center font-weight-bold">
                                {{ number_format($subtotal, 0, ',', '.') }} ₫
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold">Tổng:</td>
                        <td class="text-danger font-weight-bold text-center">
                            {{ number_format($total, 0, ',', '.') }} ₫
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>

    <!-- ACTION -->
    @if($order['status'] == 'pending')
    <div class="action-box">

        <form action="/Agile-1-VPP/admin/orders/confirm/{{ $order['id'] }}" method="POST">
            <button class="btn success"
                    onclick="return confirm('Xác nhận đơn này?')">
                ✔ Duyệt đơn
            </button>
        </form>

        <form action="/Agile-1-VPP/admin/orders/cancel/{{ $order['id'] }}" method="POST">
            <button class="btn danger"
                    onclick="return confirm('Hủy đơn này?')">
                ✖ Hủy đơn
            </button>
        </form>

    </div>
    @endif

</div>

<style>

/* HEADER */
.page-header {
    margin-bottom: 20px;
}

/* INFO GRID */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.info-card {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
}

.info-card.full {
    grid-column: span 2;
}

/* CARD */
.card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    background: #f5f5f5;
    padding: 12px;
    text-align: center;
}

.table td {
    padding: 14px;
    border-bottom: 1px solid #eee;
}

.table tr:hover {
    background: #fafafa;
}

/* ACTION */
.action-box {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

.btn {
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
}

.btn.success {
    background: #2ecc71;
    color: white;
}

.btn.danger {
    background: #e74c3c;
    color: white;
}

</style>
@endsection