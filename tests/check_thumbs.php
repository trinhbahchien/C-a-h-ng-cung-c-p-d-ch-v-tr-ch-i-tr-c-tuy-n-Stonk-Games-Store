<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance()->getConnection();
$games = $db->query("SELECT id, title, trailer_url FROM games ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

foreach ($games as $g) {
    $vid = $g['trailer_url'];
    $urls = [
        "https://img.youtube.com/vi/{$vid}/default.jpg",
        "https://img.youtube.com/vi/{$vid}/mqdefault.jpg",
        "https://img.youtube.com/vi/{$vid}/hqdefault.jpg",
    ];
    
    $statuses = [];
    foreach ($urls as $u) {
        $ch = curl_init($u);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $statuses[] = $code;
    }
    echo "#{$g['id']} {$g['title']} ($vid): " . implode(', ', $statuses) . "\n";
}
