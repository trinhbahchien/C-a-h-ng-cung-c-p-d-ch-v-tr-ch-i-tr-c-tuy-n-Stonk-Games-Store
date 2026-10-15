<?php
$html = file_get_contents('http://localhost/StonkGamesStore/');
preg_match_all('/(http:\/\/localhost\/StonkGamesStore\/public\/assets\/uploads\/games\/[a-zA-Z0-9_\-\.]+\.jpg)/', $html, $m);
$uniqueCovers = array_unique($m[1]);
echo "Homepage has " . count($uniqueCovers) . " unique local game poster URLs:\n";
foreach ($uniqueCovers as $c) {
    echo " - " . basename($c) . "\n";
}
