<div class="page-header">
    <div>
        <h1 class="page-title">Chỉnh Sửa Bài Tin: <?= htmlspecialchars($article['title']) ?></h1>
        <div class="page-subtitle">Cập nhật nội dung bài viết, phân loại chuyên mục và media hình ảnh</div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/news" class="btn-admin btn-admin-secondary">
            Quay Lại Danh Sách
        </a>
    </div>
</div>

<form action="<?= BASE_URL ?>/admin/news/edit?id=<?= $article['id'] ?>" method="POST" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        
        <!-- Cột trái: Nội dung bài viết -->
        <div>
            <div class="admin-card">
                <div class="form-group">
                    <label class="form-label">Tiêu Đề Bài Viết *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Đường Dẫn Tĩnh (Slug)</label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($article['slug']) ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Nội Dung Chi Tiết Bài Viết *</label>
                    <textarea name="content" rows="12" required class="form-control"><?= htmlspecialchars($article['content']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- Cột phải: Phân loại & Hình ảnh -->
        <div>
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Phân Loại & Liên Kết
                </h3>

                <div class="form-group">
                    <label class="form-label">Loại Tin Tức *</label>
                    <select name="type" id="newsType" class="form-control" onchange="toggleNewsLinks()">
                        <option value="system" <?= ($article['type'] === 'system') ? 'selected' : '' ?>>Thông Báo Hệ Thống</option>
                        <option value="game" <?= ($article['type'] === 'game') ? 'selected' : '' ?>>Tin Theo Trò Chơi (Game Update)</option>
                        <option value="genre" <?= ($article['type'] === 'genre') ? 'selected' : '' ?>>Tin Theo Thể Loại (Genre Spotlight)</option>
                    </select>
                </div>

                <div class="form-group" id="gameSelectBox" style="display: <?= ($article['type'] === 'game') ? 'block' : 'none' ?>;">
                    <label class="form-label">Chọn Game Liên Quan</label>
                    <select name="game_id" class="form-control">
                        <option value="">-- Chọn Tựa Game --</option>
                        <?php foreach ($games as $g): ?>
                            <option value="<?= $g['id'] ?>" <?= ($article['game_id'] == $g['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" id="catSelectBox" style="display: <?= ($article['type'] === 'genre') ? 'block' : 'none' ?>;">
                    <label class="form-label">Chọn Thể Loại Liên Quan</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Chọn Thể Loại --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($article['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    <img src="<?= ASSETS_URL ?>/uploads/news/<?= htmlspecialchars($article['image'] ?? 'news_default.jpg') ?>" 
                         alt="Thumb"
                         style="max-width: 100%; height: auto; max-height: 120px; border-radius: 6px; border: 1px solid var(--admin-border);"
                         onerror="this.src='<?= ASSETS_URL ?>/uploads/news/news_sale.jpg'">
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label">Thay Đổi Ảnh Bìa (Tải lên mới)</label>
                    <input type="file" name="image" accept="image/*" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Hoặc Điền Tên Tệp Ảnh Có Sẵn</label>
                    <input type="text" name="image_text" value="<?= htmlspecialchars($article['image'] ?? '') ?>" class="form-control">
                </div>

                <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; padding: 12px;">
                        Cập Nhật Bài Viết
                    </button>
                    <a href="<?= BASE_URL ?>/admin/news" class="btn-admin btn-admin-secondary" style="width: 100%; padding: 12px; text-align: center;">
                        Hủy Bỏ
                    </a>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
function toggleNewsLinks() {
    var type = document.getElementById('newsType').value;
    var gameBox = document.getElementById('gameSelectBox');
    var catBox = document.getElementById('catSelectBox');

    gameBox.style.display = (type === 'game') ? 'block' : 'none';
    catBox.style.display = (type === 'genre') ? 'block' : 'none';
}
</script>
