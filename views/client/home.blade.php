@extends('layouts.client')

@section('title', 'Trang chủ - Mùa tựu trường')

@section('content')
<div class="container">
    <div class="jumbotron text-white rounded-lg shadow-sm text-center mb-5" style="background: linear-gradient(135deg, #17a2b8 0%, #0056b3 100%); padding: 4rem 2rem;">
        <h1 class="display-4 font-weight-bold mb-3">Mùa tựu trường rộn ràng!</h1>
        <p class="lead">Chào mừng 
            @if(isset($_SESSION['user']))
                <strong>{{ $_SESSION['user']['name'] }}</strong> 
            @else
                <strong>bạn</strong> 
            @endif
            đến với Tre Trẻ VPP.
        </p>
        <p class="mb-4">Sắm dụng cụ học tập xịn - Đón điểm 10 lung linh.</p>
        <a href="/Agile-1-VPP/client/products" class="btn btn-light btn-lg font-weight-bold text-info px-5 rounded-pill shadow">
            Mua sắm ngay <i class="fa-solid fa-arrow-right ml-2"></i>
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h3 class="font-weight-bold m-0 text-dark">Sản phẩm nổi bật</h3>
        <a href="/Agile-1-VPP/client/products" class="text-info font-weight-bold text-decoration-none hover-primary">
            Xem tất cả <i class="fa-solid fa-angles-right"></i>
        </a>
    </div>
    
    <div class="row">
        @forelse(array_slice($products, 0, 4) as $product) 
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 product-card rounded-lg">
                    <div class="position-relative overflow-hidden rounded-top" style="height: 220px;">
                        <img src="/Agile-1-VPP/public/uploads/{{ $product['image'] }}" 
                             class="card-img-top w-100 h-100" 
                             alt="{{ $product['name'] }}" 
                             style="object-fit: cover; transition: transform 0.3s ease;">
                    </div>

                    <div class="card-body text-center d-flex flex-column">
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
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted lead">Hiện tại chưa có sản phẩm nào.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-card { transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important; }
    .product-card:hover img { transform: scale(1.08); }
    .hover-primary:hover { color: #0056b3 !important; }
</style>
@endpush