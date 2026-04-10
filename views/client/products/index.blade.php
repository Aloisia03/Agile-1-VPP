@extends('layouts.client')

@section('title', 'Tất cả sản phẩm')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent px-0 pb-2 border-bottom">
            <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/home" class="text-info">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <h3 class="font-weight-bold m-0 text-dark"><i class="fa-solid fa-boxes-stacked mr-2 text-info"></i>Tất cả sản phẩm</h3>
        
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                Sắp xếp theo
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">Mới nhất</a>
                <a class="dropdown-item" href="#">Giá tăng dần</a>
                <a class="dropdown-item" href="#">Giá giảm dần</a>
            </div>
        </div>
    </div>
    
    <div class="row">
        @forelse($products as $product) 
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 product-card rounded-lg">
                    <div class="position-relative overflow-hidden rounded-top bg-white d-flex align-items-center justify-content-center" style="height: 220px;">
                        <img src="/Agile-1-VPP/public/uploads/{{ $product['image'] }}" 
                             class="card-img-top p-2" 
                             alt="{{ $product['name'] }}" 
                             style="max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.3s ease;">
                    </div>

                    <div class="card-body text-center d-flex flex-column bg-light">
                        <h6 class="card-title text-dark font-weight-bold text-truncate mb-2" title="{{ $product['name'] }}">
                            {{ $product['name'] }}
                        </h6>
                        <p class="card-text text-danger font-weight-bold mb-3" style="font-size: 1.15rem;">
                            {{ number_format($product['price'], 0, ',', '.') }} ₫
                        </p>
                        
                        <a href="/Agile-1-VPP/client/product/{{ $product['id'] }}" 
                           class="btn btn-outline-info btn-sm mt-auto shadow-sm rounded-pill font-weight-bold">
                           <i class="fa-solid fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted lead">Hiện tại hệ thống chưa có sản phẩm nào.</p>
                <a href="/Agile-1-VPP/client/home" class="btn btn-info rounded-pill mt-2">Về trang chủ</a>
            </div>
        @endforelse
    </div>

    @if(count($products) > 0)
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">Sau</a></li>
        </ul>
    </nav>
    @endif

</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important;
    }
    .product-card:hover img {
        transform: scale(1.08);
    }
    /* Đổi màu active của phân trang cho hợp với theme */
    .page-item.active .page-link {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .page-link {
        color: #17a2b8;
    }
</style>
@endpush
