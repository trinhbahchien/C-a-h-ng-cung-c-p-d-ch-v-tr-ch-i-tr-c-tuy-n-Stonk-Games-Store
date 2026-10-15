<?php
/**
 * Stonk Games Store - Order Controller
 * Điều phối phân hệ Lịch sử mua hàng, Chi tiết đơn hàng, Mã bản quyền CD-Key và Xuất hóa đơn
 */

class OrderController extends Controller {
    private Order $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    /**
     * Danh sách lịch sử mua hàng của game thủ
     */
    public function history(): void {
        $user = $_SESSION['user'] ?? null;
        $statusFilter = strtolower($_GET['status'] ?? 'all');
        $orders = [];
        $counts = [
            'all'       => 0,
            'completed' => 0,
            'pending'   => 0
        ];

        if ($user) {
            $userId = (int)$user['id'];
            $orders = $this->orderModel->getByUserIdWithFilter($userId, $statusFilter);
            $counts = $this->orderModel->countByStatus($userId);
        }

        $lookupError = $_SESSION['lookup_error'] ?? null;
        unset($_SESSION['lookup_error']);

        $this->view('orders/history', [
            'pageTitle'    => 'Lịch Sử Mua Hàng & Hóa Đơn | Stonk Games Store',
            'user'         => $user,
            'orders'       => $orders,
            'currentTab'   => $statusFilter,
            'counts'       => $counts,
            'lookupError'  => $lookupError
        ]);
    }

    /**
     * Chi tiết đơn hàng kèm danh sách mã CD-Key bản quyền và trạng thái thanh toán
     */
    public function detail(?string $id = null): void {
        $orderId = (int)($id ?? $_GET['id'] ?? 0);

        if ($orderId <= 0) {
            $this->redirect('order/history');
            return;
        }

        $order = $this->orderModel->findById($orderId);

        if (!$order) {
            $this->view('orders/not_found', [
                'pageTitle' => 'Không Tìm Thấy Đơn Hàng | Stonk Games Store',
                'orderId'   => $orderId
            ]);
            return;
        }

        $user = $_SESSION['user'] ?? null;

        // Kiểm tra quyền truy cập: Người sở hữu đơn, Admin, hoặc khách vừa tra cứu hợp lệ qua session
        $isAuthorized = false;
        if ($user && ((int)$user['id'] === (int)$order['user_id'] || ($user['role'] ?? '') === 'admin')) {
            $isAuthorized = true;
        } elseif (isset($_SESSION['verified_order_id']) && (int)$_SESSION['verified_order_id'] === $orderId) {
            $isAuthorized = true;
        } elseif (empty($order['user_id'])) {
            // Đơn hàng khách vãng lai
            $isAuthorized = true;
        }

        $this->view('orders/detail', [
            'pageTitle'    => "Chi Tiết Đơn Hàng #{$orderId} | Stonk Games Store",
            'order'        => $order,
            'items'        => $order['items'] ?? [],
            'isAuthorized' => $isAuthorized
        ]);
    }

    /**
     * Xuất hóa đơn bán lẻ điện tử khổ A4 độc lập (sử dụng layout null tối ưu in ấn)
     */
    public function invoice(?string $id = null): void {
        $orderId = (int)($id ?? $_GET['id'] ?? 0);

        if ($orderId <= 0) {
            $this->redirect('order/history');
            return;
        }

        $order = $this->orderModel->findById($orderId);

        if (!$order) {
            $this->redirect('order/history');
            return;
        }

        // Layout null để trang in không bị dính Header / Footer của website
        $this->view('orders/invoice', [
            'pageTitle' => "Hóa Đơn Bán Hàng #{$order['id']} - Stonk Games Store",
            'order'     => $order,
            'items'     => $order['items'] ?? []
        ], null);
    }

    /**
     * Tra cứu nhanh đơn hàng dành cho khách chưa đăng nhập hoặc khách vãng lai
     */
    public function lookup(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('order/history');
            return;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $email = trim($_POST['email'] ?? '');

        if ($orderId <= 0 || empty($email)) {
            $_SESSION['lookup_error'] = 'Vui lòng nhập đầy đủ Mã đơn hàng (#ID) và Email đã dùng khi thanh toán.';
            $this->redirect('order/history');
            return;
        }

        $order = $this->orderModel->lookup($orderId, $email);

        if ($order) {
            $_SESSION['verified_order_id'] = $order['id'];
            $this->redirect('order/detail/' . $order['id']);
        } else {
            $_SESSION['lookup_error'] = "Không tìm thấy đơn hàng #{$orderId} với email '{$email}'. Vui lòng kiểm tra lại.";
            $this->redirect('order/history');
        }
    }
}
