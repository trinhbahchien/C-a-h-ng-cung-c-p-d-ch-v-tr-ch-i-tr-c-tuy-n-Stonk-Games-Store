<?php
/**
 * Stonk Games Store - News Hub (Trang Tin Tức Tổng Hợp)
 * Phong cách Steam News Hub & Epic Games Store News
 * 
 * @var array $newsList Danh sach toan bo bai viet
 * @var array|null $featuredArticle Bai viet noi bat nhat
 * @var array $gridArticles Danh sach bai viet trong luoi
 * @var string $activeType Loai tin dang loc (all, system, game, genre)
 * @var string $searchQuery Tu khoa tim kiem
 * @var int $activeCategory The loai dang loc
 * @var int $activeGame Game dang loc
 * @var array $categories Danh sach the loai
 * @var int $totalCount Tong so luong bai viet
 */
?>

<style>
/* ==========================================================================
   NEWS HUB - GAMING NEWS & STEAM ANNOUNCEMENTS
   ========================================================================== */

.news-hub-container {
    max-width: 1320px;
    margin: 0 auto;
}

/* Header & Intro */
.news-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.news-header-title {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 6px 0;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.news-header-sub {
    font-size: 14.5px;
    color: var(--text-secondary);
    margin: 0;
}

/* Tab Filters Bar */
.news-nav-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.news-tabs-group {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--bg-surface);
    padding: 6px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    overflow-x: auto;
}

.news-tab-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.25s ease;
    white-space: nowrap;
}

.news-tab-item:hover {
    color: var(--text-primary);
    background: rgba(255, 255, 255, 0.05);
}

.news-tab-item.active {
    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
    color: #0b0e14;
    font-weight: 700;
    box-shadow: 0 4px 12px var(--primary-glow);
}

html.light-mode .news-tab-item.active {
    color: #ffffff;
}

/* Search Form */
.news-search-form {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 6px 12px;
    min-width: 300px;
}

.news-search-input {
    background: transparent;
    border: none;
    outline: none;
    color: var(--text-primary);
    font-size: 13.5px;
    width: 100%;
}

.news-search-input::placeholder {
    color: var(--text-muted);
}

.news-search-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 14px;
    transition: color 0.2s ease;
}

.news-search-btn:hover {
    color: var(--primary);
}

/* Hero Featured Article */
.featured-hero-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 36px;
    display: grid;
    grid-template-columns: 1.15fr 1fr;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.featured-hero-card:hover {
    border-color: var(--primary);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
}

.featured-hero-media {
    position: relative;
    height: 100%;
    min-height: 340px;
    overflow: hidden;
    background: #000;
}

.featured-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.featured-hero-card:hover .featured-hero-img {
    transform: scale(1.05);
}

.featured-hero-body {
    padding: 36px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.news-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: fit-content;
}

.badge-system {
    background: rgba(0, 242, 254, 0.15);
    color: var(--primary);
    border: 1px solid rgba(0, 242, 254, 0.3);
}

.badge-game {
    background: rgba(0, 230, 118, 0.15);
    color: var(--accent-green);
    border: 1px solid rgba(0, 230, 118, 0.3);
}

.badge-genre {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.featured-hero-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 14px 0 12px 0;
    line-height: 1.35;
    transition: color 0.2s ease;
}

.featured-hero-title a:hover {
    color: var(--primary);
}

.featured-hero-excerpt {
    font-size: 14px;
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0 0 20px 0;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.featured-hero-meta {
    display: flex;
    align-items: center;
    gap: 18px;
    font-size: 12.5px;
    color: var(--text-muted);
    border-top: 1px solid var(--border-color);
    padding-top: 18px;
}

/* Grid Articles */
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 26px;
    margin-bottom: 40px;
}

.news-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    text-decoration: none;
}

.news-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.3);
}

.news-card-media {
    position: relative;
    height: 200px;
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
    transform: scale(1.06);
}

.news-card-badge-pos {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
}

.news-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
}

