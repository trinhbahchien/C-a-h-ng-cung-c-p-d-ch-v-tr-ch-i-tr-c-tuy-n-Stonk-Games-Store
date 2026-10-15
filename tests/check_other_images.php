<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance()->getConnection();

echo "=== KIỂM TRA ẢNH NHÀ PHÁT TRIỂN (DEVELOPERS) ===\n";
$devs = $db->query("SELECT id, name, logo FROM developers")->fetchAll(PDO::FETCH_ASSOC);
foreach ($devs as $d) {
    $logo = $d['logo'];
    $path = PUBLIC_PATH . '/assets/uploads/developers/' . $logo;
    $mediaPath = PUBLIC_PATH . '/assets/media/img/developers/' . $logo;
    $found = file_exists($path) || file_exists($mediaPath);
    echo "#{$d['id']} {$d['name']} ($logo): " . ($found ? "FOUND" : "NOT FOUND") . "\n";
}

echo "\n=== KIỂM TRA ẢNH TIN TỨC (NEWS) ===\n";
$news = $db->query("SELECT id, title, image FROM news")->fetchAll(PDO::FETCH_ASSOC);
foreach ($news as $n) {
    $img = $n['image'];
    $path = PUBLIC_PATH . '/assets/uploads/news/' . $img;
    $mediaPath = PUBLIC_PATH . '/assets/media/img/news/' . $img;
    $found = file_exists($path) || file_exists($mediaPath);
    echo "#{$n['id']} {$n['title']} ($img): " . ($found ? "FOUND" : "NOT FOUND") . "\n";
}
