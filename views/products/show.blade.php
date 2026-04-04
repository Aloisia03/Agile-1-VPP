@extends('home') {{-- Hoặc tên file layout của bạn --}}

@section('content')
    <div class="page-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Chi tiết sản phẩm</h2>
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
                </tr>
            </thead>
            <tbody>
                {{-- Dữ liệu mẫu - Sau này bạn sẽ dùng @foreach ($products as $product) --}}
                <tr>
                    <td style="padding: 15px; text-align: center;">
                        @if(isset($product['images']) && is_array($product['images']) && count($product['images']) > 0)
                            <div class="image-gallery" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @foreach($product['images'] as $image)
                                    <img src="{{ file_url($image) }}" style="border-radius: 5px; object-fit: cover; width: 80px; height: 80px;" alt="">
                                @endforeach
                            </div>
                        @else
                            <img src="{{ file_url($product['image']) }}" style="border-radius: 5px; object-fit: cover;" alt="">
                        @endif
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
                </tr>
                {{-- Kết thúc mẫu --}}
            </tbody>
        </table>
    </div>

    <!-- Upload Images Section -->
    <div class="upload-section" style="margin-top: 30px;">
        <h3>Upload thêm ảnh sản phẩm</h3>
        <form method="POST" action="/Agile-1-VPP/product/upload-images/{{ $product['id'] }}" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <div style="margin-bottom: 15px;">
                <label for="images" style="display: block; margin-bottom: 5px; font-weight: bold;">Chọn ảnh (có thể chọn nhiều):</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <button type="submit" style="background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                <i class="fa fa-upload"></i> Upload ảnh
            </button>
        </form>
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