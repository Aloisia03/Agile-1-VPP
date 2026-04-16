<?php
namespace App\Controllers;

use App\Models\Report;

class ReportController
{
    protected $reportModel;

    public function __construct()
    {
        $this->reportModel = new Report();
    }

    public function index()
    {
        // Lấy dữ liệu từ Model
        $stats = $this->reportModel->getOverviewStats();
        $topProducts = $this->reportModel->getTopSellingProducts(5); // Lấy top 5

        // Xử lý biến để tránh null nếu DB chưa có đơn nào
        $totalRevenue = $stats['total_revenue'] ?? 0;
        $totalOrders = $stats['total_completed_orders'] ?? 0;

        // Tìm sản phẩm bán chạy nhất (để làm mốc tính % cho thanh Tiến trình giao diện)
        $maxSold = 0;
        if (!empty($topProducts)) {
            $maxSold = $topProducts[0]['total_sold']; // Thằng đứng đầu là max
        }

        // Gọi ra View
        return view('reports.index', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'topProducts' => $topProducts,
            'maxSold' => $maxSold
        ]);
    }
}