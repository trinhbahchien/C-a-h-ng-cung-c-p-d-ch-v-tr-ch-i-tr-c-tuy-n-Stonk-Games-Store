<?php
/**
 * Stonk Games Store - CRUD News Test Case
 * Tuân thủ quy tắc kiểm thử bắt buộc tại .agents/AGENTS.md
 */

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/News.php';

echo "============================================================\n";
echo "   STONK GAMES STORE - KIỂM THỬ CRUD TIN TỨC & BÀI VIẾT    \n";
echo "   Quy chuẩn kiểm thử: .agents/AGENTS.md                    \n";
echo "============================================================\n\n";

$db = Database::getInstance()->getConnection();
$newsModel = new News();

$testTitle = "[TEST] Tin Tức Demo Cập Nhật Sàn 2026";
$testSlug = "test-tin-tuc-demo-" . time();
$testContent = "Đây là nội dung bản ghi thử nghiệm phục vụ kiểm thử tính năng Tin tức Hub theo quy định của .agents/AGENTS.md.";
$testImage = "news_sale.jpg";

try {
    // -------------------------------------------------------------
    // BƯỚC 1: KIỂM THỬ THÊM MỚI (CREATE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 1/3] Kiểm thử THÊM MỚI (Create Test):\n";
    $createData = [
        'title'       => $testTitle,
        'slug'        => $testSlug,
        'content'     => $testContent,
        'image'       => $testImage,
        'type'        => 'system',
        'game_id'     => null,
        'category_id' => null
    ];

    $newId = $newsModel->create($createData);
    
    // Xác minh dữ liệu trong CSDL qua Prepared Statements
    $stmt = $db->prepare("SELECT * FROM news WHERE id = :id");
    $stmt->execute([':id' => $newId]);
    $createdRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($createdRow && $createdRow['title'] === $testTitle && $createdRow['type'] === 'system') {
        echo "   [PASSED] Đã thêm thành công bài viết ID: {$newId}\n";
        echo "   [PASSED] Tiêu đề: {$createdRow['title']}\n";
        echo "   [PASSED] Phân loại: {$createdRow['type']}\n";
        echo "   [PASSED] Ảnh đại diện: {$createdRow['image']}\n\n";
    } else {
        throw new Exception("Lỗi: Không tìm thấy bản ghi vừa tạo trong CSDL!");
    }

    // -------------------------------------------------------------
    // BƯỚC 2: KIỂM THỬ CHỈNH SỬA (UPDATE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 2/3] Kiểm thử CHỈNH SỬA (Update Test):\n";
    $updatedTitle = "[TEST - ĐÃ CẬP NHẬT] Tin Tức Demo Cập Nhật Sàn 2026";
    $updatedContent = "Nội dung bài viết đã được cập nhật thành công với liên kết game Elden Ring.";
    $updateData = [
        'title'       => $updatedTitle,
        'slug'        => $testSlug,
        'content'     => $updatedContent,
        'image'       => 'news_elden.jpg',
        'type'        => 'game',
        'game_id'     => 1,
        'category_id' => null
    ];

    $updateSuccess = $newsModel->update($newId, $updateData);
    
    // Xác minh dữ liệu sau khi cập nhật
    $stmt->execute([':id' => $newId]);
    $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($updateSuccess && $updatedRow && $updatedRow['title'] === $updatedTitle && (int)$updatedRow['game_id'] === 1) {
        echo "   [PASSED] Cập nhật thành công bài viết ID: {$newId}\n";
        echo "   [PASSED] Tiêu đề mới: {$updatedRow['title']}\n";
        echo "   [PASSED] Game liên kết: ID {$updatedRow['game_id']}\n";
        echo "   [PASSED] Phân loại mới: {$updatedRow['type']}\n\n";
    } else {
        throw new Exception("Lỗi: Cập nhật bản ghi thất bại hoặc dữ liệu không khớp!");
    }

    // -------------------------------------------------------------
    // BƯỚC 3: KIỂM THỬ XÓA AN TOÀN (DELETE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 3/3] Kiểm thử XÓA AN TOÀN (Delete Test):\n";
    $deleteSuccess = $newsModel->delete($newId);

    // Xác minh CSDL đã dọn dẹp sạch sẽ bản ghi test
    $stmt->execute([':id' => $newId]);
    $deletedRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($deleteSuccess && empty($deletedRow)) {
        echo "   [PASSED] Đã xóa an toàn bản ghi test ID: {$newId}\n";
        echo "   [PASSED] Xác minh CSDL sạch sẽ: Không còn tồn tại bản ghi thử nghiệm.\n\n";
    } else {
        throw new Exception("Lỗi: Không thể xóa bản ghi test khỏi CSDL!");
    }

    echo "============================================================\n";
    echo "   KẾT QUẢ: 100% KIỂM THỬ CRUD TIN TỨC THÀNH CÔNG (PASSED)  \n";
    echo "============================================================\n";

} catch (Exception $e) {
    echo "\n[FAILED] Kiểm thử thất bại: " . $e->getMessage() . "\n";
    if (isset($newId) && $newId > 0) {
        // Dọn dẹp bản ghi test nếu xảy ra lỗi
        $newsModel->delete($newId);
        echo "[CLEANUP] Đã dọn dẹp bản ghi ID: {$newId}\n";
    }
    exit(1);
}
