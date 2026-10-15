<?php
/**
 * Stonk Games Store - Stage 6 Checkout & Order CRUD Test
 * Tuân thủ quy chuẩn kiểm thử .agents/AGENTS.md
 */

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/Game.php';
require_once __DIR__ . '/../app/Models/Order.php';

echo "============================================================\n";
echo "   STONK GAMES STORE - KIỂM THỬ GIAI ĐOẠN 6: CHECKOUT & ORDER\n";
echo "   Quy chuẩn kiểm thử: .agents/AGENTS.md                    \n";
echo "============================================================\n\n";

$db = Database::getInstance()->getConnection();
$orderModel = new Order();
$gameModel = new Game();

$testName = "[TEST] Game Thủ Stage 6";
$testEmail = "test_stage6_" . time() . "@stonkgames.com";
$testGameId = 1; // Elden Ring
$testPrice = 840000.00;

try {
    // -------------------------------------------------------------
    // BƯỚC 1: KIỂM THỬ THÊM MỚI ĐƠN HÀNG & CẤP PHÁT CD-KEY (CREATE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 1/3] Kiểm thử THÊM MỚI ĐƠN HÀNG & SINH MÃ CD-KEY (Create Test):\n";
    
    // Ghi nhận analytics trước khi mua
    $stmtBefore = $db->prepare("SELECT total_sold, revenue FROM game_analytics WHERE game_id = ?");
    $stmtBefore->execute([$testGameId]);
    $analyticsBefore = $stmtBefore->fetch(PDO::FETCH_ASSOC);
    $soldBefore = (int)($analyticsBefore['total_sold'] ?? 0);
    $revBefore = (float)($analyticsBefore['revenue'] ?? 0);

    $generatedKey = $orderModel->generateCdKey();
    if (!preg_match('/^STONK-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', $generatedKey)) {
        throw new Exception("Lỗi: Mã CD-Key sinh ra không đúng định dạng chuẩn STONK-XXXX-YYYY-ZZZZ-WWWW! Key: " . $generatedKey);
    }
    echo "   [PASSED] Đã sinh mã CD-Key chuẩn: {$generatedKey}\n";

    $orderData = [
        'user_id'        => null, // Khách mua nhanh
        'customer_name'  => $testName,
        'customer_email' => $testEmail,
        'total_amount'   => $testPrice,
        'payment_method' => 'VIETQR',
        'status'         => 'COMPLETED'
    ];

    $items = [
        [
            'game_id' => $testGameId,
            'price'   => $testPrice,
            'cd_key'  => $generatedKey
        ]
    ];

    $newOrderId = $orderModel->create($orderData, $items);
    if ($newOrderId <= 0) {
        throw new Exception("Lỗi: Không tạo được đơn hàng trong CSDL!");
    }
    echo "   [PASSED] Đã tạo thành công đơn hàng ID: #{$newOrderId}\n";

    // Kiểm tra dữ liệu đơn hàng và order_items
    $createdOrder = $orderModel->findById($newOrderId);
    if (!$createdOrder || $createdOrder['customer_name'] !== $testName) {
        throw new Exception("Lỗi: Dữ liệu đơn hàng vừa tạo không khớp!");
    }
    if (empty($createdOrder['items']) || $createdOrder['items'][0]['cd_key'] !== $generatedKey) {
        throw new Exception("Lỗi: Không tìm thấy vật phẩm và CD-Key trong order_items!");
    }
    echo "   [PASSED] Khách hàng: {$createdOrder['customer_name']}\n";
    echo "   [PASSED] Email nhận key: {$createdOrder['customer_email']}\n";
    echo "   [PASSED] Chi tiết đơn hàng chứa 1 item với CD-Key: {$createdOrder['items'][0]['cd_key']}\n";

    // Kiểm tra đồng bộ Analytics tự động
    $stmtAfter = $db->prepare("SELECT total_sold, revenue FROM game_analytics WHERE game_id = ?");
    $stmtAfter->execute([$testGameId]);
    $analyticsAfter = $stmtAfter->fetch(PDO::FETCH_ASSOC);
    $soldAfter = (int)($analyticsAfter['total_sold'] ?? 0);
    $revAfter = (float)($analyticsAfter['revenue'] ?? 0);

    if ($soldAfter !== $soldBefore + 1 || abs($revAfter - ($revBefore + $testPrice)) > 0.01) {
        throw new Exception("Lỗi: Đồng bộ game_analytics không chính xác! Sold before: {$soldBefore}, after: {$soldAfter}");
    }
    echo "   [PASSED] Đồng bộ Analytics: Lượt bán tăng từ {$soldBefore} -> {$soldAfter}, doanh thu tăng {$testPrice} đ\n\n";

    // -------------------------------------------------------------
    // BƯỚC 2: KIỂM THỬ CHỈNH SỬA / CẬP NHẬT TRẠNG THÁI (UPDATE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 2/3] Kiểm thử CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG (Update Test):\n";
    // Chuyển sang PENDING
    $orderModel->updateStatus($newOrderId, 'PENDING');
    $stmtPending = $db->prepare("SELECT status FROM orders WHERE id = ?");
    $stmtPending->execute([$newOrderId]);
    $statusPending = $stmtPending->fetchColumn();

    if ($statusPending !== 'PENDING') {
        throw new Exception("Lỗi: Không cập nhật được trạng thái đơn hàng sang PENDING!");
    }
    echo "   [PASSED] Cập nhật thành công trạng thái đơn sang PENDING\n";

    // Kiểm tra analytics: khi PENDING thì không tính vào lượt bán
    $stmtCheckPending = $db->prepare("SELECT total_sold FROM game_analytics WHERE game_id = ?");
    $stmtCheckPending->execute([$testGameId]);
    $soldWhenPending = (int)$stmtCheckPending->fetchColumn();
    if ($soldWhenPending !== $soldBefore) {
        throw new Exception("Lỗi: Analytics vẫn tính đơn PENDING vào lượt bán!");
    }
    echo "   [PASSED] Analytics tự động trừ lượt bán khi đơn chuyển PENDING (Hiện tại: {$soldWhenPending})\n";

    // Chuyển lại COMPLETED
    $orderModel->updateStatus($newOrderId, 'COMPLETED');
    $stmtCompleted = $db->prepare("SELECT status FROM orders WHERE id = ?");
    $stmtCompleted->execute([$newOrderId]);
    if ($stmtCompleted->fetchColumn() !== 'COMPLETED') {
        throw new Exception("Lỗi: Không cập nhật lại COMPLETED được!");
    }
    echo "   [PASSED] Cập nhật lại thành công trạng thái COMPLETED\n\n";

    // -------------------------------------------------------------
    // BƯỚC 3: KIỂM THỬ XÓA AN TOÀN BẢN GHI TEST (DELETE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 3/3] Kiểm thử XÓA AN TOÀN BẢN GHI TEST (Delete Test):\n";
    $deleteResult = $orderModel->delete($newOrderId);
    if (!$deleteResult) {
        throw new Exception("Lỗi: Hàm delete trả về false!");
    }

    $deletedOrder = $orderModel->findById($newOrderId);
    if ($deletedOrder) {
        throw new Exception("Lỗi: Đơn hàng vẫn còn tồn tại trong CSDL sau khi xóa!");
    }
    echo "   [PASSED] Đã xóa an toàn đơn hàng test ID: #{$newOrderId}\n";

    // Kiểm tra cascade xóa trong order_items
    $stmtItems = $db->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ?");
    $stmtItems->execute([$newOrderId]);
    if ((int)$stmtItems->fetchColumn() !== 0) {
        throw new Exception("Lỗi: Còn sót lại dữ liệu order_items sau khi xóa đơn!");
    }
    echo "   [PASSED] Bảng order_items đã được dọn sạch sẽ (Cascade Delete chuẩn xác)\n";

    // Kiểm tra analytics sau khi xóa
    $stmtFinal = $db->prepare("SELECT total_sold, revenue FROM game_analytics WHERE game_id = ?");
    $stmtFinal->execute([$testGameId]);
    $analyticsFinal = $stmtFinal->fetch(PDO::FETCH_ASSOC);
    if ((int)$analyticsFinal['total_sold'] !== $soldBefore) {
        throw new Exception("Lỗi: Analytics không phục hồi số lượng ban đầu sau khi xóa đơn test!");
    }
    echo "   [PASSED] Analytics tự động cân bằng lại chính xác tuyệt đối: {$soldBefore} bản quyền\n";
    echo "   [PASSED] Cơ sở dữ liệu sạch sẽ 100%, không để lại rác thử nghiệm.\n\n";

    echo "============================================================\n";
    echo "   KẾT QUẢ: TOÀN BỘ 3 BƯỚC CRUD CHECKOUT HOÀN TẤT 100% OK!  \n";
    echo "============================================================\n";

} catch (Exception $e) {
    echo "\n[FAILED] Kiểm thử thất bại: " . $e->getMessage() . "\n";
    if (isset($newOrderId) && $newOrderId > 0) {
        $db->exec("DELETE FROM orders WHERE id = " . (int)$newOrderId);
        $orderModel->syncAnalytics();
        echo "   (Đã dọn dẹp bản ghi ID: {$newOrderId})\n";
    }
    exit(1);
}
