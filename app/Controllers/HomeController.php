<?php
/**
 * Stonk Games Store - HomeController
 * Dieu khien trang chu Storefront (Hero Carousel, Top Sellers, New Releases, Free Games, News Hub)
 */

class HomeController extends Controller {

    protected Game $gameModel;
    protected News $newsModel;

    public function __construct() {
        $this->gameModel = new Game();
        $this->newsModel = new News();
    }

    public function index(): void {
        try {
            $db = Database::getInstance()->getConnection();
            $stmtGames = $db->query("SELECT COUNT(*) FROM games WHERE status = 1");
            $totalGames = (int)$stmtGames->fetchColumn();

            $stmtUsers = $db->query("SELECT COUNT(*) FROM users WHERE isdelete = 0");
            $totalUsers = (int)$stmtUsers->fetchColumn();

            // 1. Hero Spotlight Carousel: 5 Game noi bat (is_featured = 1)
            $spotlightGames = $this->gameModel->getAll([
                'status' => 1,
                'is_featured' => 1,
                'limit' => 5
            ]);
            // Fallback neu it hon 3 game featured thi lay theo luot ban
            if (count($spotlightGames) < 3) {
                $spotlightGames = $this->gameModel->getAll([
                    'status' => 1,
                    'sort' => 'bestselling',
                    'limit' => 5
                ]);
            }

            // 2. Top Game Ban Chay (Top Sellers)
            $topSellers = $this->gameModel->getAll([
                'status' => 1,
                'sort' => 'bestselling',
                'limit' => 6
            ]);

            // 3. Game Moi Phat Hanh (New Releases)
            $newReleases = $this->gameModel->getAll([
                'status' => 1,
                'sort' => 'release_date',
                'limit' => 6
            ]);

            // 4. Game Mien Phi (Free-to-Play)
            $freeGames = $this->gameModel->getAll([
                'status' => 1,
                'is_free' => 1,
                'limit' => 4
            ]);

            // 5. Tin tuc noi bat theo game & he thong
            $latestNews = $this->newsModel->getAll([
                'limit' => 4
            ]);

            $dbStatus = [
                'connected' => true,
                'total_games' => $totalGames,
                'total_users' => $totalUsers,
                'driver' => $db->getAttribute(PDO::ATTR_DRIVER_NAME),
                'server_version' => $db->getAttribute(PDO::ATTR_SERVER_VERSION),
            ];
        } catch (Exception $e) {
            $dbStatus = [
                'connected' => false,
                'error' => $e->getMessage()
            ];
            $spotlightGames = [];
            $topSellers = [];
            $newReleases = [];
            $freeGames = [];
            $latestNews = [];
        }

        $this->view('home/index', [
            'pageTitle' => 'Trang Chủ | Stonk Games Store - Cổng Game Bản Quyền & Tin Tức Hàng Đầu',
            'dbStatus' => $dbStatus,
            'spotlightGames' => $spotlightGames,
            'topSellers' => $topSellers,
            'newReleases' => $newReleases,
            'freeGames' => $freeGames,
            'latestNews' => $latestNews
        ], 'main');
    }
}
