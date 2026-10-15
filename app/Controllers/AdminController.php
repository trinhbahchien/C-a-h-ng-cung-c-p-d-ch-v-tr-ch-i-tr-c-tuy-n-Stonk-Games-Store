<?php
/**
 * Stonk Games Store - Admin Controller
 * Trung tâm điều hành toàn bộ hệ thống quản trị: Games, Tin tức, Thể loại, Studio, Người dùng, Đơn hàng
 */

class AdminController extends Controller {

    public function __construct() {
        // Kiểm tra quyền quản trị viên (Auth Middleware)
        if (!$this->isAdmin()) {
            $this->setFlash('danger', 'Quyền truy cập bị từ chối. Vui lòng đăng nhập tài khoản quản trị viên.');
            $this->redirect('auth/login');
        }
    }

    /**
     * Bảng điều khiển tổng quan (Dashboard)
     */
    public function dashboard(): void {
        $gameModel = $this->model('Game');
        $newsModel = $this->model('News');
        $userModel = $this->model('User');
        $orderModel = $this->model('Order');

        $stats = [
            'total_games'   => $gameModel->count(),
            'total_news'    => $newsModel->count(),
            'total_users'   => $userModel->count(),
            'total_orders'  => $orderModel->count(),
            'total_revenue' => $orderModel->getTotalRevenue()
        ];

        $recentOrders = array_slice($orderModel->getAll(), 0, 6);
        $topGames = $gameModel->getTopSellers(5);

        $this->view('admin/dashboard', [
            'pageTitle' => __('admin_dashboard'),
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'topGames' => $topGames
        ], 'admin');
    }

    // =========================================================================
    // 1. QUẢN LÝ TRÒ CHƠI (GAMES CRUD)
    // =========================================================================

    public function games(): void {
        $gameModel = $this->model('Game');
        $catModel = $this->model('Category');
        $devModel = $this->model('Developer');

        $search = trim($_GET['search'] ?? '');
        $categoryId = !empty($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $developerId = !empty($_GET['developer_id']) ? (int)$_GET['developer_id'] : null;

        $filters = [];
        if (!empty($search)) $filters['search'] = $search;
        if (!empty($categoryId)) $filters['category_id'] = $categoryId;
        if (!empty($developerId)) $filters['developer_id'] = $developerId;

        $games = $gameModel->getAll($filters);
        $categories = $catModel->getAll();
        $developers = $devModel->getAll();

        $this->view('admin/games/index', [
            'pageTitle' => __('admin_manage_games'),
            'games' => $games,
            'categories' => $categories,
            'developers' => $developers,
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedDeveloper' => $developerId
        ], 'admin');
    }

    public function createGame(): void {
        $gameModel = $this->model('Game');
        $catModel = $this->model('Category');
        $devModel = $this->model('Developer');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $rawSlug = trim($_POST['slug'] ?? '');
            $slug = !empty($rawSlug) ? $this->makeSlug($rawSlug) : $this->makeSlug($title);
            if (empty($slug)) {
                $slug = 'game-' . time();
            }
            $developerId = (int)($_POST['developer_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $discount = (int)($_POST['discount_percent'] ?? 0);
            $shortDesc = trim($_POST['short_description'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $trailerUrl = $this->extractYouTubeId($_POST['trailer_url'] ?? '');
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $status = isset($_POST['status']) ? 1 : 0;
            $releaseDate = $_POST['release_date'] ?? date('Y-m-d');
            $categoryIds = $_POST['categories'] ?? [];

            // Xử lý tải lên ảnh bìa
            $coverImage = 'default_game.jpg';
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $coverImage = $this->handleUpload($_FILES['cover_image'], 'games');
            } elseif (!empty($_POST['cover_image_text'])) {
                $coverImage = trim($_POST['cover_image_text']);
            }

            $requirements = [
                'min_os'      => trim($_POST['min_os'] ?? 'Windows 10 64-bit'),
                'min_cpu'     => trim($_POST['min_cpu'] ?? 'Intel Core i5 / AMD Ryzen 5'),
                'min_ram'     => trim($_POST['min_ram'] ?? '8 GB RAM'),
                'min_gpu'     => trim($_POST['min_gpu'] ?? 'NVIDIA GTX 1060 / AMD RX 580'),
                'min_storage' => trim($_POST['min_storage'] ?? '50 GB available space'),
                'rec_os'      => trim($_POST['rec_os'] ?? 'Windows 10/11 64-bit'),
                'rec_cpu'     => trim($_POST['rec_cpu'] ?? 'Intel Core i7 / AMD Ryzen 7'),
                'rec_ram'     => trim($_POST['rec_ram'] ?? '16 GB RAM'),
                'rec_gpu'     => trim($_POST['rec_gpu'] ?? 'NVIDIA RTX 3060 / AMD RX 6700 XT'),
                'rec_storage' => trim($_POST['rec_storage'] ?? '50 GB SSD')
            ];

            $gameData = [
                'title'             => $title,
                'slug'              => $slug,
                'developer_id'      => $developerId,
                'price'             => $price,
                'discount_percent'  => $discount,
                'short_description' => $shortDesc,
                'description'       => $desc,
                'cover_image'       => $coverImage,
                'trailer_url'       => $trailerUrl,
                'is_featured'       => $isFeatured,
                'status'            => $status,
                'release_date'      => $releaseDate
            ];

            $gameModel->create($gameData, $categoryIds, $requirements);
            $this->setFlash('success', "Thêm trò chơi '{$title}' thành công!");
            $this->redirect('admin/games');
        }

        $this->view('admin/games/create', [
            'pageTitle' => 'Thêm Trò Chơi Mới',
            'categories' => $catModel->getAll(),
            'developers' => $devModel->getAll()
        ], 'admin');
    }

    public function editGame(): void {
        $gameModel = $this->model('Game');
        $catModel = $this->model('Category');
        $devModel = $this->model('Developer');

        $id = (int)($_GET['id'] ?? 0);
        $game = $gameModel->findById($id);

        if (!$game) {
            $this->setFlash('danger', 'Trò chơi không tồn tại.');
            $this->redirect('admin/games');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $rawSlug = trim($_POST['slug'] ?? '');
            $slug = !empty($rawSlug) ? $this->makeSlug($rawSlug) : $this->makeSlug($title);
            if (empty($slug)) {
                $slug = 'game-' . $id;
            }
            $developerId = (int)($_POST['developer_id'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $discount = (int)($_POST['discount_percent'] ?? 0);
            $shortDesc = trim($_POST['short_description'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $trailerUrl = $this->extractYouTubeId($_POST['trailer_url'] ?? '');
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $status = isset($_POST['status']) ? 1 : 0;
            $releaseDate = $_POST['release_date'] ?? date('Y-m-d');
            $categoryIds = $_POST['categories'] ?? [];

            $coverImage = $game['cover_image'];
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $coverImage = $this->handleUpload($_FILES['cover_image'], 'games');
            } elseif (!empty($_POST['cover_image_text'])) {
                $coverImage = trim($_POST['cover_image_text']);
            }

            $requirements = [
                'min_os'      => trim($_POST['min_os'] ?? 'Windows 10 64-bit'),
                'min_cpu'     => trim($_POST['min_cpu'] ?? 'Intel Core i5 / AMD Ryzen 5'),
                'min_ram'     => trim($_POST['min_ram'] ?? '8 GB RAM'),
                'min_gpu'     => trim($_POST['min_gpu'] ?? 'NVIDIA GTX 1060 / AMD RX 580'),
                'min_storage' => trim($_POST['min_storage'] ?? '50 GB available space'),
                'rec_os'      => trim($_POST['rec_os'] ?? 'Windows 10/11 64-bit'),
                'rec_cpu'     => trim($_POST['rec_cpu'] ?? 'Intel Core i7 / AMD Ryzen 7'),
                'rec_ram'     => trim($_POST['rec_ram'] ?? '16 GB RAM'),
                'rec_gpu'     => trim($_POST['rec_gpu'] ?? 'NVIDIA RTX 3060 / AMD RX 6700 XT'),
                'rec_storage' => trim($_POST['rec_storage'] ?? '50 GB SSD')
            ];

            $gameData = [
                'title'             => $title,
                'slug'              => $slug,
                'developer_id'      => $developerId,
                'price'             => $price,
                'discount_percent'  => $discount,
                'short_description' => $shortDesc,
                'description'       => $desc,
                'cover_image'       => $coverImage,
                'trailer_url'       => $trailerUrl,
                'is_featured'       => $isFeatured,
                'status'            => $status,
                'release_date'      => $releaseDate
            ];

            $gameModel->update($id, $gameData, $categoryIds, $requirements);
            $this->setFlash('success', "Cập nhật trò chơi '{$title}' thành công!");
            $this->redirect('admin/games');
        }

        $this->view('admin/games/edit', [
            'pageTitle' => 'Chỉnh Sửa Trò Chơi',
            'game' => $game,
            'categories' => $catModel->getAll(),
            'developers' => $devModel->getAll()
        ], 'admin');
    }

    public function deleteGame(): void {
        $id = (int)($_GET['id'] ?? 0);
        $gameModel = $this->model('Game');
        $game = $gameModel->findById($id);

        if ($game) {
            $gameModel->delete($id);
            $this->setFlash('success', "Đã xóa trò chơi '{$game['title']}' thành công.");
        } else {
            $this->setFlash('danger', 'Trò chơi không tồn tại.');
        }

        $this->redirect('admin/games');
    }

    // =========================================================================
    // 2. QUẢN LÝ TIN TỨC (NEWS CRUD)
    // =========================================================================

    public function news(): void {
        $newsModel = $this->model('News');
        $newsList = $newsModel->getAll();

        $this->view('admin/news/index', [
            'pageTitle' => __('admin_manage_news'),
            'newsList' => $newsList
        ], 'admin');
    }

    public function createNews(): void {
        $newsModel = $this->model('News');
        $gameModel = $this->model('Game');
        $catModel = $this->model('Category');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: $this->makeSlug($title);
            $type = $_POST['type'] ?? 'system';
            $gameId = !empty($_POST['game_id']) ? (int)$_POST['game_id'] : null;
            $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $content = trim($_POST['content'] ?? '');

            $image = 'news_default.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->handleUpload($_FILES['image'], 'news');
            } elseif (!empty($_POST['image_text'])) {
                $image = trim($_POST['image_text']);
            }

            $newsData = [
                'title'       => $title,
                'slug'        => $slug,
                'type'        => $type,
                'game_id'     => $gameId,
                'category_id' => $catId,
                'content'     => $content,
                'image'       => $image
            ];

            $newsModel->create($newsData);
            $this->setFlash('success', "Đăng bài viết tin tức '{$title}' thành công!");
            $this->redirect('admin/news');
        }

        $this->view('admin/news/create', [
            'pageTitle' => 'Đăng Bài Tin Tức Mới',
            'games' => $gameModel->getAll(),
            'categories' => $catModel->getAll()
        ], 'admin');
    }

    public function editNews(): void {
        $newsModel = $this->model('News');
        $gameModel = $this->model('Game');
        $catModel = $this->model('Category');

        $id = (int)($_GET['id'] ?? 0);
        $article = $newsModel->findById($id);

        if (!$article) {
            $this->setFlash('danger', 'Bài viết không tồn tại.');
            $this->redirect('admin/news');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: $this->makeSlug($title);
            $type = $_POST['type'] ?? 'system';
            $gameId = !empty($_POST['game_id']) ? (int)$_POST['game_id'] : null;
            $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $content = trim($_POST['content'] ?? '');

            $image = $article['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->handleUpload($_FILES['image'], 'news');
            } elseif (!empty($_POST['image_text'])) {
                $image = trim($_POST['image_text']);
            }

            $newsData = [
                'title'       => $title,
                'slug'        => $slug,
                'type'        => $type,
                'game_id'     => $gameId,
                'category_id' => $catId,
                'content'     => $content,
                'image'       => $image
            ];

            $newsModel->update($id, $newsData);
            $this->setFlash('success', "Cập nhật bài tin tức '{$title}' thành công!");
            $this->redirect('admin/news');
        }

        $this->view('admin/news/edit', [
            'pageTitle' => 'Chỉnh Sửa Bài Tin Tức',
            'article' => $article,
            'games' => $gameModel->getAll(),
            'categories' => $catModel->getAll()
        ], 'admin');
    }

    public function deleteNews(): void {
        $id = (int)($_GET['id'] ?? 0);
        $newsModel = $this->model('News');
        $article = $newsModel->findById($id);

        if ($article) {
            $newsModel->delete($id);
            $this->setFlash('success', "Đã xóa bài viết '{$article['title']}' thành công.");
        } else {
            $this->setFlash('danger', 'Bài viết không tồn tại.');
        }

        $this->redirect('admin/news');
    }

    // =========================================================================
    // 3. QUẢN LÝ THỂ LOẠI (CATEGORIES CRUD)
    // =========================================================================

    public function categories(): void {
        $catModel = $this->model('Category');
        $categories = $catModel->getAll();

        $this->view('admin/categories/index', [
            'pageTitle' => __('admin_manage_cats'),
            'categories' => $categories
        ], 'admin');
    }

    public function createCategory(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: $this->makeSlug($name);
            $description = trim($_POST['description'] ?? '');

            if (!empty($name)) {
                $catModel = $this->model('Category');
                $catModel->create([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description
                ]);
                $this->setFlash('success', "Thêm thể loại '{$name}' thành công!");
            }
        }
        $this->redirect('admin/categories');
    }

    public function editCategory(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: $this->makeSlug($name);
            $description = trim($_POST['description'] ?? '');

            if ($id > 0 && !empty($name)) {
                $catModel = $this->model('Category');
                $catModel->update($id, [
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description
                ]);
                $this->setFlash('success', "Cập nhật thể loại '{$name}' thành công!");
            }
        }
        $this->redirect('admin/categories');
    }

    public function deleteCategory(): void {
        $id = (int)($_GET['id'] ?? 0);
        $catModel = $this->model('Category');
        if ($id > 0) {
            $catModel->delete($id);
            $this->setFlash('success', 'Xóa thể loại thành công.');
        }
        $this->redirect('admin/categories');
    }

    // =========================================================================
    // 4. QUẢN LÝ STUDIO NHÀ PHÁT TRIỂN (DEVELOPERS CRUD)
    // =========================================================================

    public function developers(): void {
        $devModel = $this->model('Developer');
        $developers = $devModel->getAll();

        $this->view('admin/developers/index', [
            'pageTitle' => __('admin_manage_devs'),
            'developers' => $developers
        ], 'admin');
    }

    public function createDeveloper(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: $this->makeSlug($name);
            $logo = trim($_POST['logo'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (!empty($name)) {
                $devModel = $this->model('Developer');
                $devModel->create([
                    'name' => $name,
                    'slug' => $slug,
                    'logo' => $logo,
                    'description' => $description
                ]);
                $this->setFlash('success', "Thêm studio '{$name}' thành công!");
            }
        }
        $this->redirect('admin/developers');
    }

    public function editDeveloper(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '') ?: $this->makeSlug($name);
            $logo = trim($_POST['logo'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($id > 0 && !empty($name)) {
                $devModel = $this->model('Developer');
                $devModel->update($id, [
                    'name' => $name,
                    'slug' => $slug,
                    'logo' => $logo,
                    'description' => $description
                ]);
                $this->setFlash('success', "Cập nhật studio '{$name}' thành công!");
            }
        }
        $this->redirect('admin/developers');
    }

    public function deleteDeveloper(): void {
        $id = (int)($_GET['id'] ?? 0);
        $devModel = $this->model('Developer');
        if ($id > 0) {
            $devModel->delete($id);
            $this->setFlash('success', 'Xóa studio thành công.');
        }
        $this->redirect('admin/developers');
    }

    // =========================================================================
    // 5. QUẢN LÝ TÀI KHOẢN NGƯỜI DÙNG (USERS CRUD)
    // =========================================================================

    public function users(): void {
        $userModel = $this->model('User');
        $users = $userModel->getAll();

        $this->view('admin/users/index', [
            'pageTitle' => __('admin_manage_users'),
            'users' => $users
        ], 'admin');
    }

    public function createUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'user';

            if (!empty($username) && !empty($email) && !empty($password)) {
                $userModel = $this->model('User');
                if ($userModel->findByUsername($username)) {
                    $this->setFlash('danger', 'Tên đăng nhập đã tồn tại trên hệ thống.');
                } elseif ($userModel->findByEmail($email)) {
                    $this->setFlash('danger', 'Địa chỉ email đã được đăng ký bởi tài khoản khác.');
                } else {
                    $userModel->create([
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'role' => $role
                    ]);
                    $this->setFlash('success', "Tạo tài khoản '{$username}' thành công!");
                }
            }
        }
        $this->redirect('admin/users');
    }

    public function editUser(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? ($_POST['hidden_role'] ?? 'user');

            if ($id > 0 && !empty($username) && !empty($email)) {
                $userModel = $this->model('User');
                $targetUser = $userModel->findById($id);

                if ($targetUser) {
                    // Bảo vệ tài khoản admin: Tuyệt đối không cho phép đổi role của tài khoản admin
                    if ($targetUser['username'] === 'admin' || (int)$targetUser['id'] === 1) {
                        $role = 'admin'; // Luôn khóa cố định là admin
                        $username = 'admin'; // Giữ nguyên username gốc admin
                    }

                    $updateData = [
                        'username' => $username,
                        'email' => $email,
                        'role' => $role
                    ];
                    if (!empty($password)) {
                        $updateData['password'] = $password;
                    }
                    $userModel->update($id, $updateData);
                    $this->setFlash('success', "Cập nhật tài khoản '{$username}' thành công!");
                }
            }
        }
        $this->redirect('admin/users');
    }

