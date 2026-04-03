@extends('home')

@section('content')
    @if (isset($_SESSION['flash']['error']))
        <div class="alert alert-danger">
            {{ $_SESSION['flash']['error'] }}
        </div>

        @php
            unset($_SESSION['flash']);
        @endphp
    @endif

    <div class="card">
        <h2 class="card-header">Thêm sản phẩm</h2>
        <div class="card-body">
            <form action="{{ route('/product/store') }}" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="label" for="">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm">
                </div>
                <div class="mb-3">
                    <label class="label" for="">Danh mục</label>
                    <select name="category_id" id="" class="form-select">
                        <option value="" disabled>---Chọn danh mục---</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="label" for="">Giá sản phẩm</label>
                    <input type="text" name="price" class="form-control" placeholder="Nhập giá sản phẩm">
                </div>

                <div class="mb-3">
                    <label class="label" for="">Mô tả</label>
                    <textarea name="description" class="form-control" placeholder="Nhập mô tả sản phẩm"></textarea>
                </div>
                <div class="mb-3">
                    <label class="label" for="">Hình ảnh</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Thêm</button>
                    <a href="{{ route('/products') }}" class="btn btn-sm btn btn-secondary">Quay lại</a>
                </div>


            </form>
        </div>
    </div>
@endsection
