<?php
/**
 * Stonk Games Store - CartController
 * Xử lý giỏ hàng người dùng, phiên thanh toán VietQR và cấp phát mã bản quyền CD-Key
 */

class CartController extends Controller {

    protected Game $gameModel;
    protected Order $orderModel;

    public function __construct() {
        $this->gameModel = new Game();
        $this->orderModel = new Order();
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function index(): void {
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $totalOriginal = 0;
        $totalDiscounted = 0;

        foreach ($cart as $gameId => $qty) {
            $game = $this->gameModel->findById((int)$gameId);
            if ($game) {
                $finalPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
                $items[] = [
                    'game' => $game,
                    'quantity' => 1, // Game bản quyền số cấp 1 key mỗi phiên
                    'original_price' => $game['price'],
                    'final_price' => $finalPrice,
                    'discount_percent' => $game['discount_percent']
                ];
                $totalOriginal += $game['price'];
                $totalDiscounted += $finalPrice;
            }
        }

        $this->view('cart/index', [
            'pageTitle' => 'Giỏ Hàng Của Bạn | Stonk Games Store',
            'items' => $items,
            'totalOriginal' => $totalOriginal,
            'totalDiscounted' => $totalDiscounted,
            'totalSavings' => $totalOriginal - $totalDiscounted
        ], 'main');
    }

    /**
     * Thêm game vào giỏ hàng (hỗ trợ mua nhanh chuyển thẳng đến checkout)
     */
    public function add(): void {
        $gameId = (int)($_POST['game_id'] ?? $_GET['id'] ?? 0);
        if ($gameId <= 0) {
            $this->redirect('home');
            return;
        }

        $game = $this->gameModel->findById($gameId);
        if (!$game) {
            $this->setFlash('danger', 'Trò chơi không tồn tại trên hệ thống!');
            $this->redirect('home');
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$gameId])) {
            $this->setFlash('info', "Trò chơi '{$game['title']}' đã có sẵn trong giỏ hàng!");
        } else {
            $_SESSION['cart'][$gameId] = 1;
            $this->setFlash('success', "Đã thêm '{$game['title']}' vào giỏ hàng thành công!");
        }

        // Nếu bấm "Mua Ngay" hoặc có tham số chuyển thẳng checkout
        if ((isset($_POST['redirect_checkout']) && $_POST['redirect_checkout'] == '1') || 
            (isset($_POST['buy_now']) && $_POST['buy_now'] == '1') ||
            (isset($_GET['checkout']) && $_GET['checkout'] == '1')) {
            $this->redirect('cart/checkout');
            return;
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/cart');
        header('Location: ' . $referer);
        exit;
    }

