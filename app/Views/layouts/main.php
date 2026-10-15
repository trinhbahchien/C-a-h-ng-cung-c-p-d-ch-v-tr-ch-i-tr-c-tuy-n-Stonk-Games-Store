<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Stonk Games Store') ?></title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Script áp dụng Theme Light/Dark tức thì tránh giật nháy trang -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>

    <style>
        :root {
            --bg-base: #0b0e14;
            --bg-surface: #121721;
            --bg-card: #181f2c;
            --border-color: rgba(255, 255, 255, 0.08);
            --primary: #00f2fe;
            --primary-glow: rgba(0, 242, 254, 0.35);
            --secondary: #4facfe;
            --accent-green: #00e676;
            --text-primary: #f0f6fc;
            --text-secondary: #8b949e;
            --text-muted: #6e7681;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        html.light-mode {
            --bg-base: #f1f5f9;
            --bg-surface: #ffffff;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --primary: #0284c7;
            --primary-glow: rgba(2, 132, 199, 0.25);
            --secondary: #2563eb;
            --accent-green: #16a34a;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
        }

        html.light-mode h1, html.light-mode h2, html.light-mode h3, html.light-mode h4, html.light-mode h5 {
            color: var(--text-primary) !important;
        }

        html.light-mode [style*="color: #fff"],
        html.light-mode [style*="color:#fff"],
        html.light-mode [style*="color: #ffffff"],
        html.light-mode [style*="color:#ffffff"],
        html.light-mode [style*="color: white"] {
            color: var(--text-primary) !important;
        }

        html.light-mode .btn-primary,
        html.light-mode button[type="submit"] {
            color: #ffffff !important;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        img {
            max-width: 100%;
            height: auto;
            vertical-align: middle;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        /* Header Navbar */
        .header-nav {
            background: rgba(18, 23, 33, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 20px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        html.light-mode .brand-logo {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-badge {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: rgba(0, 242, 254, 0.15);
            color: var(--primary);
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid rgba(0, 242, 254, 0.3);
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            padding: 8px 0;
            position: relative;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            padding: 0;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: var(--primary);
            transition: all 0.25s ease;
        }

        .btn-theme-toggle:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--primary);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* User Dropdown Component */
        .user-dropdown-wrapper {
            position: relative;
            display: inline-block;
        }

        .nav-user-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 6px 14px 6px 8px;
            color: var(--text-primary);
            cursor: pointer;
            font-family: var(--font-family);
            transition: all 0.2s ease;
        }

        .nav-user-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--primary);
        }

        html.light-mode .nav-user-btn {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        html.light-mode .nav-user-btn:hover {
            background: #f8fafc;
            border-color: #0284c7;
        }

        .user-btn-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #0b0e14;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
        }

        .user-btn-name {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .user-btn-chevron {
            font-size: 10px;
            color: var(--text-muted);
            transition: transform 0.25s ease;
        }

        .user-dropdown-wrapper.open .user-btn-chevron {
            transform: rotate(180deg);
        }

        /* Dedicated Outside Cart Button in Navbar */
        .nav-cart-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .nav-cart-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 12px var(--primary-glow);
            transform: translateY(-1px);
        }

        html.light-mode .nav-cart-btn {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #0f172a;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        html.light-mode .nav-cart-btn:hover {
            background: #f8fafc;
            border-color: #0284c7;
            color: #0284c7;
        }

        .nav-cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            line-height: 1;
        }

        /* User Dropdown Menu Window */
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 250px;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.6);
            padding: 8px;
            display: none;
            flex-direction: column;
            gap: 2px;
            z-index: 1000;
            backdrop-filter: blur(12px);
            transform-origin: top right;
            animation: dropdownFade 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-8px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .user-dropdown-wrapper.open .user-dropdown-menu {
            display: flex;
        }

        html.light-mode .user-dropdown-menu {
            background: #ffffff;
            border-color: #e2e8f0;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12);
        }

        .user-dropdown-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px 12px;
        }

        .user-dropdown-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #0b0e14;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
        }

        .user-dropdown-meta {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .user-meta-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        html.light-mode .user-meta-name {
            color: #0f172a;
        }

        .user-meta-role {
            font-size: 11px;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 4px 0;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .user-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--primary);
        }

        html.light-mode .user-dropdown-item {
            color: #475569;
        }

        html.light-mode .user-dropdown-item:hover {
            background: #f1f5f9;
            color: #0284c7;
        }

        .user-dropdown-item i {
            width: 16px;
            font-size: 14px;
            text-align: center;
        }

        .user-dropdown-item.item-admin {
            color: var(--primary);
            font-weight: 700;
        }

        .user-dropdown-item.item-logout {
            color: #ff5252;
        }

        .user-dropdown-item.item-logout:hover {
            background: rgba(255, 82, 82, 0.1);
            color: #ff5252;
        }

        .item-cart-count {
            margin-left: auto;
            background: rgba(0, 230, 118, 0.2);
            color: var(--accent-green);
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
        }

        html.light-mode .header-nav {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        html.light-mode .brand-badge {
            background: rgba(2, 132, 199, 0.1);
            color: #0284c7;
            border-color: rgba(2, 132, 199, 0.3);
        }

        html.light-mode .btn-theme-toggle {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #d97706;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        html.light-mode .btn-theme-toggle:hover {
            background: #f8fafc;
            border-color: #d97706;
            color: #b45309;
        }

        html.light-mode .btn-outline {
            background: #ffffff;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        html.light-mode .btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        html.light-mode .footer-gaming {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
        }

        html.light-mode .tech-pill {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }


        html.light-mode h1, html.light-mode h2, html.light-mode h3, html.light-mode h4 {
            color: #0f172a !important;
        }

        .btn-gaming {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #0b0e14;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 242, 254, 0.5);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Main Container */
        .main-wrapper {
            flex: 1;
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Footer */
        .footer-gaming {
            background: var(--bg-surface);
            border-top: 1px solid var(--border-color);
            padding: 40px 20px 30px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1320px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .footer-tech {
            display: flex;
            gap: 12px;
            font-size: 12px;
        }

        .tech-pill {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color);
            padding: 3px 10px;
            border-radius: 4px;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>

    <!-- Header Navigation (Strictly no icons in labels or module names) -->
    <header class="header-nav">
        <div class="nav-container">
            <a href="<?= BASE_URL ?>" class="brand-logo">
                STONK GAMES <span class="brand-badge">STORE</span>
            </a>

            <?php
                $currentUri = $_SERVER['REQUEST_URI'] ?? '';
                $isCatalog = strpos($currentUri, '/catalog') !== false || strpos($currentUri, '/game/') !== false;
                $isRankings = strpos($currentUri, '/rankings') !== false;
                $isNews = strpos($currentUri, '/news') !== false;
                $isHome = !$isCatalog && !$isRankings && !$isNews;
            ?>
            <ul class="nav-links">
                <li><a href="<?= BASE_URL ?>/home" class="<?= $isHome ? 'active' : '' ?>"><?= __('nav_home') ?></a></li>
                <li><a href="<?= BASE_URL ?>/catalog" class="<?= $isCatalog ? 'active' : '' ?>"><?= __('nav_catalog') ?></a></li>
                <li><a href="<?= BASE_URL ?>/rankings" class="<?= $isRankings ? 'active' : '' ?>"><?= __('nav_rankings') ?></a></li>
                <li><a href="<?= BASE_URL ?>/news" class="<?= $isNews ? 'active' : '' ?>"><?= __('nav_news') ?></a></li>
            </ul>

            <div class="nav-actions">
                <!-- Nút Chuyển Đổi Giao Diện Sáng / Tối với Icon Lightbulb -->
                <button id="themeToggleBtn" type="button" class="btn-theme-toggle" onclick="toggleMainTheme()" title="Chuyển chế độ sáng / tối" aria-label="Chuyển chế độ sáng / tối">
                    <i class="fa-regular fa-lightbulb"></i>
                </button>

                <?php 
                    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                    $currentUser = $_SESSION['user'] ?? null;
                ?>

                <!-- Nút Giỏ Hàng Độc Lập Bên Ngoài Thanh Điều Hướng (Dành cho cả khách và thành viên) -->
                <a href="<?= BASE_URL ?>/cart" class="btn-gaming btn-outline nav-cart-btn" title="Xem giỏ hàng của bạn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="nav-cart-text"><?= __('nav_cart') ?></span>
                    <?php if ($cartCount > 0): ?>
                        <span class="nav-cart-count" style="background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: #0b0e14; padding: 2px 7px; border-radius: 10px; font-size: 11px; font-weight: 800; margin-left: 6px; box-shadow: 0 0 8px rgba(0, 242, 254, 0.4);"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>

                <?php if ($currentUser): ?>
                    <!-- Menu Người Dùng Đã Đăng Nhập -->
                    <div class="user-dropdown-wrapper" id="userDropdownWrapper">
                        <button type="button" class="nav-user-btn" id="userMenuToggleBtn" onclick="toggleUserDropdown(event)" aria-haspopup="true" aria-expanded="false" title="Menu tài khoản của <?= htmlspecialchars($currentUser['username']) ?>">
                            <span class="user-btn-avatar"><?= strtoupper(substr($currentUser['username'], 0, 1)) ?></span>
                            <span class="user-btn-name"><?= htmlspecialchars($currentUser['username']) ?></span>
                            <i class="fa-solid fa-chevron-down user-btn-chevron"></i>
                        </button>

                        <div class="user-dropdown-menu" id="userDropdownMenu">
                            <div class="user-dropdown-header">
                                <div class="user-dropdown-avatar"><?= strtoupper(substr($currentUser['username'], 0, 1)) ?></div>
                                <div class="user-dropdown-meta">
                                    <span class="user-meta-name"><?= htmlspecialchars($currentUser['username']) ?></span>
                                    <span class="user-meta-role"><?= ($currentUser['role'] === 'admin') ? 'Quản Trị Viên' : 'Game Thủ Thành Viên' ?></span>
                                </div>
                            </div>

                            <div class="user-dropdown-divider"></div>

                            <a href="<?= BASE_URL ?>/profile" class="user-dropdown-item">
                                <i class="fa-solid fa-user-gear"></i>
                                <span>Thông Tin Tài Khoản</span>
                            </a>

                            <!-- CHỨC NĂNG LỊCH SỬ MUA HÀNG NGAY BÊN DƯỚI THÔNG TIN TÀI KHOẢN -->
                            <a href="<?= BASE_URL ?>/order/history" class="user-dropdown-item item-order-history" style="color: var(--primary);">
                                <i class="fa-solid fa-receipt"></i>
                                <span>Lịch Sử Mua Hàng</span>
                            </a>

                            <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                                <div class="user-dropdown-divider"></div>
                                <a href="<?= BASE_URL ?>/admin" class="user-dropdown-item item-admin">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span>Trang Quản Trị Hệ Thống</span>
                                </a>
                            <?php endif; ?>

                            <div class="user-dropdown-divider"></div>

                            <a href="<?= BASE_URL ?>/auth/logout" class="user-dropdown-item item-logout">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span>Đăng Xuất</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Khách chưa đăng nhập: Nút Đăng nhập -->
                    <a href="<?= BASE_URL ?>/auth/login" class="btn-gaming btn-primary">
                        <?= __('nav_login') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content Injection -->
    <main class="main-wrapper">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer Gaming Partial -->
    <?php require_once APP_PATH . '/Views/partials/footer.php'; ?>

    <!-- Script Xử Lý Chuyển Đổi Theme Sáng / Tối -->
    <script>
        function toggleMainTheme() {
            const isLight = document.documentElement.classList.toggle('light-mode');
            const newTheme = isLight ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            updateThemeBtnText();
        }

        function updateThemeBtnText() {
            const btn = document.getElementById('themeToggleBtn');
            if (btn) {
                const isLight = document.documentElement.classList.contains('light-mode');
                btn.innerHTML = isLight 
                    ? '<i class="fa-solid fa-lightbulb"></i>' 
                    : '<i class="fa-regular fa-lightbulb"></i>';
                btn.setAttribute('title', isLight ? 'Chuyển sang Chế độ tối' : 'Chuyển sang Chế độ sáng');
            }
        }

        document.addEventListener('DOMContentLoaded', updateThemeBtnText);

        function toggleUserDropdown(event) {
            event.stopPropagation();
            const wrapper = document.getElementById('userDropdownWrapper');
            if (wrapper) {
                wrapper.classList.toggle('open');
            }
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('userDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                wrapper.classList.remove('open');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const wrapper = document.getElementById('userDropdownWrapper');
                if (wrapper) wrapper.classList.remove('open');
            }
        });
    </script>
</body>
</html>
