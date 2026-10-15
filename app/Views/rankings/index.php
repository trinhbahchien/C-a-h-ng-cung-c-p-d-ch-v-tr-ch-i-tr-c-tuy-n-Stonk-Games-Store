<?php
/**
 * Stonk Games Store - Bảng Xếp Hạng Game & Doanh Số (Steam Charts Style)
 * 
 * @var array $rankedGames Danh sach game da sap xep theo tieu chi
 * @var array $categories Danh sach the loai de loc
 * @var string $activeTab Tab dang chon (bestselling, top_rated, low_refund, revenue)
 * @var int $activeCategory ID the loai dang loc
 * @var string $currentCategoryName Ten the loai dang loc
 */

$top3 = array_slice($rankedGames, 0, 3);
?>

<style>
/* ==========================================================================
   RANKINGS PAGE - STEAM CHARTS & LEADERBOARD STYLE
   ========================================================================== */

/* Hero Banner */
.rankings-hero {
    background: linear-gradient(135deg, rgba(0, 242, 254, 0.08) 0%, rgba(79, 172, 254, 0.04) 100%);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 35px 30px;
    margin-bottom: 35px;
    position: relative;
    overflow: hidden;
}

.rankings-hero::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
    opacity: 0.3;
    pointer-events: none;
}

.rankings-hero-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 10px 0;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.rankings-hero-title i {
    color: #f1c40f;
}

.rankings-hero-desc {
    font-size: 14.5px;
    color: var(--text-secondary);
    margin: 0;
    max-width: 780px;
    line-height: 1.6;
}

/* Tabs & Filter Bar */
.rankings-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.rankings-tabs-group {
    display: flex;
    gap: 8px;
    background: var(--bg-surface);
    padding: 6px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    overflow-x: auto;
}

.rankings-tab-btn {
    padding: 9px 18px;
    font-size: 13.5px;
    font-weight: 700;
    border-radius: 7px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.rankings-tab-btn:hover {
    color: var(--text-primary);
    background: rgba(255, 255, 255, 0.05);
}

.rankings-tab-btn.active {
    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
    color: #0b0e14 !important;
    box-shadow: 0 2px 10px var(--primary-glow);
}

.rankings-filter-select {
    padding: 10px 16px;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 13.5px;
    font-family: inherit;
    cursor: pointer;
}

.rankings-filter-select:focus {
    outline: none;
    border-color: var(--primary);
}

/* Podium Top 3 Cards */
.podium-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

@media (max-width: 900px) {
    .podium-grid {
        grid-template-columns: 1fr;
    }
}

.podium-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.podium-card:hover {
    transform: translateY(-5px);
}

.podium-card.rank-1 {
    border-color: rgba(241, 196, 15, 0.5);
    box-shadow: 0 10px 30px rgba(241, 196, 15, 0.15);
}

.podium-card.rank-2 {
    border-color: rgba(189, 195, 199, 0.5);
    box-shadow: 0 10px 30px rgba(189, 195, 199, 0.1);
}

.podium-card.rank-3 {
    border-color: rgba(211, 84, 0, 0.5);
    box-shadow: 0 10px 30px rgba(211, 84, 0, 0.1);
}

.podium-badge-ribbon {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    padding: 5px 12px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 12px;
    color: #0b0e14;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.ribbon-gold {
    background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%);
}

.ribbon-silver {
    background: linear-gradient(135deg, #e0e0e0 0%, #bdc3c7 100%);
}

.ribbon-bronze {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    color: #ffffff !important;
}

.podium-media {
    position: relative;
    height: 170px;
    overflow: hidden;
    background: #000;
}

.podium-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.podium-card:hover .podium-img {
    transform: scale(1.05);
}

.podium-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
}

.podium-title {
    font-size: 17px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 6px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.podium-studio {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 15px;
}

.podium-stats-list {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 16px;
    font-size: 12.5px;
}

.podium-stat-item {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
}

.podium-stat-label {
    color: var(--text-muted);
}

.podium-stat-value {
    color: var(--text-primary);
    font-weight: 700;
}

/* Leaderboard Full Table */
.leaderboard-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.leaderboard-table-wrapper {
    overflow-x: auto;
}

.leaderboard-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    text-align: left;
}

.leaderboard-table th {
    background: var(--bg-card);
    padding: 14px 18px;
    color: var(--text-muted);
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.leaderboard-table td {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.leaderboard-row {
    transition: background 0.2s ease;
}

.leaderboard-row:hover {
    background: rgba(255, 255, 255, 0.03);
}

.leaderboard-rank-cell {
    font-weight: 800;
    font-size: 15px;
    color: var(--text-muted);
    width: 50px;
}

.rank-badge-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-weight: 800;
    font-size: 13px;
    color: #0b0e14;
}

.rank-badge-gold { background: #f1c40f; }
.rank-badge-silver { background: #bdc3c7; }
.rank-badge-bronze { background: #e67e22; color: #fff; }

.leaderboard-game-cell {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 260px;
}

.leaderboard-game-thumb {
    width: 60px;
    height: 38px;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
    background: #000;
}

.leaderboard-game-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.leaderboard-game-title {
    font-weight: 700;
    color: var(--text-primary);
    text-decoration: none;
    display: block;
    line-height: 1.4;
    transition: color 0.2s ease;
}

.leaderboard-game-title:hover {
    color: var(--primary);
}

.leaderboard-game-category {
    font-size: 11.5px;
    color: var(--text-muted);
}

.rate-progress-bar {
    width: 90px;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 4px;
}

.rate-progress-fill {
    height: 100%;
    border-radius: 3px;
}
</style>

<!-- Hero Section -->
<div class="rankings-hero">
    <h1 class="rankings-hero-title">
        <i class="fa-solid fa-trophy"></i> BẢNG XẾP HẠNG TRÒ CHƠI &bull; STEAM CHARTS
    </h1>
    <p class="rankings-hero-desc">
        Hệ thống dữ liệu xếp hạng minh bạch thời gian thực của Stonk Games Store. Thống kê phản ánh doanh số bán chạy, mức độ hài lòng của cộng đồng người chơi và tỷ lệ hoàn trả thực tế.
    </p>
</div>

<!-- Toolbar: Tabs & Category Filter -->
<div class="rankings-toolbar">
    <div class="rankings-tabs-group">
        <a href="<?= BASE_URL ?>/rankings?tab=bestselling<?= $activeCategory ? '&category=' . $activeCategory : '' ?>" 
           class="rankings-tab-btn <?= $activeTab === 'bestselling' ? 'active' : '' ?>">
            <i class="fa-solid fa-fire"></i> Bán Chạy Nhất
        </a>
        <a href="<?= BASE_URL ?>/rankings?tab=top_rated<?= $activeCategory ? '&category=' . $activeCategory : '' ?>" 
           class="rankings-tab-btn <?= $activeTab === 'top_rated' ? 'active' : '' ?>">
            <i class="fa-solid fa-star"></i> Đánh Giá Cao Nhất
        </a>
        <a href="<?= BASE_URL ?>/rankings?tab=low_refund<?= $activeCategory ? '&category=' . $activeCategory : '' ?>" 
           class="rankings-tab-btn <?= $activeTab === 'low_refund' ? 'active' : '' ?>">
            <i class="fa-solid fa-shield-check"></i> Ít Hoàn Trả Nhất
        </a>
        <a href="<?= BASE_URL ?>/rankings?tab=revenue<?= $activeCategory ? '&category=' . $activeCategory : '' ?>" 
           class="rankings-tab-btn <?= $activeTab === 'revenue' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i> Doanh Thu Khủng
        </a>
    </div>

    <!-- Dropdown Loc Theo The Loai -->
    <div>
        <form method="GET" action="<?= BASE_URL ?>/rankings" style="margin: 0; display: inline-flex; align-items: center; gap: 8px;">
            <input type="hidden" name="tab" value="<?= htmlspecialchars($activeTab) ?>">
            <label for="categoryFilter" style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Lọc theo:</label>
            <select id="categoryFilter" name="category" class="rankings-filter-select" onchange="this.form.submit()">
                <option value="0">Tất Cả Thể Loại</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $activeCategory == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<?php if (!empty($currentCategoryName)): ?>
    <div style="margin-bottom: 25px; font-size: 14px; color: var(--text-secondary);">
        Đang hiển thị bảng xếp hạng dành riêng cho thể loại: <strong style="color: var(--primary);"><?= htmlspecialchars($currentCategoryName) ?></strong>
    </div>
<?php endif; ?>

<!-- Podium Top 3 Vinh Danh -->
<?php if (!empty($top3)): ?>
    <div class="podium-grid">
        <?php foreach ($top3 as $idx => $pGame): ?>
            <?php 
                $rankNum = $idx + 1;
                $ribbonClass = $rankNum === 1 ? 'ribbon-gold' : ($rankNum === 2 ? 'ribbon-silver' : 'ribbon-bronze');
                $ribbonIcon = $rankNum === 1 ? 'fa-crown' : ($rankNum === 2 ? 'fa-medal' : 'fa-award');
                $ribbonText = $rankNum === 1 ? 'HẠNG 1 &bull; QUÁN QUÂN' : ($rankNum === 2 ? 'HẠNG 2 &bull; Á QUÂN' : 'HẠNG 3 &bull; ĐỒNG');
                $pCover = get_game_cover_url($pGame['cover_image']);
                $pPrice = (float)$pGame['price'];
                $pDiscount = (int)$pGame['discount_percent'];
                $pFinal = $pPrice * (1 - ($pDiscount / 100));
            ?>
            <div class="podium-card rank-<?= $rankNum ?>">
                <div class="podium-badge-ribbon <?= $ribbonClass ?>">
                    <i class="fa-solid <?= $ribbonIcon ?>"></i> <?= $ribbonText ?>
                </div>

                <div class="podium-media">
                    <img src="<?= $pCover ?>" alt="<?= htmlspecialchars($pGame['title']) ?>" class="podium-img">
                </div>

                <div class="podium-body">
                    <div>
                        <h3 class="podium-title" title="<?= htmlspecialchars($pGame['title']) ?>">
                            <?= htmlspecialchars($pGame['title']) ?>
                        </h3>
                        <div class="podium-studio">
                            <i class="fa-solid fa-building"></i> <?= htmlspecialchars($pGame['developer_name'] ?? 'Publisher') ?>
                        </div>

                        <div class="podium-stats-list">
                            <div class="podium-stat-item">
                                <span class="podium-stat-label">Tổng lượt bán:</span>
                                <span class="podium-stat-value" style="color: var(--primary);">
                                    <?= number_format($pGame['total_sold'] ?? 0) ?> bản
                                </span>
                            </div>
                            <div class="podium-stat-item">
                                <span class="podium-stat-label">Đánh giá tích cực:</span>
                                <span class="podium-stat-value" style="color: #00e676;">
                                    <?= (float)$pGame['positive_rate'] ?>%
                                </span>
                            </div>
                            <div class="podium-stat-item">
                                <span class="podium-stat-label">Tỷ lệ hoàn trả:</span>
                                <span class="podium-stat-value" style="color: #ff7675;">
                                    <?= (float)$pGame['refund_rate'] ?>%
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 10px;">
                        <div>
                            <?php if ($pPrice == 0): ?>
                                <span style="color: #00e676; font-weight: 800; font-size: 15px;">MIỄN PHÍ</span>
                            <?php else: ?>
                                <span style="font-weight: 800; font-size: 16px; color: var(--text-primary);">
                                    <?= number_format($pFinal, 0, ',', '.') ?> đ
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= BASE_URL ?>/game/detail?id=<?= $pGame['id'] ?>" class="btn-gaming btn-primary" style="padding: 8px 16px; font-size: 12.5px;">
                            Xem Chi Tiết
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Leaderboard Full Table -->
<div class="leaderboard-card">
    <div class="leaderboard-table-wrapper">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th style="text-align: center; width: 60px;">#</th>
                    <th>Tựa Game & Thể Loại</th>
                    <th>Nhà Phát Triển</th>
                    <th style="text-align: right;">Đã Bán</th>
                    <th style="text-align: center;">Tỷ Lệ Hoàn Trả</th>
                    <th style="text-align: center;">Hài Lòng</th>
                    <th style="text-align: right;">Giá Bán</th>
                    <th style="text-align: center; width: 130px;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rankedGames)): ?>
                    <?php foreach ($rankedGames as $index => $row): ?>
                        <?php 
                            $rank = $index + 1;
                            $rowCover = get_game_cover_url($row['cover_image']);
                            $rowPrice = (float)$row['price'];
                            $rowDiscount = (int)$row['discount_percent'];
                            $rowFinal = $rowPrice * (1 - ($rowDiscount / 100));
                            $posRate = (float)($row['positive_rate'] ?? 90);
                        ?>
                        <tr class="leaderboard-row">
                            <!-- Rank Number -->
                            <td style="text-align: center;" class="leaderboard-rank-cell">
                                <?php if ($rank === 1): ?>
                                    <span class="rank-badge-pill rank-badge-gold">1</span>
                                <?php elseif ($rank === 2): ?>
                                    <span class="rank-badge-pill rank-badge-silver">2</span>
                                <?php elseif ($rank === 3): ?>
                                    <span class="rank-badge-pill rank-badge-bronze">3</span>
                                <?php else: ?>
                                    <?= $rank ?>
                                <?php endif; ?>
                            </td>

                            <!-- Game Thumb & Title -->
                            <td>
                                <div class="leaderboard-game-cell">
                                    <div class="leaderboard-game-thumb">
                                        <img src="<?= $rowCover ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                                    </div>
                                    <div>
                                        <a href="<?= BASE_URL ?>/game/detail?id=<?= $row['id'] ?>" class="leaderboard-game-title">
                                            <?= htmlspecialchars($row['title']) ?>
                                        </a>
                                        <span class="leaderboard-game-category">
                                            <?= htmlspecialchars($row['category_names'] ?? 'Gaming') ?>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Developer -->
                            <td>
                                <span style="color: var(--text-secondary); font-weight: 500;">
                                    <?= htmlspecialchars($row['developer_name'] ?? 'N/A') ?>
                                </span>
                            </td>

                            <!-- Total Sold -->
                            <td style="text-align: right; font-weight: 700; color: var(--primary);">
                                <?= number_format($row['total_sold'] ?? 0) ?>
                            </td>

                            <!-- Refund Rate -->
                            <td style="text-align: center;">
                                <?php $refRate = (float)($row['refund_rate'] ?? 0); ?>
                                <span style="font-weight: 700; color: <?= $refRate <= 2.0 ? '#00e676' : ($refRate <= 5.0 ? '#f39c12' : '#ff7675') ?>;">
                                    <?= $refRate ?>%
                                </span>
                            </td>

                            <!-- Positive Rate & Bar -->
                            <td style="text-align: center;">
                                <div style="display: inline-flex; flex-direction: column; align-items: center;">
                                    <span style="font-weight: 700; color: #00e676; font-size: 13px;">
                                        <?= $posRate ?>%
                                    </span>
                                    <div class="rate-progress-bar">
                                        <div class="rate-progress-fill" style="width: <?= $posRate ?>%; background: #00e676;"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Price -->
                            <td style="text-align: right; white-space: nowrap;">
                                <?php if ($rowPrice == 0): ?>
                                    <span style="color: #00e676; font-weight: 800;">MIỄN PHÍ</span>
                                <?php else: ?>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        <?= number_format($rowFinal, 0, ',', '.') ?> đ
                                    </div>
                                    <?php if ($rowDiscount > 0): ?>
                                        <span style="font-size: 11px; color: #00e676; font-weight: 700;">-<?= $rowDiscount ?>%</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            <!-- Action -->
                            <td style="text-align: center; white-space: nowrap;">
                                <a href="<?= BASE_URL ?>/game/detail?id=<?= $row['id'] ?>" class="btn-gaming btn-outline" style="padding: 6px 14px; font-size: 12px;">
                                    Chi Tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Chưa có dữ liệu bảng xếp hạng phù hợp với tiêu chí lọc đã chọn.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
