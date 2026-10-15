<?php
/**
 * Stonk Games Store - News Detail Page (Trang Đọc Chi Tiết Bài Viết)
 * Phong cách Gaming Editorial & Steam News Article
 * 
 * @var array $news Chi tiet bai viet
 * @var array|null $relatedGame Thong tin game lien quan (neu co)
 * @var array $relatedNews Danh sach bai viet lien quan cung chu de
 */

$newsImg = get_news_image_url($news['image'] ?? null);
$newsDate = date('d/m/Y', strtotime($news['created_at']));
$bClass = 'badge-system';
$bText = 'Thông Báo Sàn';
if ($news['type'] === 'game') {
    $bClass = 'badge-game';
    $bText = 'Cập Nhật Game';
} elseif ($news['type'] === 'genre') {
    $bClass = 'badge-genre';
    $bText = 'Chuyên Mục Thể Loại';
}
?>

<style>
/* ==========================================================================
   NEWS DETAIL ARTICLE - GAMING EDITORIAL STYLE
   ========================================================================== */

.article-container {
    max-width: 960px;
    margin: 0 auto;
}

/* Breadcrumbs */
.article-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.article-breadcrumbs a {
    color: var(--text-secondary);
    transition: color 0.2s ease;
}

.article-breadcrumbs a:hover {
    color: var(--primary);
}

.article-breadcrumbs i {
    font-size: 10px;
}

/* Article Header */
.article-header {
    margin-bottom: 28px;
}

.article-badge-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.article-title {
    font-size: 34px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1.3;
    margin: 0 0 18px 0;
    letter-spacing: -0.5px;
}

.article-meta-bar {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 13.5px;
    color: var(--text-muted);
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
    flex-wrap: wrap;
}

.article-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

/* Featured Hero Image */
.article-cover-wrap {
    width: 100%;
    max-height: 480px;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 36px;
    background: #000;
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}

.article-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Article Body Typography */
.article-body-content {
    font-size: 16px;
    line-height: 1.85;
    color: var(--text-primary);
    margin-bottom: 40px;
}

.article-body-content p {
    margin-bottom: 20px;
}

.article-lead {
    font-size: 18px;
    font-weight: 500;
    line-height: 1.7;
    color: var(--text-primary);
    padding: 16px 20px;
    background: var(--bg-surface);
    border-left: 4px solid var(--primary);
    border-radius: 0 10px 10px 0;
    margin-bottom: 26px;
}

/* Related Game Buy Box Mini Card */
.article-game-box {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 22px;
    margin: 36px 0;
    display: flex;
    align-items: center;
    gap: 22px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.article-game-thumb {
    width: 140px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: #000;
}

.article-game-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.article-game-info {
    flex: 1;
}

.article-game-title {
    font-size: 17px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 4px 0;
}

.article-game-studio {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.article-game-price-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.article-game-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

/* Share & Action Bar */
.article-action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 20px 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 50px;
    flex-wrap: wrap;
}

/* Related News Section */
.related-news-section {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
}

.related-section-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 24px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.related-news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 22px;
}

.news-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    box-sizing: border-box;
}

.news-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
}

.news-card-media {
    position: relative;
    width: 100%;
    height: 160px;
    background: #000;
    overflow: hidden;
    display: block;
}

.news-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}

.news-card:hover .news-card-img {
    transform: scale(1.06);
}

.news-card-body {
    padding: 18px;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
    background: var(--bg-surface);
}

