<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, title, slug, trailer_url, status FROM games ORDER BY id ASC");
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($games, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
