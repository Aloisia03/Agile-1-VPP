@extends('home') {{-- Hoặc tên file layout của bạn --}}

@section('content')
    <div class="page-action" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Danh sách danh mục</h2>
        <a href="#" class="btn btn-success"><i class="fa-solid fa-plus"></i> Thêm danh mục mới</a>
    </div>

    <div class="card"
        style="background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 15px;">Tên danh mục</th>
                    <th style="padding: 15px;">Mô tả</th>
                    <th style="padding: 15px; text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                    {{-- Dữ liệu mẫu - Sau này bạn sẽ dùng @foreach ($products as $product) --}}
                    <tr>

                        <td style="padding: 15px;">
                            <strong style="color: #2c3e50;">{{ $category['name'] }}</strong><br>
                            {{-- <small style="color: #7f8c8d;">SKU: TL027-XANH</small> --}}
                        </td>

                        <td style="padding: 15px;">
                            <span
                                style="background: #e1f7ec; color: #27ae60; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                {{ $category['description'] }}</span>
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="" style="color: #3498db; margin-right: 10px;" title="chi tiết">Chi tiết</a>
                            <a href="#" style="color: #e74c3c;" title="Xóa"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
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