.news-card-date {
    font-size: 11.5px;
    color: var(--text-muted);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.news-card-title {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 10px 0;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

html.light-mode .news-card {
    background: #ffffff;
    border-color: #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

html.light-mode .news-card-body {
    background: #ffffff;
}

html.light-mode .news-card-title {
    color: #0f172a;
}

@media (max-width: 768px) {
    .article-title {
        font-size: 26px;
    }
    .article-game-box {
        flex-direction: column;
        align-items: flex-start;
    }
    .article-game-thumb {
        width: 100%;
        height: 160px;
    }
    .article-game-actions {
        width: 100%;
        margin-top: 10px;
    }
}
</style>

<div class="article-container">

    <!-- Breadcrumbs Navigation -->
    <nav class="article-breadcrumbs" aria-label="Điều hướng bài viết">
        <a href="<?= BASE_URL ?>/home">Trang Chủ</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="<?= BASE_URL ?>/news">Tin Tức</a>
        <i class="fa-solid fa-chevron-right"></i>
        <span style="color: var(--text-primary);"><?= htmlspecialchars($news['title']) ?></span>
    </nav>

    <!-- Article Header -->
    <header class="article-header">
        <div class="article-badge-row">
            <span class="news-badge <?= $bClass ?>">
                <?= $bText ?>
            </span>
            <?php if (!empty($news['game_title'])): ?>
                <a href="<?= BASE_URL ?>/game/detail?id=<?= $news['game_id'] ?>" style="font-size: 13px; color: var(--primary); font-weight: 600;">
                    <i class="fa-solid fa-gamepad"></i> <?= htmlspecialchars($news['game_title']) ?>
                </a>
            <?php elseif (!empty($news['category_name'])): ?>
                <a href="<?= BASE_URL ?>/catalog?category=<?= $news['category_id'] ?>" style="font-size: 13px; color: #f59e0b; font-weight: 600;">
                    <i class="fa-solid fa-tag"></i> <?= htmlspecialchars($news['category_name']) ?>
                </a>
            <?php endif; ?>
        </div>

        <h1 class="article-title">
            <?= htmlspecialchars($news['title']) ?>
        </h1>

        <div class="article-meta-bar">
            <div class="article-meta-item">
                <i class="fa-solid fa-user-pen" style="color: var(--primary);"></i>
                <span>Ban Biên Tập Stonk Store</span>
            </div>
            <div class="article-meta-item">
                <i class="fa-regular fa-calendar" style="color: var(--text-muted);"></i>
                <span>Ngày đăng: <?= $newsDate ?></span>
            </div>
            <div class="article-meta-item" style="margin-left: auto;">
                <i class="fa-regular fa-clock"></i>
                <span>3 phút đọc</span>
            </div>
        </div>
    </header>

    <!-- Ảnh Bìa Bài Viết Lớn (Hero Cover) -->
    <div class="article-cover-wrap">
        <img src="<?= $newsImg ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="article-cover-img">
    </div>

    <!-- Nội Dung Bài Viết (Article Body) -->
    <article class="article-body-content">
        <!-- Đoạn mở đầu nổi bật (Lead Paragraph) -->
        <div class="article-lead">
            <?= htmlspecialchars(mb_substr($news['content'], 0, 180)) ?>...
        </div>

        <!-- Toàn văn nội dung bài viết -->
        <div>
            <?= nl2br(htmlspecialchars($news['content'])) ?>
        </div>
    </article>

    <!-- Hộp Giới Thiệu Game Trực Tiếp (Buy Box Mini Card nếu bài viết thuộc về 1 game) -->
    <?php if ($relatedGame): ?>
        <?php 
            $rgCover = get_game_cover_url($relatedGame['cover_image'] ?? null);
            $rgPrice = (float)$relatedGame['price'];
            $rgDisc = (int)$relatedGame['discount_percent'];
            $rgFinal = $rgPrice * (1 - ($rgDisc / 100));
        ?>
        <div class="article-game-box">
            <div class="article-game-thumb">
                <img src="<?= $rgCover ?>" alt="<?= htmlspecialchars($relatedGame['title']) ?>">
            </div>
            <div class="article-game-info">
                <h3 class="article-game-title"><?= htmlspecialchars($relatedGame['title']) ?></h3>
                <div class="article-game-studio">
                    Phát triển bởi <strong><?= htmlspecialchars($relatedGame['developer_name'] ?? 'Publisher') ?></strong>
                </div>
                <div class="article-game-price-row">
                    <?php if ($rgPrice == 0): ?>
                        <span style="color: #00e676; font-weight: 800; font-size: 15px;">MIỄN PHÍ</span>
                    <?php else: ?>
                        <?php if ($rgDisc > 0): ?>
                            <span class="game-badge-discount" style="padding: 2px 6px; font-size: 11px;">-<?= $rgDisc ?>%</span>
                        <?php endif; ?>
                        <span style="font-size: 15px; font-weight: 800; color: var(--text-primary);">
                            <?= number_format($rgFinal, 0, ',', '.') ?> đ
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="article-game-actions">
                <a href="<?= BASE_URL ?>/game/detail?id=<?= $relatedGame['id'] ?>" class="btn-gaming btn-primary" style="padding: 9px 18px; font-size: 12.5px;">
                    <i class="fa-solid fa-cart-shopping"></i> Xem Trò Chơi
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Thanh Chia Sẻ & Nút Quay Lại -->
    <div class="article-action-bar">
        <a href="<?= BASE_URL ?>/news" class="btn-gaming btn-outline" style="padding: 8px 18px; font-size: 13px;">
            <i class="fa-solid fa-arrow-left"></i> Quay Lại Tin Tức
        </a>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" class="btn-gaming btn-outline" style="padding: 8px 14px; font-size: 12.5px;" onclick="navigator.clipboard.writeText(window.location.href); alert('Đã sao chép liên kết bài viết vào bộ nhớ tạm!');">
                <i class="fa-regular fa-copy"></i> Sao Chép Link
            </button>
            <a href="<?= BASE_URL ?>/catalog" class="btn-gaming btn-primary" style="padding: 8px 18px; font-size: 12.5px;">
                <i class="fa-solid fa-store"></i> Khám Phá Cửa Hàng
            </a>
        </div>
    </div>

    <!-- Danh Sách Bài Viết Liên Quan Cùng Chủ Đề (Related Articles) -->
    <?php if (!empty($relatedNews)): ?>
        <section class="related-news-section">
            <h3 class="related-section-title">
                <i class="fa-solid fa-newspaper" style="color: var(--primary);"></i> Bài Viết Liên Quan
            </h3>
            <div class="related-news-grid">
                <?php foreach ($relatedNews as $rn): ?>
                    <?php 
                        $rnImg = get_news_image_url($rn['image'] ?? null);
                        $rnDate = date('d/m/Y', strtotime($rn['created_at']));
                    ?>
                    <a href="<?= BASE_URL ?>/news/detail?id=<?= $rn['id'] ?>" class="news-card">
                        <div class="news-card-media">
                            <img src="<?= $rnImg ?>" alt="<?= htmlspecialchars($rn['title']) ?>" class="news-card-img">
                        </div>
                        <div class="news-card-body">
                            <div>
                                <div class="news-card-date">
                                    <i class="fa-regular fa-calendar"></i> <?= $rnDate ?>
                                </div>
                                <h4 class="news-card-title" title="<?= htmlspecialchars($rn['title']) ?>">
                                    <?= htmlspecialchars($rn['title']) ?>
                                </h4>
                            </div>
                            <span style="color: var(--primary); font-size: 12.5px; font-weight: 700; margin-top: 10px; display: inline-flex; align-items: center; gap: 4px;">
                                Đọc bài viết <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</div>
