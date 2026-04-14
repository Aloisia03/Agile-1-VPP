@extends('layouts.client')

@section('title', $product['name'])

@section('content')
<div class="container py-4">

    {{-- BREADCRUMB --}}
    <nav>
        <ol class="breadcrumb bg-transparent px-0">
            <li class="breadcrumb-item">
                <a href="/Agile-1-VPP/client/home" class="text-info">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/Agile-1-VPP/client/products" class="text-info">Sản phẩm</a>
            </li>
            <li class="breadcrumb-item active">{{ $product['name'] }}</li>
        </ol>
    </nav>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm rounded-lg mt-3">
        <div class="row no-gutters">

            {{-- IMAGE --}}
            <div class="col-md-5 product-img-wrap">
                <img src="{{ file_url($product['image'] ?? '') }}"
                     onerror="this.src='https://via.placeholder.com/400x400'"
                     alt="{{ $product['name'] }}">
            </div>

            {{-- INFO --}}
            <div class="col-md-7 p-5 bg-light">

                <h2 class="font-weight-bold mb-2">{{ $product['name'] }}</h2>

                <p class="text-muted mb-3">
                    Mã SP: #{{ $product['id'] }} |
                    <span class="badge badge-success">Còn hàng</span>
                </p>

                <input type="hidden" id="base_price" value="{{ $product['price'] }}">

                {{-- PRICE --}}
                <div class="price-box mb-4">
                    <h3 id="display_price">
                        {{ number_format($product['price'], 0, ',', '.') }} ₫
                    </h3>
                </div>

                {{-- DESCRIPTION --}}
                <p class="mb-4 text-muted">
                    {{ $product['description'] ?? 'Đang cập nhật mô tả...' }}
                </p>

                {{-- FORM --}}
                <form action="/Agile-1-VPP/client/cart/add" method="POST">
                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                    {{-- QUANTITY --}}
                    <div class="d-flex align-items-center mb-4">
                        <strong class="mr-3">Số lượng:</strong>

                        <div class="qty-box">
                            <button type="button" onclick="changeQty(-1)">-</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" readonly>
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <button class="btn btn-outline-info btn-block py-3 font-weight-bold">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <button name="buy_now" value="1" class="btn btn-info btn-block py-3 font-weight-bold">
                                Mua ngay ⚡
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
/* IMAGE */
.product-img-wrap {
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
}

.product-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* PRICE */
.price-box {
    background: #fff;
    border: 2px solid #17a2b8;
    border-radius: 10px;
    padding: 15px;
}

.price-box h3 {
    color: #e74c3c;
    margin: 0;
    font-weight: bold;
}

/* QUANTITY */
.qty-box {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.qty-box button {
    width: 35px;
    border: none;
    background: #f8f9fa;
    font-weight: bold;
}

.qty-box input {
    width: 50px;
    text-align: center;
    border: none;
}
</style>
@endpush


@push('scripts')
<script>
function changeQty(step) {
    let input = document.getElementById('qty');
    let price = parseInt(document.getElementById('base_price').value);
    let display = document.getElementById('display_price');

    let val = parseInt(input.value) + step;

    if (val >= 1) {
        input.value = val;

        let total = price * val;

        display.innerText = new Intl.NumberFormat('vi-VN').format(total) + ' ₫';
    }
}
</script>
@endpush