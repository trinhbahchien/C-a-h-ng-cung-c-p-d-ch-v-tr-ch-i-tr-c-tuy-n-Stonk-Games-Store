<?php
/**
 * Stonk Games Store - Front Controller Router
 * Phân tích URL và điều hướng chính xác đến Controller & Action
 * Tương thích cả Clean URL (.htaccess) và Query Parameter (?url=...)
 */

class App {
    protected string $controller = DEFAULT_CONTROLLER;
    protected string $action = DEFAULT_ACTION;
    protected array $params = [];

    /**
     * Bảng ánh xạ 24 Route nghiệp vụ theo đặc tả tài liệu kiến trúc PROJECT_ARCHITECTURE.md
     */
    private array $routes = [
        ''                      => ['HomeController', 'index'],
        'home'                  => ['HomeController', 'index'],
        'catalog'               => ['GameController', 'catalog'],
        'games'                 => ['GameController', 'catalog'],
        'game/detail'           => ['GameController', 'detail'],
        'games/detail'          => ['GameController', 'detail'],
        'rankings'              => ['RankingController', 'index'],
        'news'                  => ['NewsController', 'index'],
        'news/detail'           => ['NewsController', 'detail'],
        'cart'                  => ['CartController', 'index'],
        'cart/add'              => ['CartController', 'add'],
        'cart/remove'           => ['CartController', 'remove'],
        'cart/clear'            => ['CartController', 'clear'],
        'checkout'              => ['CartController', 'checkout'],
        'cart/checkout'         => ['CartController', 'checkout'],
        'cart/process'          => ['CartController', 'processCheckout'],
        'cart/processCheckout'  => ['CartController', 'processCheckout'],
        'cart/process-checkout' => ['CartController', 'processCheckout'],
        'cart/success'          => ['CartController', 'success'],
        'auth/login'            => ['AuthController', 'login'],
        'auth/register'         => ['AuthController', 'register'],
        'auth/logout'           => ['AuthController', 'logout'],
        'profile'               => ['AuthController', 'profile'],
        'profile/update'        => ['AuthController', 'updateProfile'],
        'auth/profile'          => ['AuthController', 'profile'],
        'orders'                => ['OrderController', 'history'],
        'order/history'         => ['OrderController', 'history'],
        'order/detail'          => ['OrderController', 'detail'],
        'order/invoice'         => ['OrderController', 'invoice'],
        'order/lookup'          => ['OrderController', 'lookup'],
        'admin'                 => ['AdminController', 'dashboard'],
        'admin/dashboard'       => ['AdminController', 'dashboard'],
        'admin/games'           => ['AdminController', 'games'],
        'admin/games/create'    => ['AdminController', 'createGame'],
        'admin/games/edit'      => ['AdminController', 'editGame'],
        'admin/games/delete'    => ['AdminController', 'deleteGame'],
        'admin/news'            => ['AdminController', 'news'],
        'admin/news/create'     => ['AdminController', 'createNews'],
        'admin/news/edit'       => ['AdminController', 'editNews'],
        'admin/news/delete'     => ['AdminController', 'deleteNews'],
        'admin/categories'      => ['AdminController', 'categories'],
        'admin/categories/create' => ['AdminController', 'createCategory'],
        'admin/categories/edit'   => ['AdminController', 'editCategory'],
        'admin/categories/delete' => ['AdminController', 'deleteCategory'],
        'admin/developers'      => ['AdminController', 'developers'],
        'admin/developers/create' => ['AdminController', 'createDeveloper'],
        'admin/developers/edit'   => ['AdminController', 'editDeveloper'],
        'admin/developers/delete' => ['AdminController', 'deleteDeveloper'],
        'admin/users'           => ['AdminController', 'users'],
        'admin/users/create'    => ['AdminController', 'createUser'],
        'admin/users/edit'      => ['AdminController', 'editUser'],
        'admin/users/delete'    => ['AdminController', 'deleteUser'],
        'admin/orders'          => ['AdminController', 'orders'],
        'admin/orders/detail'   => ['AdminController', 'orderDetail'],
        'admin/orders/status'   => ['AdminController', 'updateOrderStatus'],
    ];

