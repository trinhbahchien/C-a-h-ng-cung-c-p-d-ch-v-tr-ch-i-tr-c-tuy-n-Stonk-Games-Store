<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance()->getConnection();
$games = $db->query("SELECT id, title, cover_image FROM games ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "Checking game cover images in database:\n\n";

foreach ($games as $g) {
    $cover = $g['cover_image'];
    $url = get_game_cover_url($cover);
    
    // Check if it's local file or remote URL
    $status = 'UNKNOWN';
    if (strpos($cover, 'http://') === 0 || strpos($cover, 'https://') === 0) {
        $ch = curl_init($cover);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $status = ($code >= 200 && $code < 400) ? "REMOTE OK (HTTP $code)" : "REMOTE BROKEN (HTTP $code)";
    } else {
        $localPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'games' . DIRECTORY_SEPARATOR . $cover;
        $mediaPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'games' . DIRECTORY_SEPARATOR . $cover;
        $existsUploads = file_exists($localPath);
        $existsMedia = file_exists($mediaPath);
        if ($existsUploads) {
            $status = "LOCAL UPLOADS FOUND";
        } elseif ($existsMedia) {
            $status = "LOCAL IN MEDIA BUT NOT IN UPLOADS!";
        } else {
            $status = "LOCAL FILE NOT FOUND";
        }
    }
    
    echo sprintf("#%02d %-35s | Cover: %-40s | Status: %s\n", $g['id'], substr($g['title'], 0, 35), substr($cover, 0, 40), $status);
}
