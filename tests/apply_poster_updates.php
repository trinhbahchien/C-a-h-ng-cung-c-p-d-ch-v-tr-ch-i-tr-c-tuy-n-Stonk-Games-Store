<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$covers = [
    1  => 'elden_ring.jpg',
    2  => 'cyberpunk.jpg',
    3  => 'gow.jpg',
    4  => 'fc24.jpg',
    5  => 'genshin.jpg',
    6  => 're4.jpg',
    7  => 'gta5.jpg',
    8  => 'rdr2.jpg',
    9  => 'witcher3.jpg',
    10 => 'bg3.jpg',
    11 => 'sekiro.jpg',
    12 => 'ac_mirage.jpg',
    13 => 'ff7_remake.jpg',
    14 => 'cs2.jpg',
    15 => 'mhw.jpg',
    16 => 'ds3.jpg',
    17 => 'fh5.jpg',
    18 => 'dota2.jpg',
];

$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'games' . DIRECTORY_SEPARATOR;

echo "=== KIỂM TRA TỆP ẢNH POSTER CỤC BỘ TRONG UPLOADS/GAMES ===\n";
foreach ($covers as $id => $filename) {
    $fullPath = $dir . $filename;
    if (file_exists($fullPath)) {
        echo sprintf("Game #%02d: %-15s [OK - %d bytes]\n", $id, $filename, filesize($fullPath));
    } else {
        echo sprintf("Game #%02d: %-15s [THIẾU TỆP]\n", $id, $filename);
    }
}

echo "\n=== CẬP NHẬT CƠ SỞ DỮ LIỆU MYSQL VỚI ẢNH CỤC BỘ (100% PREPARED STATEMENTS) ===\n";
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("UPDATE games SET cover_image = :cover_image WHERE id = :id");

foreach ($covers as $id => $filename) {
    $stmt->execute([
        ':cover_image' => $filename,
        ':id'          => $id
    ]);
    echo "Đã cập nhật Game #{$id} => cover_image = '{$filename}'\n";
}

// Cập nhật cả bản ghi test nếu có
$testStmt = $db->prepare("UPDATE games SET cover_image = 'default_game.jpg' WHERE cover_image = '' OR cover_image IS NULL");
$testStmt->execute();

echo "\nHoàn thành cập nhật ảnh bìa cục bộ 100% thành công!\n";