    public function __construct() {
        $url = $this->parseUrl();
        $matched = false;

        // 1. Kiểm tra đối sánh trong Bảng định tuyến rõ ràng (Explicit Routing Table)
        if (isset($this->routes[$url])) {
            [$this->controller, $this->action] = $this->routes[$url];
            $matched = true;
        } else {
            // 2. Nếu không có trong bảng tĩnh, thử phân giải động theo cấu trúc: Controller/Action/Params
            $segments = explode('/', $url);
            if (!empty($segments[0])) {
                $possibleController = ucfirst($segments[0]) . 'Controller';
                $controllerFile = APP_PATH . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $possibleController . '.php';
                
                if (file_exists($controllerFile)) {
                    $this->controller = $possibleController;
                    unset($segments[0]);

                    if (isset($segments[1]) && !empty($segments[1])) {
                        $this->action = $segments[1];
                        unset($segments[1]);
                    }
                    $this->params = $segments ? array_values($segments) : [];
                    $matched = true;
                }
            }
        }

        // 3. Nạp và thực thi Controller
        $this->dispatch();
    }

    /**
     * Phân tích và làm sạch chuỗi URL yêu cầu
     */
    private function parseUrl(): string {
        $url = '';

        // Ưu tiên 1: Lấy từ biến $_GET['url'] do .htaccess hoặc query string truyền vào
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
        } else {
            // Ưu tiên 2: Phân tích trực tiếp từ $_SERVER['REQUEST_URI']
            $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');

            if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
                $url = substr($requestUri, strlen($scriptName));
            } else {
                $baseFolder = '/StonkGamesStore';
                if (strpos($requestUri, $baseFolder) === 0) {
                    $url = substr($requestUri, strlen($baseFolder));
                } else {
                    $url = $requestUri;
                }
            }

            // Loại bỏ 'public/' nếu còn trong URL
            $url = preg_replace('#^/?public/#i', '', $url);
            $url = trim($url, '/');
        }

        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Khởi tạo Controller và gọi Action tương ứng
     */
    private function dispatch(): void {
        $controllerFile = APP_PATH . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $this->controller . '.php';

        if (!file_exists($controllerFile)) {
            $this->render404("Không tìm thấy Controller: " . htmlspecialchars($this->controller));
            return;
        }

        require_once $controllerFile;

        if (!class_exists($this->controller)) {
            $this->render404("Không tìm thấy Class Controller: " . htmlspecialchars($this->controller));
            return;
        }

        $controllerInstance = new $this->controller();

        if (!method_exists($controllerInstance, $this->action)) {
            $this->render404("Không tìm thấy Action <code>{$this->action}</code> trong Controller <code>{$this->controller}</code>");
            return;
        }

        // Gọi Action với các tham số nếu có
        call_user_func_array([$controllerInstance, $this->action], $this->params);
    }

    /**
     * Hiển thị trang 404 Gaming thân thiện
     */
    private function render404(string $message): void {
        http_response_code(404);
        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Trang không tồn tại | Stonk Games Store</title>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    background-color: #0b0e14;
                    color: #e0e6ed;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    text-align: center;
                }
                .error-box {
                    max-width: 600px;
                    padding: 40px;
                    background: rgba(22, 27, 34, 0.85);
                    border: 1px solid rgba(0, 242, 254, 0.2);
                    border-radius: 12px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
                }
                h1 {
                    font-size: 72px;
                    margin: 0;
                    color: #00f2fe;
                    text-shadow: 0 0 20px rgba(0, 242, 254, 0.5);
                }
                h2 { margin: 10px 0 20px; font-size: 24px; color: #fff; }
                p { color: #8b949e; line-height: 1.6; }
                .btn {
                    display: inline-block;
                    margin-top: 25px;
                    padding: 12px 28px;
                    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
                    color: #0b0e14;
                    text-decoration: none;
                    font-weight: bold;
                    border-radius: 6px;
                    transition: transform 0.2s, box-shadow 0.2s;
                }
                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(0, 242, 254, 0.4);
                }
            </style>
        </head>
        <body>
            <div class="error-box">
                <h1>404</h1>
                <h2>TRANG KHÔNG TỒN TẠI</h2>
                <p><?= $message ?></p>
                <a href="<?= BASE_URL ?>" class="btn">VỀ TRANG CHỦ</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
