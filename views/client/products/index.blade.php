@extends('layouts.client')

@section('title', 'Sản phẩm - Tre Trẻ VPP')

@section('content')
<div class="product-index-page bg-white py-5">
    <div class="container">
        {{-- TIÊU ĐỀ TRANG --}}
        <div class="text-center mb-5">
            <h1 class="text-uppercase font-weight-bold" style="letter-spacing: 4px; font-size: 2rem;">Bộ Sưu Tập</h1>
            <div class="mx-auto bg-dark mt-2" style="width: 50px; height: 2px;"></div>
        </div>

        <div class="row">
            {{-- BÊN TRÁI: BỘ LỌC (FILTER) --}}
            <div class="col-lg-3 pr-lg-5 mb-5">
                <div class="filter-sidebar">
                    <form action="/Agile-1-VPP/client/products" method="GET">
                        {{-- Tìm kiếm --}}
                        <div class="filter-group mb-4">
                            <h6 class="text-uppercase font-weight-bold small mb-3" style="letter-spacing: 1px;">Tìm kiếm</h6>
                            <div class="input-group border-bottom shadow-none">
                                <input type="text" name="keyword" class="form-control border-0 bg-transparent pl-0 shadow-none" 
                                       placeholder="Bạn tìm gì..." value="{{ $_GET['keyword'] ?? '' }}">
                                <div class="input-group-append">
                                    <button class="btn border-0 bg-transparent" type="submit"><i class="fa-solid fa-magnifying-glass small"></i></button>
                                </div>
                            </div>
                        </div>

                        {{-- Danh mục --}}
                        <div class="filter-group mb-4 mt-5">
                            <h6 class="text-uppercase font-weight-bold small mb-3" style="letter-spacing: 1px;">Danh mục</h6>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="/Agile-1-VPP/client/products" class="filter-link {{ empty($_GET['category_id']) ? 'active' : '' }}">Tất cả sản phẩm</a>
                                </li>
                                @foreach($categories as $cat)
                                    <li>
                                        <a href="/Agile-1-VPP/client/products?category_id={{ $cat['id'] }}" 
                                           class="filter-link {{ ($_GET['category_id'] ?? '') == $cat['id'] ? 'active' : '' }}">
                                            {{ $cat['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Sắp xếp --}}
                        <div class="filter-group mb-4 mt-5">
                            <h6 class="text-uppercase font-weight-bold small mb-3" style="letter-spacing: 1px;">Sắp xếp theo giá</h6>
                            <select name="sort" class="form-control border-0 border-bottom bg-transparent shadow-none pl-0 rounded-0" onchange="this.form.submit()">
                                <option value="">Mặc định</option>
                                <option value="asc" {{ ($_GET['sort'] ?? '') == 'asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                <option value="desc" {{ ($_GET['sort'] ?? '') == 'desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            {{-- BÊN PHẢI: DANH SÁCH SẢN PHẨM --}}
            <div class="col-lg-9">
                <div class="row no-gutters border-top border-left">
                    @forelse($products as $p)
                        <div class="col-6 col-md-4 border-right border-bottom p-0">
                            <div class="product-card-luxury">
                                <a href="/Agile-1-VPP/client/product/{{ $p['id'] }}" class="text-decoration-none">
                                    <div class="product-img-box">
                                        <img src="{{ file_url($p['image'] ?? '') }}" 
                                             onerror="this.src='https://via.placeholder.com/300x300'" 
                                             alt="{{ $p['name'] }}">
                                        {{-- Nút xem nhanh khi hover --}}
                                        <div class="quick-view-btn text-uppercase">Xem chi tiết</div>
                                    </div>
                                    <div class="product-info-box p-4 text-center">
                                        <h6 class="product-name text-uppercase">{{ $p['name'] }}</h6>
                                        <div class="product-price">{{ number_format($p['price'], 0, ',', '.') }} ₫</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center">
                            <p class="text-muted italic">Không tìm thấy sản phẩm nào phù hợp.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* TYPOGRAPHY & LAYOUT */
    .product-index-page { font-family: 'Montserrat', sans-serif; color: #333; }
    
    /* FILTER SIDEBAR */
    .filter-link { color: #888; text-decoration: none; font-size: 0.85rem; padding: 8px 0; display: block; transition: 0.3s; }
    .filter-link:hover, .filter-link.active { color: #000; font-weight: bold; padding-left: 5px; }

    /* PRODUCT CARD LUXURY */
    .product-card-luxury { transition: all 0.4s ease; background: #white; position: relative; overflow: hidden; }
    .product-img-box { height: 280px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
    .product-img-box img { max-height: 80%; transition: transform 0.6s ease; }
    
    .product-name { font-size: 0.75rem; font-weight: 500; letter-spacing: 1px; color: #222; margin-bottom: 10px; height: 35px; overflow: hidden; }
    .product-price { font-size: 0.9rem; font-weight: bold; color: #444; }

    /* HOVER EFFECTS */
    .product-card-luxury:hover { background: #fdfdfd; }
    .product-card-luxury:hover img { transform: scale(1.1); }
    
    .quick-view-btn {
        position: absolute; bottom: -50px; left: 0; width: 100%; background: rgba(0,0,0,0.8);
        color: #white; font-size: 0.7rem; padding: 12px 0; text-align: center;
        transition: bottom 0.3s ease; letter-spacing: 2px;
    }
    .product-card-luxury:hover .quick-view-btn { bottom: 0; }

    /* BORDER FIX */
    .no-gutters > [class*="col-"] { padding: 0; }
</style>
@endsection