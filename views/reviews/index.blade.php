@extends('home')
@section('content')
<div class="container-fluid py-4">
    <h4 class="font-weight-bold text-dark mb-4">Quản lý Đánh giá & Phản hồi</h4>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive text-dark">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 pl-4">Khách hàng</th>
                        <th class="border-0">Sản phẩm</th>
                        <th class="border-0 text-center">Đánh giá</th>
                        <th class="border-0" style="width: 30%;">Nội dung</th>
                        <th class="border-0 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $r)
                    <tr>
                        <td class="pl-4 font-weight-bold">{{ $r['user_name'] }}</td>
                        <td>{{ $r['product_name'] }}</td>
                        <td class="text-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-star {{ $i <= $r['rating'] ? 'fa-solid text-warning' : 'fa-regular text-muted' }}"></i>
                            @endfor
                        </td>
                        <td><small>{{ $r['content'] }}</small></td>
                        <td class="text-center">
                            <form action="/Agile-1-VPP/reviews/update/{{ $r['id'] }}" method="POST">
                                <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                                    <option value="pending" {{ $r['status'] == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="approved" {{ $r['status'] == 'approved' ? 'selected' : '' }}>Hiển thị</option>
                                    <option value="hidden" {{ $r['status'] == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection