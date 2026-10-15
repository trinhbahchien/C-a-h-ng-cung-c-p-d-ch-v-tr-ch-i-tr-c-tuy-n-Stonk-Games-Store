<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_manage_news') ?></h1>
        <div class="page-subtitle">Quản lý tin tức trò chơi, thông báo hệ thống và tin chuyên đề theo thể loại</div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/news/create" class="btn-admin btn-admin-primary">
            + Đăng Bài Tin Mới
        </a>
    </div>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Hình Ảnh</th>
                    <th>Tiêu Đề Bài Viết</th>
                    <th>Phân Loại</th>
                    <th>Liên Kết</th>
                    <th>Ngày Đăng</th>
                    <th style="text-align: right; width: 160px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($newsList)): ?>
                    <?php foreach ($newsList as $n): ?>
                        <tr>
                            <td>
                                <img src="<?= ASSETS_URL ?>/uploads/news/<?= htmlspecialchars($n['image'] ?? 'news_default.jpg') ?>" 
                                     alt="Thumb"
                                     style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);"
                                     onerror="this.src='<?= ASSETS_URL ?>/uploads/news/news_sale.jpg'">
                            </td>
                            <td style="max-width: 280px;">
                                <div style="font-weight: 700; color: var(--admin-text-main); font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($n['title']) ?>">
                                    <?= htmlspecialchars($n['title']) ?>
                                </div>
                                <div style="font-size: 11px; color: var(--admin-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 260px;" title="<?= htmlspecialchars($n['slug']) ?>">
                                    Đường dẫn: <?= htmlspecialchars(mb_strimwidth($n['slug'], 0, 35, '...')) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($n['type'] === 'system'): ?>
                                    <span class="badge-status badge-active">HỆ THỐNG</span>
                                <?php elseif ($n['type'] === 'game'): ?>
                                    <span class="badge-status badge-admin">THEO GAME</span>
                                <?php else: ?>
                                    <span class="badge-status badge-user">THỂ LOẠI</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($n['type'] === 'game' && !empty($n['game_title'])): ?>
                                    <span style="font-size: 12px; color: var(--admin-primary); font-weight: 600;">
                                        <?= htmlspecialchars($n['game_title']) ?>
                                    </span>
                                <?php elseif ($n['type'] === 'genre' && !empty($n['category_name'])): ?>
                                    <span style="font-size: 12px; color: #a29bfe; font-weight: 600;">
                                        <?= htmlspecialchars($n['category_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 11px; color: var(--admin-text-muted);">Tin Chung</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-size: 12px; color: var(--admin-text-muted);">
                                    <?= date('d/m/Y', strtotime($n['created_at'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= BASE_URL ?>/admin/news/edit?id=<?= $n['id'] ?>" 
                                   class="btn-admin btn-admin-secondary btn-admin-sm">
                                    Sửa
                                </a>
                                <a href="<?= BASE_URL ?>/admin/news/delete?id=<?= $n['id'] ?>" 
                                   class="btn-admin btn-admin-danger btn-admin-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: var(--admin-text-muted);">
                            Chưa có bài viết tin tức nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
