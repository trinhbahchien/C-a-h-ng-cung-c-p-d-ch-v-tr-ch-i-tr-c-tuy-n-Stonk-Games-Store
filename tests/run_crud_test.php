<?php
/**
 * Stonk Games Store - CRUD Video Trailer Test Case
 * Tuân thủ quy tắc kiểm thử bắt buộc tại .agents/AGENTS.md
 */

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/Game.php';

echo "============================================================\n";
echo "   STONK GAMES STORE - KIỂM THỬ CRUD GAME VÀ VIDEO TRAILER  \n";
echo "   Quy chuẩn kiểm thử: .agents/AGENTS.md                    \n";
echo "============================================================\n\n";

$db = Database::getInstance()->getConnection();
$gameModel = new Game();

$testTitle = "[TEST] Game Demo Trailer";
$testSlug = "test-game-demo-trailer-" . time();
$testTrailer = "https://www.youtube.com/watch?v=AKXiKBnzpBQ"; // Full URL to test auto-extraction
$testDeveloperId = 1;

// Helper extract ID
function normalizeYtId(string $input): string {
    $input = trim($input);
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/|live\/))([\w-]{11})/i', $input, $matches)) {
        return $matches[1];
    }
    return $input;
}

try {
    // -------------------------------------------------------------
    // BƯỚC 1: KIỂM THỬ THÊM MỚI (CREATE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 1/3] Kiểm thử THÊM MỚI (Create Test):\n";
    $cleanTrailerId = normalizeYtId($testTrailer);
    
    $gameData = [
        'title'             => $testTitle,
        'slug'              => $testSlug,
        'developer_id'      => $testDeveloperId,
        'price'             => 499000.00,
        'discount_percent'  => 10,
        'short_description' => 'Bản ghi thử nghiệm kiểm tra tính năng video trailer.',
        'description'       => 'Mô tả chi tiết cho bản ghi thử nghiệm CRUD video trailer hệ thống Stonk Games Store.',
        'cover_image'       => 'default_game.jpg',
        'trailer_url'       => $cleanTrailerId,
        'is_featured'       => 0,
        'status'            => 1,
        'release_date'      => date('Y-m-d')
    ];

    $requirements = [
        'min_os'      => 'Windows 10 64-bit',
        'min_cpu'     => 'Intel Core i5',
        'min_ram'     => '8 GB RAM',
        'min_gpu'     => 'GTX 1060',
        'min_storage' => '20 GB',
        'rec_os'      => 'Windows 11',
        'rec_cpu'     => 'Intel Core i7',
        'rec_ram'     => '16 GB RAM',
        'rec_gpu'     => 'RTX 3060',
        'rec_storage' => '20 GB SSD'
    ];

    $newGameId = $gameModel->create($gameData, [1, 2], $requirements);
    
    // Kiểm tra dữ liệu trong CSDL
    $stmt = $db->prepare("SELECT * FROM games WHERE id = :id");
    $stmt->execute([':id' => $newGameId]);
    $createdGame = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($createdGame && $createdGame['title'] === $testTitle && $createdGame['trailer_url'] === $cleanTrailerId) {
        echo "   [PASSED] Đã thêm thành công game ID: {$newGameId}\n";
        echo "   [PASSED] Tiêu đề: {$createdGame['title']}\n";
        echo "   [PASSED] Trailer ID chuẩn hóa: {$createdGame['trailer_url']}\n\n";
    } else {
        throw new Exception("Lỗi: Không tìm thấy bản ghi vừa tạo trong CSDL!");
    }

    // -------------------------------------------------------------
    // BƯỚC 2: KIỂM THỬ CHỈNH SỬA (UPDATE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 2/3] Kiểm thử CHỈNH SỬA (Update Test):\n";
    $updatedTitle = "[TEST - ĐÃ CẬP NHẬT] Game Demo Trailer";
    $newTrailerInput = "https://youtu.be/dIQGI36BxDE?si=test1234";
    $updatedTrailerId = normalizeYtId($newTrailerInput);

    $updateData = [
        'title'             => $updatedTitle,
        'slug'              => $testSlug,
        'developer_id'      => $testDeveloperId,
        'price'             => 599000.00,
        'discount_percent'  => 25,
        'short_description' => 'Mô tả ngắn đã được cập nhật thành công.',
        'description'       => 'Nội dung chi tiết đã được cập nhật với video trailer God of War Ragnarok.',
        'cover_image'       => 'default_game.jpg',
        'trailer_url'       => $updatedTrailerId,
        'is_featured'       => 1,
        'status'            => 1,
        'release_date'      => date('Y-m-d')
    ];

    $gameModel->update($newGameId, $updateData, [1, 3], $requirements);

    $stmt->execute([':id' => $newGameId]);
    $updatedGame = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($updatedGame && $updatedGame['title'] === $updatedTitle && $updatedGame['trailer_url'] === $updatedTrailerId && (int)$updatedGame['is_featured'] === 1) {
        echo "   [PASSED] Cập nhật thành công game ID: {$newGameId}\n";
        echo "   [PASSED] Tiêu đề mới: {$updatedGame['title']}\n";
        echo "   [PASSED] Trailer ID mới: {$updatedGame['trailer_url']}\n";
        echo "   [PASSED] Cờ Featured: {$updatedGame['is_featured']}\n\n";
    } else {
        throw new Exception("Lỗi: Bản ghi cập nhật không khớp dữ liệu trong CSDL!");
    }

    // -------------------------------------------------------------
    // BƯỚC 3: KIỂM THỬ XÓA AN TOÀN (DELETE TEST)
    // -------------------------------------------------------------
    echo ">> [BƯỚC 3/3] Kiểm thử XÓA (Delete Test):\n";
    $deleteResult = $gameModel->delete($newGameId);

    $stmt->execute([':id' => $newGameId]);
    $deletedGame = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($deleteResult && !$deletedGame) {
        echo "   [PASSED] Đã xóa an toàn bản ghi test ID: {$newGameId}\n";
        echo "   [PASSED] Cơ sở dữ liệu sạch sẽ, không để lại rác thử nghiệm.\n\n";
    } else {
        throw new Exception("Lỗi: Xóa bản ghi test không thành công!");
    }

    echo "============================================================\n";
    echo "   KẾT QUẢ: TOÀN BỘ 3 BƯỚC CRUD KIỂM THỬ HOÀN TẤT 100% OK! \n";
    echo "============================================================\n";

} catch (Exception $e) {
    echo "\n[FAILED] Kiểm thử thất bại: " . $e->getMessage() . "\n";
    // Dọn dẹp nếu có
    if (isset($newGameId) && $newGameId > 0) {
        $db->exec("DELETE FROM games WHERE id = " . (int)$newGameId);
        echo "   (Đã dọn dẹp bản ghi ID: {$newGameId})\n";
    }
}
