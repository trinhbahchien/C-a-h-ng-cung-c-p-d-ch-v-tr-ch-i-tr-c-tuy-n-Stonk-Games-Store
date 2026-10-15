<?php
/**
 * Stonk Games Store - Single Entry Point (Front Controller)
 * Khởi động toàn bộ ứng dụng Pure PHP MVC
 */

// 1. Khởi động phiên làm việc (Session)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Nạp cấu hình hệ thống
require_once dirname(__DIR__) . '/app/Config/config.php';

// 3. Cơ chế tự động nạp lớp (Autoloading) không dùng Composer
spl_autoload_register(function ($className) {
    $directories = [
        APP_PATH . '/Core/',
        APP_PATH . '/Controllers/',
        APP_PATH . '/Models/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 4. Khoi tao Dich vu Chuyen ngu He thong
require_once APP_PATH . '/Core/Lang.php';
Lang::init();

// 5. Khởi chạy Router ứng dụng
$app = new App();
