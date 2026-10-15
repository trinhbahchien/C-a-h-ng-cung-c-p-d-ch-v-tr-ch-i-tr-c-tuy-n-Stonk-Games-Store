<div class="page-header">
    <div>
        <h1 class="page-title">Thêm Trò Chơi Mới</h1>
        <div class="page-subtitle">Nhập thông tin game, studio, thể loại và cấu hình phần cứng PC</div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/games" class="btn-admin btn-admin-secondary">
            Quay Lại Danh Sách
        </a>
    </div>
</div>

<form action="<?= BASE_URL ?>/admin/games/create" method="POST" enctype="multipart/form-data">
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
                    <input type="text" name="title" maxlength="120" required class="form-control" placeholder="Ví dụ: Black Myth: Wukong">
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Đường Dẫn Tĩnh (Slug)</label>
                        <input type="text" name="slug" maxlength="70" class="form-control" placeholder="Tự động tạo nếu để trống (vd: black-myth-wukong)">
                        <div class="form-hint">Để trống để hệ thống tự động sinh từ tên game (tối đa 70 ký tự)</div>
                    </div>
                    <div class="form-col">
                        <label class="form-label">Studio Phát Triển *</label>
                        <select name="developer_id" required class="form-control">
                            <option value="">-- Chọn Studio --</option>
                            <?php foreach ($developers as $dev): ?>
                                <option value="<?= $dev['id'] ?>"><?= htmlspecialchars($dev['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Giá Bán Niêm Yết (VND) *</label>
                        <input type="number" step="1000" name="price" required class="form-control" placeholder="1200000 (Điền 0 nếu là game miễn phí)">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Giảm Giá (%)</label>
                        <input type="number" min="0" max="100" name="discount_percent" value="0" class="form-control" placeholder="0 - 100">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô Tả Ngắn (Giới thiệu tóm tắt) *</label>
                    <textarea name="short_description" rows="3" required class="form-control" placeholder="Tóm tắt ngắn gọn hiển thị trên thẻ card và carousel nổi bật..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô Tả Chi Tiết Trò Chơi *</label>
                    <textarea name="description" rows="7" required class="form-control" placeholder="Cốt truyện, tính năng lối chơi, đồ họa, bối cảnh chi tiết..."></textarea>
                </div>
            </div>

            <!-- Khung 2: Cấu hình phần cứng PC -->
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
                        <input type="text" name="min_os" value="Windows 10 64-bit" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Bộ Xử Lý (CPU Tối Thiểu)</label>
                        <input type="text" name="min_cpu" value="Intel Core i5-8400 / AMD Ryzen 3 3300X" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Bộ Nhớ RAM</label>
                        <input type="text" name="min_ram" value="12 GB RAM" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Card Đồ Họa (GPU)</label>
                        <input type="text" name="min_gpu" value="NVIDIA GeForce GTX 1060 6GB / AMD Radeon RX 580" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Dung Lượng Ổ Cứng</label>
                        <input type="text" name="min_storage" value="70 GB dung lượng khả dụng" class="form-control">
                    </div>
                </div>

                <h4 style="font-size: 13px; font-weight: 700; color: var(--admin-secondary); margin: 25px 0 15px; text-transform: uppercase;">
                    2. Cấu Hình Đề Nghị (Recommended)
                </h4>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Hệ Điều Hành (Rec OS)</label>
                        <input type="text" name="rec_os" value="Windows 10/11 64-bit" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Bộ Xử Lý (CPU Đề Nghị)</label>
                        <input type="text" name="rec_cpu" value="Intel Core i7-8700K / AMD Ryzen 5 3600X" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Bộ Nhớ RAM</label>
                        <input type="text" name="rec_ram" value="16 GB RAM" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Card Đồ Họa (GPU)</label>
                        <input type="text" name="rec_gpu" value="NVIDIA GeForce RTX 2060 / AMD Radeon RX 5700" class="form-control">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Dung Lượng Ổ Cứng</label>
                        <input type="text" name="rec_storage" value="70 GB ổ cứng SSD" class="form-control">
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
                    <input type="date" name="release_date" value="<?= date('Y-m-d') ?>" class="form-control">
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 15px;">
                    <input type="checkbox" name="status" id="status" value="1" checked style="width: 18px; height: 18px;">
                    <label for="status" style="font-size: 13px; font-weight: 600; color: #fff; cursor: pointer;">
                        Hiển Thị Công Khai Trên Sàn
                    </label>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" style="width: 18px; height: 18px;">
                    <label for="is_featured" style="font-size: 13px; font-weight: 600; color: var(--admin-primary); cursor: pointer;">
                        Đưa Lên Slider Nổi Bật (Spotlight)
                    </label>
                </div>

                <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%; padding: 12px;">
                        Lưu Trò Chơi Mới
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

                <div class="form-group">
                    <label class="form-label">Tải Lên Ảnh Bìa (Upload)</label>
                    <input type="file" name="cover_image" accept="image/*" class="form-control">
                    <div class="form-hint">Định dạng: JPG, PNG, WEBP (Khuyên nghị tỷ lệ 3:4)</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Hoặc Điền Tên Tệp Ảnh Có Sẵn</label>
                    <input type="text" name="cover_image_text" placeholder="elden_ring.jpg" class="form-control">
                    <div class="form-hint">Nếu tệp đã nằm trong thư mục uploads/games</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mã Video YouTube Trailer (ID)</label>
                    <input type="text" name="trailer_url" placeholder="AKXiKBnzpBQ hoặc link YouTube" class="form-control">
                    <div class="form-hint">Nhập ID video YouTube để trình chiếu trailer</div>
                </div>
            </div>

            <!-- Khung 5: Chọn thể loại -->
            <div class="admin-card">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                    Thể Loại (Genres)
                </h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 8px; max-height: 240px; overflow-y: auto; padding-right: 5px;">
                    <?php foreach ($categories as $cat): ?>
                        <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #c9d1d9; cursor: pointer;">
                            <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" style="width: 16px; height: 16px;">
                            <span><?= htmlspecialchars($cat['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</form>
