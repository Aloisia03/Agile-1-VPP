<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">Admin</a>
    @if(isset($_SESSION['user']))
    <div class="text-white">
        <a href="/Agile-1-VPP/profile" class="text-white text-decoration-none fw-semibold" style="transition: color 0.2s ease;" onmouseover="this.style.color='#9ad1ff'" onmouseout="this.style.color='white'">{{ $_SESSION['user']['name'] }}</a> |
        <a href="/Agile-1-VPP/profile" class="text-white">Hồ sơ</a> |
        <a href="/Agile-1-VPP/logout" class="text-white">Đăng xuất</a>
    </div>
    @endif
</nav>  