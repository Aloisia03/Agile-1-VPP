<?php
namespace App\Controllers;

use App\Models\Order;

class OrderController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    // Danh sách đơn
  public function index()
{
    $orders = $this->orderModel->getAllOrders();

    // 🔍 filter theo mã đơn
    if (!empty($_GET['order_id'])) {
        $keyword = $_GET['order_id'];

        $orders = array_filter($orders, function ($order) use ($keyword) {
            return isset($order['id']) && $order['id'] == $keyword;
        });
    }

    // 🔍 filter theo trạng thái (CLICK STATS)
    if (!empty($_GET['status'])) {
        $status = $_GET['status'];

        $orders = array_filter($orders, function ($order) use ($status) {
            return isset($order['status']) && $order['status'] == $status;
        });
    }

    return view('orders.index', [
        'orders' => $orders
    ]);

}

    // Chi tiết đơn
    public function show($id)
    {
        $order = $this->orderModel->getOrderByIdAdmin($id);
        $items = $this->orderModel->getOrderDetail($id);

        return view('orders.show', compact('order', 'items'));
    }

    // Xác nhận
    public function confirm($id)
    {
        $this->orderModel->updateStatusAdmin($id, 'confirmed');
        header("Location: /Agile-1-VPP/orders");
        exit;
    }

    // Hủy
    public function cancel($id)
    {
        $this->orderModel->updateStatusAdmin($id, 'canceled');
        header("Location: /Agile-1-VPP/orders");
        exit;
    }
    function statusText($status) {
    return [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'canceled' => 'Đã hủy',
    ][$status] ?? $status;
}

}