@extends('home') {{-- Hoặc tên file layout của bạn --}}

@section('content')
    <form action="/products" method="GET"
        style="padding: 11px 25px; background: #e7e5e5; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;">
        <select name="category_id" onchange="">
            <option value="">-- Tất cả danh mục --</option>
            @foreach ($categories as $category)
                <option value="{{ $category['id'] }}"
                    {{ isset($_GET['category_id']) && $_GET['category_id'] == $category['id'] ? 'selected' : '' }}>
                    {{ $category['name'] }}
                </option>
            @endforeach
        </select>

        {{-- <div class="category-search mb-4" style="margin-bottom: 20px;">
            <strong>Lọc theo danh mục: </strong>
            <a href="{{ route('/products') }}" class="btn btn-outline-secondary btn-sm">Tất cả sản phẩm</a>

            @foreach ($categories as $cat)
                <a href="{{ route('/category/{id}/products', ['id' => $cat['id']]) }}"
                    class="btn btn-outline-primary btn-sm">
                    {{ $cat['name'] }}
                </a>
            @endforeach
        </div> --}}

        {{-- <button type="submit" class="btn btn-primary"
            style="padding: 11px 25px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;"> --}}
        <a style="padding: 11px 25px; background: #1b1946; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;"
            href="{{ route('/category/{id}/products', ['id' => $category['id']]) }}" style="margin-right: 5px;"> Tìm kiếm</a>
        {{-- </button> --}}

        <a href="{{ route('/products') }}" class="btn"
            style="padding: 11px 15px; background: #f3f4f6; color: #4b5563; border-radius: 6px; text-decoration: none; font-size: 14px; text-align: center;">Reset</a>
    </form>

    <div style="margin-bottom: 20px; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
        <label for="sort" style="font-weight: bold;">Sắp xếp theo:</label>
        <form method="GET" action="" id="sortForm">
            <select name="sort" onchange="this.form.submit()">
                <option value="">Mặc định</option>
                <option value="price_asc" {{ $currentSort == 'price_asc' ? 'selected' : '' }}>
                    Giá: Thấp đến Cao
                </option>
                <option value="price_desc" {{ $currentSort == 'price_desc' ? 'selected' : '' }}>
                    Giá: Cao đến Thấp
                </option>
            </select>
        </form>
    </div>

    <div class="page-action"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 1.5rem; color: #1f2937;">Danh sách sản phẩm</h2>
        <a href="{{ route('/product/create') }}" class="btn btn-success"
            style="background: #10b981; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600;"><i
                class="fa-solid fa-plus"></i> Thêm sản phẩm
            mới</a>
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
                    <form action="" enctype="multipart/form-data">
                        {{-- Dữ liệu mẫu - Sau này bạn sẽ dùng @foreach ($products as $product) --}}
                        <tr>
                            <td style="padding: 15px; text-align: center;">
                                <img src="/uploads/products/{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                    width="100">
                            </td>
                            <td style="padding: 15px;">
                                <strong style="color: #2c3e50;">{{ $product['name'] }}</strong><br>
                                {{-- <small style="color: #7f8c8d;">SKU: TL027-XANH</small> --}}
                            </td>
                            <td style="padding: 15px;">{{ $product['category_name'] }}</td>
                            <td style="padding: 15px;"><strong>{{ $product['price'] }}</strong></td>
                            <td style="padding: 15px;">
                                <span
                                    style="background: #e1f7ec; color: #27ae60; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                    {{ $product['description'] }}</span>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="{{ route('/product/show/' . $product['id']) }}"
                                    style="color: #3498db; margin-right: 10px;" title="chi tiết">Chi tiết</a>
                                <a href="#" style="color: #e74c3c;" title="Xóa"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </form>
                @endforeach
                {{-- Kết thúc mẫu --}}
            </tbody>
        </table>
    </div>

    <style>
        /* Hiệu ứng hover cho dòng trong bảng */
        tbody tr:hover {
            background-color: #fcfcfc;
        }

        tbody tr td {
            border-bottom: 1px solid #eee;
        }
    </style>
@endsection
