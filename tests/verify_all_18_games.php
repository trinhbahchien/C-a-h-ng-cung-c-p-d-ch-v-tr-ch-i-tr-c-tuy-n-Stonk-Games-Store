<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$proposedUpdates = [
    2  => 'cCkYB6yZ8VI', // Cyberpunk 2077 Next Gen Update Launch Trailer
    9  => 'XHrskkHf958', // The Witcher 3: Wild Hunt Official Launch Trailer
    11 => '7Po7INInqzE', // Sekiro: Shadows Die Twice Reveal Trailer
    16 => '_zDZYrIUgKE', // Dark Souls III Opening Cinematic Trailer
];

$db = Database::getInstance()->getConnection();
$games = $db->query("SELECT id, title, trailer_url FROM games ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "Testing all 18 games with proposed video updates...\n\n";

$allPassed = true;

foreach ($games as $g) {
    $id = $g['id'];
    $title = $g['title'];
    $vid = $proposedUpdates[$id] ?? $g['trailer_url'];
    $isUpdated = isset($proposedUpdates[$id]);
    
    $url = "https://www.youtube.com/watch?v={$vid}";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $familyFriendly = 'false';
    if (preg_match('/<meta itemprop="isFamilyFriendly" content="([^"]+)"/i', $html, $m)) {
        $familyFriendly = $m[1];
    } elseif (preg_match('/"isFamilyFriendly":\s*(true|false)/i', $html, $m)) {
        $familyFriendly = $m[1];
    }
    
    $loginReq = (strpos($html, '"status":"LOGIN_REQUIRED"') !== false || strpos($html, 'Sign in to confirm your age') !== false);
    $ytTitle = '';
    if (preg_match('/<title>(.*?)<\/title>/i', $html, $m)) {
        $ytTitle = html_entity_decode($m[1], ENT_QUOTES);
    }
    
    $passed = ($httpCode === 200 && $familyFriendly === 'true' && !$loginReq);
    if (!$passed) {
        $allPassed = false;
    }
    
    echo sprintf("[%s] #%02d: %-32s | Vid: %-12s | FF: %-5s | LoginReq: %-3s | %s %s\n",
        $passed ? 'PASS' : 'FAIL',
        $id,
        $title,
        $vid,
        $familyFriendly,
        $loginReq ? 'YES' : 'NO',
        $isUpdated ? '[NEW]' : '     ',
        substr($ytTitle, 0, 40)
    );
}

echo "\nResult: " . ($allPassed ? "ALL 18 GAMES PASS 100%!" : "SOME GAMES FAILED!") . "\n";
