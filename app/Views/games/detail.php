<?php
/**
 * Stonk Games Store - Chi Tiết Trò Chơi (Steam Product Detail Page)
 * 
 * @var array $game Thong tin tro choi
 * @var array $categories Danh sach the loai cua game
 * @var array|null $requirements Cau hinh may tinh toi thieu va de nghi
 * @var array $reviews Danh sach danh gia tu cong dong
 * @var array $relatedNews Tin tuc lien quan
 * @var array $relatedGames Game tuong tu
 */

$discountPercent = (int)($game['discount_percent'] ?? 0);
$originalPrice = (float)($game['price'] ?? 0);
$finalPrice = $originalPrice * (1 - ($discountPercent / 100));
$isFree = $originalPrice == 0;
$coverUrl = get_game_cover_url($game['cover_image']);

// Xu ly YouTube Trailer ID / URL
$rawTrailer = trim($game['trailer_url'] ?? '');
$trailerId = '';
if (!empty($rawTrailer)) {
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/|live\/))([\w-]{11})/i', $rawTrailer, $matches)) {
        $trailerId = $matches[1];
    } elseif (preg_match('/^[\w-]{11}$/', $rawTrailer)) {
        $trailerId = $rawTrailer;
    }
}

// Xac dinh danh gia tong quan Steam Style
$positiveRate = (float)($game['positive_rate'] ?? 95);
if ($positiveRate >= 95) {
    $reviewSummary = 'Cực Kỳ Tích Cực (Overwhelmingly Positive)';
    $reviewSummaryColor = '#00f2fe';
} elseif ($positiveRate >= 80) {
    $reviewSummary = 'Rất Tích Cực (Very Positive)';
    $reviewSummaryColor = '#4facfe';
} elseif ($positiveRate >= 70) {
    $reviewSummary = 'Đa Phần Tích Cực (Mostly Positive)';
    $reviewSummaryColor = '#00e676';
} elseif ($positiveRate >= 40) {
    $reviewSummary = 'Trái Chiều (Mixed)';
    $reviewSummaryColor = '#f39c12';
} else {
    $reviewSummary = 'Tiêu Cực (Mostly Negative)';
    $reviewSummaryColor = '#e74c3c';
}
?>

<style>
/* ==========================================================================
   GAME DETAIL PAGE - STEAM & EPIC GAMES STORE AESTHETICS
   ========================================================================== */

.detail-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.detail-breadcrumb a {
    color: var(--text-secondary);
    transition: color 0.2s ease;
}

.detail-breadcrumb a:hover {
    color: var(--primary);
}

.detail-breadcrumb .separator {
    opacity: 0.5;
}

.detail-breadcrumb .current {
    color: var(--text-primary);
    font-weight: 600;
}

/* Header Game Title */
.detail-header {
    margin-bottom: 25px;
}

.detail-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 10px 0;
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.detail-meta-tags {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    font-size: 13px;
    color: var(--text-secondary);
}

.detail-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.detail-badge-pill {
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
}

.badge-official {
    background: rgba(0, 242, 254, 0.12);
    border: 1px solid rgba(0, 242, 254, 0.4);
    color: var(--primary);
}

/* Main Showcase Grid (Cinema & Buy Box) */
.detail-showcase-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 30px;
    align-items: start;
    margin-bottom: 45px;
}

@media (max-width: 1024px) {
    .detail-showcase-grid {
        grid-template-columns: 1fr;
    }
}

/* Cinema Media Player Area */
.cinema-player-container {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
}

.cinema-screen-wrapper {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
    background: #000000;
    overflow: hidden;
}

.cinema-screen-wrapper iframe,
.cinema-screen-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
    object-fit: cover;
}

.cinema-tabs-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 16px;
    background: var(--bg-surface);
    border-top: 1px solid var(--border-color);
}

.cinema-tabs-list {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow-x: auto;
}

.cinema-thumb-btn {
    position: relative;
    width: 96px;
    height: 56px;
    border-radius: 6px;
    border: 2px solid var(--border-color);
    overflow: hidden;
    cursor: pointer;
    background: #0b0e14;
    flex-shrink: 0;
    transition: all 0.2s ease;
    padding: 0;
}

.cinema-thumb-btn:hover {
    border-color: var(--secondary);
    transform: translateY(-1px);
}

.cinema-thumb-btn.active {
    border-color: var(--primary);
    box-shadow: 0 0 10px var(--primary-glow);
}

.cinema-thumb-btn img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.cinema-thumb-badge {
    position: absolute;
    bottom: 3px;
    right: 3px;
    background: rgba(0, 0, 0, 0.85);
    color: #ffffff !important;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 3px;
    letter-spacing: 0.3px;
    line-height: 1.2;
}

.cinema-thumb-badge.badge-trailer {
    background: rgba(220, 38, 38, 0.9);
}

.cinema-thumb-badge.badge-poster {
    background: rgba(14, 165, 233, 0.9);
}

.cinema-actions-bar {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.cinema-yt-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12.5px;
    font-weight: 600;
    color: #ef4444 !important;
    text-decoration: none;
    padding: 7px 14px;
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-radius: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.cinema-yt-btn:hover {
    background: #ef4444;
    color: #ffffff !important;
    border-color: #ef4444;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

html.light-mode .cinema-yt-btn {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626 !important;
}

html.light-mode .cinema-yt-btn:hover {
    background: #dc2626;
    color: #ffffff !important;
}

@media (max-width: 640px) {
    .cinema-tabs-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    .cinema-actions-bar {
        width: 100%;
    }
    .cinema-yt-btn {
        width: 100%;
        justify-content: center;
    }
}

html.light-mode button.btn-buy-secondary,
html.light-mode button[type="submit"].btn-buy-secondary,
html.light-mode .btn-buy-secondary {
    background: #f1f5f9 !important;
    border: 1px solid #cbd5e1 !important;
    color: #0f172a !important;
    font-weight: 700 !important;
}

html.light-mode button.btn-buy-secondary:hover,
html.light-mode button[type="submit"].btn-buy-secondary:hover,
html.light-mode .btn-buy-secondary:hover {
    background: #e2e8f0 !important;
    border-color: var(--primary) !important;
    color: var(--primary) !important;
}

/* Buy Box & Side Info */
.detail-buybox-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.buybox-cover-wrapper {
    position: relative;
    height: 180px;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.buybox-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.buybox-summary-desc {
    font-size: 13.5px;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 20px;
}

.buybox-meta-rows {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 22px;
    font-size: 13px;
}

.buybox-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px dashed var(--border-color);
}

.buybox-meta-row:last-child {
    border-bottom: none;
}

.buybox-meta-label {
    color: var(--text-muted);
}

.buybox-meta-value {
    color: var(--text-primary);
    font-weight: 600;
}

.buybox-price-zone {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 18px;
}

.buybox-price-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.buybox-discount-tag {
    background: #00e676;
    color: #0b0e14;
    font-weight: 800;
    font-size: 16px;
    padding: 6px 10px;
    border-radius: 6px;
}

.buybox-price-stack {
    text-align: right;
}

.buybox-price-old {
    font-size: 13px;
    color: var(--text-muted);
    text-decoration: line-through;
}

.buybox-price-current {
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
}

html.light-mode .buybox-price-current {
    color: #0f172a;
}

.buybox-actions-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-buy-primary {
    width: 100%;
    padding: 13px 20px;
    font-size: 15px;
    font-weight: 700;
    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
    color: #0b0e14;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.btn-buy-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 242, 254, 0.6);
}

.btn-buy-secondary {
    width: 100%;
    padding: 11px 18px;
    font-size: 14px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.btn-buy-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--primary);
    color: var(--primary);
}

.buybox-guarantee-list {
    margin-top: 15px;
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.buybox-guarantee-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.buybox-guarantee-item i {
    color: var(--accent-green);
    font-size: 13px;
}

/* Category Tags */
.game-tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 30px;
}

.game-tag-chip {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
}

.game-tag-chip:hover {
    background: rgba(0, 242, 254, 0.12);
    border-color: var(--primary);
    color: var(--primary);
}

/* Content Columns Layout */
.detail-content-columns {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 30px;
    margin-bottom: 50px;
}

@media (max-width: 992px) {
    .detail-content-columns {
        grid-template-columns: 1fr;
    }
}

/* Content Cards */
.content-box-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 28px;
    margin-bottom: 30px;
}

.content-box-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}

.content-box-title i {
    color: var(--primary);
}

.game-desc-typography {
    font-size: 15px;
    color: var(--text-secondary);
    line-height: 1.8;
}

.game-desc-typography p {
    margin-bottom: 16px;
}

/* System Requirements Comparative Table */
.req-table-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .req-table-grid {
        grid-template-columns: 1fr;
    }
}

.req-col-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 20px;
}

.req-col-header {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--primary);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.req-col-badge {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 2px 8px;
    border-radius: 4px;
    background: rgba(0, 242, 254, 0.1);
    color: var(--primary);
}

.req-row-item {
    margin-bottom: 12px;
    font-size: 13px;
    line-height: 1.5;
}

.req-row-label {
    color: var(--text-muted);
    font-weight: 600;
    display: block;
    margin-bottom: 2px;
}

.req-row-value {
    color: var(--text-primary);
    font-weight: 500;
}

/* Community Reviews Section */
.reviews-stat-banner {
    background: linear-gradient(135deg, rgba(0, 242, 254, 0.08) 0%, rgba(79, 172, 254, 0.08) 100%);
    border: 1px solid rgba(0, 242, 254, 0.3);
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}

.reviews-summary-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.reviews-score-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
    color: #0b0e14;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 800;
}

.reviews-score-text h4 {
    margin: 0 0 4px 0;
    font-size: 18px;
    color: var(--text-primary);
}

.reviews-score-text p {
    margin: 0;
    font-size: 13px;
    color: var(--text-secondary);
}

.review-item-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 18px 20px;
    margin-bottom: 16px;
    transition: transform 0.2s ease;
}

.review-item-card:hover {
    border-color: rgba(0, 242, 254, 0.3);
}

.review-user-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.review-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.review-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: #0b0e14;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 14px;
}

.review-user-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
}

.review-stars {
    color: #f1c40f;
    font-size: 12px;
}

.review-text-content {
    font-size: 14px;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* Review Form */
.review-form-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.form-group-custom {
    margin-bottom: 15px;
}

.form-label-custom {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 6px;
}

.form-control-custom {
    width: 100%;
    padding: 10px 14px;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 14px;
    font-family: inherit;
    box-sizing: border-box;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px var(--primary-glow);
}

/* Related Games Sidebar Grid */
.related-game-item {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 10px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    text-decoration: none;
}

.related-game-item:hover {
    transform: translateX(4px);
    border-color: var(--primary);
}

.related-thumb {
    width: 75px;
    height: 48px;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
}

.related-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-info {
    flex: 1;
    overflow: hidden;
}

.related-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 3px;
}

.related-price {
    font-size: 12px;
    color: var(--accent-green);
    font-weight: 700;
}
</style>

<!-- Breadcrumbs Navigation -->
<nav class="detail-breadcrumb" aria-label="Breadcrumb">
    <a href="<?= BASE_URL ?>/home">Trang Chủ</a>
    <span class="separator">/</span>
    <a href="<?= BASE_URL ?>/catalog">Cửa Hàng</a>
    <span class="separator">/</span>
    <?php if (!empty($categories[0])): ?>
        <a href="<?= BASE_URL ?>/catalog?category=<?= $categories[0]['id'] ?>"><?= htmlspecialchars($categories[0]['name']) ?></a>
        <span class="separator">/</span>
    <?php endif; ?>
    <span class="current"><?= htmlspecialchars($game['title']) ?></span>
</nav>

<!-- Header Title & Badges -->
<div class="detail-header">
    <h1 class="detail-title"><?= htmlspecialchars($game['title']) ?></h1>
    <div class="detail-meta-tags">
        <span class="detail-badge-pill badge-official">
            <i class="fa-solid fa-shield-halved"></i> BẢN QUYỀN SỐ CHÍNH HÃNG 100%
        </span>
        <span class="detail-meta-item">
            <i class="fa-solid fa-building"></i> Phát triển: <strong><?= htmlspecialchars($game['developer_name'] ?? 'Publisher') ?></strong>
        </span>
        <span class="detail-meta-item">
            <i class="fa-solid fa-calendar-days"></i> Ngày phát hành: <?= htmlspecialchars($game['release_date'] ?? '2024') ?>
        </span>
        <span class="detail-meta-item" style="color: <?= $reviewSummaryColor ?>; font-weight: 700;">
            <i class="fa-solid fa-thumbs-up"></i> <?= $reviewSummary ?>
        </span>
    </div>
</div>

<!-- Main Showcase Area: Left Cinema Video Player | Right Buy Box -->
<div class="detail-showcase-grid">
    
    <!-- Left: Cinema Media Showcase -->
    <div class="cinema-player-container">
        <div class="cinema-screen-wrapper" id="cinemaScreen">
            <?php if (!empty($trailerId)): ?>
                <iframe id="mainMediaViewer"
                        src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($trailerId) ?>?rel=0&autoplay=0" 
                        title="<?= htmlspecialchars($game['title']) ?> Official Trailer" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            <?php else: ?>
                <img id="mainMediaViewer" src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>">
            <?php endif; ?>
        </div>

        <!-- Cinema Toolbar & Thumbnails Selector -->
        <div class="cinema-tabs-bar">
            <div class="cinema-tabs-list">
                <?php if (!empty($trailerId)): ?>
                    <button type="button" class="cinema-thumb-btn active" onclick="switchMainMedia('video', '<?= htmlspecialchars($trailerId) ?>', this)" title="Xem Video Trailer">
                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($trailerId) ?>/mqdefault.jpg" 
                             alt="Trailer" 
                             onload="if(this.dataset.fallback !== '1' && this.naturalWidth <= 120){ this.dataset.fallback='1'; this.src='<?= $coverUrl ?>'; }"
                             onerror="this.onerror=null; this.src='<?= $coverUrl ?>';">
                        <span class="cinema-thumb-badge badge-trailer"><i class="fa-solid fa-play"></i> TRAILER</span>
                    </button>
                <?php endif; ?>

                <button type="button" class="cinema-thumb-btn <?= empty($trailerId) ? 'active' : '' ?>" onclick="switchMainMedia('image', '<?= $coverUrl ?>', this)" title="Xem Bìa / Poster">
                    <img src="<?= $coverUrl ?>" alt="Cover">
                    <span class="cinema-thumb-badge badge-poster"><i class="fa-solid fa-image"></i> POSTER</span>
                </button>
            </div>

            <?php if (!empty($trailerId)): ?>
                <div class="cinema-actions-bar">
                    <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($trailerId) ?>" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="cinema-yt-btn" 
                       title="Mở trailer gốc trên YouTube">
                        <i class="fa-brands fa-youtube"></i> 
                        <span>Xem trên YouTube</span> 
                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Buy Box & Instant Activation Info -->
    <div class="detail-buybox-card">
        <div>
            <div class="buybox-cover-wrapper">
                <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>" class="buybox-cover-img">
            </div>

            <p class="buybox-summary-desc">
                <?= nl2br(htmlspecialchars($game['short_description'])) ?>
            </p>

            <div class="buybox-meta-rows">
                <div class="buybox-meta-row">
                    <span class="buybox-meta-label">Đánh giá chung:</span>
                    <span class="buybox-meta-value" style="color: <?= $reviewSummaryColor ?>;">
                        <?= $positiveRate ?>% Tích cực (<?= number_format($game['total_reviews'] ?? 0) ?> bài)
                    </span>
                </div>
                <div class="buybox-meta-row">
                    <span class="buybox-meta-label">Đã bán:</span>
                    <span class="buybox-meta-value"><?= number_format($game['total_sold'] ?? 0) ?> bản quyền</span>
                </div>
                <div class="buybox-meta-row">
                    <span class="buybox-meta-label">Nhà phát triển:</span>
                    <span class="buybox-meta-value"><?= htmlspecialchars($game['developer_name'] ?? 'N/A') ?></span>
                </div>
                <div class="buybox-meta-row">
                    <span class="buybox-meta-label">Nền tảng:</span>
                    <span class="buybox-meta-value"><i class="fa-brands fa-windows"></i> Windows PC</span>
                </div>
            </div>
        </div>

        <div>
            <!-- Khung Giá & Nút Thêm Giỏ Hàng -->
            <div class="buybox-price-zone">
                <div class="buybox-price-row">
                    <?php if ($isFree): ?>
                        <span class="buybox-discount-tag" style="background: #00e676; color: #0b0e14;">MIỄN PHÍ</span>
                        <div class="buybox-price-stack">
                            <span class="buybox-price-current">0 đ</span>
                        </div>
                    <?php else: ?>
                        <?php if ($discountPercent > 0): ?>
                            <span class="buybox-discount-tag">-<?= $discountPercent ?>%</span>
                            <div class="buybox-price-stack">
                                <div class="buybox-price-old"><?= number_format($originalPrice, 0, ',', '.') ?> đ</div>
                                <div class="buybox-price-current"><?= number_format($finalPrice, 0, ',', '.') ?> đ</div>
                            </div>
                        <?php else: ?>
                            <div class="buybox-price-stack" style="text-align: left;">
                                <div class="buybox-price-current"><?= number_format($originalPrice, 0, ',', '.') ?> đ</div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="buybox-actions-group">
                <form action="<?= BASE_URL ?>/cart/add" method="POST" style="margin: 0;">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <button type="submit" class="btn-buy-primary">
                        <i class="fa-solid fa-cart-shopping"></i> THÊM VÀO GIỎ HÀNG
                    </button>
                </form>

                <form action="<?= BASE_URL ?>/cart/add" method="POST" style="margin: 0;">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <input type="hidden" name="redirect_checkout" value="1">
                    <button type="submit" class="btn-buy-secondary">
                        <i class="fa-solid fa-bolt"></i> MUA NGAY &bull; NHẬN KEY TỨC THÌ
                    </button>
                </form>
            </div>

            <div class="buybox-guarantee-list">
                <div class="buybox-guarantee-item">
                    <i class="fa-solid fa-circle-check"></i> Cấp mã CD-Key tự động qua hệ thống ngay sau thanh toán.
                </div>
                <div class="buybox-guarantee-item">
                    <i class="fa-solid fa-qrcode"></i> Quét mã VietQR chuyển khoản tức thời 24/7.
                </div>
                <div class="buybox-guarantee-item">
                    <i class="fa-solid fa-rotate-left"></i> Chính sách hoàn tiền minh bạch nếu mã bản quyền có lỗi.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tags thể loại bấm được để lọc game -->
<?php if (!empty($categories)): ?>
    <div class="game-tags-list">
        <span style="font-size: 13px; color: var(--text-muted); align-self: center; margin-right: 5px;">Thể loại:</span>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/catalog?category=<?= $cat['id'] ?>" class="game-tag-chip">
                # <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Two Columns Layout: Left Main Specs & Reviews | Right Similar Games & Patch Notes -->
<div class="detail-content-columns">
    
    <!-- Cột chính bên trái: Giới thiệu, Cấu hình máy tính, Đánh giá -->
    <div>
        
        <!-- Giới thiệu chi tiết trò chơi (About This Game) -->
        <div class="content-box-card">
            <h3 class="content-box-title">
                <i class="fa-solid fa-book-open"></i> VỀ TRÒ CHƠI NÀY
            </h3>
            <div class="game-desc-typography">
                <?= nl2br(htmlspecialchars($game['description'] ?? $game['short_description'])) ?>
            </div>
        </div>

        <!-- Bảng Cấu Hình Máy Tính (System Requirements - Comparative 2 Columns) -->
        <div class="content-box-card">
            <h3 class="content-box-title">
                <i class="fa-solid fa-microchip"></i> YÊU CẦU CẤU HÌNH HỆ THỐNG (PC REQUIREMENTS)
            </h3>
            
            <?php if ($requirements): ?>
                <div class="req-table-grid">
                    
                    <!-- Cấu hình Tối thiểu -->
                    <div class="req-col-card">
                        <div class="req-col-header">
                            <span>TỐI THIỂU (MINIMUM)</span>
                            <span class="req-col-badge">720p / 30FPS</span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Hệ điều hành:</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['min_os'] ?? 'Windows 10 64-bit') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Vi xử lý (CPU):</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['min_cpu'] ?? 'Intel Core i5') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Bộ nhớ (RAM):</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['min_ram'] ?? '8 GB RAM') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Đồ họa (GPU):</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['min_gpu'] ?? 'GTX 1060') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Lưu trữ:</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['min_storage'] ?? '50 GB') ?></span>
                        </div>
                    </div>

                    <!-- Cấu hình Đề nghị -->
                    <div class="req-col-card">
                        <div class="req-col-header">
                            <span>ĐỀ NGHỊ (RECOMMENDED)</span>
                            <span class="req-col-badge" style="background: rgba(0, 230, 118, 0.15); color: #00e676;">1080p / 60FPS</span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Hệ điều hành:</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['rec_os'] ?? 'Windows 10/11 64-bit') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Vi xử lý (CPU):</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['rec_cpu'] ?? 'Intel Core i7') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Bộ nhớ (RAM):</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['rec_ram'] ?? '16 GB RAM') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Đồ họa (GPU):</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['rec_gpu'] ?? 'RTX 2060 Super') ?></span>
                        </div>
                        <div class="req-row-item">
                            <span class="req-row-label">Lưu trữ:</span>
                            <span class="req-row-value"><?= htmlspecialchars($requirements['rec_storage'] ?? 'SSD 60 GB') ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); font-size: 14px;">Thông số cấu hình đang được cập nhật từ nhà phát hành.</p>
            <?php endif; ?>
        </div>

        <!-- Đánh giá của cộng đồng (Community Reviews Steam Style) -->
        <div class="content-box-card">
            <h3 class="content-box-title">
                <i class="fa-solid fa-comments"></i> ĐÁNH GIÁ TỪ CỘNG ĐỒNG GAME THỦ
            </h3>

            <!-- Thống kê tổng hợp điểm số -->
            <div class="reviews-stat-banner">
                <div class="reviews-summary-left">
                    <div class="reviews-score-circle">
                        <?= number_format($game['average_rating'] ?? 5.0, 1) ?>
                    </div>
                    <div class="reviews-score-text">
                        <h4>Mức độ hài lòng: <?= $reviewSummary ?></h4>
                        <p><?= $positiveRate ?>% đánh giá tích cực dựa trên <?= number_format($game['total_reviews'] ?? count($reviews)) ?> người chơi xác thực.</p>
                    </div>
                </div>
                <div>
                    <span class="tech-pill" style="border-color: var(--primary); color: var(--primary); font-weight: 700;">
                        <i class="fa-solid fa-check-double"></i> 100% ĐÁNH GIÁ XÁC THỰC
                    </span>
                </div>
            </div>

            <!-- Form gửi đánh giá mới -->
            <div class="review-form-wrapper">
                <h4 style="margin: 0 0 15px 0; color: var(--text-primary); font-size: 15px;">
                    <i class="fa-solid fa-pen-to-square"></i> Viết đánh giá của bạn
                </h4>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>" method="POST">
                        <input type="hidden" name="submit_review" value="1">
                        
                        <div class="form-group-custom">
                            <label class="form-label-custom">Điểm đánh giá:</label>
                            <select name="rating" class="form-control-custom" style="max-width: 200px;">
                                <option value="5">⭐⭐⭐⭐⭐ - 5 Sao (Tuyệt phẩm)</option>
                                <option value="4">⭐⭐⭐⭐ - 4 Sao (Rất tốt)</option>
                                <option value="3">⭐⭐⭐ - 3 Sao (Bình thường)</option>
                                <option value="2">⭐⭐ - 2 Sao (Kém)</option>
                                <option value="1">⭐ - 1 Sao (Rất thất vọng)</option>
                            </select>
                        </div>

                        <div class="form-group-custom">
                            <label class="form-label-custom">Nhận xét chi tiết:</label>
                            <textarea name="comment" rows="3" class="form-control-custom" placeholder="Chia sẻ cảm nhận về gameplay, đồ họa, cốt truyện hoặc hiệu năng..." required></textarea>
                        </div>

                        <button type="submit" class="btn-buy-primary" style="width: auto; padding: 10px 24px; font-size: 13px;">
                            <i class="fa-solid fa-paper-plane"></i> Gửi Đánh Giá
                        </button>
                    </form>
                <?php else: ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: gap: 15px;">
                        <p style="margin: 0; color: var(--text-secondary); font-size: 13.5px;">
                            Bạn cần đăng nhập tài khoản Stonk Games Store để gửi đánh giá.
                        </p>
                        <a href="<?= BASE_URL ?>/auth/login" class="btn-gaming btn-primary" style="padding: 8px 18px; font-size: 13px;">
                            Đăng Nhập Ngay
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Danh sách các bài đánh giá gần đây -->
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $rev): ?>
                    <div class="review-item-card">
                        <div class="review-user-header">
                            <div class="review-user-info">
                                <div class="review-user-avatar">
                                    <?= strtoupper(substr($rev['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="review-user-name">
                                        <?= htmlspecialchars($rev['username']) ?>
                                        <?php if (($rev['role'] ?? '') === 'admin'): ?>
                                            <span style="font-size: 10px; background: rgba(0, 242, 254, 0.2); color: var(--primary); padding: 1px 5px; border-radius: 3px; font-weight: 700;">ADMIN</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="review-stars">
                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                            <?php if ($s <= $rev['rating']): ?>
                                                <i class="fa-solid fa-star"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star" style="color: var(--text-muted);"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span style="font-size: 11px; color: var(--text-muted); margin-left: 6px;">
                                            <?= date('d/m/Y', strtotime($rev['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php if ($rev['rating'] >= 4): ?>
                                    <span style="font-size: 12px; color: #00e676; font-weight: 700;">
                                        <i class="fa-solid fa-thumbs-up"></i> Khuyên dùng
                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: #ff7675; font-weight: 700;">
                                        <i class="fa-solid fa-thumbs-down"></i> Không khuyên dùng
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="review-text-content">
                            <?= nl2br(htmlspecialchars($rev['comment'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 20px 0;">
                    Chưa có bài đánh giá nào. Hãy là người đầu tiên đánh giá tựa game này!
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cột phụ bên phải: Tin tức cập nhật & Game tương tự -->
    <div>
        
        <!-- Tin tức / Patch notes liên quan đến game -->
        <?php if (!empty($relatedNews)): ?>
            <div class="content-box-card" style="padding: 20px;">
                <h4 style="margin: 0 0 16px 0; font-size: 16px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                    <i class="fa-solid fa-newspaper" style="color: var(--primary);"></i> BẢN CẬP NHẬT & TIN TỨC
                </h4>
                <?php foreach ($relatedNews as $news): ?>
                    <div style="margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px dashed var(--border-color);">
                        <a href="<?= BASE_URL ?>/news/detail?id=<?= $news['id'] ?>" style="font-size: 13.5px; font-weight: 700; color: var(--text-primary); line-height: 1.4; display: block; margin-bottom: 4px; transition: color 0.2s ease;">
                            <?= htmlspecialchars($news['title']) ?>
                        </a>
                        <span style="font-size: 11px; color: var(--text-muted);">
                            <i class="fa-regular fa-clock"></i> <?= date('d/m/Y', strtotime($news['created_at'])) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Gợi ý tựa game tương tự (Similar Games) -->
        <div class="content-box-card" style="padding: 20px;">
            <h4 style="margin: 0 0 16px 0; font-size: 16px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                <i class="fa-solid fa-fire" style="color: #ff7675;"></i> GỢI Ý CÙNG THỂ LOẠI
            </h4>

            <?php if (!empty($relatedGames)): ?>
                <?php foreach ($relatedGames as $rel): ?>
                    <?php 
                        $relCover = get_game_cover_url($rel['cover_image']);
                        $relPrice = (float)$rel['price'];
                        $relDiscount = (int)$rel['discount_percent'];
                        $relFinal = $relPrice * (1 - ($relDiscount / 100));
                    ?>
                    <a href="<?= BASE_URL ?>/game/detail?id=<?= $rel['id'] ?>" class="related-game-item">
                        <div class="related-thumb">
                            <img src="<?= $relCover ?>" alt="<?= htmlspecialchars($rel['title']) ?>">
                        </div>
                        <div class="related-info">
                            <div class="related-title"><?= htmlspecialchars($rel['title']) ?></div>
                            <div class="related-price">
                                <?php if ($relPrice == 0): ?>
                                    MIỄN PHÍ
                                <?php else: ?>
                                    <?= number_format($relFinal, 0, ',', '.') ?> đ
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted); font-size: 13px;">Chưa có thêm gợi ý tương tự.</p>
            <?php endif; ?>
        </div>

        <!-- Khung hỗ trợ & Đối tác -->
        <div class="content-box-card" style="padding: 20px; font-size: 12.5px; color: var(--text-muted); line-height: 1.6;">
            <h4 style="margin: 0 0 12px 0; font-size: 14px; color: var(--text-primary);">
                <i class="fa-solid fa-headset" style="color: var(--primary);"></i> HỖ TRỢ TRỰC TUYẾN
            </h4>
            <p style="margin: 0 0 8px 0;">Mọi thắc mắc về mã kích hoạt game hoặc thanh toán VietQR đều được hỗ trợ 24/7 qua tổng đài hỗ trợ trực tuyến.</p>
            <div style="font-weight: 700; color: var(--primary);">
                Email: support@stonkgames.vn
            </div>
        </div>

    </div>
</div>

<!-- JavaScript dieu khien Cinema Player va Thumbnails -->
<script>
function switchMainMedia(type, source, btnElement) {
    const screen = document.getElementById('cinemaScreen');
    const thumbBtns = document.querySelectorAll('.cinema-thumb-btn');

    thumbBtns.forEach(btn => btn.classList.remove('active'));
    if (btnElement) {
        btnElement.classList.add('active');
    } else {
        const evt = window.event;
        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add('active');
        }
    }

    if (type === 'video') {
        screen.innerHTML = `
            <iframe id="mainMediaViewer"
                    src="https://www.youtube-nocookie.com/embed/${source}?autoplay=1&rel=0" 
                    title="Official Video Trailer" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        `;
    } else {
        screen.innerHTML = `
            <img id="mainMediaViewer" src="${source}" alt="Game Cover Art" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
        `;
    }
}
</script>
