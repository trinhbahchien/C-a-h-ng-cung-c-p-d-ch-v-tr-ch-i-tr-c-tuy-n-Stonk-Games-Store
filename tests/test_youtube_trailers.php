<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, title, slug, trailer_url FROM games ORDER BY id ASC");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Checking " . count($games) . " games for YouTube trailer availability...\n\n";

$results = [];

foreach ($games as $game) {
    $id = $game['id'];
    $title = $game['title'];
    $vid = trim($game['trailer_url']);
    
    // Extract video ID if it's full URL
    if (strpos($vid, 'youtube.com') !== false || strpos($vid, 'youtu.be') !== false) {
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $vid, $matches);
        $cleanId = $matches[1] ?? $vid;
    } else {
        $cleanId = $vid;
    }

    $oembedUrl = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=" . urlencode($cleanId) . "&format=json";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $oembedUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = 'OK';
    $details = '';
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        $videoTitle = $data['title'] ?? 'N/A';
        $details = "HTTP 200 - " . $videoTitle;
    } else {
        $status = 'ERROR';
        $details = "HTTP " . $httpCode . " - Video unavailable/deleted/private or invalid ID";
    }
    
    $results[] = [
        'id' => $id,
        'title' => $title,
        'trailer_url' => $vid,
        'clean_id' => $cleanId,
        'status' => $status,
        'http_code' => $httpCode,
        'details' => $details
    ];
    
    echo sprintf("[%s] ID %2d: %-35s | Video: %-15s | %s\n", $status, $id, substr($title, 0, 35), $cleanId, $details);
}

echo "\nSummary of Broken Videos:\n";
$broken = array_filter($results, fn($r) => $r['status'] === 'ERROR');
if (empty($broken)) {
    echo "No broken videos found.\n";
} else {
    foreach ($broken as $b) {
        echo "- Game #{$b['id']} ({$b['title']}): {$b['trailer_url']} (HTTP {$b['http_code']})\n";
    }
}
