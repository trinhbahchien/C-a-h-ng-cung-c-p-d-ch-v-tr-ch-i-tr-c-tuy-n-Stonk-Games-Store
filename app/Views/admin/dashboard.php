<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_dashboard') ?></h1>
        <div class="page-subtitle">Tổng quan thông số hệ thống và hoạt động kinh doanh Stonk Games Store</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="<?= BASE_URL ?>/admin/games/create" class="btn-admin btn-admin-primary">
            Thêm Game Mới
        </a>
        <a href="<?= BASE_URL ?>/admin/news/create" class="btn-admin btn-admin-secondary">
            Đăng Bài Tin Tức
        </a>
    </div>
</div>

<!-- Thẻ Chỉ Số Thống Kê -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Chỉ số 1: Games -->
    <div class="admin-card" style="margin-bottom: 0; padding: 20px; border-left: 4px solid var(--admin-primary);">
        <div style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Tổng Số Games
        </div>
        <div style="font-size: 32px; font-weight: 800; color: #fff; margin: 8px 0 4px;">
            <?= number_format($stats['total_games']) ?>
        </div>
        <div style="font-size: 12px; color: var(--admin-primary); font-weight: 600;">
            Khả dụng trên sàn
        </div>
    </div>

    <!-- Chỉ số 2: Tin tức -->
    <div class="admin-card" style="margin-bottom: 0; padding: 20px; border-left: 4px solid var(--admin-secondary);">
        <div style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Bài Viết Tin Tức
        </div>
        <div style="font-size: 32px; font-weight: 800; color: #fff; margin: 8px 0 4px;">
            <?= number_format($stats['total_news']) ?>
        </div>
        <div style="font-size: 12px; color: var(--admin-secondary); font-weight: 600;">
            Tin hệ thống & game
        </div>
    </div>

    <!-- Chỉ số 3: Người dùng -->
    <div class="admin-card" style="margin-bottom: 0; padding: 20px; border-left: 4px solid #a29bfe;">
        <div style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Người Dùng
        </div>
        <div style="font-size: 32px; font-weight: 800; color: #fff; margin: 8px 0 4px;">
            <?= number_format($stats['total_users']) ?>
        </div>
        <div style="font-size: 12px; color: #a29bfe; font-weight: 600;">
            Tài khoản đã đăng ký
        </div>
    </div>

    <!-- Chỉ số 4: Đơn hàng -->
    <div class="admin-card" style="margin-bottom: 0; padding: 20px; border-left: 4px solid #fdcb6e;">
        <div style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Tổng Đơn Hàng
        </div>
        <div style="font-size: 32px; font-weight: 800; color: #fff; margin: 8px 0 4px;">
            <?= number_format($stats['total_orders']) ?>
        </div>
        <div style="font-size: 12px; color: #fdcb6e; font-weight: 600;">
            Giao dịch ghi nhận
        </div>
    </div>

    <!-- Chỉ số 5: Doanh thu -->
    <div class="admin-card" style="margin-bottom: 0; padding: 20px; border-left: 4px solid var(--admin-success);">
        <div style="font-size: 11px; font-weight: 700; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Doanh Thu Hoàn Tất
        </div>
        <div style="font-size: 26px; font-weight: 800; color: var(--admin-success); margin: 8px 0 4px;">
            <?= number_format($stats['total_revenue'], 0, ',', '.') ?> đ
        </div>
        <div style="font-size: 12px; color: var(--admin-text-muted);">
            Thanh toán VietQR
        </div>
    </div>
</div>

<!-- Lưới 2 cột: Game bán chạy & Đơn hàng gần đây -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
    <!-- Cột trái: Top 5 Game bán chạy nhất -->
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">
                Top 5 Game Bán Chạy Nhất
            </h3>
            <a href="<?= BASE_URL ?>/admin/games" style="font-size: 12px; color: var(--admin-primary); font-weight: 600;">
                Xem Tất Cả
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tựa Game</th>
                        <th>Giá Bán</th>
                        <th>Đã Bán</th>
                        <th>Tích Cực</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($topGames)): ?>
                        <?php foreach ($topGames as $game): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #fff;"><?= htmlspecialchars($game['title']) ?></div>
                                    <div style="font-size: 11px; color: var(--admin-text-muted);"><?= htmlspecialchars($game['developer_name']) ?></div>
                                </td>
                                <td>
                                    <?php if ($game['price'] == 0): ?>
                                        <span style="color: var(--admin-success); font-weight: 700;">MIỄN PHÍ</span>
                                    <?php else: ?>
                                        <?= number_format($game['price'] * (100 - $game['discount_percent']) / 100, 0, ',', '.') ?> đ
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 700; color: var(--admin-primary);">
                                    <?= number_format($game['total_sold']) ?>
                                </td>
                                <td>
                                    <span style="color: var(--admin-success); font-weight: 600;">
                                        <?= number_format($game['positive_rate'], 1) ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; color: var(--admin-text-muted);">Chưa có dữ liệu</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cột phải: Đơn hàng gần đây -->
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">
                Đơn Hàng Gần Đây
            </h3>
            <a href="<?= BASE_URL ?>/admin/orders" style="font-size: 12px; color: var(--admin-primary); font-weight: 600;">
                Quản Lý Đơn Hàng
            </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentOrders)): ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/orders/detail?id=<?= $order['id'] ?>" style="color: var(--admin-primary); font-weight: 700;">
                                        #<?= $order['id'] ?>
                                    </a>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #fff;"><?= htmlspecialchars($order['customer_name']) ?></div>
                                    <div style="font-size: 11px; color: var(--admin-text-muted);"><?= htmlspecialchars($order['customer_email']) ?></div>
                                </td>
                                <td style="font-weight: 700;">
                                    <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                                </td>
                                <td>
                                    <span class="badge-status <?= $order['status'] === 'COMPLETED' ? 'badge-active' : 'badge-hidden' ?>">
                                        <?= $order['status'] === 'COMPLETED' ? 'HOÀN TẤT' : 'CHỜ XỬ LÝ' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; color: var(--admin-text-muted);">Chưa có đơn hàng nào</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