.news-card-date {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.news-card-title {
    font-size: 16.5px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 10px 0;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-card-content {
    font-size: 13.5px;
    color: var(--text-secondary);
    line-height: 1.55;
    margin: 0 0 16px 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px dashed var(--border-color);
    font-size: 12px;
    color: var(--text-muted);
}

.news-card-tag {
    color: var(--primary);
    font-weight: 600;
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 900px) {
    .featured-hero-card {
        grid-template-columns: 1fr;
    }
    .featured-hero-media {
        min-height: 240px;
    }
    .news-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="news-hub-container">

    <!-- Header Tiêu Đề -->
    <div class="news-header">
        <div>
            <h1 class="news-header-title">
                <i class="fa-solid fa-newspaper" style="color: var(--primary);"></i> TIN TỨC & BẢN CẬP NHẬT TRÒ CHƠI
            </h1>
            <p class="news-header-sub">
                Cập nhật thông báo sự kiện, nhật ký phát hành bản vá và cẩm nang phân tích chuyên sâu của làng game quốc tế.
            </p>
        </div>
        <div style="font-size: 13.5px; color: var(--text-muted);">
            Hiển thị <strong><?= $totalCount ?></strong> bài viết
        </div>
    </div>

    <!-- Thanh Điều Hướng Tab & Tìm Kiếm -->
    <div class="news-nav-bar">
        <!-- Nhóm Tab Lọc Loại Tin -->
        <div class="news-tabs-group">
            <a href="<?= BASE_URL ?>/news" class="news-tab-item <?= $activeType === 'all' ? 'active' : '' ?>">
                <i class="fa-solid fa-layer-group"></i> Tất Cả Tin Tức
            </a>
            <a href="<?= BASE_URL ?>/news?type=system" class="news-tab-item <?= $activeType === 'system' ? 'active' : '' ?>">
                <i class="fa-solid fa-bullhorn"></i> Thông Báo Sàn
            </a>
            <a href="<?= BASE_URL ?>/news?type=game" class="news-tab-item <?= $activeType === 'game' ? 'active' : '' ?>">
                <i class="fa-solid fa-gamepad"></i> Cập Nhật Trò Chơi
            </a>
            <a href="<?= BASE_URL ?>/news?type=genre" class="news-tab-item <?= $activeType === 'genre' ? 'active' : '' ?>">
                <i class="fa-solid fa-bookmark"></i> Chuyên Mục Thể Loại
            </a>
        </div>

        <!-- Ô Tìm Kiếm Bài Viết -->
        <form method="GET" action="<?= BASE_URL ?>/news" class="news-search-form">
            <?php if ($activeType !== 'all'): ?>
                <input type="hidden" name="type" value="<?= htmlspecialchars($activeType) ?>">
            <?php endif; ?>
            <input type="text" name="search" class="news-search-input" placeholder="Tìm theo tiêu đề, nội dung..." value="<?= htmlspecialchars($searchQuery) ?>">
            <?php if (!empty($searchQuery)): ?>
                <a href="<?= BASE_URL ?>/news<?= $activeType !== 'all' ? '?type=' . htmlspecialchars($activeType) : '' ?>" style="color: var(--text-muted); font-size: 13px; margin-right: 4px;" title="Xóa tìm kiếm">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            <?php endif; ?>
            <button type="submit" class="news-search-btn" title="Tìm kiếm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Bài Viết Tiêu Điểm Lớn (Hero Featured Article) -->
    <?php if ($featuredArticle): ?>
        <?php 
            $fImg = get_news_image_url($featuredArticle['image'] ?? null);
            $fDate = date('d/m/Y', strtotime($featuredArticle['created_at']));
            $fBadgeClass = 'badge-system';
            $fBadgeText = 'Thông Báo Sàn';
            if ($featuredArticle['type'] === 'game') {
                $fBadgeClass = 'badge-game';
                $fBadgeText = 'Cập Nhật Game';
            } elseif ($featuredArticle['type'] === 'genre') {
                $fBadgeClass = 'badge-genre';
                $fBadgeText = 'Chuyên Mục Thể Loại';
            }
        ?>
        <article class="featured-hero-card">
            <a href="<?= BASE_URL ?>/news/detail?id=<?= $featuredArticle['id'] ?>" class="featured-hero-media">
                <img src="<?= $fImg ?>" alt="<?= htmlspecialchars($featuredArticle['title']) ?>" class="featured-hero-img">
            </a>
            <div class="featured-hero-body">
                <div>
                    <div class="news-badge <?= $fBadgeClass ?>">
                        <i class="fa-solid fa-star"></i> <?= $fBadgeText ?>
                    </div>
                    <h2 class="featured-hero-title">
                        <a href="<?= BASE_URL ?>/news/detail?id=<?= $featuredArticle['id'] ?>">
                            <?= htmlspecialchars($featuredArticle['title']) ?>
                        </a>
                    </h2>
                    <p class="featured-hero-excerpt">
                        <?= htmlspecialchars(strip_tags($featuredArticle['content'])) ?>
                    </p>
                </div>
                <div class="featured-hero-meta">
                    <span><i class="fa-regular fa-calendar"></i> <?= $fDate ?></span>
                    <?php if (!empty($featuredArticle['game_title'])): ?>
                        <span><i class="fa-solid fa-gamepad"></i> <?= htmlspecialchars($featuredArticle['game_title']) ?></span>
                    <?php elseif (!empty($featuredArticle['category_name'])): ?>
                        <span><i class="fa-solid fa-tags"></i> <?= htmlspecialchars($featuredArticle['category_name']) ?></span>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/news/detail?id=<?= $featuredArticle['id'] ?>" style="margin-left: auto; color: var(--primary); font-weight: 700; text-decoration: none;">
                        Đọc Tiếp <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </article>
    <?php endif; ?>

    <!-- Lưới Thẻ Bài Viết (News Grid) -->
    <?php if (!empty($gridArticles)): ?>
        <div class="news-grid">
            <?php foreach ($gridArticles as $item): ?>
                <?php 
                    $cardImg = get_news_image_url($item['image'] ?? null);
                    $cardDate = date('d/m/Y', strtotime($item['created_at']));
                    $bClass = 'badge-system';
                    $bText = 'Thông Báo Sàn';
                    if ($item['type'] === 'game') {
                        $bClass = 'badge-game';
                        $bText = 'Cập Nhật Game';
                    } elseif ($item['type'] === 'genre') {
                        $bClass = 'badge-genre';
                        $bText = 'Chuyên Mục';
                    }
                ?>
                <a href="<?= BASE_URL ?>/news/detail?id=<?= $item['id'] ?>" class="news-card">
                    <div class="news-card-media">
                        <img src="<?= $cardImg ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="news-card-img">
                        <div class="news-card-badge-pos">
                            <span class="news-badge <?= $bClass ?>"><?= $bText ?></span>
                        </div>
                    </div>
                    <div class="news-card-body">
                        <div>
                            <div class="news-card-date">
                                <i class="fa-regular fa-calendar"></i> <?= $cardDate ?>
                            </div>
                            <h3 class="news-card-title" title="<?= htmlspecialchars($item['title']) ?>">
                                <?= htmlspecialchars($item['title']) ?>
                            </h3>
                            <p class="news-card-content">
                                <?= htmlspecialchars(strip_tags($item['content'])) ?>
                            </p>
                        </div>
                        <div class="news-card-footer">
                            <span class="news-card-tag">
                                <?php if (!empty($item['game_title'])): ?>
                                    <i class="fa-solid fa-gamepad"></i> <?= htmlspecialchars($item['game_title']) ?>
                                <?php elseif (!empty($item['category_name'])): ?>
                                    <i class="fa-solid fa-tag"></i> <?= htmlspecialchars($item['category_name']) ?>
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-info"></i> Hệ Thống
                                <?php endif; ?>
                            </span>
                            <span style="color: var(--primary); font-weight: 600;">
                                Xem Thêm <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php elseif (!$featuredArticle): ?>
        <!-- Trạng Thái Rỗng (Empty State) -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 70px 20px; text-align: center; margin-bottom: 40px;">
            <i class="fa-solid fa-newspaper" style="font-size: 48px; color: var(--text-muted); margin-bottom: 18px;"></i>
            <h3 style="margin: 0 0 10px 0; color: var(--text-primary); font-size: 20px;">Không tìm thấy bài viết tin tức nào</h3>
            <p style="margin: 0 0 24px 0; color: var(--text-secondary); font-size: 14px;">
                Không có bài viết nào phù hợp với bộ lọc hiện tại hoặc từ khóa tìm kiếm "<strong><?= htmlspecialchars($searchQuery) ?></strong>".
            </p>
            <a href="<?= BASE_URL ?>/news" class="btn-gaming btn-primary" style="padding: 10px 24px; font-size: 13px;">
                <i class="fa-solid fa-arrow-rotate-left"></i> Xem Tất Cả Tin Tức
            </a>
        </div>
    <?php endif; ?>

</div>
