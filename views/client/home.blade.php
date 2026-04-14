@extends('layouts.client')

@section('title', 'Trang chủ - Mùa tựu trường')

@section('content')
<div class="container py-4">

    {{-- HERO --}}
    <div class="hero-banner text-white text-center mb-5">
        <h1 class="display-5 font-weight-bold mb-3">🎒 Mùa tựu trường rộn ràng!</h1>

        <p class="lead">
            Chào mừng 
            @if(isset($_SESSION['user']))
            
                <strong>{{ $_SESSION['user']['name'] }}</strong>
            @else
                <strong>bạn</strong>
            @endif
            đến với Tre Trẻ VPP
        </p>

        <p class="mb-4">Sắm đồ xịn – Học hết mình – Điểm 10 cực đỉnh ✨</p>

        <a href="/Agile-1-VPP/client/products" class="btn btn-light btn-lg px-5 rounded-pill font-weight-bold text-info shadow">
            Mua sắm ngay <i class="fa-solid fa-arrow-right ml-2"></i>
        </a>
    </div>

    {{-- TITLE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold m-0">🔥 Sản phẩm nổi bật</h4>
        <a href="/Agile-1-VPP/client/products" class="text-info font-weight-bold">
            Xem tất cả →
        </a>
    </div>

    {{-- PRODUCT LIST --}}
    <div class="row">
        @forelse(array_slice($products, 0, 4) as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                <div class="card product-card h-100 border-0 shadow-sm">

                    {{-- IMAGE --}}
                    <div class="product-img">
                        <img src="{{ file_url($product['image'] ?? '') }}"
                             onerror="this.src='https://via.placeholder.com/300x200'"
                             alt="">
                    </div>

                    {{-- BODY --}}
                    <div class="card-body text-center d-flex flex-column">
                        <h6 class="font-weight-bold text-truncate mb-2">
                            {{ $product['name'] }}
                        </h6>

                        <div class="text-danger font-weight-bold mb-3" style="font-size: 1.1rem;">
                            {{ number_format($product['price'], 0, ',', '.') }} ₫
                        </div>

                        <a href="/Agile-1-VPP/client/product/{{ $product['id'] }}"
                           class="btn btn-outline-info btn-sm mt-auto rounded-pill">
                           <i class="fa-solid fa-eye"></i> Xem chi tiết
                        </a>
                    </div>

                </div>

            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có sản phẩm</p>
            </div>
        @endforelse
    </div>

</div>
@endsection


@push('styles')
<style>
/* HERO */
.hero-banner {
    background: linear-gradient(135deg, #17a2b8, #0056b3);
    padding: 60px 20px;
    border-radius: 15px;
}

/* CARD */
.product-card {
    border-radius: 12px;
    transition: 0.3s;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

/* IMAGE FIX */
.product-img {
    height: 200px;
    overflow: hidden;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.4s;
}

.product-card:hover img {
    transform: scale(1.1);
}
</style>
@endpush