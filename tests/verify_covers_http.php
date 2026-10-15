<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance()->getConnection();
$games = $db->query('SELECT id, title, cover_image FROM games WHERE id <= 18 ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);

echo "Verifying all 18 game cover URLs via local web server (Apache):\n\n";

$allOk = true;

foreach ($games as $g) {
    $url = get_game_cover_url($g['cover_image']);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $ok = ($code === 200);
    if (!$ok) $allOk = false;
    
    echo sprintf("[%s] Game #%02d: %-32s | %s => HTTP %d\n",
        $ok ? 'OK' : 'FAIL',
        $g['id'],
        $g['title'],
        $url,
        $code
    );
}

echo "\nResult: " . ($allOk ? "ALL 18 GAME POSTERS RETURN HTTP 200 (100% LOCAL & AVAILABLE)!" : "SOME POSTERS FAILED!") . "\n";
