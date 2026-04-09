@extends('home')

@section('content')
    <div class="page-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Danh sách sản phẩm</h2>
        <a href="#" class="btn btn-success"><i class="fa-solid fa-plus"></i> Thêm sản phẩm mới</a>
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
                            <img src="{{ file_url($product['image']) }}"
                                style="border-radius: 5px; object-fit: cover;" alt="">
                        </td>
                        <td style="padding: 15px;">
                            <strong style="color: #2c3e50;">{{ $product['name'] }}</strong><br>
                        </td>
                        <td style="padding: 15px;">{{ $product['category_name'] }}</td>
                        <td style="padding: 15px;"><strong>{{ $product['price'] }}</strong></td>
                        <td style="padding: 15px;">
                            <span
                                style="background: #e1f7ec; color: #27ae60; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                {{ $product['description'] }}
                            </span>
                        </td>

                        <td style="padding: 15px; text-align: center;">

                            <!-- Chi tiết -->
                            <a href="{{ route('/product/show/' . $product['id']) }}"
                                style="color: #3498db; margin-right: 10px;" title="chi tiết">
                                Chi tiết
                            </a>

                            <!-- 🛒 THÊM GIỎ HÀNG -->
                            <form method="POST" action="/Agile-1-VPP/cart/add" style="display:inline;">
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    style="background:#27ae60; color:white; border:none; padding:5px 8px; border-radius:5px; cursor:pointer; margin-right:8px;"
                                    title="Thêm giỏ hàng">
                                    <i class="fa fa-cart-plus"></i>
                                </button>
                            </form>

                            <!-- Xóa -->
                            <a href="#" style="color: #e74c3c;" title="Xóa">
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