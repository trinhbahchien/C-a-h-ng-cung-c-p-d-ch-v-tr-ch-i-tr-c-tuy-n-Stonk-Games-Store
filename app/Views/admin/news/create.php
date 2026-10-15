<div class="page-header">
    <div>
        <h1 class="page-title">Đăng Bài Tin Tức Mới</h1>
        <div class="page-subtitle">Soạn thảo thông báo hệ thống, tin phát hành game hoặc chuyên mục thể loại</div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/news" class="btn-admin btn-admin-secondary">
            Quay Lại Danh Sách
        </a>
    </div>
</div>

<form action="<?= BASE_URL ?>/admin/news/create" method="POST" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        
        <!-- Cột trái: Nội dung bài viết -->
        <div>
            <div class="admin-card">
                <div class="form-group">
                    <label class="form-label">Tiêu Đề Bài Viết *</label>
                    <input type="text" name="title" required class="form-control" placeholder="Ví dụ: Cập nhật hệ thống thanh toán VietQR tự động">
                </div>

                <div class="form-group">
                    <label class="form-label">Đường Dẫn Tĩnh (Slug)</label>
                    <input type="text" name="slug" class="form-control" placeholder="Tự động tạo từ tiêu đề nếu để trống">
                </div>

                <div class="form-group">
                    <label class="form-label">Nội Dung Chi Tiết Bài Viết *</label>
                    <textarea name="content" rows="12" required class="form-control" placeholder="Soạn thảo toàn văn nội dung bài viết tin tức tại đây..."></textarea>
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
                        <option value="system">Thông Báo Hệ Thống</option>
                        <option value="game">Tin Theo Trò Chơi (Game Update)</option>
                        <option value="genre">Tin Theo Thể Loại (Genre Spotlight)</option>
                    </select>
                </div>

                <div class="form-group" id="gameSelectBox" style="display: none;">
                    <label class="form-label">Chọn Game Liên Quan</label>
                    <select name="game_id" class="form-control">
                        <option value="">-- Chọn Tựa Game --</option>
                        <?php foreach ($games as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" id="catSelectBox" style="display: none;">
                    <label class="form-label">Chọn Thể Loại Liên Quan</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Chọn Thể Loại --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">Tải Lên Ảnh Bài Viết (Upload)</label>
                    <input type="file" name="image" accept="image/*" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Hoặc Điền Tên Tệp Ảnh Có Sẵn</label>
                    <input type="text" name="image_text" placeholder="news_sale.jpg" class="form-control">
                    <div class="form-hint">Trong thư mục public/assets/uploads/news/</div>
                </div>

                <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; padding: 12px;">
                        Đăng Bài Viết
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
toggleNewsLinks();
</script>
