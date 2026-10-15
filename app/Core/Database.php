<?php
/**
 * Stonk Games Store - Database Core
 * Design Pattern: Singleton Pattern
 * Driver: PDO (PHP Data Objects) with 100% Prepared Statements Safe
 * Charset: 100% UTF-8 Unicode (utf8mb4_unicode_ci)
 */

class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    /**
     * Khoi tao ket noi CSDL PDO an toan trong constructor rieng tu (Private Constructor)
     */
    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Nem Exception khi co loi truy van
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mac dinh tra ve mang ket hop
            PDO::ATTR_EMULATE_PREPARES   => false,                  // 100% Prepared Statements thuc thu
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE " . (defined('DB_COLLATION') ? DB_COLLATION : 'utf8mb4_unicode_ci')
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("<h3>Loi ket noi Co so du lieu:</h3><p>Khong the ket noi den MySQL voi co che PDO Singleton. Vui long kiem tra lai dich vu MySQL tren XAMPP.</p><pre>" . htmlspecialchars($e->getMessage()) . "</pre>");
        }
    }

    /**
     * Ngan chan sao chep doi tuong (Clone)
     */
    private function __clone() {}

    /**
     * Ngan chan Deserialize
     */
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * Lay thuc the duy nhat cua Database (Singleton Instance)
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lay doi tuong ket noi PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * Shortcut tinh de lay truc tiep ket noi PDO
     */
    public static function pdo(): PDO {
        return self::getInstance()->getConnection();
    }
}
