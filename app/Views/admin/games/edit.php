<div class="page-header">
    <div>
        <h1 class="page-title">Chỉnh Sửa Trò Chơi: <?= htmlspecialchars($game['title']) ?></h1>
        <div class="page-subtitle">Cập nhật thông tin game, giá bán, media và cấu hình phần cứng</div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/games" class="btn-admin btn-admin-secondary">
            Quay Lại Danh Sách
        </a>
    </div>
</div>

<form action="<?= BASE_URL ?>/admin/games/edit?id=<?= $game['id'] ?>" method="POST" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        
        <!-- Cột trái: Thông tin trò chơi & Cấu hình -->
        <div>
            <!-- Khung 1: Thông tin cơ bản -->
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Thông Tin Cơ Bản
                </h3>

                <div class="form-group">
                    <label class="form-label">Tựa Game (Tiêu Đề) *</label>
                    <input type="text" name="title" maxlength="120" value="<?= htmlspecialchars($game['title']) ?>" required class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Đường Dẫn Tĩnh (Slug)</label>
                        <input type="text" name="slug" maxlength="70" value="<?= htmlspecialchars($game['slug']) ?>" class="form-control">
                        <div class="form-hint" style="font-size: 11px; color: var(--admin-text-muted); margin-top: 4px;">Tối đa 70 ký tự chữ thường và dấu gạch ngang</div>
                    </div>
                    <div class="form-col">
                        <label class="form-label">Studio Phát Triển *</label>
                        <select name="developer_id" required class="form-control">
                            <option value="">-- Chọn Studio --</option>
                            <?php foreach ($developers as $dev): ?>
                                <option value="<?= $dev['id'] ?>" <?= ($game['developer_id'] == $dev['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dev['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Giá Bán Niêm Yết (VND) *</label>
                        <input type="number" step="1000" name="price" value="<?= (int)$game['price'] ?>" required class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Giảm Giá (%)</label>
                        <input type="number" min="0" max="100" name="discount_percent" value="<?= (int)$game['discount_percent'] ?>" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô Tả Ngắn (Giới thiệu tóm tắt) *</label>
                    <textarea name="short_description" rows="3" required class="form-control"><?= htmlspecialchars($game['short_description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô Tả Chi Tiết Trò Chơi *</label>
                    <textarea name="description" rows="7" required class="form-control"><?= htmlspecialchars($game['description']) ?></textarea>
                </div>
            </div>

            <!-- Khung 2: Cấu hình phần cứng PC -->
            <?php $req = $game['requirements'] ?? []; ?>
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Cấu Hình Hệ Thống PC (System Requirements)
                </h3>

                <h4 style="font-size: 13px; font-weight: 700; color: var(--admin-primary); margin-bottom: 15px; text-transform: uppercase;">
                    1. Cấu Hình Tối Thiểu (Minimum)
                </h4>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Hệ Điều Hành (Min OS)</label>
                        <input type="text" name="min_os" value="<?= htmlspecialchars($req['min_os'] ?? 'Windows 10 64-bit') ?>" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Bộ Xử Lý (CPU Tối Thiểu)</label>
                        <input type="text" name="min_cpu" value="<?= htmlspecialchars($req['min_cpu'] ?? 'Intel Core i5-8400') ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Bộ Nhớ RAM</label>
                        <input type="text" name="min_ram" value="<?= htmlspecialchars($req['min_ram'] ?? '8 GB RAM') ?>" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Card Đồ Họa (GPU)</label>
                        <input type="text" name="min_gpu" value="<?= htmlspecialchars($req['min_gpu'] ?? 'GTX 1060') ?>" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Dung Lượng Ổ Cứng</label>
                        <input type="text" name="min_storage" value="<?= htmlspecialchars($req['min_storage'] ?? '50 GB') ?>" class="form-control">
                    </div>
                </div>

                <h4 style="font-size: 13px; font-weight: 700; color: var(--admin-secondary); margin: 25px 0 15px; text-transform: uppercase;">
                    2. Cấu Hình Đề Nghị (Recommended)
                </h4>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Hệ Điều Hành (Rec OS)</label>
                        <input type="text" name="rec_os" value="<?= htmlspecialchars($req['rec_os'] ?? 'Windows 10/11 64-bit') ?>" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Bộ Xử Lý (CPU Đề Nghị)</label>
                        <input type="text" name="rec_cpu" value="<?= htmlspecialchars($req['rec_cpu'] ?? 'Intel Core i7-8700K') ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Bộ Nhớ RAM</label>
                        <input type="text" name="rec_ram" value="<?= htmlspecialchars($req['rec_ram'] ?? '16 GB RAM') ?>" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Card Đồ Họa (GPU)</label>
                        <input type="text" name="rec_gpu" value="<?= htmlspecialchars($req['rec_gpu'] ?? 'RTX 2060') ?>" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Dung Lượng Ổ Cứng</label>
                        <input type="text" name="rec_storage" value="<?= htmlspecialchars($req['rec_storage'] ?? '50 GB SSD') ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Media, Thể loại & Trạng thái phát hành -->
        <div>
            <!-- Khung 3: Thiết lập phát hành -->
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Thiết Lập Phát Hành
                </h3>

                <div class="form-group">
                    <label class="form-label">Ngày Phát Hành</label>
                    <input type="date" name="release_date" value="<?= htmlspecialchars($game['release_date']) ?>" class="form-control">
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                    <input type="checkbox" name="status" id="status" value="1" <?= ($game['status'] == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    <label for="status" style="font-size: 13px; font-weight: 600; color: #fff; cursor: pointer;">
                        Hiển Thị Công Khai Trên Sàn
                    </label>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" <?= ($game['is_featured'] == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    <label for="is_featured" style="font-size: 13px; font-weight: 600; color: var(--admin-primary); cursor: pointer;">
                        Đưa Lên Slider Nổi Bật (Spotlight)
                    </label>
                </div>

                <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; padding: 12px;">
                        Cập Nhật Trò Chơi
                    </button>
                    <a href="<?= BASE_URL ?>/admin/games" class="btn-admin btn-admin-secondary" style="width: 100%; padding: 12px; text-align: center;">
                        Hủy Bỏ
                    </a>
                </div>
            </div>

            <!-- Khung 4: Hình ảnh & Video -->
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Hình Ảnh & Video Trailer
                </h3>

                <div style="margin-bottom: 15px; text-align: center;">
                    <img src="<?= get_game_cover_url($game['cover_image']) ?>" 
                         alt="Cover" 
                         style="max-width: 140px; height: auto; border-radius: 6px; border: 1px solid var(--admin-border);"
                         onerror="this.src='<?= ASSETS_URL ?>/uploads/games/default_game.jpg'">
                    <div style="font-size: 11px; color: var(--admin-text-muted); margin-top: 6px;">
                        Tệp ảnh hiện tại: <?= htmlspecialchars($game['cover_image']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Thay Đổi Ảnh Bìa (Tải lên mới)</label>
                    <input type="file" name="cover_image" accept="image/*" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Hoặc Điền Tên Tệp Ảnh Có Sẵn</label>
                    <input type="text" name="cover_image_text" value="<?= htmlspecialchars($game['cover_image']) ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Mã Video YouTube Trailer (ID)</label>
                    <input type="text" name="trailer_url" value="<?= htmlspecialchars($game['trailer_url'] ?? '') ?>" class="form-control">
                </div>
            </div>

            <!-- Khung 5: Chọn thể loại -->
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Thể Loại (Genres)
                </h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 8px; max-height: 240px; overflow-y: auto; padding-right: 5px;">
                    <?php 
                    $selectedCatIds = $game['category_ids'] ?? [];
                    foreach ($categories as $cat): 
                        $checked = in_array($cat['id'], $selectedCatIds);
                    ?>
                        <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #c9d1d9; cursor: pointer;">
                            <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" <?= $checked ? 'checked' : '' ?> style="width: 16px; height: 16px;">
                            <span><?= htmlspecialchars($cat['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</form>
