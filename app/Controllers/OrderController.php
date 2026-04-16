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

    // Xác nhận nhanh
    public function confirm($id)
    {
        // 1. Cập nhật trạng thái đơn thành confirmed
        $this->orderModel->updateStatusAdmin($id, 'confirmed');

        // 2. Thực hiện trừ kho
        $this->orderModel->reduceStock($id);

        header("Location: /Agile-1-VPP/orders");
        exit;
    }

    // Hủy nhanh
    public function cancel($id)
    {
        $this->orderModel->updateStatusAdmin($id, 'canceled');
        header("Location: /Agile-1-VPP/orders");
        exit;
    }

    // ==========================================
    // CHỨC NĂNG MỚI: CẬP NHẬT TRẠNG THÁI TỪ DROPDOWN
    // ==========================================
    public function updateStatus($id)
    {
        // Lấy giá trị trạng thái mới từ form gửi lên (dropdown select có name="status")
        $newStatus = $_POST['status'] ?? 'pending';

        // Gọi hàm cập nhật trong model (giống như bạn làm với confirm và cancel)
        $this->orderModel->updateStatusAdmin($id, $newStatus);

        // Chuyển hướng về lại trang danh sách đơn hàng
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