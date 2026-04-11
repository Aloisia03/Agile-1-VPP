@extends('home')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:16px; flex-wrap:wrap;">
        <h2 style="margin:0;">Thống kê doanh thu và sản phẩm bán chạy</h2>
        <form method="GET" action="{{ route('statistics') }}" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap;">
            <div>
                <label style="display:block; font-size:12px; color:#6c757d;">Từ ngày</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control">
            </div>
            <div>
                <label style="display:block; font-size:12px; color:#6c757d;">Đến ngày</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="form-control">
            </div>
            <div>
                <label style="display:block; font-size:12px; color:#6c757d;">Top sản phẩm</label>
                <input type="number" min="1" max="50" name="top_limit" value="{{ $topLimit }}" class="form-control" style="width:110px;">
            </div>
            <button type="submit" class="btn btn-primary">Lọc</button>
        </form>
    </div>

    @if($errorMessage)
        <div class="alert alert-warning">{{ $errorMessage }}</div>
    @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-bottom:16px;">
        <div class="card p-3">
            <div style="font-size:12px; color:#6c757d;">Tổng doanh thu</div>
            <div style="font-size:24px; font-weight:700; color:#198754;">
                {{ number_format($summary['total_revenue'], 0, ',', '.') }} VND
            </div>
        </div>
        <div class="card p-3">
            <div style="font-size:12px; color:#6c757d;">Tổng đơn hàng</div>
            <div style="font-size:24px; font-weight:700;">
                {{ number_format($summary['total_orders'], 0, ',', '.') }}
            </div>
        </div>
        <div class="card p-3">
            <div style="font-size:12px; color:#6c757d;">Tổng sản phẩm đã bán</div>
            <div style="font-size:24px; font-weight:700;">
                {{ number_format($summary['total_items'], 0, ',', '.') }}
            </div>
        </div>
        <div class="card p-3">
            <div style="font-size:12px; color:#6c757d;">Giá trị trung bình đơn hàng</div>
            <div style="font-size:24px; font-weight:700; color:#0d6efd;">
                {{ number_format($summary['average_order_value'], 0, ',', '.') }} VND
            </div>
        </div>
    </div>

    <div class="stats-grid-charts" style="display:grid; grid-template-columns:2fr 1fr; gap:14px; margin-bottom:16px;">
        <div class="card p-3">
            <h5 style="margin:0 0 12px 0;">Doanh thu theo ngày</h5>
            <canvas id="revenueChart" height="110"></canvas>
        </div>
        <div class="card p-3">
            <h5 style="margin:0 0 12px 0;">Top sản phẩm bán chạy</h5>
            <canvas id="topProductsChart" height="110"></canvas>
        </div>
    </div>

    <div class="stats-grid-tables" style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
        <div class="card p-3">
            <h5 style="margin:0 0 10px 0;">Bảng doanh thu theo ngày</h5>
            <div style="max-height:380px; overflow:auto;">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày</th>
                            <th style="text-align:right;">Doanh thu</th>
                            <th style="text-align:right;">Số đơn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyRevenue as $row)
                            <tr>
                                <td>{{ $row['report_date'] }}</td>
                                <td style="text-align:right;">{{ number_format($row['revenue'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($row['orders_count'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-3" id="top-products">
            <h5 style="margin:0 0 10px 0;">Top {{ $topLimit }} sản phẩm bán chạy</h5>
            <div style="max-height:380px; overflow:auto;">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="text-align:right;">Số lượng bán</th>
                            <th style="text-align:right;">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td style="text-align:right;">{{ number_format($item['sold_quantity'], 0, ',', '.') }}</td>
                                <td style="text-align:right;">{{ number_format($item['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media (max-width: 992px) {
            .stats-grid-charts,
            .stats-grid-tables {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <script>
        const revenueLabels = <?= json_encode($chartData['labels']); ?>;
        const revenueValues = <?= json_encode($chartData['values']); ?>;
        const topLabels = <?= json_encode($topChartLabels); ?>;
        const topValues = <?= json_encode($topChartValues); ?>;

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: revenueValues,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.15)',
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
                    label: 'So luong da ban',
                    data: topValues,
                    backgroundColor: '#198754'
                }]
            },
            options: {
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
