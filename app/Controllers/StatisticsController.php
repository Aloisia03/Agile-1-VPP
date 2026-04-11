<?php
namespace App\Controllers;

use App\Models\Statistics;

class StatisticsController
{
    public function index()
    {
        $today = date('Y-m-d');
        $firstDayOfMonth = date('Y-m-01');

        $fromDate = $_GET['from_date'] ?? $firstDayOfMonth;
        $toDate = $_GET['to_date'] ?? $today;
        $topLimit = (int)($_GET['top_limit'] ?? 10);
        $topLimit = $topLimit > 0 ? min($topLimit, 50) : 10;

        if (!$this->isValidDate($fromDate)) {
            $fromDate = $firstDayOfMonth;
        }
        if (!$this->isValidDate($toDate)) {
            $toDate = $today;
        }

        if ($fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $summary = [
            'total_revenue' => 0,
            'total_orders' => 0,
            'total_items' => 0,
            'average_order_value' => 0,
        ];
        $dailyRevenue = [];
        $topProducts = [];
        $errorMessage = null;

        try {
            $statisticsModel = new Statistics();
            $summary = $statisticsModel->getRevenueSummary($fromDate, $toDate);
            $dailyRevenue = $statisticsModel->getRevenueByDay($fromDate, $toDate);
            $topProducts = $statisticsModel->getTopSellingProducts($fromDate, $toDate, $topLimit);
        } catch (\Throwable $e) {
            $errorMessage = 'Chưa có dữ liệu đơn hàng hoặc thiếu bảng orders/order_items. Vui lòng chạy migration Sprint 3.';
        }

        $chartData = $this->buildRevenueChartData($dailyRevenue, $fromDate, $toDate);
        $topChartLabels = array_map(static fn(array $item): string => $item['name'], $topProducts);
        $topChartValues = array_map(static fn(array $item): int => $item['sold_quantity'], $topProducts);

        return view('statistics.index', compact(
            'fromDate',
            'toDate',
            'topLimit',
            'summary',
            'dailyRevenue',
            'topProducts',
            'chartData',
            'topChartLabels',
            'topChartValues',
            'errorMessage'
        ));
    }

    private function isValidDate(string $value): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return false;
        }

        [$year, $month, $day] = array_map('intval', explode('-', $value));
        return checkdate($month, $day, $year);
    }

    private function buildRevenueChartData(array $dailyRevenue, string $fromDate, string $toDate): array
    {
        $mapped = [];
        foreach ($dailyRevenue as $row) {
            $mapped[$row['report_date']] = $row['revenue'];
        }

        $labels = [];
        $values = [];

        $cursor = strtotime($fromDate);
        $end = strtotime($toDate);

        while ($cursor <= $end) {
            $key = date('Y-m-d', $cursor);
            $labels[] = $key;
            $values[] = (float)($mapped[$key] ?? 0);
            $cursor = strtotime('+1 day', $cursor);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
