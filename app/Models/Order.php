<?php
/**
 * Stonk Games Store - Order Model
 * Quan ly don hang mua game, chi tiet vat pham va cap phat ma CD-Key ban quyen
 */

class Order extends Model {

    /**
     * Lay danh sach toan bo don hang (kem so luong san pham trong don)
     */
    public function getAll(): array {
        $sql = "SELECT o.*, 
                       u.username,
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.id DESC";
        return $this->fetchAll($sql);
    }

    /**
     * Lay danh sach don hang theo user_id
     */
    public function getByUserId(int $userId): array {
        $sql = "SELECT o.*, 
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
                FROM orders o
                WHERE o.user_id = ?
                ORDER BY o.id DESC";
        return $this->fetchAll($sql, [$userId]);
    }

    /**
     * Chi tiet don hang theo ID
     */
    public function findById(int $id): ?array {
        $sql = "SELECT o.*, u.username 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";
        $order = $this->fetch($sql, [$id]);
        if ($order) {
            $order['items'] = $this->getItems($id);
        }
        return $order;
    }

    /**
     * Lay danh sach chi tiet san pham va ma CD-Key cua don hang
     */
    public function getItems(int $orderId): array {
        $sql = "SELECT oi.*, 
                       g.title AS game_title, 
                       g.cover_image,
                       g.slug AS game_slug
                FROM order_items oi
                JOIN games g ON oi.game_id = g.id
                WHERE oi.order_id = ?
                ORDER BY oi.id ASC";
        return $this->fetchAll($sql, [$orderId]);
    }

    /**
     * Tao don hang moi va tu dong sinh ma CD-Key cho tung game
     */
    public function create(array $orderData, array $items): int {
        $sql = "INSERT INTO orders (user_id, customer_name, customer_email, total_amount, payment_method, status)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->query($sql, [
            !empty($orderData['user_id']) ? (int)$orderData['user_id'] : null,
            $orderData['customer_name'],
            $orderData['customer_email'],
            (float)$orderData['total_amount'],
            $orderData['payment_method'] ?? 'VIETQR',
            $orderData['status'] ?? 'COMPLETED'
        ]);

        $orderId = (int)$this->lastInsertId();

        $itemSql = "INSERT INTO order_items (order_id, game_id, price, cd_key) VALUES (?, ?, ?, ?)";
        foreach ($items as $item) {
            $cdKey = $item['cd_key'] ?? $this->generateCdKey();
            $this->query($itemSql, [
                $orderId,
                (int)$item['game_id'],
                (float)$item['price'],
                $cdKey
            ]);
        }

        // Dong bo luot ban va doanh thu tu dong tu order_items
        $this->syncAnalytics();

        return $orderId;
    }

    /**
     * Cap nhat trang thai don hang
     */
    public function updateStatus(int $id, string $status): bool {
        $result = $this->execute("UPDATE orders SET status = ? WHERE id = ?", [$status, $id]);
        $this->syncAnalytics();
        return $result;
    }

    /**
     * Xoa don hang
     */
    public function delete(int $id): bool {
        $result = $this->execute("DELETE FROM orders WHERE id = ?", [$id]);
        $this->syncAnalytics();
        return $result;
    }

    /**
     * Dong bo toan bo luot ban (total_sold) va doanh thu (revenue)
     * vao bang game_analytics theo dung so hoa don da ban trong orders (chi tinh don COMPLETED)
     */
    public function syncAnalytics(?int $gameId = null): void {
        if ($gameId !== null) {
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
                    ), 0.00)
                    WHERE ga.game_id = ?";
            $this->execute($sql, [$gameId]);
        } else {
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

    /**
     * Tong so don hang
     */
    public function count(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM orders");
    }

    /**
     * Tong doanh thu toan san (chi tinh don COMPLETED)
     */
    public function getTotalRevenue(): float {
        return (float)$this->fetchColumn("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status = 'COMPLETED'");
    }

    /**
     * Ham sinh ma CD-Key ban quyen doc quyen dang: STONK-XXXX-YYYY-ZZZZ-WWWW
     */
    public function generateCdKey(): string {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $blocks = [];
        $blocks[] = 'STONK';
        for ($i = 0; $i < 4; $i++) {
            $block = '';
            for ($j = 0; $j < 4; $j++) {
                $block .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $blocks[] = $block;
        }
        return implode('-', $blocks);
    }

    /**
     * Lay danh sach don hang theo User ID kem bo loc trang thai (ALL, COMPLETED, PENDING)
     */
    public function getByUserIdWithFilter(int $userId, string $status = 'all'): array {
        $params = [$userId];
        $whereStatus = "";
        
        $statusUpper = strtoupper(trim($status));
        if (in_array($statusUpper, ['COMPLETED', 'PENDING'])) {
            $whereStatus = " AND o.status = ? ";
            $params[] = $statusUpper;
        }

        $sql = "SELECT o.*, 
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count,
                       (SELECT g.title FROM order_items oi JOIN games g ON oi.game_id = g.id WHERE oi.order_id = o.id LIMIT 1) AS first_game_title,
                       (SELECT g.cover_image FROM order_items oi JOIN games g ON oi.game_id = g.id WHERE oi.order_id = o.id LIMIT 1) AS first_game_cover
                FROM orders o
                WHERE o.user_id = ? {$whereStatus}
                ORDER BY o.id DESC";
        
        return $this->fetchAll($sql, $params);
    }

    /**
     * Dem so luong don hang theo tung nhom trang thai cua User
     */
    public function countByStatus(int $userId): array {
        $sql = "SELECT 
                    COUNT(*) AS total_all,
                    SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) AS total_completed,
                    SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) AS total_pending
                FROM orders
                WHERE user_id = ?";
        
        $row = $this->fetch($sql, [$userId]);
        return [
            'all'       => (int)($row['total_all'] ?? 0),
            'completed' => (int)($row['total_completed'] ?? 0),
            'pending'   => (int)($row['total_pending'] ?? 0),
        ];
    }

    /**
     * Tra cuu nhanh don hang danh cho khach vang lai bang ID don hang va Email
     */
    public function lookup(int $id, string $email): ?array {
        $sql = "SELECT o.*, u.username 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = ? AND LOWER(TRIM(o.customer_email)) = LOWER(TRIM(?))";
        
        $order = $this->fetch($sql, [$id, $email]);
        if ($order) {
            $order['items'] = $this->getItems($id);
        }
        return $order;
    }
}

