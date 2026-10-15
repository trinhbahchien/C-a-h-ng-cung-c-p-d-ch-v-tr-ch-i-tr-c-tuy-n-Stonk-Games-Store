<?php
/**
 * Stonk Games Store - Cửa Hàng & Bộ Lọc Đa Tầng (Catalog Epic / Steam Style)
 * 
 * @var array $games Danh sach game theo bo loc
 * @var array $categories Danh sach the loai
 * @var array $developers Danh sach studio
 * @var int $activeCategory The loai dang chon
 * @var int $activeDev Studio dang chon
 * @var string $activeSort Kieu sap xep
 * @var string $searchQuery Tu khoa tim kiem
 * @var bool $isFree Loc game mien phi
 * @var bool $isSale Loc game giam gia
 */
?>

<style>
/* ==========================================================================
   CATALOG PAGE - EPIC GAMES STORE & STEAM STOREFRONT
   ========================================================================== */

.catalog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.catalog-title {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.catalog-layout-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
}

@media (max-width: 992px) {
    .catalog-layout-grid {
        grid-template-columns: 1fr;
    }
}

/* Sidebar Filters */
.catalog-sidebar {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    height: fit-content;
}

.filter-section {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.filter-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.filter-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 14px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-search-box {
    position: relative;
    margin-bottom: 15px;
}

.filter-search-input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 13.5px;
    box-sizing: border-box;
}

.filter-search-input:focus {
    outline: none;
    border-color: var(--primary);
}

.filter-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
}

.filter-options-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-height: 220px;
    overflow-y: auto;
}

.filter-checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: var(--text-secondary);
    cursor: pointer;
    transition: color 0.2s ease;
}

.filter-checkbox-label:hover {
    color: var(--primary);
}

.filter-checkbox-label.active {
    color: var(--primary);
    font-weight: 700;
}

/* Toolbar Controls */
.catalog-controls-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 12px 20px;
    margin-bottom: 25px;
}

.catalog-count-text {
    font-size: 14px;
    color: var(--text-secondary);
}

.catalog-sort-select {
    padding: 8px 14px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 13px;
    cursor: pointer;
}

.catalog-sort-select:focus {
    outline: none;
    border-color: var(--primary);
}

/* Games Grid */
.catalog-games-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 22px;
}

.catalog-game-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    text-decoration: none;
}

.catalog-game-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.catalog-card-media {
    position: relative;
    height: 160px;
    background: #000;
    overflow: hidden;
}

.catalog-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.catalog-game-card:hover .catalog-card-img {
    transform: scale(1.06);
}

.catalog-card-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
}

.catalog-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 6px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.catalog-card-studio {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 12px;
}

.catalog-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 10px;
    border-top: 1px dashed var(--border-color);
}
</style>

<!-- Header -->
<div class="catalog-header">
    <h1 class="catalog-title">
        <i class="fa-solid fa-store" style="color: var(--primary);"></i> CỬA HÀNG GAME BẢN QUYỀN
    </h1>
</div>

