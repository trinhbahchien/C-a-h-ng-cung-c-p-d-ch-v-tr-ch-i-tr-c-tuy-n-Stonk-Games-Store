<?php
/**
 * Stonk Games Store - Base Controller
 * Quản lý kết nối giữa Model và View, nạp layout và phản hồi HTTP
 */

abstract class Controller {

    /**
     * Nạp và render giao diện View kèm Layout
     * @param string $viewPath Đường dẫn view tính từ app/Views (ví dụ: 'home/index' hoặc 'games/detail')
     * @param array $data Dữ liệu truyền từ Controller sang View
     * @param string|null $layout Tên layout ('main', 'admin' hoặc null nếu không dùng layout)
     */
    protected function view(string $viewPath, array $data = [], ?string $layout = 'main'): void {
        // Trích xuất mảng dữ liệu thành các biến cục bộ
        extract($data);

        $viewFile = APP_PATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            die("<h3>Lỗi Render View:</h3><p>Không tìm thấy tệp giao diện: <code>" . htmlspecialchars($viewFile) . "</code></p>");
        }

        // Bắt đầu vùng đệm bộ nhớ (Output Buffering) để render View con
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Nếu có layout bao ngoài
        if ($layout !== null) {
            $layoutFile = APP_PATH . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
            } else {
                // Fallback nếu chưa tạo layout: in trực tiếp nội dung view
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Khởi tạo và nạp Model
     * @param string $modelName Tên Model (ví dụ: 'Game', 'News', 'User')
     */
    protected function model(string $modelName): object {
        $modelFile = APP_PATH . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . $modelName . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            if (class_exists($modelName)) {
                return new $modelName();
            }
        }

        die("<h3>Lỗi nạp Model:</h3><p>Không tìm thấy Model: <code>" . htmlspecialchars($modelName) . "</code> tại <code>" . htmlspecialchars($modelFile) . "</code></p>");
    }

    /**
     * Phản hồi dạng JSON (phục vụ AJAX hoặc API)
     */
    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Chuyển hướng trang (Redirect)
     */
    protected function redirect(string $path): void {
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            header("Location: " . $path);
        } else {
            $url = BASE_URL . '/' . ltrim($path, '/');
            header("Location: " . $url);
        }
        exit;
    }

    /**
     * Kiểm tra trạng thái đăng nhập
     */
    protected function isLoggedIn(): bool {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Kiểm tra quyền quản trị Admin
     */
    protected function isAdmin(): bool {
        return $this->isLoggedIn() && ($_SESSION['user']['role'] ?? '') === 'admin';
    }

    /**
     * Lấy thông tin user hiện tại trong session
     */
    protected function currentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Lưu thông báo nhanh (Flash message)
     */
    protected function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type, // 'success', 'danger', 'warning', 'info'
            'message' => $message
        ];
    }
}
