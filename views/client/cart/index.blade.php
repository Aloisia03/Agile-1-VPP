@extends('layouts.client')

@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="container py-4">
    <h3 class="font-weight-bold mb-4 text-info"><i class="fa-solid fa-cart-shopping mr-2"></i> Giỏ hàng của bạn</h3>

    @if(!empty($cart))
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="py-3">Sản phẩm</th>
                                    <th class="py-3">Đơn giá</th>
                                    <th class="py-3">Số lượng</th>
                                    <th class="py-3">Thành tiền</th>
                                    <th class="py-3">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalPrice = 0; @endphp
                                @foreach($cart as $id => $item)
                                    @php 
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $totalPrice += $subtotal;
                                    @endphp
                                    <tr>
                                        <td class="text-left d-flex align-items-center">
                                            <img src="/Agile-1-VPP/public/uploads/{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <span class="font-weight-bold">{{ $item['name'] }}</span>
                                        </td>
                                        <td class="align-middle text-danger">{{ number_format($item['price'], 0, ',', '.') }} ₫</td>
                                        <td class="align-middle">
                                            <span class="badge badge-info px-3 py-2" style="font-size: 1rem;">{{ $item['quantity'] }}</span>
                                        </td>
                                        <td class="align-middle font-weight-bold text-danger">{{ number_format($subtotal, 0, ',', '.') }} ₫</td>
                                        <td class="align-middle">
                                            <a href="/Agile-1-VPP/client/cart/remove/{{ $id }}" class="btn btn-outline-danger btn-sm rounded-circle" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-lg p-4">
                    <h5 class="font-weight-bold mb-3 border-bottom pb-2">Tổng quan đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tổng tạm tính:</span>
                        <span class="font-weight-bold">{{ number_format($totalPrice, 0, ',', '.') }} ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Phí giao hàng:</span>
                        <span class="text-success font-weight-bold">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 border-top pt-3">
                        <h5 class="font-weight-bold">Tổng cộng:</h5>
                        <h4 class="font-weight-bold text-danger m-0">{{ number_format($totalPrice, 0, ',', '.') }} ₫</h4>
                    </div>
                    
                    <a href="/Agile-1-VPP/client/checkout" class="btn btn-info btn-block py-3 font-weight-bold text-uppercase rounded-pill shadow-sm">
                        Tiến hành đặt hàng <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                    <a href="/Agile-1-VPP/client/products" class="btn btn-link btn-block text-muted text-decoration-none mt-2">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white shadow-sm rounded-lg">
            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" style="width: 150px; opacity: 0.5;" class="mb-4">
            <h4 class="text-muted mb-3">Giỏ hàng của bạn đang trống!</h4>
            <a href="/Agile-1-VPP/client/products" class="btn btn-info px-4 py-2 font-weight-bold rounded-pill">Về trang sản phẩm ngay</a>
        </div>
    @endif
</div>
@endsection