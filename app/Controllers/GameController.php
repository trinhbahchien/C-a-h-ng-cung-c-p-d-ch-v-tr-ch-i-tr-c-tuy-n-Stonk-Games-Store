<?php
/**
 * Stonk Games Store - GameController
 * Phụ trách hiển thị Trang Chi Tiết Game (Steam Style) và Cửa Hàng (Catalog)
 */

class GameController extends Controller {

    /**
     * Trang Chi Tiết Trò Chơi (Steam Product Detail Page)
     */
    public function detail(): void {
        /** @var Game $gameModel */
        $gameModel = $this->model('Game');
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

        $game = null;
        if ($id > 0) {
            $game = $gameModel->findById($id);
        } elseif (!empty($slug)) {
            $game = $gameModel->findBySlug($slug);
        }

        // Kiểm tra trò chơi có tồn tại không
        if (!$game) {
            $_SESSION['flash_error'] = 'Không tìm thấy trò chơi được yêu cầu hoặc trò chơi đã bị ngừng kinh doanh!';
            $this->redirect('home');
            return;
        }

        // Xử lý gửi đánh giá cộng đồng (Community Review Submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
            if (!isset($_SESSION['user'])) {
                $_SESSION['flash_error'] = 'Bạn cần đăng nhập để gửi đánh giá trò chơi!';
                $this->redirect('auth/login');
                return;
            }

            $rating = (int)($_POST['rating'] ?? 5);
            $rating = max(1, min(5, $rating));
            $comment = trim($_POST['comment'] ?? '');

            if (empty($comment)) {
                $_SESSION['flash_error'] = 'Vui lòng nhập nội dung đánh giá của bạn!';
            } else {
                $userId = (int)$_SESSION['user']['id'];
                $gameModel->addReview($game['id'], $userId, $rating, $comment);
                $_SESSION['flash_success'] = 'Cảm ơn bạn! Đánh giá của bạn đã được đăng thành công.';
            }

            $this->redirect('game/detail?id=' . $game['id']);
            return;
        }

        // Lấy danh sách thể loại của game
        $categories = $game['categories'] ?? $gameModel->getCategories($game['id']);
        $categoryIds = !empty($categories) ? array_column($categories, 'id') : [];

        // Lấy thông tin cấu hình phần cứng (System Requirements)
        $requirements = $game['requirements'] ?? null;
        if (!$requirements) {
            $requirements = $gameModel->fetch("SELECT * FROM system_requirements WHERE game_id = ?", [$game['id']]);
        }

        // Lấy danh sách đánh giá của game thủ
        $reviews = $gameModel->getReviews($game['id']);

        // Lấy tin tức / bản cập nhật liên quan đến game này
        /** @var News $newsModel */
        $newsModel = $this->model('News');
        $relatedNews = $newsModel->getAll([
            'game_id' => $game['id'],
            'limit'   => 4
        ]);

        // Lấy các game tương tự cùng thể loại
        $relatedGames = $gameModel->getRelatedGames($game['id'], $categoryIds, 4);

        $this->view('games/detail', [
            'pageTitle'    => htmlspecialchars($game['title']) . ' - Chi Tiết Bản Quyền | ' . APP_NAME,
            'game'         => $game,
            'categories'   => $categories,
            'requirements' => $requirements,
            'reviews'      => $reviews,
            'relatedNews'  => $relatedNews,
            'relatedGames' => $relatedGames,
        ]);
    }

    public function index(): void {
        $this->catalog();
    }

    /**
     * Cửa hàng lọc game đa tầng (Catalog Epic / Steam Style)
     */
    public function catalog(): void {
        /** @var Game $gameModel */
        $gameModel = $this->model('Game');
        /** @var Category $categoryModel */
        $categoryModel = $this->model('Category');
        /** @var Developer $devModel */
        $devModel = $this->model('Developer');

        $search = trim($_GET['search'] ?? '');
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $devId = isset($_GET['developer']) ? (int)$_GET['developer'] : 0;
        $sort = trim($_GET['sort'] ?? 'newest');
        $maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;
        $isFree = isset($_GET['free']) && $_GET['free'] === '1';
        $isSale = isset($_GET['sale']) && $_GET['sale'] === '1';

        $filters = [
            'status'       => 1,
            'search'       => $search,
            'category_id'  => $categoryId > 0 ? $categoryId : null,
            'developer_id' => $devId > 0 ? $devId : null,
            'sort'         => $sort,
            'max_price'    => $maxPrice,
            'is_free'      => $isFree,
            'is_sale'      => $isSale,
            'limit'        => 50
        ];

        $games = $gameModel->getAll($filters);
        $allCategories = $categoryModel->getAll();
        $allDevelopers = $devModel->getAll();

        $this->view('games/catalog', [
            'pageTitle'      => 'Cửa Hàng Game Bản Quyền & Bộ Lọc | ' . APP_NAME,
            'games'          => $games,
            'categories'     => $allCategories,
            'developers'     => $allDevelopers,
            'activeCategory' => $categoryId,
            'activeDev'      => $devId,
            'activeSort'     => $sort,
            'searchQuery'    => $search,
            'isFree'         => $isFree,
            'isSale'         => $isSale,
            'maxPrice'       => $maxPrice
        ]);
    }
}
