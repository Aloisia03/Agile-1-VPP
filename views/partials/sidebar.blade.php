<aside class="sidebar">
    <div class="sidebar-logo">
        <i class="fa-solid fa-pen-nib"></i>
        <span>Góc học tập VPP</span>
    </div>

    <nav class="sidebar-nav">
        
        <small class="nav-label">TỔNG QUAN</small>
        <a href="/Agile-1-VPP/reports" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Báo cáo & Thống kê</span>
        </a>

        <small class="nav-label">QUẢN LÝ HÀNG HÓA</small>
        <a href="/Agile-1-VPP/products" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'products') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i>
            <span>Sản phẩm</span>
        </a>
        <a href="/Agile-1-VPP/category" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'category') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Danh mục</span>
        </a>
        <a href="/Agile-1-VPP/inventory" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'inventory') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-warehouse"></i>
            <span>Kho hàng kho</span>
        </a>

        <small class="nav-label">GIAO DỊCH</small>
        <a href="/Agile-1-VPP/orders" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'orders') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Đơn hàng</span>
            {{-- Ví dụ cách hiện số đơn chờ duyệt: <span class="badge-count">3</span> --}}
        </a>
        <a href="/Agile-1-VPP/reviews" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'reviews') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-star-half-stroke"></i>
            <span>Đánh giá & Phản hồi</span>
        </a>

        <small class="nav-label">HỆ THỐNG</small>
         <a href="/Agile-1-VPP/users" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            <span>Quản lý Tài khoản</span>
        </a>
        <a href="/Agile-1-VPP/settings" class="nav-item {{ strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Cài đặt hệ thống</span>
        </a>
    </nav>
</aside>

<style>
    .sidebar {
        background: #2c3e50;
        color: #ecf0f1;
        display: flex;
        flex-direction: column;
        height: 100vh; /* Đảm bảo menu full chiều cao */
    }

    .sidebar-logo {
        padding: 25px;
        font-size: 1.4rem;
        font-weight: bold;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #1a252f;
    }

    .sidebar-logo i {
        color: #3498db;
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto; /* Cho phép cuộn nếu màn hình nhỏ */
        padding-bottom: 20px;
    }

    /* Ẩn thanh cuộn mặc định cho đẹp */
    .sidebar-nav::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #34495e; 
        border-radius: 5px;
    }

    .nav-label {
        display: block;
        padding: 25px 25px 10px;
        font-size: 0.75rem;
        color: #7f8c8d;
        letter-spacing: 1px;
        font-weight: 700;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 25px;
        color: #bdc3c7;
        text-decoration: none;
        transition: all 0.3s;
        border-left: 4px solid transparent;
        position: relative;
    }

    .nav-item i {
        width: 28px;
        font-size: 1.1rem;
    }

    .nav-item span {
        font-size: 0.95rem;
        font-weight: 500;
    }

    .nav-item:hover {
        background: #34495e;
        color: #fff;
        text-decoration: none;
    }

    /* Trạng thái trang đang chọn */
    .nav-item.active {
        background: #34495e;
        color: #fff;
        border-left-color: #3498db;
    }

    /* Badge thông báo đơn hàng mới */
    .badge-count {
        position: absolute;
        right: 20px;
        background: #e74c3c;
        color: white;
        font-size: 0.7rem;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: bold;
    }
</style>