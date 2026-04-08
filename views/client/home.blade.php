@extends('layouts.client')

@section('title', $title)

@section('content')
<div class="container py-4">
    <div class="jumbotron jumbotron-fluid bg-info text-white rounded shadow-sm text-center">
        <div class="container">
            <h1 class="display-4 font-weight-bold">Mùa tựu trường rộn ràng!</h1>
            <p class="lead">Sắm dụng cụ học tập xịn - Đón điểm 10 lung linh.</p>
        </div>
    </div>

    <h3 class="mb-4 font-weight-bold border-bottom pb-2">Sản phẩm nổi bật</h3>
    
    <div class="row">
        @if(!empty($products))
            @foreach(array_slice($products, 0, 4) as $product) {{-- Chỉ hiện 4 cái --}}
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="/Agile-1-VPP/public/uploads/{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                        <p class="card-text text-danger font-weight-bold">{{ number_format($product['price']) }}đ</p>
                        <a href="/Agile-1-VPP/product/{{ $product['id'] }}" class="btn btn-outline-info btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection