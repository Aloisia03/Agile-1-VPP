@extends('home')

@section('content')
    <div class="card"
        style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 20px; max-width: 600px; margin: 0 auto;">
        <h2 style="margin-bottom: 20px;">Thêm sản phẩm mới</h2>
        <form action="/Agile-1-VPP/product/store" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Tên sản phẩm *</label>
                <input type="text" name="name" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Danh mục *</label>
                <select name="category_id" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Giá bán *</label>
                <input type="number" name="price" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Mô tả</label>
                <textarea name="description" rows="4"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Ảnh sản phẩm</label>
                <input type="file" name="image" accept="image/*"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="text-align: right;">
                <a href="/Agile-1-VPP/products" style="padding: 10px 15px; color: #4b5563; text-decoration: none;">Hủy</a>
                <button type="submit"
                    style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer;">Lưu
                    sản phẩm</button>
            </div>
        </form>
    </div>
@endsection