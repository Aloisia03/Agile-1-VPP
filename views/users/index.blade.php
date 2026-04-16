@extends('home')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fc; min-height: 100vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark">Quản lý Khách hàng</h4>
        <div class="text-muted font-weight-bold">Tổng số: {{ count($users) }} tài khoản</div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 pl-4">Khách hàng</th>
                        <th class="border-0">Liên hệ</th>
                        <th class="border-0 text-center">Đơn hàng</th>
                        <th class="border-0 text-right">Đã chi tiêu</th>
                        <th class="border-0 text-center">Trạng thái</th>
                        <th class="border-0 text-center pr-4">Thao tác</th>
                        <th class="border-0 text-center pr-4">Quyền điều hành</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="{{ $user['status'] == 'banned' ? 'opacity: 0.6; background: #fdfdfe;' : '' }}">
                        <td class="pl-4 align-middle">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($user['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $user['name'] }}</div>
                                    <small class="text-muted">ID: #{{ $user['id'] }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="small text-dark">{{ $user['email'] }}</div>
                            <div class="small text-muted">{{ $user['phone'] ?? 'Chưa có SĐT' }}</div>
                        </td>
                        <td class="text-center align-middle font-weight-bold text-primary">
                            {{ $user['total_orders'] }}
                        </td>
                        <td class="text-right align-middle font-weight-bold text-success">
                            {{ number_format($user['total_spent'], 0, ',', '.') }} ₫
                        </td>
                        <td class="text-center align-middle">
                            @if($user['status'] == 'active')
                                <span class="badge badge-pill badge-success px-3 py-1">Hoạt động</span>
                            @else
                                <span class="badge badge-pill badge-danger px-3 py-1">Bị khóa</span>
                            @endif
                        </td>
                        <td class="text-center align-middle pr-4">
                            <form action="/Agile-1-VPP/users/toggle-status/{{ $user['id'] }}" method="POST" class="m-0">
                                @if($user['status'] == 'active')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Khóa tài khoản này?')">
                                        <i class="fa-solid fa-user-slash mr-1"></i> Khóa
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Mở lại tài khoản?')">
                                        <i class="fa-solid fa-user-check mr-1"></i> Mở khóa
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td class="align-middle text-center">
                            <form action="/Agile-1-VPP/users/update-role/{{ $user['id'] }}" method="POST" class="m-0">
                                <select name="role" class="form-control form-control-sm font-weight-bold" 
                                        onchange="this.form.submit()"
                                        style="border-radius: 20px; {{ $user['role'] == 'admin' ? 'color: #e74a3b; border-color: #e74a3b;' : 'color: #4e73df; border-color: #4e73df;' }}">
                                    <option value="customer" {{ $user['role'] == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                                    <option value="staff" {{ $user['role'] == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                    <option value="admin" {{ $user['role'] == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
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