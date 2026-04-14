@extends('layouts.client')

@section('title', 'Tất cả sản phẩm')

@section('content')
<div class="container py-4">

    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0 pb-2 border-bottom">
            <li class="breadcrumb-item">
                <a href="/Agile-1-VPP/client/home" class="text-info">Trang chủ</a>
            </li>
            <li class="breadcrumb-item active">Sản phẩm</li>
        </ol>
    </nav>

    <!-- FILTER + SEARCH -->
    <form method="GET" class="mb-4">

        <div class="row align-items-end">

            <!-- SEARCH -->
            <div class="col-md-4 mb-2">
                <label class="font-weight-bold">Tìm sản phẩm</label>
                <input type="text"
                       name="keyword"
                       class="form-control"
                       placeholder="Nhập tên sản phẩm..."
                       value="<?= $_GET['keyword'] ?? '' ?>">
            </div>

            <!-- CATEGORY -->
            <div class="col-md-3 mb-2">
                <label class="font-weight-bold">Danh mục</label>
                <select name="category_id" class="form-control">

                    <option value="">Tất cả danh mục</option>

                    @foreach($categories as $cate)
                        <option value="{{ $cate['id'] }}"
                            {{ (isset($_GET['category_id']) && $_GET['category_id'] == $cate['id']) ? 'selected' : '' }}>
                            {{ $cate['name'] }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- SORT -->
            <div class="col-md-3 mb-2">
                <label class="font-weight-bold">Sắp xếp giá</label>
                <select name="sort" class="form-control">

                    <option value="">Mặc định</option>

                    <option value="asc"
                        {{ (isset($_GET['sort']) && $_GET['sort']=='asc') ? 'selected' : '' }}>
                        Tăng dần
                    </option>

                    <option value="desc"
                        {{ (isset($_GET['sort']) && $_GET['sort']=='desc') ? 'selected' : '' }}>
                        Giảm dần
                    </option>

                </select>
            </div>

            <!-- BUTTON -->
            <div class="col-md-2 mb-2">
                <button class="btn btn-info btn-block font-weight-bold">
                    Lọc
                </button>
            </div>

        </div>

    </form>

    <!-- ACTIVE FILTER DISPLAY -->
    @if(!empty($_GET['keyword']) || !empty($_GET['category_id']) || !empty($_GET['sort']))
        <div class="mb-3">
            <span class="badge badge-info p-2">
                Đang áp dụng bộ lọc
            </span>
        </div>
    @endif

    <!-- PRODUCT LIST -->
    <div class="row">

        @forelse($products as $product)

        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

            <div class="card product-card border-0 shadow-sm h-100">

                <!-- IMAGE -->
                <div class="product-img">
                    <img
                        src="{{ file_url($product['image'] ?? '') }}"
                        onerror="this.src='https://via.placeholder.com/300x250?text=No+Image'"
                        alt="{{ $product['name'] }}">
                </div>

                <!-- BODY -->
                <div class="card-body text-center d-flex flex-column">

                    <h6 class="product-name">
                        {{ $product['name'] }}
                    </h6>

                    <div class="product-price">
                        {{ number_format($product['price'], 0, ',', '.') }} ₫
                    </div>

                    <div class="mt-auto">
                        <a href="/Agile-1-VPP/client/product/{{ $product['id'] }}"
                           class="btn btn-outline-info btn-sm w-100 rounded-pill">
                            <i class="fa-solid fa-eye"></i> Xem chi tiết
                        </a>
                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="col-12 text-center py-5 bg-white shadow-sm rounded">
            <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted lead">Không tìm thấy sản phẩm phù hợp</p>
        </div>

        @endforelse

    </div>

</div>
@endsection

@push('styles')
<style>

.product-card {
    border-radius: 14px;
    transition: 0.3s;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.product-img {
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: 0.3s;
}

.product-card:hover img {
    transform: scale(1.05);
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
    height: 40px;
    overflow: hidden;
}

.product-price {
    color: #e74c3c;
    font-weight: bold;
    font-size: 1.1rem;
    margin-bottom: 10px;
}

.btn-outline-info:hover {
    background: #17a2b8;
    color: #fff;
}

</style>
@endpush