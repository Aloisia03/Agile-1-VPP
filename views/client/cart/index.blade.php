@extends('layouts.client')

@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="container py-4">

    <h3 class="font-weight-bold mb-4 text-info">
        <i class="fa-solid fa-cart-shopping mr-2"></i> Giỏ hàng của bạn
    </h3>

    @if(!empty($cart))

        <div class="row">

            <!-- CART TABLE -->
            <div class="col-lg-8 mb-4">

                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">

                    <div class="table-responsive">

                        <table class="table align-middle text-center mb-0">

                            <thead class="bg-info text-white">
                                <tr>
                                    <th class="py-3 text-left">Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>

                                @php $totalPrice = 0; @endphp

                                @foreach($cart as $id => $item)

                                    @php
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $totalPrice += $subtotal;
                                    @endphp

                                    <tr class="border-bottom">

                                        <!-- PRODUCT -->
                                        <td class="text-left d-flex align-items-center gap-2">

                                            <img
                                                src="{{ file_url($item['image'] ?? '') }}"
                                                onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'"
                                                style="width:60px;height:60px;object-fit:cover;border-radius:10px;"
                                            >

                                            <div class="ml-2">
                                                <div class="font-weight-bold">
                                                    {{ $item['name'] }}
                                                </div>
                                            </div>

                                        </td>

                                        <!-- PRICE -->
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($item['price'],0,',','.') }} ₫
                                        </td>

                                        <!-- QUANTITY -->
                                        <td>

                                            <form method="POST"
                                                  action="/Agile-1-VPP/client/cart/update"
                                                  class="d-flex justify-content-center align-items-center">

                                                <input type="hidden" name="id" value="{{ $id }}">

                                                <input type="number"
                                                       name="quantity"
                                                       value="{{ $item['quantity'] }}"
                                                       min="1"
                                                       class="form-control form-control-sm text-center"
                                                       style="width:70px;border-radius:8px;">

                                                <button type="submit"
                                                        class="btn btn-sm btn-primary ml-2 rounded-circle"
                                                        title="Cập nhật">
                                                    <i class="fa fa-sync"></i>
                                                </button>

                                            </form>

                                        </td>

                                        <!-- SUBTOTAL -->
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($subtotal,0,',','.') }} ₫
                                        </td>

                                        <!-- REMOVE -->
                                        <td>
                                            <a href="/Agile-1-VPP/client/cart/remove/{{ $id }}"
                                               class="btn btn-sm btn-outline-danger rounded-circle"
                                               onclick="return confirm('Xóa sản phẩm này?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <!-- SUMMARY -->
            <div class="col-lg-4">

                <div class="card shadow-sm border-0 rounded-lg p-4 sticky-top" style="top:20px;">

                    <h5 class="font-weight-bold mb-3 border-bottom pb-2">
                        Tổng đơn hàng
                    </h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="font-weight-bold">
                            {{ number_format($totalPrice,0,',','.') }} ₫
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí ship:</span>
                        <span class="text-success font-weight-bold">Miễn phí</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="font-weight-bold">Tổng cộng:</h5>
                        <h4 class="text-danger font-weight-bold">
                            {{ number_format($totalPrice,0,',','.') }} ₫
                        </h4>
                    </div>

                    <a href="/Agile-1-VPP/client/checkout"
                       class="btn btn-info btn-block py-3 font-weight-bold rounded-pill shadow-sm">
                        Tiến hành đặt hàng
                    </a>

                    <a href="/Agile-1-VPP/client/products"
                       class="btn btn-link btn-block text-muted mt-2">
                        ← Tiếp tục mua sắm
                    </a>

                </div>

            </div>

        </div>

    @else

        <!-- EMPTY CART -->
        <div class="text-center py-5 bg-white shadow-sm rounded-lg">

            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png"
                 style="width:140px;opacity:0.5;"
                 class="mb-4">

            <h4 class="text-muted mb-3">
                Giỏ hàng của bạn đang trống!
            </h4>

            <a href="/Agile-1-VPP/client/products"
               class="btn btn-info px-4 py-2 font-weight-bold rounded-pill">
                Mua sắm ngay
            </a>

        </div>

    @endif

</div>
@endsection