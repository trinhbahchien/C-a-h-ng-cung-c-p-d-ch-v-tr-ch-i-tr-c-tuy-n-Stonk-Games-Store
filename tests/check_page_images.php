<?php
$urls = [
    'Homepage' => 'http://localhost/StonkGamesStore/',
    'Catalog'  => 'http://localhost/StonkGamesStore/games',
    'Elden Ring Detail' => 'http://localhost/StonkGamesStore/game/detail?id=1',
    'Cyberpunk Detail' => 'http://localhost/StonkGamesStore/game/detail?id=2'
];

foreach ($urls as $pageName => $pageUrl) {
    echo "=== Kiểm tra trang: $pageName ($pageUrl) ===\n";
    $html = file_get_contents($pageUrl);
    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $html, $matches);
    $images = array_unique($matches[1]);
    
    $okCount = 0;
    $failCount = 0;
    foreach ($images as $img) {
        if (strpos($img, 'data:') === 0) continue;
        
        $ch = curl_init($img);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($code === 200) {
            $okCount++;
        } else {
            $failCount++;
            echo "  [FAIL $code] $img\n";
        }
    }
    echo "  Kết quả: $okCount ảnh OK, $failCount ảnh lỗi.\n\n";
}
