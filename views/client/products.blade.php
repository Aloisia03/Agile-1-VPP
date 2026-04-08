@extends('layouts.client')

@section('title', $title)

@section('content')
<div class="container py-4">
    <h3 class="mb-4 font-weight-bold text-center">Tất cả sản phẩm</h3>
    
    <div class="row">
        @if(!empty($products))
            @foreach($products as $product)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="/Agile-1-VPP/public/uploads/{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                        <p class="text-muted small">{{ $product['category_name'] ?? 'Văn phòng phẩm' }}</p>
                        <h5 class="text-danger font-weight-bold mt-auto mb-3">{{ number_format($product['price']) }}đ</h5>
                        
                        <form action="/Agile-1-VPP/checkout" method="POST" class="w-100 mt-auto">
                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-cart-plus"></i> Mua ngay</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12 text-center text-muted py-5">Chưa có sản phẩm nào.</div>
        @endif
    </div>
</div>
@endsection