    /**
     * Xóa game khỏi giỏ hàng
     */
    public function remove(): void {
        $gameId = (int)($_POST['game_id'] ?? $_GET['id'] ?? 0);
        if (isset($_SESSION['cart'][$gameId])) {
            unset($_SESSION['cart'][$gameId]);
            $this->setFlash('success', 'Đã xóa trò chơi khỏi giỏ hàng.');
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/cart');
        header('Location: ' . $referer);
        exit;
    }

    /**
     * Xóa sạch toàn bộ giỏ hàng
     */
    public function clear(): void {
        $_SESSION['cart'] = [];
        unset($_SESSION['checkout_transfer_code']);
        $this->setFlash('info', 'Đã xóa toàn bộ trò chơi trong giỏ hàng.');
        $this->redirect('cart');
    }

    /**
     * Trang thanh toán đơn hàng & quét mã VietQR Napas247
     */
    public function checkout(): void {
        if (empty($_SESSION['cart'])) {
            $this->setFlash('warning', 'Giỏ hàng của bạn đang trống! Vui lòng chọn game trước khi thanh toán.');
            $this->redirect('cart');
            return;
        }

        // Tính toán thông tin danh sách sản phẩm và tổng tiền
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $totalOriginal = 0;
        $totalDiscounted = 0;

        foreach ($cart as $gameId => $qty) {
            $game = $this->gameModel->findById((int)$gameId);
            if ($game) {
                $finalPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
                $items[] = [
                    'game' => $game,
                    'price' => $finalPrice,
                    'original_price' => $game['price'],
                    'discount_percent' => $game['discount_percent']
                ];
                $totalOriginal += $game['price'];
                $totalDiscounted += $finalPrice;
            }
        }

        // Tạo mã nội dung chuyển khoản duy nhất cho phiên thanh toán
        if (empty($_SESSION['checkout_transfer_code'])) {
            $_SESSION['checkout_transfer_code'] = 'STONK' . random_int(10000, 99999);
        }
        $transferCode = $_SESSION['checkout_transfer_code'];

        $user = $_SESSION['user'] ?? null;

        $this->view('cart/checkout', [
            'pageTitle' => 'Thanh Toán Đơn Hàng & VietQR | Stonk Games Store',
            'items' => $items,
            'totalOriginal' => $totalOriginal,
            'totalDiscounted' => $totalDiscounted,
            'totalSavings' => $totalOriginal - $totalDiscounted,
            'transferCode' => $transferCode,
            'user' => $user
        ], 'main');
    }

    /**
     * Xử lý xác nhận thanh toán, tạo đơn hàng và cấp phát mã CD-Key
     */
    public function processCheckout(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cart/checkout');
            return;
        }

        if (empty($_SESSION['cart'])) {
            $this->setFlash('warning', 'Giỏ hàng của bạn đang trống!');
            $this->redirect('cart');
            return;
        }

        $customerName = trim($_POST['customer_name'] ?? '');
        $customerEmail = trim($_POST['customer_email'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? 'VIETQR');

        // Nếu người dùng đã đăng nhập, ưu tiên dùng thông tin tài khoản nếu form trống
        if (isset($_SESSION['user'])) {
            if (empty($customerName)) {
                $customerName = $_SESSION['user']['fullname'] ?? $_SESSION['user']['username'];
            }
            if (empty($customerEmail)) {
                $customerEmail = $_SESSION['user']['email'];
            }
        }

        // Xác thực hợp lệ
        if (empty($customerName) || empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('danger', 'Vui lòng nhập đầy đủ và chính xác Họ tên cùng Địa chỉ Email nhận mã bản quyền.');
            $this->redirect('cart/checkout');
            return;
        }

        // Thu thập vật phẩm và tính tiền thực tế từ CSDL
        $cart = $_SESSION['cart'] ?? [];
        $orderItems = [];
        $totalAmount = 0.00;

        foreach ($cart as $gameId => $qty) {
            $game = $this->gameModel->findById((int)$gameId);
            if ($game) {
                $finalPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
                $orderItems[] = [
                    'game_id' => $game['id'],
                    'price' => $finalPrice,
                    'cd_key' => $this->orderModel->generateCdKey()
                ];
                $totalAmount += $finalPrice;
            }
        }

        if (empty($orderItems)) {
            $this->setFlash('danger', 'Không tìm thấy thông tin sản phẩm hợp lệ trong giỏ hàng.');
            $this->redirect('cart');
            return;
        }

        // Lưu đơn hàng vào CSDL
        $orderData = [
            'user_id' => isset($_SESSION['user']) ? (int)$_SESSION['user']['id'] : null,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'status' => 'COMPLETED' // Đơn hàng thanh toán số cấp mã bản quyền ngay lập tức
        ];

        $orderId = $this->orderModel->create($orderData, $orderItems);

        // Dọn dẹp giỏ hàng và lưu mã đơn hàng mới nhất vào Session
        unset($_SESSION['cart']);
        unset($_SESSION['checkout_transfer_code']);
        $_SESSION['last_order_id'] = $orderId;

        $this->setFlash('success', "Giao dịch thành công! Đơn hàng #{$orderId} đã được xác nhận và mã CD-Key đã được khởi tạo.");
        $this->redirect('cart/success?order_id=' . $orderId);
    }

    /**
     * Trang hoàn tất thanh toán & kích hoạt giấy phép bản quyền CD-Key
     */
    public function success(): void {
        $orderId = (int)($_GET['order_id'] ?? $_SESSION['last_order_id'] ?? 0);

        if ($orderId <= 0) {
            $this->redirect('home');
            return;
        }

        $order = $this->orderModel->findById($orderId);
        if (!$order) {
            $this->setFlash('danger', "Không tìm thấy thông tin đơn hàng #{$orderId}.");
            $this->redirect('home');
            return;
        }

        $this->view('cart/success', [
            'pageTitle' => "Chi Tiết Đơn Hàng #{$orderId} & Mã Bản Quyền | Stonk Games Store",
            'order' => $order
        ], 'main');
    }
}
