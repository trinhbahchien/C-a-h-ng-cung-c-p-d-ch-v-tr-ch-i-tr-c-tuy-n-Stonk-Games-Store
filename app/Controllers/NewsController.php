<?php
/**
 * Stonk Games Store - NewsController
 * Cổng Tin tức Hub phong cách Steam & Epic Games
 * Hỗ trợ lọc theo loại tin (Hệ thống / Game / Thể loại), tìm kiếm từ khóa, và xem bài viết chi tiết
 */

class NewsController extends Controller {

    /**
     * Trang Tin Tức Tổng Hợp (News Hub)
     */
    public function index(): void {
        /** @var News $newsModel */
        $newsModel = $this->model('News');
        /** @var Category $categoryModel */
        $categoryModel = $this->model('Category');
        /** @var Game $gameModel */
        $gameModel = $this->model('Game');

        $type = trim($_GET['type'] ?? 'all');
        $search = trim($_GET['search'] ?? '');
        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $gameId = isset($_GET['game_id']) ? (int)$_GET['game_id'] : 0;

        $filters = [];
        if (in_array($type, ['system', 'game', 'genre'], true)) {
            $filters['type'] = $type;
        } else {
            $type = 'all';
        }

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        if ($categoryId > 0) {
            $filters['category_id'] = $categoryId;
        }

        if ($gameId > 0) {
            $filters['game_id'] = $gameId;
        }

        $newsList = $newsModel->getAll($filters);
        $categories = $categoryModel->getAll();

        // Xác định bài viết tiêu điểm (Featured Hero Article)
        // Nếu không có tìm kiếm, chọn bài đầu tiên làm Featured Article
        $featuredArticle = null;
        $gridArticles = $newsList;

        if (empty($search) && $categoryId === 0 && $gameId === 0 && !empty($newsList)) {
            $featuredArticle = $newsList[0];
            $gridArticles = array_slice($newsList, 1);
        }

        $this->view('news/index', [
            'pageTitle'        => 'Tin Tức Game & Thông Báo Bản Quyền | ' . APP_NAME,
            'newsList'         => $newsList,
            'featuredArticle'  => $featuredArticle,
            'gridArticles'     => $gridArticles,
            'activeType'       => $type,
            'searchQuery'      => $search,
            'activeCategory'   => $categoryId,
            'activeGame'       => $gameId,
            'categories'       => $categories,
            'totalCount'       => count($newsList)
        ]);
    }

    /**
     * Trang Chi Tiết Bài Viết Tin Tức (News Detail Page)
     */
    public function detail(): void {
        /** @var News $newsModel */
        $newsModel = $this->model('News');
        /** @var Game $gameModel */
        $gameModel = $this->model('Game');

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

        $news = null;
        if ($id > 0) {
            $news = $newsModel->findById($id);
        } elseif (!empty($slug)) {
            $news = $newsModel->findBySlug($slug);
        }

        // Nếu không tìm thấy bài viết, chuyển hướng về trang tin tức
        if (!$news) {
            $_SESSION['flash_error'] = 'Không tìm thấy bài viết tin tức yêu cầu hoặc bài viết đã bị xóa!';
            $this->redirect('news');
            return;
        }

        // Lấy thông tin game liên quan nếu bài viết được gán cho game_id
        $relatedGame = null;
        if (!empty($news['game_id'])) {
            $relatedGame = $gameModel->findById((int)$news['game_id']);
        }

        // Lấy danh sách bài viết liên quan (cùng loại hoặc mới nhất, trừ bài hiện tại)
        $relatedFilter = [];
        if (!empty($news['type'])) {
            $relatedFilter['type'] = $news['type'];
        }
        $relatedFilter['limit'] = 4;
        $rawRelated = $newsModel->getAll($relatedFilter);
        
        $relatedNews = array_filter($rawRelated, function($item) use ($news) {
            return (int)$item['id'] !== (int)$news['id'];
        });

        // Nếu còn ít hơn 3 bài, lấy thêm bài viết mới nhất
        if (count($relatedNews) < 3) {
            $latest = $newsModel->getLatest(5);
            foreach ($latest as $l) {
                if ((int)$l['id'] !== (int)$news['id']) {
                    $exists = false;
                    foreach ($relatedNews as $rn) {
                        if ((int)$rn['id'] === (int)$l['id']) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $relatedNews[] = $l;
                    }
                }
                if (count($relatedNews) >= 4) break;
            }
        }

        $this->view('news/detail', [
            'pageTitle'    => htmlspecialchars($news['title']) . ' | Tin Tức ' . APP_NAME,
            'news'         => $news,
            'relatedGame'  => $relatedGame,
            'relatedNews'  => array_slice($relatedNews, 0, 3)
        ]);
    }
}
