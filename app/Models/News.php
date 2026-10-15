<?php
/**
 * Stonk Games Store - News Model
 * Quan ly tin tuc tro choi, thong bao he thong va chuyen muc the loai
 */

class News extends Model {

    /**
     * Lay danh sach tin tuc (kem ten game lien quan hoac the loai)
     */
    public function getAll(array $filters = []): array {
        $sql = "SELECT n.*, 
                       g.title AS game_title, 
                       g.slug AS game_slug,
                       c.name AS category_name, 
                       c.slug AS category_slug
                FROM news n
                LEFT JOIN games g ON n.game_id = g.id
                LEFT JOIN categories c ON n.category_id = c.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['type'])) {
            $sql .= " AND n.type = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['game_id'])) {
            $sql .= " AND n.game_id = ?";
            $params[] = (int)$filters['game_id'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND n.category_id = ?";
            $params[] = (int)$filters['category_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (n.title LIKE ? OR n.content LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY n.id DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        return $this->fetchAll($sql, $params);
    }

    /**
     * Lay chi tiet 1 bai viet
     */
    public function findById(int $id): ?array {
        $sql = "SELECT n.*, 
                       g.title AS game_title, 
                       g.slug AS game_slug,
                       c.name AS category_name, 
                       c.slug AS category_slug
                FROM news n
                LEFT JOIN games g ON n.game_id = g.id
                LEFT JOIN categories c ON n.category_id = c.id
                WHERE n.id = ?";
        return $this->fetch($sql, [$id]);
    }

    /**
     * Tim bai viet theo Slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT n.*, 
                       g.title AS game_title, 
                       g.slug AS game_slug,
                       c.name AS category_name, 
                       c.slug AS category_slug
                FROM news n
                LEFT JOIN games g ON n.game_id = g.id
                LEFT JOIN categories c ON n.category_id = c.id
                WHERE n.slug = ?";
        return $this->fetch($sql, [$slug]);
    }

    /**
     * Tao moi 1 bai viet
     */
    public function create(array $data): int {
        $sql = "INSERT INTO news (title, slug, content, image, type, game_id, category_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $this->query($sql, [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? 'news_default.jpg',
            $data['type'] ?? 'system',
            !empty($data['game_id']) ? (int)$data['game_id'] : null,
            !empty($data['category_id']) ? (int)$data['category_id'] : null
        ]);

        return (int)$this->lastInsertId();
    }

    /**
     * Cap nhat bai viet
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE news SET 
                title = ?, slug = ?, content = ?, image = ?, type = ?, game_id = ?, category_id = ?
                WHERE id = ?";

        return $this->execute($sql, [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['image'],
            $data['type'] ?? 'system',
            !empty($data['game_id']) ? (int)$data['game_id'] : null,
            !empty($data['category_id']) ? (int)$data['category_id'] : null,
            $id
        ]);
    }

    /**
     * Xoa bai viet
     */
    public function delete(int $id): bool {
        return $this->execute("DELETE FROM news WHERE id = ?", [$id]);
    }

    /**
     * Dem so bai viet
     */
    public function count(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM news");
    }

    /**
     * Lay tin tuc moi nhat
     */
    public function getLatest(int $limit = 4): array {
        return $this->getAll(['limit' => $limit]);
    }

    /**
     * Lay tin tuc theo game
     */
    public function getByGameId(int $gameId, int $limit = 4): array {
        return $this->getAll(['game_id' => $gameId, 'limit' => $limit]);
    }
}
