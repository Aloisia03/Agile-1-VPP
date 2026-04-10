@extends('home')

@section('content')
<div class="page-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;">Danh sách sản phẩm</h2>
    <a href="#" class="btn btn-success">
        <i class="fa-solid fa-plus"></i> Thêm sản phẩm mới
    </a>
</div>

<div class="card"
    style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
    
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 15px; text-align: center; width: 80px;">Ảnh</th>
                <th style="padding: 15px;">Tên sản phẩm</th>
                <th style="padding: 15px;">Danh mục</th>
                <th style="padding: 15px;">Giá bán</th>
                <th style="padding: 15px;">Mô tả</th>
                <th style="padding: 15px; text-align: center;">Thao tác</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
            <tr>
                <td style="padding: 15px; text-align: center;">
                    <img src="{{ file_url($product['image']) }}" width="60" height="60"
                        style="border-radius: 5px; object-fit: cover;">
                </td>

                <td style="padding: 15px;">
                    <strong style="color: #2c3e50;">{{ $product['name'] }}</strong>
                </td>

                <td style="padding: 15px;">
                    {{ $product['category_name'] }}
                </td>

                <td style="padding: 15px;">
                    <strong>{{ number_format($product['price']) }}đ</strong>
                </td>

                <td style="padding: 15px;">
                    <span style="font-size: 13px; color: #555;">
                        {{ $product['description'] }}
                    </span>
                </td>

                <td style="padding: 15px; text-align: center;">

                    <!-- 👁️ XEM -->
                    <a href="/product/show/{{ $product['id'] }}"
                        style="color: #3498db; margin-right: 10px;">
                        Chi tiết
                    </a>

                    <!-- 🛒 THÊM GIỎ HÀNG -->
                    <form method="POST" action="/cart/add" style="display:inline;">
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="quantity" value="1">

                        <button type="submit"
                            style="background:#27ae60; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">
                            <i class="fa fa-cart-plus"></i>
                        </button>
                    </form>

                    <!-- 🗑️ XÓA -->
                    <a href="#" style="color: #e74c3c; margin-left:10px;">
                        <i class="fa-solid fa-trash"></i>
                    </a>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
tbody tr:hover {
    background-color: #fcfcfc;
}

tbody tr td {
    border-bottom: 1px solid #eee;
}
</style>
@endsection