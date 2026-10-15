<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/Game.php';

$gameModel = new Game();
$games = $gameModel->getAll(['status' => 1, 'limit' => 50]);
echo "GameModel::getAll() returned " . count($games) . " active games:\n";
foreach ($games as $g) {
    echo "- #{$g['id']}: {$g['title']} => {$g['cover_image']}\n";
}
