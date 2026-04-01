@extends('home')

@section('title', 'Giỏ hàng')

@section('content')
<div class="content-wrapper">

    <h2 class="page-title">🛒 Giỏ hàng của bạn</h2>

    @if(empty($cartItems))
        <div class="empty-cart">
            <p>Giỏ hàng đang trống</p>
            <a href="/Agile-1-VPP/products" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    @else

        <div class="card">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                        <td class="product-name">
                            {{ $item['name'] }}
                        </td>

                        <td>
                            {{ number_format($item['price']) }}đ
                        </td>

                        <td>
                            <form method="POST" action="/Agile-1-VPP/cart/update" class="form-inline">
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">

                                <input type="number" 
                                       name="quantity" 
                                       value="{{ $item['quantity'] }}" 
                                       min="1" 
                                       class="qty-input">

                                <button class="btn btn-primary btn-sm">
                                    Cập nhật
                                </button>
                            </form>
                        </td>

                        <td class="subtotal">
                            {{ number_format($item['subtotal']) }}đ
                        </td>

                        <td>
                            <form method="POST" action="/Agile-1-VPP/cart/update">
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                <input type="hidden" name="quantity" value="0">

                                <button class="btn btn-danger btn-sm">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cart-footer">
            <h3>Tổng: <span>{{ number_format($total) }}đ</span></h3>

            <div class="actions">
                <a href="/Agile-1-VPP/products" class="btn btn-secondary">
                    ← Mua tiếp
                </a>

                <a href="/Agile-1-VPP/checkout" class="btn btn-success">
                    Thanh toán
                </a>
            </div>
        </div>

    @endif
</div>

<style>
.content-wrapper {
    padding: 20px;
}

.page-title {
    margin-bottom: 20px;
}

.card {
    background: #fff;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    text-align: left;
    padding: 12px;
    background: #f5f5f5;
}

.cart-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.product-name {
    font-weight: 500;
}

.qty-input {
    width: 60px;
    padding: 5px;
    margin-right: 5px;
}

.form-inline {
    display: flex;
    align-items: center;
    gap: 5px;
}

.subtotal {
    font-weight: bold;
    color: #e74c3c;
}

.cart-footer {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-footer h3 span {
    color: #27ae60;
}

.empty-cart {
    text-align: center;
    padding: 40px;
}
</style>
@endsection