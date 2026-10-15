<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_manage_games') ?></h1>
        <div class="page-subtitle">Quản lý danh mục, cập nhật giá bán, giảm giá và cấu hình trò chơi</div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/games/create" class="btn-admin btn-admin-primary">
            + Thêm Trò Chơi Mới
        </a>
    </div>
</div>

<!-- Thanh Lọc & Tìm Kiếm -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form action="<?= BASE_URL ?>/admin/games" method="GET" style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>"
                   placeholder="Tìm theo tên game hoặc studio..."
                   class="form-control" style="padding: 8px 12px; font-size: 13px;">
        </div>

        <div style="min-width: 180px;">
            <select name="category_id" class="form-control" style="padding: 8px 12px; font-size: 13px;">
                <option value="">-- Tất Cả Thể Loại --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($selectedCategory == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="min-width: 180px;">
            <select name="developer_id" class="form-control" style="padding: 8px 12px; font-size: 13px;">
                <option value="">-- Tất Cả Studio --</option>
                <?php foreach ($developers as $dev): ?>
                    <option value="<?= $dev['id'] ?>" <?= ($selectedDeveloper == $dev['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dev['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-admin btn-admin-secondary" style="padding: 8px 16px;">
            Lọc Dữ Liệu
        </button>

        <?php if (!empty($search) || !empty($selectedCategory) || !empty($selectedDeveloper)): ?>
            <a href="<?= BASE_URL ?>/admin/games" class="btn-admin btn-admin-secondary" style="padding: 8px 16px; color: var(--admin-danger);">
                Bỏ Lọc
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Bảng Danh Sách Trò Chơi -->
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Ảnh Bìa</th>
                    <th style="max-width: 250px;">Tựa Game</th>
                    <th style="max-width: 160px;">Studio</th>
                    <th style="max-width: 160px;">Thể Loại</th>
                    <th style="width: 120px;">Giá Niêm Yết</th>
                    <th style="width: 90px;">Đã Bán</th>
                    <th style="width: 110px;">Trạng Thái</th>
                    <th style="text-align: right; width: 140px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($games)): ?>
                    <?php foreach ($games as $g): ?>
                        <tr>
                            <td>
                                <img src="<?= get_game_cover_url($g['cover_image']) ?>" 
                                     alt="<?= htmlspecialchars($g['title']) ?>"
                                     style="width: 50px; height: 65px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);"
                                     onerror="this.src='<?= ASSETS_URL ?>/uploads/games/default_game.jpg'">
                            </td>
                            <td style="max-width: 250px;">
                                <div style="font-weight: 700; color: var(--admin-text-main); font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($g['title']) ?>">
                                    <?= htmlspecialchars($g['title']) ?>
                                </div>
                                <div style="font-size: 11px; color: var(--admin-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 240px;" title="<?= htmlspecialchars($g['slug']) ?>">
                                    Đường dẫn: <?= htmlspecialchars(mb_strimwidth($g['slug'], 0, 32, '...')) ?>
                                </div>
                            </td>
                            <td style="max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($g['developer_name']) ?>">
                                <span style="font-weight: 600; color: var(--admin-text-main);">
                                    <?= htmlspecialchars($g['developer_name']) ?>
                                </span>
                            </td>
                            <td style="max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($g['category_names'] ?? 'Chưa phân loại') ?>">
                                <span style="font-size: 12px; color: var(--admin-text-muted);">
                                    <?= htmlspecialchars($g['category_names'] ?? 'Chưa phân loại') ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($g['price'] == 0): ?>
                                    <span style="color: var(--admin-success); font-weight: 700;">MIỄN PHÍ</span>
                                <?php else: ?>
                                    <div style="font-weight: 700; color: #fff;">
                                        <?= number_format($g['price'] * (100 - $g['discount_percent']) / 100, 0, ',', '.') ?> đ
                                    </div>
                                    <?php if ($g['discount_percent'] > 0): ?>
                                        <div style="font-size: 11px; color: var(--admin-danger); text-decoration: line-through;">
                                            <?= number_format($g['price'], 0, ',', '.') ?> đ (-<?= $g['discount_percent'] ?>%)
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--admin-primary);">
                                    <?= number_format($g['total_sold']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($g['status'] == 1): ?>
                                    <span class="badge-status badge-active">HIỂN THỊ</span>
                                <?php else: ?>
                                    <span class="badge-status badge-hidden">ẨN</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= BASE_URL ?>/admin/games/edit?id=<?= $g['id'] ?>" 
                                   class="btn-admin btn-admin-secondary btn-admin-sm">
                                    Sửa
                                </a>
                                <a href="<?= BASE_URL ?>/admin/games/delete?id=<?= $g['id'] ?>" 
                                   class="btn-admin btn-admin-danger btn-admin-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa game <?= htmlspecialchars(addslashes($g['title'])) ?>?');">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: var(--admin-text-muted);">
                            Không tìm thấy trò chơi nào phù hợp với tiêu chí lọc.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
