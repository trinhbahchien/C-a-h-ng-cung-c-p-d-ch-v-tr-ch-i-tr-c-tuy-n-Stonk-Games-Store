<?php
/**
 * Stonk Games Store - RankingController
 * Phụ trách hiển thị Bảng Xếp Hạng Doanh Số & Mức Độ Hài Lòng (Steam Charts Style)
 */

class RankingController extends Controller {

    /**
     * Bảng Xếp Hạng Game Toàn Sàn (Steam Charts Leaderboard)
     */
    public function index(): void {
        /** @var Game $gameModel */
        $gameModel = $this->model('Game');
        /** @var Category $categoryModel */
        $categoryModel = $this->model('Category');

        $tab = trim($_GET['tab'] ?? 'bestselling');
        $validTabs = ['bestselling', 'top_rated', 'low_refund', 'revenue'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'bestselling';
        }

        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $categoryFilter = $categoryId > 0 ? $categoryId : null;

        // Lấy danh sách bảng xếp hạng theo tiêu chí
        $rankedGames = $gameModel->getRankings($tab, $categoryFilter, 20);

        // Lấy danh sách thể loại để hiển thị bộ lọc nhanh
        $categories = $categoryModel->getAll();

        // Tên danh mục đang lọc (nếu có)
        $currentCategoryName = '';
        if ($categoryId > 0) {
            foreach ($categories as $cat) {
                if ($cat['id'] == $categoryId) {
                    $currentCategoryName = $cat['name'];
                    break;
                }
            }
        }

        $this->view('rankings/index', [
            'pageTitle'           => 'Bảng Xếp Hạng Game Bán Chạy & Đánh Giá (Steam Charts) | ' . APP_NAME,
            'rankedGames'         => $rankedGames,
            'categories'          => $categories,
            'activeTab'           => $tab,
            'activeCategory'      => $categoryId,
            'currentCategoryName' => $currentCategoryName
        ]);
    }
}
