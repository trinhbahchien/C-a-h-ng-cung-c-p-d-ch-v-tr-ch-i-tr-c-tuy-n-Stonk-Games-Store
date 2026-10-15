<?php
require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    $updates = [
        2  => ['title' => 'Cyberpunk 2077',           'trailer_url' => 'cCkYB6yZ8VI'],
        9  => ['title' => 'The Witcher 3: Wild Hunt',  'trailer_url' => 'XHrskkHf958'],
        11 => ['title' => 'Sekiro: Shadows Die Twice', 'trailer_url' => '7Po7INInqzE'],
        16 => ['title' => 'Dark Souls III',            'trailer_url' => '_zDZYrIUgKE'],
    ];
    
    $stmt = $db->prepare("UPDATE games SET trailer_url = :trailer_url WHERE id = :id");
    
    echo "Updating broken/restricted trailer URLs in database...\n";
    foreach ($updates as $id => $info) {
        $stmt->execute([
            ':trailer_url' => $info['trailer_url'],
            ':id'          => $id
        ]);
        echo "Updated Game #{$id} ({$info['title']}) => trailer_url = '{$info['trailer_url']}'\n";
    }
    
    echo "\nDatabase update completed successfully.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
