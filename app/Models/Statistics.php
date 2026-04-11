<?php
namespace App\Models;

use App\Model;

class Statistics extends Model
{
    public function getRevenueSummary(string $fromDate, string $toDate): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(
            'COALESCE(SUM(oi.quantity * oi.unit_price), 0) AS total_revenue',
            'COUNT(DISTINCT o.id) AS total_orders',
            'COALESCE(SUM(oi.quantity), 0) AS total_items'
        )
            ->from('orders', 'o')
            ->innerJoin('o', 'order_items', 'oi', 'oi.order_id = o.id')
            ->where('o.created_at BETWEEN :fromDateTime AND :toDateTime')
            ->andWhere("o.status IN ('paid', 'completed')")
            ->setParameter('fromDateTime', $fromDate . ' 00:00:00')
            ->setParameter('toDateTime', $toDate . ' 23:59:59');

        $result = $query->executeQuery()->fetchAssociative() ?: [];

        $totalRevenue = (float)($result['total_revenue'] ?? 0);
        $totalOrders = (int)($result['total_orders'] ?? 0);

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_items' => (int)($result['total_items'] ?? 0),
            'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
        ];
    }

    public function getRevenueByDay(string $fromDate, string $toDate): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(
            'DATE(o.created_at) AS report_date',
            'COALESCE(SUM(oi.quantity * oi.unit_price), 0) AS revenue',
            'COUNT(DISTINCT o.id) AS orders_count'
        )
            ->from('orders', 'o')
            ->innerJoin('o', 'order_items', 'oi', 'oi.order_id = o.id')
            ->where('o.created_at BETWEEN :fromDateTime AND :toDateTime')
            ->andWhere("o.status IN ('paid', 'completed')")
            ->groupBy('DATE(o.created_at)')
            ->orderBy('report_date', 'ASC')
            ->setParameter('fromDateTime', $fromDate . ' 00:00:00')
            ->setParameter('toDateTime', $toDate . ' 23:59:59');

        $rows = $query->executeQuery()->fetchAllAssociative();

        return array_map(static function (array $row): array {
            return [
                'report_date' => $row['report_date'],
                'revenue' => (float)$row['revenue'],
                'orders_count' => (int)$row['orders_count'],
            ];
        }, $rows);
    }

    public function getTopSellingProducts(string $fromDate, string $toDate, int $limit = 10): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(
            'p.id',
            'p.name',
            'p.image',
            'COALESCE(SUM(oi.quantity), 0) AS sold_quantity',
            'COALESCE(SUM(oi.quantity * oi.unit_price), 0) AS revenue',
            'COUNT(DISTINCT oi.order_id) AS total_orders'
        )
            ->from('order_items', 'oi')
            ->innerJoin('oi', 'orders', 'o', 'o.id = oi.order_id')
            ->innerJoin('oi', 'products', 'p', 'p.id = oi.product_id')
            ->where('o.created_at BETWEEN :fromDateTime AND :toDateTime')
            ->andWhere("o.status IN ('paid', 'completed')")
            ->groupBy('p.id')
            ->addGroupBy('p.name')
            ->addGroupBy('p.image')
            ->orderBy('sold_quantity', 'DESC')
            ->addOrderBy('revenue', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('fromDateTime', $fromDate . ' 00:00:00')
            ->setParameter('toDateTime', $toDate . ' 23:59:59');

        $rows = $query->executeQuery()->fetchAllAssociative();

        return array_map(static function (array $row): array {
            return [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'image' => $row['image'] ?? null,
                'sold_quantity' => (int)$row['sold_quantity'],
                'revenue' => (float)$row['revenue'],
                'total_orders' => (int)$row['total_orders'],
            ];
        }, $rows);
    }
}
