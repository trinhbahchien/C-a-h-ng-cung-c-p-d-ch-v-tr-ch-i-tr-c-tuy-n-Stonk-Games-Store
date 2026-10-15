<?php
/**
 * Stonk Games Store - Game Model
 * Quan ly toan bo thong tin tro choi, the loai lien ket va phan tich du lieu
 */

class Game extends Model {

    /**
     * Lay danh sach tat ca tro choi (kem thong tin studio, the loai, luot ban)
     */
    public function getAll(array $filters = []): array {
        $sql = "SELECT g.*, 
                       d.name AS developer_name, 
                       d.slug AS developer_slug,
                       COALESCE((SELECT COUNT(oi.id) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0) AS total_sold,
                       COALESCE((SELECT SUM(oi.price) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0.0) AS revenue,
                       COALESCE(ga.total_reviews, 0) AS total_reviews,
                       COALESCE(ga.positive_rate, 0) AS positive_rate,
                       COALESCE(ga.average_rating, 5.0) AS average_rating,
                       COALESCE(ga.refund_rate, 0.0) AS refund_rate,
                       (SELECT GROUP_CONCAT(c.name SEPARATOR ', ') 
                        FROM game_categories gc 
                        JOIN categories c ON gc.category_id = c.id 
                        WHERE gc.game_id = g.id) AS category_names,
                       (SELECT GROUP_CONCAT(c.id SEPARATOR ',') 
                        FROM game_categories gc 
                        JOIN categories c ON gc.category_id = c.id 
                        WHERE gc.game_id = g.id) AS category_ids
                FROM games g
                JOIN developers d ON g.developer_id = d.id
                LEFT JOIN game_analytics ga ON g.id = ga.game_id
                WHERE 1=1";

        $params = [];

        // Loc theo trang thai (status)
        if (isset($filters['status'])) {
            $sql .= " AND g.status = ?";
            $params[] = (int)$filters['status'];
        }

        // Tim kiem theo ten game hoac studio
        if (!empty($filters['search'])) {
            $sql .= " AND (g.title LIKE ? OR d.name LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        // Loc theo Studio
        if (!empty($filters['developer_id'])) {
            $sql .= " AND g.developer_id = ?";
            $params[] = (int)$filters['developer_id'];
        }

        // Loc theo The loai (Category)
        if (!empty($filters['category_id'])) {
            $sql .= " AND EXISTS (SELECT 1 FROM game_categories gc WHERE gc.game_id = g.id AND gc.category_id = ?)";
            $params[] = (int)$filters['category_id'];
        }

        // Loc theo khoang gia
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $sql .= " AND g.price <= ?";
            $params[] = (float)$filters['max_price'];
        }

        // Loc game mien phi
        if (!empty($filters['is_free'])) {
            $sql .= " AND g.price = 0";
        }

        // Loc game dang sale
        if (!empty($filters['is_sale'])) {
            $sql .= " AND g.discount_percent > 0";
        }

        // Loc game noi bat (is_featured)
        if (isset($filters['is_featured'])) {
            $sql .= " AND g.is_featured = ?";
            $params[] = (int)$filters['is_featured'];
        }

        // Sap xep (Sort)
        $orderBy = $filters['sort'] ?? 'newest';
        switch ($orderBy) {
            case 'featured':
                $sql .= " ORDER BY g.is_featured DESC, total_sold DESC";
                break;
            case 'release_date':
                $sql .= " ORDER BY g.release_date DESC, g.id DESC";
                break;
            case 'price_asc':
                $sql .= " ORDER BY (g.price * (100 - g.discount_percent) / 100) ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY (g.price * (100 - g.discount_percent) / 100) DESC";
                break;
            case 'bestselling':
                $sql .= " ORDER BY total_sold DESC, g.id DESC";
                break;
            case 'rating':
                $sql .= " ORDER BY ga.average_rating DESC, ga.positive_rate DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY g.title ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY g.id DESC";
                break;
        }

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        return $this->fetchAll($sql, $params);
    }

    /**
     * Chi tiet tro choi theo ID (kem Studio, Analytics, Requirements, Categories)
     */
    public function findById(int $id): ?array {
        $sql = "SELECT g.*, 
                       d.name AS developer_name, 
                       d.slug AS developer_slug,
                       d.logo AS developer_logo,
                       d.description AS developer_description,
                       COALESCE((SELECT COUNT(oi.id) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0) AS total_sold,
                       COALESCE(ga.total_refunded, 0) AS total_refunded,
                       COALESCE(ga.refund_rate, 0.0) AS refund_rate,
                       COALESCE(ga.total_reviews, 0) AS total_reviews,
                       COALESCE(ga.positive_reviews, 0) AS positive_reviews,
                       COALESCE(ga.positive_rate, 0.0) AS positive_rate,
                       COALESCE(ga.average_rating, 5.0) AS average_rating,
                       COALESCE((SELECT SUM(oi.price) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0.0) AS revenue
                FROM games g
                JOIN developers d ON g.developer_id = d.id
                LEFT JOIN game_analytics ga ON g.id = ga.game_id
                WHERE g.id = ?";
        
        $game = $this->fetch($sql, [$id]);
        if (!$game) {
            return null;
        }

        // Lay danh sach the loai
        $game['categories'] = $this->getCategories($id);
        $game['category_ids'] = array_column($game['categories'], 'id');

        // Lay cau hinh he thong
        $reqSql = "SELECT * FROM system_requirements WHERE game_id = ?";
        $game['requirements'] = $this->fetch($reqSql, [$id]);

        return $game;
    }

    /**
     * Tim tro choi theo Slug
     */
    public function findBySlug(string $slug): ?array {
        $row = $this->fetch("SELECT id FROM games WHERE slug = ?", [$slug]);
        if ($row) {
            return $this->findById((int)$row['id']);
        }
        return null;
    }

    /**
     * Lay danh sach the loai cua 1 game
     */
    public function getCategories(int $gameId): array {
        $sql = "SELECT c.* 
                FROM categories c
                JOIN game_categories gc ON c.id = gc.category_id
                WHERE gc.game_id = ?
                ORDER BY c.name ASC";
        return $this->fetchAll($sql, [$gameId]);
    }

    /**
     * Tao moi 1 game kem categories, requirements, analytics
     */
    public function create(array $data, array $categoryIds = [], array $requirements = []): int {
        $sql = "INSERT INTO games 
                (title, slug, developer_id, price, discount_percent, short_description, description, cover_image, trailer_url, is_featured, status, release_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->query($sql, [
            $data['title'],
            $data['slug'],
            (int)$data['developer_id'],
            (float)$data['price'],
            (int)($data['discount_percent'] ?? 0),
            $data['short_description'] ?? '',
            $data['description'] ?? '',
            $data['cover_image'] ?? 'default_game.jpg',
            $data['trailer_url'] ?? '',
            (int)($data['is_featured'] ?? 0),
            (int)($data['status'] ?? 1),
            $data['release_date'] ?? date('Y-m-d')
        ]);

        $gameId = (int)$this->lastInsertId();

        // 1. Gan the loai vao game_categories
        if (!empty($categoryIds)) {
            $catSql = "INSERT INTO game_categories (game_id, category_id) VALUES (?, ?)";
            foreach ($categoryIds as $catId) {
                $this->query($catSql, [$gameId, (int)$catId]);
            }
        }

        // 2. Gan cau hinh vao system_requirements
        $reqSql = "INSERT INTO system_requirements 
                   (game_id, min_os, min_cpu, min_ram, min_gpu, min_storage, rec_os, rec_cpu, rec_ram, rec_gpu, rec_storage)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $this->query($reqSql, [
            $gameId,
            $requirements['min_os'] ?? 'Windows 10 64-bit',
            $requirements['min_cpu'] ?? 'Intel Core i5-8400 / AMD Ryzen 3 3300X',
            $requirements['min_ram'] ?? '8 GB RAM',
            $requirements['min_gpu'] ?? 'NVIDIA GeForce GTX 1060 / AMD Radeon RX 580',
            $requirements['min_storage'] ?? '50 GB available space',
            $requirements['rec_os'] ?? 'Windows 10/11 64-bit',
            $requirements['rec_cpu'] ?? 'Intel Core i7-8700K / AMD Ryzen 5 3600X',
            $requirements['rec_ram'] ?? '16 GB RAM',
            $requirements['rec_gpu'] ?? 'NVIDIA GeForce RTX 2060 / AMD Radeon RX 5700',
            $requirements['rec_storage'] ?? '50 GB SSD'
        ]);

        // 3. Khoi tao bang game_analytics mac dinh
        $analyticsSql = "INSERT INTO game_analytics 
                         (game_id, total_sold, total_refunded, refund_rate, total_reviews, positive_reviews, positive_rate, average_rating, revenue)
                         VALUES (?, 0, 0, 0.00, 0, 0, 100.00, 5.00, 0.00)";
        $this->query($analyticsSql, [$gameId]);

        return $gameId;
    }

    /**
     * Cap nhat thong tin game
     */
    public function update(int $id, array $data, ?array $categoryIds = null, ?array $requirements = null): bool {
        $sql = "UPDATE games SET 
                title = ?, slug = ?, developer_id = ?, price = ?, discount_percent = ?,
                short_description = ?, description = ?, cover_image = ?, trailer_url = ?,
                is_featured = ?, status = ?, release_date = ?
                WHERE id = ?";

        $success = $this->execute($sql, [
            $data['title'],
            $data['slug'],
            (int)$data['developer_id'],
            (float)$data['price'],
            (int)($data['discount_percent'] ?? 0),
            $data['short_description'] ?? '',
            $data['description'] ?? '',
            $data['cover_image'],
            $data['trailer_url'] ?? '',
            (int)($data['is_featured'] ?? 0),
            (int)($data['status'] ?? 1),
            $data['release_date'] ?? date('Y-m-d'),
            $id
        ]);

        // Cap nhat Categories neu co truyen vao
        if ($categoryIds !== null) {
            $this->execute("DELETE FROM game_categories WHERE game_id = ?", [$id]);
            $catSql = "INSERT INTO game_categories (game_id, category_id) VALUES (?, ?)";
            foreach ($categoryIds as $catId) {
                $this->query($catSql, [$id, (int)$catId]);
            }
        }

        // Cap nhat Requirements neu co truyen vao
        if ($requirements !== null) {
            $check = $this->fetch("SELECT id FROM system_requirements WHERE game_id = ?", [$id]);
            if ($check) {
                $reqSql = "UPDATE system_requirements SET 
                           min_os = ?, min_cpu = ?, min_ram = ?, min_gpu = ?, min_storage = ?,
                           rec_os = ?, rec_cpu = ?, rec_ram = ?, rec_gpu = ?, rec_storage = ?
                           WHERE game_id = ?";
                $this->execute($reqSql, [
                    $requirements['min_os'] ?? 'Windows 10 64-bit',
                    $requirements['min_cpu'] ?? 'Intel Core i5',
                    $requirements['min_ram'] ?? '8 GB RAM',
                    $requirements['min_gpu'] ?? 'GTX 1060',
                    $requirements['min_storage'] ?? '50 GB',
                    $requirements['rec_os'] ?? 'Windows 10/11 64-bit',
                    $requirements['rec_cpu'] ?? 'Intel Core i7',
                    $requirements['rec_ram'] ?? '16 GB RAM',
                    $requirements['rec_gpu'] ?? 'RTX 2060',
                    $requirements['rec_storage'] ?? '50 GB SSD',
                    $id
                ]);
            } else {
                $reqSql = "INSERT INTO system_requirements 
                           (game_id, min_os, min_cpu, min_ram, min_gpu, min_storage, rec_os, rec_cpu, rec_ram, rec_gpu, rec_storage)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->query($reqSql, [
                    $id,
                    $requirements['min_os'] ?? 'Windows 10 64-bit',
                    $requirements['min_cpu'] ?? 'Intel Core i5',
                    $requirements['min_ram'] ?? '8 GB RAM',
                    $requirements['min_gpu'] ?? 'GTX 1060',
                    $requirements['min_storage'] ?? '50 GB',
                    $requirements['rec_os'] ?? 'Windows 10/11 64-bit',
                    $requirements['rec_cpu'] ?? 'Intel Core i7',
                    $requirements['rec_ram'] ?? '16 GB RAM',
                    $requirements['rec_gpu'] ?? 'RTX 2060',
                    $requirements['rec_storage'] ?? '50 GB SSD'
                ]);
            }
        }

        return $success;
    }

    /**
     * Xoa game (Foreign keys tu dong cascade xoa game_categories, requirements, analytics, reviews)
     */
    public function delete(int $id): bool {
        return $this->execute("DELETE FROM games WHERE id = ?", [$id]);
    }

    /**
     * Dem tong so luong game
     */
    public function count(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM games");
    }

    /**
     * Lay danh sach game Spotlight Carousel (is_featured = 1)
     */
    public function getFeatured(int $limit = 4): array {
        return $this->getAll(['status' => 1, 'limit' => $limit, 'sort' => 'bestselling']);
    }

    /**
     * Lay Top game ban chay nhat (Steam Charts Best Sellers)
     */
    public function getTopSellers(int $limit = 8): array {
        return $this->getAll(['status' => 1, 'limit' => $limit, 'sort' => 'bestselling']);
    }

    /**
     * Lay game moi phat hanh
     */
    public function getNewReleases(int $limit = 8): array {
        return $this->getAll(['status' => 1, 'limit' => $limit, 'sort' => 'newest']);
    }

    /**
     * Lay game mien phi (Free-to-Play)
     */
    public function getFreeToPlay(int $limit = 8): array {
        return $this->getAll(['status' => 1, 'is_free' => true, 'limit' => $limit, 'sort' => 'bestselling']);
    }

    /**
     * Lay game giam gia dac biet (Special Offers)
     */
    public function getDiscounted(int $limit = 8): array {
        return $this->getAll(['status' => 1, 'is_sale' => true, 'limit' => $limit, 'sort' => 'bestselling']);
    }

    /**
     * Lay danh sach danh gia cua cong dong cho 1 game
     */
    public function getReviews(int $gameId): array {
        $sql = "SELECT r.*, u.username, u.role
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.game_id = ?
                ORDER BY r.id DESC";
        return $this->fetchAll($sql, [$gameId]);
    }

    /**
     * Them danh gia moi cho game va cap nhat analytics
     */
    public function addReview(int $gameId, int $userId, int $rating, string $comment): bool {
        $sql = "INSERT INTO reviews (game_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
        $success = $this->execute($sql, [$gameId, $userId, $rating, $comment]);
        if ($success) {
            // Cap nhat lai game_analytics
            $statsSql = "SELECT COUNT(*) as total_reviews, 
                                SUM(CASE WHEN rating >= 4 THEN 1 ELSE 0 END) as positive_reviews,
                                AVG(rating) as avg_rating
                         FROM reviews WHERE game_id = ?";
            $stats = $this->fetch($statsSql, [$gameId]);
            if ($stats && $stats['total_reviews'] > 0) {
                $total = (int)$stats['total_reviews'];
                $positive = (int)$stats['positive_reviews'];
                $positiveRate = round(($positive / $total) * 100, 2);
                $avgRating = round((float)$stats['avg_rating'], 2);

                $updateAnalyticsSql = "UPDATE game_analytics SET 
                                       total_reviews = ?, positive_reviews = ?, positive_rate = ?, average_rating = ?
                                       WHERE game_id = ?";
                $this->execute($updateAnalyticsSql, [$total, $positive, $positiveRate, $avgRating, $gameId]);
            }
        }
        return $success;
    }

    /**
     * Lay game lien quan cung the loai (tru game hien tai)
     */
    public function getRelatedGames(int $gameId, array $categoryIds = [], int $limit = 4): array {
        if (empty($categoryIds)) {
            return $this->getAll(['status' => 1, 'limit' => $limit, 'sort' => 'bestselling']);
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $sql = "SELECT DISTINCT g.*, d.name AS developer_name,
                       COALESCE((SELECT COUNT(oi.id) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0) AS total_sold,
                       COALESCE(ga.positive_rate, 0) AS positive_rate
                FROM games g
                JOIN developers d ON g.developer_id = d.id
                LEFT JOIN game_analytics ga ON g.id = ga.game_id
                JOIN game_categories gc ON g.id = gc.game_id
                WHERE gc.category_id IN ($placeholders)
                  AND g.id != ?
                  AND g.status = 1
                ORDER BY total_sold DESC
                LIMIT " . (int)$limit;

        $params = array_merge($categoryIds, [$gameId]);
        return $this->fetchAll($sql, $params);
    }

    /**
     * Lay bang xep hang game Steam Charts theo tieu chi
     */
    public function getRankings(string $type = 'bestselling', ?int $categoryId = null, int $limit = 20): array {
        $sql = "SELECT g.*, 
                       d.name AS developer_name, 
                       d.slug AS developer_slug,
                       COALESCE((SELECT COUNT(oi.id) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0) AS total_sold,
                       COALESCE(ga.total_refunded, 0) AS total_refunded,
                       COALESCE(ga.refund_rate, 0.0) AS refund_rate,
                       COALESCE(ga.total_reviews, 0) AS total_reviews,
                       COALESCE(ga.positive_rate, 0.0) AS positive_rate,
                       COALESCE(ga.average_rating, 5.0) AS average_rating,
                       COALESCE((SELECT SUM(oi.price) 
                                 FROM order_items oi 
                                 JOIN orders o ON oi.order_id = o.id 
                                 WHERE oi.game_id = g.id AND o.status = 'COMPLETED'), 0.0) AS revenue,
                       (SELECT GROUP_CONCAT(c.name SEPARATOR ', ') 
                        FROM game_categories gc 
                        JOIN categories c ON gc.category_id = c.id 
                        WHERE gc.game_id = g.id) AS category_names
                FROM games g
                JOIN developers d ON g.developer_id = d.id
                LEFT JOIN game_analytics ga ON g.id = ga.game_id
                WHERE g.status = 1";

        $params = [];

        if ($categoryId !== null && $categoryId > 0) {
            $sql .= " AND EXISTS (SELECT 1 FROM game_categories gc WHERE gc.game_id = g.id AND gc.category_id = ?)";
            $params[] = (int)$categoryId;
        }

        switch ($type) {
            case 'top_rated':
                $sql .= " ORDER BY ga.positive_rate DESC, ga.average_rating DESC, ga.total_reviews DESC";
                break;
            case 'low_refund':
                $sql .= " ORDER BY ga.refund_rate ASC, total_sold DESC";
                break;
            case 'revenue':
                $sql .= " ORDER BY revenue DESC";
                break;
            case 'bestselling':
            default:
                $sql .= " ORDER BY total_sold DESC, g.id DESC";
                break;
        }

        $sql .= " LIMIT " . (int)$limit;

        return $this->fetchAll($sql, $params);
    }

    /**
     * Dong bo toan bo luot ban (total_sold) va doanh thu (revenue)
     * vao bang game_analytics theo dung so hoa don da ban trong orders
     */
    public function syncAnalytics(): void {
        $sql = "UPDATE game_analytics ga
                SET total_sold = (
                    SELECT COUNT(oi.id)
                    FROM order_items oi
                    JOIN orders o ON oi.order_id = o.id
                    WHERE oi.game_id = ga.game_id AND o.status = 'COMPLETED'
                ),
                revenue = COALESCE((
                    SELECT SUM(oi.price)
                    FROM order_items oi
                    JOIN orders o ON oi.order_id = o.id
                    WHERE oi.game_id = ga.game_id AND o.status = 'COMPLETED'
                ), 0.00)";
        $this->execute($sql);
    }
}

