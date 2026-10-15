<?php
/**
 * Stonk Games Store - Configuration File
 * Pure PHP MVC System Config
 * Quy tac: Khong su dung icon trong ten module hoac nhan giao dien
 */

// Bat thong bao loi trong moi truong phat trien (Development mode)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Thiet lap mui gio Viet Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Dinh nghia cac duong dan thu muc tuyet doi
define('ROOT_PATH', dirname(__DIR__, 2));
define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
define('UPLOADS_PATH', PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads');

// Tu dong nhan dien Base URL linh hoat cho moi may tinh va moi thu muc tren XAMPP
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$baseFolder = trim(preg_replace('#/public$#', '', $scriptDir), '/');

if (!empty($baseFolder)) {
    define('BASE_URL', $protocol . $host . '/' . $baseFolder);
} else {
    define('BASE_URL', $protocol . $host);
}

// URL tai nguyen tinh (Static Assets & Uploads)
define('ASSETS_URL', BASE_URL . '/public/assets');
define('UPLOADS_URL', BASE_URL . '/public/assets/uploads');
define('MEDIA_URL', BASE_URL . '/public/assets/media');

// Thong so Website
define('APP_NAME', 'Stonk Games Store');
define('APP_VERSION', '1.0.0');

// Cau hinh Co so du lieu MySQL (PDO) voi UTF-8 Unicode
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'stonk_games_store');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// Cau hinh Controller & Action mac dinh
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_ACTION', 'index');

/**
 * Helper tra ve URL day du cua anh bia game (Tu dong nhan dien URL online https:// hoac anh upload local)
 */
function get_game_cover_url(?string $image): string {
    if (empty($image)) {
        return UPLOADS_URL . '/games/default_game.jpg';
    }
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0 || strpos($image, '//') === 0) {
        return $image;
    }
    $uploadPath = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'games' . DIRECTORY_SEPARATOR . $image;
    if (file_exists($uploadPath)) {
        return UPLOADS_URL . '/games/' . $image;
    }
    $mediaPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'games' . DIRECTORY_SEPARATOR . $image;
    if (file_exists($mediaPath)) {
        return MEDIA_URL . '/img/games/' . $image;
    }
    return UPLOADS_URL . '/games/' . $image;
}

/**
 * Helper tra ve URL day du cua anh bai viet tin tuc
 */
function get_news_image_url(?string $image): string {
    if (empty($image)) {
        return MEDIA_URL . '/img/news/news_sale.jpg';
    }
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0 || strpos($image, '//') === 0) {
        return $image;
    }
    $uploadPath = UPLOADS_PATH . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR . $image;
    if (file_exists($uploadPath)) {
        return UPLOADS_URL . '/news/' . $image;
    }
    $mediaPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR . $image;
    if (file_exists($mediaPath)) {
        return MEDIA_URL . '/img/news/' . $image;
    }
    return UPLOADS_URL . '/news/' . $image;
}
