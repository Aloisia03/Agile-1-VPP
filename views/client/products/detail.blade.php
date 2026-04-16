@extends('layouts.client')

@section('title', $product['name'])

@section('content')
<div class="product-detail-page bg-white">
    <div class="container py-4">
        {{-- BREADCRUMB - Nhỏ gọn, tinh tế --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0 mb-4 shadow-none" style="font-size: 0.8rem; letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/home" class="text-uppercase text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/Agile-1-VPP/client/products" class="text-uppercase text-muted">Sản phẩm</a></li>
                <li class="breadcrumb-item active text-uppercase font-weight-bold" aria-current="page">{{ $product['name'] }}</li>
            </ol>
        </nav>

        <div class="row no-gutters shadow-sm rounded border">
            {{-- BÊN TRÁI: HÌNH ẢNH (Chiếm 7 phần để tạo sự thoáng đãng) --}}
            <div class="col-lg-7 bg-white border-right d-flex align-items-center justify-content-center p-5">
                <div class="product-gallery">
                    <img src="{{ file_url($product['image'] ?? '') }}" 
                         onerror="this.src='https://via.placeholder.com/600x600'" 
                         alt="{{ $product['name'] }}"
                         class="img-fluid main-image">
                </div>
            </div>

            {{-- BÊN PHẢI: THÔNG TIN CHI TIẾT --}}
            <div class="col-lg-5 p-5 bg-white">
                <div class="product-info-header">
                    <h1 class="product-title">{{ $product['name'] }}</h1>
                    <div class="product-price-premium mt-3">
                        {{ number_format($product['price'], 0, ',', '.') }} ₫
                    </div>
                    <input type="hidden" id="base_price" value="{{ $product['price'] }}">
                </div>

                <div class="product-selection-zone mt-5">
                    {{-- CHỌN SỐ LƯỢNG --}}
                    <div class="d-flex align-items-center mb-4">
                        <span class="text-uppercase small font-weight-bold mr-3" style="letter-spacing: 1px;">Số lượng</span>
                        <div class="premium-qty-box">
                            <button type="button" onclick="changeQty(-1)">-</button>
                            <input type="number" id="qty" name="quantity" value="1" readonly>
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    {{-- NÚT THAO TÁC --}}
                    <form action="/Agile-1-VPP/client/cart/add" method="POST">
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="final_qty" id="final_qty" value="1">
                        
                        <button type="submit" class="btn btn-dark btn-block btn-buy-now mb-3">
                            MUA NGAY
                        </button>
                        <button type="submit" class="btn btn-outline-dark btn-block btn-add-cart">
                            THÊM VÀO GIỎ HÀNG
                        </button>
                    </form>
                </div>

                {{-- ACCORDION CHI TIẾT (Y hệt mẫu Pandora) --}}
                <div class="mt-5 border-top shadow-none" id="productAccordion">
                    <div class="accordion-item border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center cursor-pointer" data-toggle="collapse" data-target="#collapseDesc">
                            <span class="text-uppercase small font-weight-bold">Chi tiết sản phẩm</span>
                            <i class="fa-solid fa-plus small"></i>
                        </div>
                        <div id="collapseDesc" class="collapse show mt-3 text-muted small line-height-lg">
                            {{ $product['description'] ?? 'Sản phẩm văn phòng phẩm cao cấp, mang lại sự tinh tế và chuyên nghiệp cho không gian làm việc của bạn.' }}
                        </div>
                    </div>

                    <div class="accordion-item border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center cursor-pointer collapsed" data-toggle="collapse" data-target="#collapseShipping">
                            <span class="text-uppercase small font-weight-bold">Giao hàng & Đổi trả</span>
                            <i class="fa-solid fa-plus small"></i>
                        </div>
                        <div id="collapseShipping" class="collapse mt-3 text-muted small">
                            Giao hàng nhanh trong 24h tại Bắc Ninh. Đổi trả miễn phí trong vòng 7 ngày nếu có lỗi từ nhà sản xuất.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SẢN PHẨM CÙNG LOẠI --}}
        <div class="mt-5 pt-5 border-top">
            <h5 class="text-uppercase font-weight-bold mb-4" style="letter-spacing: 2px;">Sản phẩm cùng danh mục</h5>
            <div class="row">
                @forelse($relatedProducts as $related)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100 related-card">
                            <a href="/Agile-1-VPP/client/product/{{ $related['id'] }}" class="text-decoration-none text-dark">
                                <div class="related-img-wrap p-3">
                                    <img src="{{ file_url($related['image'] ?? '') }}" 
                                         onerror="this.src='https://via.placeholder.com/200x200'" 
                                         class="img-fluid" alt="{{ $related['name'] }}">
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="small font-weight-bold text-truncate mb-1">{{ $related['name'] }}</h6>
                                    <div class="text-danger small font-weight-bold">{{ number_format($related['price'], 0, ',', '.') }} ₫</div>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted small italic">Chưa có sản phẩm liên quan.</div>
                @endforelse
            </div>
        </div>

        {{-- GỢI Ý CHO BẠN (Sản phẩm bán chạy hoặc ngẫu nhiên) --}}
        <div class="mt-5 pt-5 border-top">
            <h5 class="text-uppercase font-weight-bold mb-4" style="letter-spacing: 2px;">Gợi ý dành riêng cho bạn</h5>
            <div class="row">
                @foreach($suggestedProducts as $suggest)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card border-0 shadow-sm h-100 related-card">
                            <a href="/Agile-1-VPP/client/product/{{ $suggest['id'] }}" class="text-decoration-none text-dark">
                                <div class="related-img-wrap p-3">
                                    <img src="{{ file_url($suggest['image'] ?? '') }}" 
                                         onerror="this.src='https://via.placeholder.com/200x200'" 
                                         class="img-fluid" alt="{{ $suggest['name'] }}">
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="small font-weight-bold text-truncate mb-1">{{ $suggest['name'] }}</h6>
                                    <div class="text-danger small font-weight-bold">{{ number_format($suggest['price'], 0, ',', '.') }} ₫</div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- KHU VỰC ĐÁNH GIÁ - Thiết kế lại tối giản --}}
        <div class="mt-5 pt-5 border-top">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="text-uppercase font-weight-bold" style="letter-spacing: 2px;">Đánh giá & Nhận xét</h2>
                        <div class="mt-2 text-warning">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                            <span class="text-dark ml-2">({{ count($reviews) }} đánh giá)</span>
                        </div>
                    </div>

                    {{-- LIST REVIEWS --}}
                    <div class="reviews-list">
                        @forelse($reviews as $rev)
                            <div class="review-entry py-4 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <h6 class="font-weight-bold text-uppercase mb-1" style="font-size: 0.8rem;">{{ $rev['user_name'] }}</h6>
                                    <div class="text-warning small">
                                        @for($i=1; $i<=5; $i++) <i class="{{ $i <= $rev['rating'] ? 'fa-solid' : 'fa-regular' }} fa-star"></i> @endfor
                                    </div>
                                </div>
                                <p class="text-muted small mt-2">{{ $rev['content'] }}</p>
                                <small class="text-muted italic">{{ date('d/m/Y', strtotime($rev['created_at'])) }}</small>
                            </div>
                        @empty
                            <div class="text-center py-5" style="border: 1px dashed #ccc; border-radius: 4px;">
                                <i class="fa-regular fa-comment-dots mb-3 d-block text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted small text-uppercase" style="letter-spacing: 1px;">Hãy là người đầu tiên đánh giá sản phẩm này</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- FORM GỬI --}}
                    @if(isset($_SESSION['user']))
                        <div class="mt-5 p-5 bg-light rounded">
                            <h5 class="text-center text-uppercase font-weight-bold mb-4">Để lại ý kiến của bạn</h5>
                            <form action="/Agile-1-VPP/client/product/review" method="POST">
                                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <select name="rating" class="form-control border-0 shadow-sm">
                                            <option value="5">5 Sao - Tuyệt vời</option>
                                            <option value="4">4 Sao - Tốt</option>
                                            <option value="3">3 Sao - Khá</option>
                                            <option value="2">2 Sao - Tệ</option>
                                            <option value="1">1 Sao - Rất tệ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <textarea name="content" class="form-control border-0 shadow-sm" rows="1" placeholder="Nội dung đánh giá..." required></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-dark px-5 py-2 mt-2 d-block mx-auto">GỬI</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* PHONG CÁCH PANDORA LUXURY */
    .product-detail-page { font-family: 'Montserrat', sans-serif; color: #222; }
    .cursor-pointer { cursor: pointer; }
    
    /* Title & Price */
    .product-title { font-size: 1.8rem; font-weight: 500; letter-spacing: 0.5px; color: #000; }
    .product-price-premium { font-size: 1.4rem; font-weight: 400; color: #444; }

    /* Buttons */
    .btn-buy-now { background: #000; border: 1px solid #000; border-radius: 0; padding: 15px; font-weight: bold; letter-spacing: 2px; }
    .btn-buy-now:hover { background: #333; }
    .btn-add-cart { border: 1px solid #000; border-radius: 0; padding: 15px; font-weight: bold; letter-spacing: 2px; }

    /* Quantity Box */
    .premium-qty-box { display: flex; border: 1px solid #ccc; width: fit-content; }
    .premium-qty-box button { background: none; border: none; padding: 5px 15px; font-size: 1.2rem; }
    .premium-qty-box input { width: 40px; border: none; text-align: center; font-weight: bold; background: none; }

    /* Gallery */
    .main-image { max-height: 550px; transition: transform 0.6s ease; }
    .main-image:hover { transform: scale(1.1); }

    /* Accordion */
    .accordion-item .fa-plus { transition: transform 0.3s; }
    .accordion-item .collapsed .fa-plus { transform: rotate(0deg); }
    .accordion-item:not(.collapsed) .fa-plus { transform: rotate(45deg); }

    /* Review */
    .review-entry { border-color: #eee !important; }

    .related-card { transition: all 0.3s ease; }
    .related-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .related-img-wrap { height: 180px; display: flex; align-items: center; justify-content: center; background: #fff; overflow: hidden; }
    .related-img-wrap img { max-height: 100%; transition: transform 0.5s; }
    .related-card:hover .related-img-wrap img { transform: scale(1.1); }
</style>

<script>
function changeQty(step) {
    let input = document.getElementById('qty');
    let finalInput = document.getElementById('final_qty');
    let val = parseInt(input.value) + step;
    if (val >= 1) {
        input.value = val;
        finalInput.value = val;
    }
}
</script>
@endsection