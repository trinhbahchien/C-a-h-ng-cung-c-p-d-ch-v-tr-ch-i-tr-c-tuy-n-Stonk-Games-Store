<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance()->getConnection();
$games = $db->query("SELECT id, title, trailer_url FROM games ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "Checking Age Restrictions and Embed Status on YouTube...\n\n";

foreach ($games as $g) {
    $vid = $g['trailer_url'];
    $url = "https://www.youtube.com/watch?v={$vid}";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    
    $isFamilyFriendly = 'Unknown';
    if (preg_match('/<meta itemprop="isFamilyFriendly" content="([^"]+)"/i', $html, $m)) {
        $isFamilyFriendly = $m[1];
    } elseif (preg_match('/"isFamilyFriendly":\s*(true|false)/i', $html, $m)) {
        $isFamilyFriendly = $m[1];
    }
    
    $unplayable = false;
    $reason = '';
    if (preg_match('/"status":"(UNPLAYABLE|LOGIN_REQUIRED|ERROR)"/i', $html, $m)) {
        $reason = $m[1];
    }
    
    if (strpos($html, 'Sign in to confirm your age') !== false) {
        $reason .= ' (Sign in to confirm your age)';
    }
    if (strpos($html, 'Playback on other websites has been disabled') !== false) {
        $reason .= ' (Embed disabled)';
    }

    echo sprintf("ID %2d: %-32s | Vid: %-12s | FamilyFriendly: %-6s | Reason: %s\n",
        $g['id'], $g['title'], $vid, $isFamilyFriendly, $reason ?: 'None (Clean)'
    );
}