    public function deleteUser(): void {
        $id = (int)($_GET['id'] ?? 0);
        $userModel = $this->model('User');
        $targetUser = $userModel->findById($id);

        if ($targetUser && ($targetUser['username'] === 'admin' || (int)$targetUser['id'] === 1)) {
            $this->setFlash('danger', 'Không thể xóa tài khoản Admin mặc định của hệ thống.');
        } elseif ($id === (int)($_SESSION['user']['id'] ?? 0)) {
            $this->setFlash('danger', 'Không thể tự xóa tài khoản đang đăng nhập.');
        } elseif ($id > 0) {
            $userModel->delete($id);
            $this->setFlash('success', 'Tài khoản đã được chuyển sang trạng thái đã xóa (Soft Delete thành công, bảo toàn dữ liệu).');
        }

        $this->redirect('admin/users');
    }

    // =========================================================================
    // 6. QUẢN LÝ ĐƠN HÀNG VÀ CD-KEY (ORDERS & KEYS)
    // =========================================================================

    public function orders(): void {
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAll();

        $this->view('admin/orders/index', [
            'pageTitle' => __('admin_manage_orders'),
            'orders' => $orders
        ], 'admin');
    }

    public function orderDetail(): void {
        $id = (int)($_GET['id'] ?? 0);
        $orderModel = $this->model('Order');
        $order = $orderModel->findById($id);

        if (!$order) {
            $this->setFlash('danger', 'Đơn hàng không tồn tại.');
            $this->redirect('admin/orders');
        }

        $this->view('admin/orders/detail', [
            'pageTitle' => "Chi Tiết Đơn Hàng #{$id}",
            'order' => $order
        ], 'admin');
    }

