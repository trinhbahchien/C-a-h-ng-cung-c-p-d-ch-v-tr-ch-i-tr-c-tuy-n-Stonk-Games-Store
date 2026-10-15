<?php
/**
 * Stonk Games Store - Language and Localization Service
 * Chuyen ngu he thong (System UI) linh hoat giua Tieng Viet va Tieng Anh.
 * Noi dung Games va Tin tuc luon hien thi bang tieng Anh chuan nhu yeu cau.
 * Tuyet doi khong su dung icon hay emoji.
 */

class Lang {
    private static ?array $translations = null;
    private static string $currentLang = 'vi';

    /**
     * Khoi tao ngon ngu tu Session hoac tham so yeu cau
     */
    public static function init(): void {
        self::$currentLang = 'vi';
        unset($_SESSION['lang']);

        $langFile = APP_PATH . DIRECTORY_SEPARATOR . 'Lang' . DIRECTORY_SEPARATOR . 'vi.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        } else {
            self::$translations = [];
        }
    }

    /**
     * Lay chuoi chuyen ngu he thong theo key
     */
    public static function get(string $key, array $replace = []): string {
        if (self::$translations === null) {
            self::init();
        }

        $text = self::$translations[$key] ?? $key;

        foreach ($replace as $placeholder => $value) {
            $text = str_replace(':' . $placeholder, (string)$value, $text);
        }

        return $text;
    }

    /**
     * Lay ma ngon ngu hien tai ('vi' hoac 'en')
     */
    public static function current(): string {
        if (self::$translations === null) {
            self::init();
        }
        return self::$currentLang;
    }
}

/**
 * Ham tro giup toan cuc (Global Helper) de goi gon trong Views
 */
if (!function_exists('__')) {
    function __(string $key, array $replace = []): string {
        return Lang::get($key, $replace);
    }
}