<!-- Main Layout Grid -->
<div class="catalog-layout-grid">
    
    <!-- Sidebar Filters Form -->
    <aside class="catalog-sidebar">
        <form method="GET" action="<?= BASE_URL ?>/catalog" id="catalogFilterForm">
            <input type="hidden" name="sort" id="catalogHiddenSort" value="<?= htmlspecialchars($activeSort) ?>">
            
            <!-- Tim Kiem Tu Khoa Kep -->
            <div class="filter-section">
                <h4 class="filter-title"><i class="fa-solid fa-magnifying-glass"></i> Tìm Kiếm</h4>
                <div class="filter-search-box">
                    <i class="fa-solid fa-magnifying-glass filter-search-icon"></i>
                    <input type="text" name="search" class="filter-search-input" placeholder="Tên game hoặc studio..." value="<?= htmlspecialchars($searchQuery) ?>" onkeydown="if(event.key==='Enter') this.form.submit();">
                </div>
            </div>

            <!-- Loc The Loai -->
            <div class="filter-section">
                <h4 class="filter-title"><i class="fa-solid fa-gamepad"></i> Thể Loại</h4>
                <div class="filter-options-list">
                    <label class="filter-checkbox-label <?= $activeCategory == 0 ? 'active' : '' ?>">
                        <input type="radio" name="category" value="0" <?= $activeCategory == 0 ? 'checked' : '' ?> onchange="this.form.submit()">
                        Tất cả thể loại
                    </label>
                    <?php foreach ($categories as $cat): ?>
                        <label class="filter-checkbox-label <?= $activeCategory == $cat['id'] ? 'active' : '' ?>">
                            <input type="radio" name="category" value="<?= $cat['id'] ?>" <?= $activeCategory == $cat['id'] ? 'checked' : '' ?> onchange="this.form.submit()">
                            <?= htmlspecialchars($cat['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Loc Muc Gia -->
            <div class="filter-section">
                <h4 class="filter-title"><i class="fa-solid fa-tags"></i> Mức Giá</h4>
                <div class="filter-options-list">
                    <label class="filter-checkbox-label <?= empty($maxPrice) && !$isFree ? 'active' : '' ?>">
                        <input type="radio" name="max_price" value="" <?= empty($maxPrice) && !$isFree ? 'checked' : '' ?> onchange="this.form.submit()">
                        Tất cả mức giá
                    </label>
                    <label class="filter-checkbox-label <?= $maxPrice == 300000 ? 'active' : '' ?>">
                        <input type="radio" name="max_price" value="300000" <?= $maxPrice == 300000 ? 'checked' : '' ?> onchange="this.form.submit()">
                        Dưới 300.000 đ
                    </label>
                    <label class="filter-checkbox-label <?= $maxPrice == 700000 ? 'active' : '' ?>">
                        <input type="radio" name="max_price" value="700000" <?= $maxPrice == 700000 ? 'checked' : '' ?> onchange="this.form.submit()">
                        Dưới 700.000 đ
                    </label>
                    <label class="filter-checkbox-label <?= $maxPrice == 1000000 ? 'active' : '' ?>">
                        <input type="radio" name="max_price" value="1000000" <?= $maxPrice == 1000000 ? 'checked' : '' ?> onchange="this.form.submit()">
                        Dưới 1.000.000 đ
                    </label>
                    <label class="filter-checkbox-label <?= $maxPrice == 1500000 ? 'active' : '' ?>">
                        <input type="radio" name="max_price" value="1500000" <?= $maxPrice == 1500000 ? 'checked' : '' ?> onchange="this.form.submit()">
                        Dưới 1.500.000 đ
                    </label>
                </div>
            </div>

            <!-- Loc Studio / Developer -->
            <div class="filter-section">
                <h4 class="filter-title"><i class="fa-solid fa-building"></i> Nhà Phát Triển</h4>
                <div class="filter-options-list">
                    <label class="filter-checkbox-label <?= $activeDev == 0 ? 'active' : '' ?>">
                        <input type="radio" name="developer" value="0" <?= $activeDev == 0 ? 'checked' : '' ?> onchange="this.form.submit()">
                        Tất cả nhà phát triển
                    </label>
                    <?php foreach ($developers as $dev): ?>
                        <label class="filter-checkbox-label <?= $activeDev == $dev['id'] ? 'active' : '' ?>">
                            <input type="radio" name="developer" value="<?= $dev['id'] ?>" <?= $activeDev == $dev['id'] ? 'checked' : '' ?> onchange="this.form.submit()">
                            <?= htmlspecialchars($dev['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tuy Chon Nhanh -->
            <div class="filter-section">
                <h4 class="filter-title"><i class="fa-solid fa-sliders"></i> Tùy Chọn</h4>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label class="filter-checkbox-label">
                        <input type="checkbox" name="sale" value="1" <?= $isSale ? 'checked' : '' ?> onchange="this.form.submit()">
                        Đang giảm giá đặc biệt
                    </label>
                    <label class="filter-checkbox-label">
                        <input type="checkbox" name="free" value="1" <?= $isFree ? 'checked' : '' ?> onchange="this.form.submit()">
                        Game miễn phí (Free-to-Play)
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-buy-primary" style="width: 100%; margin-top: 15px; font-size: 13.5px; padding: 10px; border-radius: 8px;">
                Áp Dụng Bộ Lọc
            </button>
            <a href="<?= BASE_URL ?>/catalog" class="btn-buy-secondary" style="width: 100%; margin-top: 8px; font-size: 12.5px; padding: 8px; text-decoration: none; box-sizing: border-box; text-align: center; border-radius: 8px;">
                Đặt Lại Bộ Lọc
            </a>
        </form>
    </aside>

    <!-- Main Content Right -->
    <main>
        <!-- Controls Bar -->
        <div class="catalog-controls-bar">
            <div class="catalog-count-text">
                Tìm thấy <strong><?= count($games) ?></strong> tựa game phù hợp
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="catalogSort" style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Sắp xếp:</label>
                <select id="catalogSort" class="catalog-sort-select" onchange="document.getElementById('catalogHiddenSort').value = this.value; document.getElementById('catalogFilterForm').submit();">
                    <option value="newest" <?= $activeSort === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="bestselling" <?= $activeSort === 'bestselling' ? 'selected' : '' ?>>Bán chạy nhất</option>
                    <option value="rating" <?= $activeSort === 'rating' ? 'selected' : '' ?>>Đánh giá cao nhất</option>
                    <option value="price_asc" <?= $activeSort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= $activeSort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                </select>
            </div>
        </div>

        <!-- Games Grid -->
        <?php if (!empty($games)): ?>
            <div class="catalog-games-grid">
                <?php foreach ($games as $g): ?>
                    <?php 
                        $cUrl = get_game_cover_url($g['cover_image']);
                        $gPrice = (float)$g['price'];
                        $gDisc = (int)$g['discount_percent'];
                        $gFinal = $gPrice * (1 - ($gDisc / 100));
                    ?>
                    <a href="<?= BASE_URL ?>/game/detail?id=<?= $g['id'] ?>" class="catalog-game-card">
                        <div class="catalog-card-media">
                            <img src="<?= $cUrl ?>" alt="<?= htmlspecialchars($g['title']) ?>" class="catalog-card-img">
                            <?php if ($gPrice == 0): ?>
                                <span class="game-badge-free">FREE TO PLAY</span>
                            <?php elseif ($gDisc > 0): ?>
                                <span class="game-badge-discount">-<?= $gDisc ?>%</span>
                            <?php endif; ?>
                        </div>
                        <div class="catalog-card-body">
                            <div>
                                <h3 class="catalog-card-title" title="<?= htmlspecialchars($g['title']) ?>">
                                    <?= htmlspecialchars($g['title']) ?>
                                </h3>
                                <div class="catalog-card-studio">
                                    <?= htmlspecialchars($g['developer_name'] ?? 'Publisher') ?>
                                </div>
                            </div>
                            <div class="catalog-card-footer">
                                <div>
                                    <?php if ($gPrice == 0): ?>
                                        <span style="color: #00e676; font-weight: 800; font-size: 14px;">MIỄN PHÍ</span>
                                    <?php else: ?>
                                        <span style="font-weight: 800; color: var(--text-primary); font-size: 14px;">
                                            <?= number_format($gFinal, 0, ',', '.') ?> đ
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span style="font-size: 11px; color: var(--text-muted);">
                                    <i class="fa-solid fa-star" style="color: #f1c40f;"></i> <?= (float)$g['positive_rate'] ?>%
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 60px 20px; text-align: center;">
                <i class="fa-solid fa-magnifying-glass" style="font-size: 40px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h3 style="margin: 0 0 10px 0; color: var(--text-primary);">Không tìm thấy trò chơi nào phù hợp</h3>
                <p style="margin: 0 0 20px 0; color: var(--text-secondary); font-size: 14px;">Thử đổi từ khóa tìm kiếm hoặc bỏ chọn một số tiêu chí lọc xem sao nhé.</p>
                <a href="<?= BASE_URL ?>/catalog" class="btn-gaming btn-primary" style="padding: 10px 24px; font-size: 13px;">
                    Đặt Lại Tất Cả Bộ Lọc
                </a>
            </div>
        <?php endif; ?>
    </main>
</div>
