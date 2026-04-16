@extends('home')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark">Quản lý Kho hàng</h4>
        <span class="badge badge-info p-2">Tổng số mặt hàng: {{ count($products) }}</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 pl-4">Sản phẩm</th>
                        <th class="border-0">Danh mục</th>
                        <th class="border-0 text-center">Giá bán</th>
                        <th class="border-0 text-center">Tồn kho</th>
                        <th class="border-0 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td class="pl-4 font-weight-bold">{{ $p['name'] }}</td>
                        <td>{{ $p['category_name'] }}</td>
                        <td class="text-center text-primary font-weight-bold">{{ number_format($p['price'], 0, ',', '.') }} ₫</td>
                        
                        <td class="text-center align-middle" style="width: 200px;">
                            <form action="/Agile-1-VPP/inventory/update" method="POST" class="d-flex justify-content-center m-0">
                                <input type="hidden" name="product_id" value="{{ $p['id'] }}">
                                
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" name="stock" value="{{ $p['stock'] }}" class="form-control text-center font-weight-bold" min="0" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success shadow-sm" data-toggle="tooltip" title="Lưu số lượng">
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>

                        <td class="text-center">
                            @if($p['stock'] <= 0)
                                <span class="badge badge-danger px-3 py-2">Hết hàng</span>
                            @elseif($p['stock'] < 10)
                                <span class="badge badge-warning px-3 py-2 text-dark">Sắp hết</span>
                            @else
                                <span class="badge badge-success px-3 py-2">Còn hàng</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection