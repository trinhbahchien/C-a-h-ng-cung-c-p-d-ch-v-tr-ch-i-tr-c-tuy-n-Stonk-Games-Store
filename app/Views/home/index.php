<?php
/**
 * Stonk Games Store - Storefront Homepage (Stage 3)
 * 
 * @var array $spotlightGames Danh sach game noi bat cho Hero Carousel
 * @var array $topSellers Danh sach top game ban chay
 * @var array $newReleases Danh sach game moi phat hanh
 * @var array $freeGames Danh sach game mien phi
 * @var array $latestNews Danh sach tin tuc moi nhat
 * @var array $dbStatus Thong tin he thong va CSDL
 */
$spotlightGames = $spotlightGames ?? [];
$topSellers = $topSellers ?? [];
$newReleases = $newReleases ?? [];
$freeGames = $freeGames ?? [];
$latestNews = $latestNews ?? [];
$dbStatus = $dbStatus ?? [];
?>

<style>
/* ==========================================================================
   HOMEPAGE STYLES & HERO SPOTLIGHT CAROUSEL (STEAM / EPIC GAMES STYLE)
   ========================================================================== */

/* Flash notification */
.home-flash-alert {
    padding: 14px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: fadeIn 0.3s ease;
}

/* Hero Carousel Wrapper */
.hero-spotlight-wrapper {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    margin-bottom: 40px;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

@media (max-width: 992px) {
    .hero-spotlight-wrapper {
        grid-template-columns: 1fr;
    }
}

/* Left Hero Screen */
.hero-screen {
    position: relative;
    min-height: 440px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    overflow: hidden;
    background: #0b0e14;
}

.hero-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.5s ease, transform 0.5s ease;
    transform: scale(1.02);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 36px;
    background-size: cover;
    background-position: center;
}

.hero-slide.active {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
    z-index: 2;
}

.hero-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(11, 14, 20, 0.1) 0%, rgba(11, 14, 20, 0.75) 55%, rgba(11, 14, 20, 0.98) 100%);
    z-index: 1;
}

.hero-slide-content {
    position: relative;
    z-index: 3;
    max-width: 680px;
}

.hero-badge-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(0, 242, 254, 0.2);
    border: 1px solid var(--primary);
    color: var(--primary);
    font-size: 11px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 12px;
}

.hero-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff !important;
    line-height: 1.2;
    margin-bottom: 8px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
}

.hero-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    font-size: 13px;
    color: #cbd5e1 !important;
    margin-bottom: 12px;
}

.hero-desc {
    font-size: 14px;
    color: #e2e8f0 !important;
    line-height: 1.5;
    margin-bottom: 20px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.6);
}

.hero-action-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.hero-price-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.hero-discount-pill {
    background: #00e676;
    color: #0b0e14 !important;
    font-size: 15px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 4px;
}

.hero-price-original {
    font-size: 13px;
    color: #94a3b8 !important;
    text-decoration: line-through;
}

.hero-price-final {
    font-size: 22px;
    font-weight: 800;
    color: #ffffff !important;
}

/* Dam bao chu luon trang sang tren nen anh toi cua Hero Slide du o Light Mode */
html.light-mode .hero-screen h2,
html.light-mode .hero-title {
    color: #ffffff !important;
}
html.light-mode .hero-meta,
html.light-mode .hero-meta span {
    color: #cbd5e1 !important;
}
html.light-mode .hero-desc {
    color: #e2e8f0 !important;
}
html.light-mode .hero-price-final {
    color: #ffffff !important;
}

/* Right Thumbnail List */
.hero-thumbs-list {
    background: var(--bg-card);
    display: flex;
    flex-direction: column;
    padding: 12px;
    gap: 8px;
    overflow-y: auto;
}

.hero-thumb-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    cursor: pointer;
    background: transparent;
    border: 1px solid transparent;
    transition: all 0.25s ease;
    overflow: hidden;
}

.hero-thumb-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.hero-thumb-item.active {
    background: rgba(0, 242, 254, 0.1);
    border-color: rgba(0, 242, 254, 0.4);
}

html.light-mode .hero-thumb-item.active {
    background: rgba(2, 132, 199, 0.1);
    border-color: #0284c7;
}

.hero-thumb-img {
    width: 64px;
    height: 42px;
    border-radius: 4px;
    object-fit: cover;
    flex-shrink: 0;
}

.hero-thumb-info {
    flex: 1;
    min-width: 0;
}

.hero-thumb-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
}

.hero-thumb-price {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.hero-thumb-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: var(--primary);
    width: 0%;
    transition: width 0.1s linear;
}

.hero-thumb-item.active .hero-thumb-progress {
    width: 100%;
    transition: width 5s linear;
}

/* Feature Benefits Bar */
.benefits-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 45px;
}

.benefit-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.25s ease, border-color 0.25s ease;
}

.benefit-card:hover {
    transform: translateY(-2px);
    border-color: var(--primary);
}

.benefit-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.benefit-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 2px;
}

.benefit-desc {
    font-size: 12px;
    color: var(--text-muted);
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 22px;
}

.section-label {
    font-size: 11px;
    font-weight: 800;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 1px;
    display: block;
    margin-bottom: 4px;
}

.section-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0;
}

.section-link {
    font-size: 13px;
    font-weight: 700;
    color: var(--primary);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: transform 0.2s ease;
}

.section-link:hover {
    transform: translateX(4px);
}

/* Game Grid & Cards */
.game-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 22px;
    margin-bottom: 50px;
}

.game-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.game-card:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 242, 254, 0.4);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.3);
}

.game-card-media {
    position: relative;
    height: 180px;
    background: #000;
    overflow: hidden;
}

.game-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.game-card:hover .game-card-img {
    transform: scale(1.05);
}

.game-badge-discount {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #00e676;
    color: #0b0e14 !important;
    font-weight: 800;
    font-size: 12px;
    padding: 3px 7px;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 230, 118, 0.4);
}

.game-badge-free {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #00e676;
    color: #0b0e14 !important;
    font-weight: 800;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 4px;
    letter-spacing: 0.5px;
}

.game-badge-featured {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 242, 254, 0.9);
    color: #0b0e14 !important;
    font-weight: 800;
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}

.game-card-body {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.game-card-developer {
    font-size: 12px;
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 4px;
}

.game-card-title {
    font-size: 17px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 8px;
    line-height: 1.3;
}

.game-card-desc {
    font-size: 13px;
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.game-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--border-color);
    padding-top: 14px;
}

.game-price-wrap {
    display: flex;
    flex-direction: column;
}

.game-price-orig {
    font-size: 11px;
    color: var(--text-muted);
    text-decoration: line-through;
}

.game-price-val {
    font-size: 16px;
    font-weight: 800;
    color: var(--text-primary);
}

.game-btn-cart {
    background: rgba(0, 242, 254, 0.15);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 7px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.game-btn-cart:hover {
    background: var(--primary);
    color: #0b0e14 !important;
}

/* Top Sellers Ranked List (Steam Style Table) */
.ranked-sellers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
    gap: 16px;
    margin-bottom: 50px;
}

@media (max-width: 576px) {
    .ranked-sellers-grid {
        grid-template-columns: 1fr;
    }
}

.ranked-game-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 14px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: transform 0.2s ease, border-color 0.2s ease;
}

.ranked-game-card:hover {
    transform: translateX(4px);
    border-color: var(--primary);
}

.rank-number {
    font-size: 20px;
    font-weight: 900;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.rank-1 { background: rgba(255, 215, 0, 0.15); color: #ffd700; border: 1px solid rgba(255, 215, 0, 0.4); }
.rank-2 { background: rgba(192, 192, 192, 0.15); color: #c0c0c0; border: 1px solid rgba(192, 192, 192, 0.4); }
.rank-3 { background: rgba(205, 127, 50, 0.15); color: #cd7f32; border: 1px solid rgba(205, 127, 50, 0.4); }
.rank-other { background: rgba(255, 255, 255, 0.05); color: var(--text-muted); border: 1px solid var(--border-color); font-size: 15px; }

.ranked-thumb {
    width: 76px;
    height: 48px;
    border-radius: 6px;
    object-fit: cover;
    flex-shrink: 0;
}

.ranked-info {
    flex: 1;
    min-width: 0;
}

.ranked-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
}

.ranked-meta {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 10px;
}

.ranked-pricing {
    text-align: right;
    flex-shrink: 0;
}

/* Free Games Showcase Banner */
.free-games-banner {
    background: linear-gradient(135deg, rgba(0, 230, 118, 0.08) 0%, rgba(0, 242, 254, 0.08) 100%);
    border: 1px solid rgba(0, 230, 118, 0.3);
    border-radius: 14px;
    padding: 30px;
    margin-bottom: 50px;
}

/* News Hub Grid */
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
}

.news-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, border-color 0.25s ease;
}

.news-card:hover {
    transform: translateY(-3px);
    border-color: var(--primary);
}

.news-card-media {
    position: relative;
    height: 150px;
    background: #000;
    overflow: hidden;
}

.news-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.news-card:hover .news-card-img {
    transform: scale(1.05);
}

.news-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(0, 242, 254, 0.9);
    color: #0b0e14 !important;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}

.news-card-body {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.news-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.4;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-card:hover .news-card-title {
    color: var(--primary);
}

.news-card-date {
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>

<!-- Flash Message (Ví dụ: Thêm vào giỏ thành công) -->
<?php if (isset($_SESSION['flash'])): ?>
    <div class="home-flash-alert" style="background: <?= $_SESSION['flash']['type'] === 'success' ? 'rgba(0, 230, 118, 0.15)' : 'rgba(0, 242, 254, 0.15)' ?>; border: 1px solid <?= $_SESSION['flash']['type'] === 'success' ? '#00e676' : 'var(--primary)' ?>; color: <?= $_SESSION['flash']['type'] === 'success' ? '#00e676' : 'var(--primary)' ?>;">
        <i class="fa-solid <?= $_SESSION['flash']['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-info' ?>"></i>
        <span><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- ==========================================================================
     1. HERO SPOTLIGHT CAROUSEL (STEAM / EPIC GAMES FEATURED BANNER)
     ========================================================================== -->
<?php if (!empty($spotlightGames)): ?>
<div class="hero-spotlight-wrapper" id="heroSpotlightWrapper">
    
    <!-- Left: Screen Chinh Hien Thi Game Dang Chon -->
    <div class="hero-screen" id="heroScreen">
        <?php foreach ($spotlightGames as $index => $game): ?>
            <?php 
                $coverUrl = get_game_cover_url($game['cover_image']);
                $discountPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
            ?>
            <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>" 
                 data-slide="<?= $index ?>"
                 style="background-image: url('<?= $coverUrl ?>');">
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-content">
                    <span class="hero-badge-tag">
                        <i class="fa-solid fa-star"></i> SIÊU PHẨM NỔI BẬT &bull; STONK CHOICE
                    </span>
                    <h2 class="hero-title">
                        <?= htmlspecialchars($game['title']) ?>
                    </h2>
                    <div class="hero-meta">
                        <span><i class="fa-solid fa-building"></i> <?= htmlspecialchars($game['developer_name'] ?? 'Publisher') ?></span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-gamepad"></i> <?= htmlspecialchars($game['category_names'] ?? 'Gaming') ?></span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-fire" style="color: #ff7675;"></i> <?= number_format($game['total_sold'] ?? 0) ?> lượt bán</span>
                    </div>
                    <p class="hero-desc">
                        <?= htmlspecialchars($game['short_description']) ?>
                    </p>
                    <div class="hero-action-bar">
                        <div class="hero-price-box">
                            <?php if ($game['price'] == 0): ?>
                                <span class="hero-discount-pill" style="background: #00e676; color: #0b0e14;">MIỄN PHÍ</span>
                            <?php else: ?>
                                <?php if ($game['discount_percent'] > 0): ?>
                                    <span class="hero-discount-pill">-<?= $game['discount_percent'] ?>%</span>
                                    <span class="hero-price-original"><?= number_format($game['price'], 0, ',', '.') ?> đ</span>
                                <?php endif; ?>
                                <span class="hero-price-final"><?= number_format($discountPrice, 0, ',', '.') ?> đ</span>
                            <?php endif; ?>
                        </div>

                        <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>" class="btn-gaming btn-primary" style="padding: 10px 22px; font-size: 13px;">
                            <i class="fa-solid fa-circle-info"></i> Xem Chi Tiết
                        </a>

                        <form action="<?= BASE_URL ?>/cart/add" method="POST" style="margin: 0; display: inline;">
                            <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                            <button type="submit" class="btn-gaming btn-outline" style="padding: 10px 18px; font-size: 13px; background: rgba(0, 0, 0, 0.6); border-color: rgba(255, 255, 255, 0.4); color: #ffffff;">
                                <i class="fa-solid fa-cart-plus"></i> Thêm Giỏ Hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Right: Danh Sach 5 Thumbnail Dieu Huong -->
    <div class="hero-thumbs-list" id="heroThumbsList">
        <?php foreach ($spotlightGames as $index => $game): ?>
            <?php 
                $coverUrl = get_game_cover_url($game['cover_image']);
                $discountPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
            ?>
            <div class="hero-thumb-item <?= $index === 0 ? 'active' : '' ?>" 
                 data-target="<?= $index ?>" 
                 onclick="switchHeroSlide(<?= $index ?>)"
                 onmouseenter="switchHeroSlide(<?= $index ?>)">
                <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>" 
                     class="hero-thumb-img"
                     onerror="this.src='<?= MEDIA_URL ?>/img/games/elden_ring.jpg';">
                <div class="hero-thumb-info">
                    <div class="hero-thumb-title"><?= htmlspecialchars($game['title']) ?></div>
                    <div class="hero-thumb-price">
                        <?php if ($game['price'] == 0): ?>
                            <span style="color: var(--accent-green); font-weight: 700;">MIỄN PHÍ</span>
                        <?php else: ?>
                            <?php if ($game['discount_percent'] > 0): ?>
                                <span style="background: #00e676; color: #0b0e14; padding: 1px 4px; border-radius: 3px; font-weight: 800; font-size: 10px;">
                                    -<?= $game['discount_percent'] ?>%
                                </span>
                            <?php endif; ?>
                            <span><?= number_format($discountPrice, 0, ',', '.') ?> đ</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-thumb-progress"></div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
<?php endif; ?>

<!-- ==========================================================================
     2. THANH CAM KET DICH VU & DIEM NOI BAT SAN (BENEFITS BAR)
     ========================================================================== -->
<div class="benefits-bar">
    <div class="benefit-card">
        <div class="benefit-icon" style="background: rgba(0, 242, 254, 0.15); color: var(--primary);">
            <i class="fa-solid fa-key"></i>
        </div>
        <div>
            <div class="benefit-title">Cấp CD-Key Tức Thì</div>
            <div class="benefit-desc">Nhận mã tự động sau khi quét mã thanh toán</div>
        </div>
    </div>

    <div class="benefit-card">
        <div class="benefit-icon" style="background: rgba(0, 230, 118, 0.15); color: var(--accent-green);">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <div>
            <div class="benefit-title">Thanh Toán VietQR 24/7</div>
            <div class="benefit-desc">Quét mã Napas247 xác nhận giao dịch trong 3s</div>
        </div>
    </div>

    <div class="benefit-card">
        <div class="benefit-icon" style="background: rgba(162, 155, 254, 0.15); color: #a29bfe;">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
            <div class="benefit-title">100% Bản Quyền Gốc</div>
            <div class="benefit-desc">Kích hoạt trực tiếp trên Steam, Epic, Riot</div>
        </div>
    </div>

    <div class="benefit-card">
        <div class="benefit-icon" style="background: rgba(253, 203, 110, 0.15); color: #fdcb6e;">
            <i class="fa-solid fa-headset"></i>
        </div>
        <div>
            <div class="benefit-title">Hỗ Trợ Game Thủ 24/7</div>
            <div class="benefit-desc">Tư vấn kích hoạt & bảo hành 1 đổi 1 trọn đời</div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     3. TOP GAME BAN CHAY NHAT (TOP SELLERS - STEAM CHARTS RANKED)
     ========================================================================== -->
<div style="margin-bottom: 50px;">
    <div class="section-header">
        <div>
            <span class="section-label">Bảng Xếp Hạng Toàn Sàn</span>
            <h2 class="section-title">Top Game Bán Chạy Nhất</h2>
        </div>
        <a href="<?= BASE_URL ?>/rankings" class="section-link">
            Xem Toàn Bộ Bảng Xếp Hạng &rarr;
        </a>
    </div>

    <div class="ranked-sellers-grid">
        <?php foreach ($topSellers as $idx => $game): ?>
            <?php 
                $rank = $idx + 1;
                $coverUrl = get_game_cover_url($game['cover_image']);
                $discountPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
            ?>
            <div class="ranked-game-card">
                <div class="rank-number <?= $rank === 1 ? 'rank-1' : ($rank === 2 ? 'rank-2' : ($rank === 3 ? 'rank-3' : 'rank-other')) ?>">
                    #<?= $rank ?>
                </div>

                <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>">
                    <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>"
                         class="ranked-thumb"
                         onerror="this.src='<?= MEDIA_URL ?>/img/games/elden_ring.jpg';">
                </a>

                <div class="ranked-info">
                    <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>" class="ranked-title" title="<?= htmlspecialchars($game['title']) ?>">
                        <?= htmlspecialchars($game['title']) ?>
                    </a>
                    <div class="ranked-meta">
                        <span><i class="fa-solid fa-cart-shopping"></i> <?= number_format($game['total_sold'] ?? 0) ?> đã bán</span>
                        <span>&bull;</span>
                        <span style="color: var(--accent-green);"><i class="fa-solid fa-thumbs-up"></i> <?= number_format($game['positive_rate'] ?? 95, 1) ?>%</span>
                    </div>
                </div>

                <div class="ranked-pricing">
                    <?php if ($game['price'] == 0): ?>
                        <div style="font-size: 14px; font-weight: 800; color: var(--accent-green);">MIỄN PHÍ</div>
                    <?php else: ?>
                        <?php if ($game['discount_percent'] > 0): ?>
                            <div style="font-size: 11px; color: var(--text-muted); text-decoration: line-through;">
                                <?= number_format($game['price'], 0, ',', '.') ?> đ
                            </div>
                        <?php endif; ?>
                        <div style="font-size: 15px; font-weight: 800; color: var(--text-primary);">
                            <?= number_format($discountPrice, 0, ',', '.') ?> đ
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/cart/add" method="POST" style="margin-top: 4px;">
                        <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                        <button type="submit" class="game-btn-cart" title="Thêm vào giỏ">
                            <i class="fa-solid fa-cart-plus"></i> Giỏ
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ==========================================================================
     4. GAMES MOI PHAT HANH (NEW RELEASES GRID)
     ========================================================================== -->
<div style="margin-bottom: 50px;">
    <div class="section-header">
        <div>
            <span class="section-label">Mới Ra Mắt Trên Cửa Hàng</span>
            <h2 class="section-title">Games Mới Phát Hành</h2>
        </div>
        <a href="<?= BASE_URL ?>/catalog?sort=newest" class="section-link">
            Xem Tất Cả Trò Chơi &rarr;
        </a>
    </div>

    <div class="game-grid">
        <?php foreach ($newReleases as $game): ?>
            <?php 
                $coverUrl = get_game_cover_url($game['cover_image']);
                $discountPrice = $game['price'] * (1 - ($game['discount_percent'] / 100));
            ?>
            <div class="game-card">
                <div class="game-card-media">
                    <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>">
                        <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>" 
                             class="game-card-img"
                             onerror="this.src='<?= MEDIA_URL ?>/img/games/elden_ring.jpg';">
                    </a>

                    <?php if ($game['discount_percent'] > 0): ?>
                        <span class="game-badge-discount">-<?= $game['discount_percent'] ?>%</span>
                    <?php endif; ?>

                    <?php if (!empty($game['is_featured'])): ?>
                        <span class="game-badge-featured">Nổi Bật</span>
                    <?php endif; ?>
                </div>

                <div class="game-card-body">
                    <div>
                        <div class="game-card-developer">
                            <?= htmlspecialchars($game['developer_name'] ?? 'Publisher') ?>
                        </div>
                        <h3 class="game-card-title">
                            <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>">
                                <?= htmlspecialchars($game['title']) ?>
                            </a>
                        </h3>
                        <p class="game-card-desc">
                            <?= htmlspecialchars($game['short_description']) ?>
                        </p>
                    </div>

                    <div class="game-card-footer">
                        <div class="game-price-wrap">
                            <?php if ($game['price'] == 0): ?>
                                <span style="font-size: 15px; font-weight: 800; color: var(--accent-green);">MIỄN PHÍ</span>
                            <?php else: ?>
                                <?php if ($game['discount_percent'] > 0): ?>
                                    <span class="game-price-orig"><?= number_format($game['price'], 0, ',', '.') ?> đ</span>
                                <?php endif; ?>
                                <span class="game-price-val"><?= number_format($discountPrice, 0, ',', '.') ?> đ</span>
                            <?php endif; ?>
                        </div>

                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>" class="btn-gaming btn-outline" style="padding: 6px 12px; font-size: 11px;">
                                Chi Tiết
                            </a>
                            <form action="<?= BASE_URL ?>/cart/add" method="POST" style="margin: 0;">
                                <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                                <button type="submit" class="game-btn-cart" title="Thêm vào giỏ">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ==========================================================================
     5. GAMES MIEN PHI DINH CAO (FREE-TO-PLAY SHOWCASE)
     ========================================================================== -->
<?php if (!empty($freeGames)): ?>
<div class="free-games-banner">
    <div class="section-header" style="margin-bottom: 24px;">
        <div>
            <span class="section-label" style="color: var(--accent-green);">Không Giới Hạn Trải Nghiệm</span>
            <h2 class="section-title">Games Miễn Phí (Free-To-Play)</h2>
        </div>
        <a href="<?= BASE_URL ?>/catalog?free=1" class="section-link" style="color: var(--accent-green);">
            Kho Game Miễn Phí &rarr;
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
        <?php foreach ($freeGames as $game): ?>
            <?php $coverUrl = get_game_cover_url($game['cover_image']); ?>
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
                <div style="position: relative; height: 140px; overflow: hidden;">
                    <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>">
                        <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>" 
                             style="width: 100%; height: 100%; object-fit: cover;"
                             onerror="this.src='<?= MEDIA_URL ?>/img/games/genshin.jpg';">
                    </a>
                    <span class="game-badge-free">FREE TO PLAY</span>
                </div>
                <div style="padding: 16px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; color: var(--primary); font-weight: 700; margin-bottom: 4px;">
                            <?= htmlspecialchars($game['developer_name'] ?? 'Publisher') ?>
                        </div>
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin: 0 0 6px;">
                            <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>">
                                <?= htmlspecialchars($game['title']) ?>
                            </a>
                        </h4>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; border-top: 1px solid var(--border-color); padding-top: 12px;">
                        <span style="font-size: 13px; font-weight: 800; color: var(--accent-green);">MIỄN PHÍ</span>
                        <a href="<?= BASE_URL ?>/game/detail?id=<?= $game['id'] ?>" class="btn-gaming btn-outline" style="padding: 6px 14px; font-size: 11px; border-color: var(--accent-green); color: var(--accent-green);">
                            Chơi Ngay
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ==========================================================================
     6. TIN TUC NOI BAT THEO GAME & HE THONG (NEWS HUB SNIPPET)
     ========================================================================== -->
<?php if (!empty($latestNews)): ?>
<div style="margin-bottom: 50px;">
    <div class="section-header">
        <div>
            <span class="section-label">Cổng Thông Tin Trò Chơi</span>
            <h2 class="section-title">Tin Tức & Sự Kiện Mới Nhất</h2>
        </div>
        <a href="<?= BASE_URL ?>/news" class="section-link">
            Xem Tất Cả Tin Tức &rarr;
        </a>
    </div>

    <div class="news-grid">
        <?php foreach ($latestNews as $article): ?>
            <?php 
                $newsImg = UPLOADS_URL . '/news/' . ($article['image'] ?? 'news_sale.jpg');
                $typeLabel = ($article['type'] === 'game') ? 'Tin Game' : (($article['type'] === 'genre') ? 'Chuyên Mục' : 'Thông Báo');
            ?>
            <div class="news-card">
                <div class="news-card-media">
                    <a href="<?= BASE_URL ?>/news/detail?id=<?= $article['id'] ?>">
                        <img src="<?= $newsImg ?>" alt="<?= htmlspecialchars($article['title']) ?>" 
                             class="news-card-img"
                             onerror="this.src='<?= MEDIA_URL ?>/img/news/news_sale.jpg';">
                    </a>
                    <span class="news-badge"><?= $typeLabel ?></span>
                </div>
                <div class="news-card-body">
                    <div>
                        <h4 class="news-card-title">
                            <a href="<?= BASE_URL ?>/news/detail?id=<?= $article['id'] ?>">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </h4>
                    </div>
                    <div class="news-card-date">
                        <i class="fa-regular fa-clock"></i>
                        <span><?= date('d/m/Y', strtotime($article['created_at'])) ?></span>
                        <?php if (!empty($article['game_title'])): ?>
                            <span>&bull;</span>
                            <span style="color: var(--primary);"><?= htmlspecialchars($article['game_title']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<!-- ==========================================================================
     JAVASCRIPT: HERO SPOTLIGHT AUTO-ROTATE SLIDER
     ========================================================================== -->
<script>
(function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const thumbs = document.querySelectorAll('.hero-thumb-item');
    const totalSlides = slides.length;
    let autoPlayTimer = null;
    const SLIDE_DURATION = 5000;

    window.switchHeroSlide = function(targetIndex) {
        if (targetIndex === currentSlide || targetIndex < 0 || targetIndex >= totalSlides) return;
        
        // Remove active class
        if (slides[currentSlide]) slides[currentSlide].classList.remove('active');
        if (thumbs[currentSlide]) thumbs[currentSlide].classList.remove('active');

        // Set new active
        currentSlide = targetIndex;
        if (slides[currentSlide]) slides[currentSlide].classList.add('active');
        if (thumbs[currentSlide]) thumbs[currentSlide].classList.add('active');

        resetAutoPlay();
    };

    function nextHeroSlide() {
        const next = (currentSlide + 1) % totalSlides;
        window.switchHeroSlide(next);
    }

    function startAutoPlay() {
        if (totalSlides <= 1) return;
        clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(nextHeroSlide, SLIDE_DURATION);
    }

    function resetAutoPlay() {
        clearInterval(autoPlayTimer);
        startAutoPlay();
    }

    const wrapper = document.getElementById('heroSpotlightWrapper');
    if (wrapper) {
        wrapper.addEventListener('mouseenter', () => clearInterval(autoPlayTimer));
        wrapper.addEventListener('mouseleave', () => startAutoPlay());
    }

    // Khoi chay auto rotate
    startAutoPlay();
})();
</script>