    public function updateOrderStatus(): void {
        $id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
        $status = $_GET['status'] ?? ($_POST['status'] ?? 'COMPLETED');

        if ($id > 0 && in_array($status, ['COMPLETED', 'PENDING'])) {
            $orderModel = $this->model('Order');
            $orderModel->updateStatus($id, $status);
            $this->setFlash('success', "Cập nhật trạng thái đơn hàng #{$id} thành '{$status}'.");
        }

        $this->redirect('admin/orders');
    }

    // =========================================================================
    // PHƯƠNG THỨC TIỆN ÍCH HỖ TRỢ
    // =========================================================================

    private function makeSlug(string $str): string {
        // Nếu chuỗi là URL đầy đủ, bóc tách từ khóa hoặc đường dẫn ngắn gọn
        if (preg_match('~^https?://~i', $str)) {
            $parsed = parse_url($str);
            if (!empty($parsed['query'])) {
                parse_str($parsed['query'], $queryParams);
                if (!empty($queryParams['q'])) {
                    $str = $queryParams['q'];
                } elseif (!empty($queryParams['v'])) {
                    $str = $queryParams['v'];
                } else {
                    $str = $parsed['path'] ?? 'game';
                }
            } else {
                $str = $parsed['path'] ?? 'game';
            }
        }
        $str = preg_replace('~[^\pL\d]+~u', '-', $str);
        $str = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $str);
        $str = preg_replace('~[^-\w]+~', '', $str);
        $str = trim($str, '-');
        $str = preg_replace('~-+~', '-', $str);
        // Giới hạn độ dài slug tối đa 70 ký tự để không làm vỡ giao diện và CSDL
        return strtolower(substr($str, 0, 70));
    }

    private function handleUpload(array $file, string $subfolder): string {
        $targetDir = UPLOADS_PATH . DIRECTORY_SEPARATOR . $subfolder;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $allowed)) {
            return 'default_game.jpg';
        }

        $filename = uniqid('img_', true) . '.' . $ext;
        $destination = $targetDir . DIRECTORY_SEPARATOR . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        return 'default_game.jpg';
    }

    private function extractYouTubeId(string $input): string {
        $input = trim($input);
        if (empty($input)) {
            return '';
        }
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/|live\/))([\w-]{11})/i', $input, $matches)) {
            return $matches[1];
        }
        if (preg_match('/^[\w-]{11}$/', $input)) {
            return $input;
        }
        return $input;
    }
}
