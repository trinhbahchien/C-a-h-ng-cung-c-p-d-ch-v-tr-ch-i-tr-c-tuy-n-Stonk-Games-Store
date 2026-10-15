<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Bảng Điều Khiển Quản Trị | Stonk Games Store') ?></title>
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
            --admin-bg-base: #0b0e14;
            --admin-bg-sidebar: #0f141c;
            --admin-bg-surface: #141b26;
            --admin-bg-card: #182230;
            --admin-border: rgba(255, 255, 255, 0.08);
            --admin-border-hover: rgba(0, 242, 254, 0.3);
            --admin-primary: #00f2fe;
            --admin-primary-glow: rgba(0, 242, 254, 0.25);
            --admin-secondary: #4facfe;
            --admin-success: #00e676;
            --admin-danger: #ff5252;
            --admin-warning: #ffb142;
            --admin-text-main: #f0f6fc;
            --admin-text-muted: #8b949e;
            --admin-input-bg: #0d1117;
            --admin-input-border: #30363d;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Chế độ Sáng (Light Mode) */
        html.light-mode {
            --admin-bg-base: #f1f5f9;
            --admin-bg-sidebar: #ffffff;
            --admin-bg-surface: #ffffff;
            --admin-bg-card: #ffffff;
            --admin-border: #e2e8f0;
            --admin-border-hover: rgba(2, 132, 199, 0.4);
            --admin-primary: #0284c7;
            --admin-primary-glow: rgba(2, 132, 199, 0.25);
            --admin-secondary: #2563eb;
            --admin-success: #16a34a;
            --admin-danger: #dc2626;
            --admin-warning: #d97706;
            --admin-text-main: #0f172a;
            --admin-text-muted: #64748b;
            --admin-input-bg: #f8fafc;
            --admin-input-border: #cbd5e1;
        }

        html.light-mode h1, html.light-mode h2, html.light-mode h3, html.light-mode h4, html.light-mode h5 {
            color: var(--admin-text-main) !important;
        }

        html.light-mode [style*="color: #fff"],
        html.light-mode [style*="color:#fff"],
        html.light-mode [style*="color: #ffffff"],
        html.light-mode [style*="color:#ffffff"],
        html.light-mode [style*="color: white"] {
            color: var(--admin-text-main) !important;
        }

        html.light-mode .btn-admin-primary,
        html.light-mode .btn-admin-danger,
        html.light-mode button[type="submit"],
        html.light-mode .admin-avatar-text {
            color: #ffffff !important;
        }

        html.light-mode .btn-admin-secondary {
            color: #0f172a !important;
            background: #ffffff !important;
            border-color: #cbd5e1 !important;
        }

        html.light-mode .btn-admin-secondary:hover {
            background: #f1f5f9 !important;
            border-color: #0284c7 !important;
            color: #0284c7 !important;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--admin-bg-base);
            color: var(--admin-text-main);
            min-height: 100vh;
            display: flex;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Khung bố cục Admin */
        .admin-layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 260px;
            background: var(--admin-bg-sidebar);
            border-right: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: background 0.25s ease, border-color 0.25s ease;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid var(--admin-border);
        }

        .brand-link {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        html.light-mode .brand-link {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .badge-portal {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.5px;
            background: rgba(0, 242, 254, 0.15);
            color: var(--admin-primary);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(0, 242, 254, 0.3);
            text-transform: uppercase;
        }

        html.light-mode .badge-portal {
            background: rgba(2, 132, 199, 0.1);
            color: var(--admin-primary);
            border-color: rgba(2, 132, 199, 0.3);
        }

        .admin-profile-box {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--admin-border);
        }

        html.light-mode .admin-profile-box {
            background: #f8fafc;
        }

        .admin-avatar-text {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #0b0e14;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
        }

        html.light-mode .admin-avatar-text {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
        }

        .admin-profile-info {
            display: flex;
            flex-direction: column;
        }

        .admin-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--admin-text-main);
        }

        .admin-role-badge {
            font-size: 10px;
            font-weight: 700;
            color: var(--admin-primary);
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 15px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }

        .menu-section-label {
            font-size: 10px;
            font-weight: 800;
            color: var(--admin-text-muted);
            padding: 12px 10px 4px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--admin-text-muted);
            transition: all 0.2s ease;
        }

        .menu-bullet {
            font-size: 12px;
            color: var(--admin-text-muted);
        }

        .menu-item:hover {
            color: var(--admin-text-main);
            background: rgba(255, 255, 255, 0.05);
        }

        html.light-mode .menu-item:hover {
            background: #f1f5f9;
            color: var(--admin-text-main);
        }

        .menu-item.active {
            color: var(--admin-primary);
            background: rgba(0, 242, 254, 0.1);
            border-left: 3px solid var(--admin-primary);
        }

        html.light-mode .menu-item.active {
            background: rgba(2, 132, 199, 0.1);
            color: var(--admin-primary);
            border-left-color: var(--admin-primary);
        }

        .menu-item.active .menu-bullet {
            color: var(--admin-primary);
        }

        .menu-logout:hover {
            color: var(--admin-danger);
            background: rgba(255, 82, 82, 0.1);
        }

        /* Vùng nội dung chính */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--admin-bg-base);
            transition: background 0.25s ease;
        }

        /* Top Bar */
        .admin-topbar {
            height: 64px;
            background: var(--admin-bg-surface);
            border-bottom: 1px solid var(--admin-border);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
            transition: background 0.25s ease, border-color 0.25s ease;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.2px;
            color: var(--admin-text-main);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Nút Chuyển Đổi Giao Diện Sáng / Tối */
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
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--admin-border);
            color: var(--admin-primary);
            transition: all 0.25s ease;
        }

        .btn-theme-toggle:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--admin-primary);
            transform: translateY(-1px);
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

        .btn-view-store {
            font-size: 12px;
            font-weight: 700;
            color: var(--admin-primary);
            background: rgba(0, 242, 254, 0.1);
            border: 1px solid rgba(0, 242, 254, 0.3);
            padding: 7px 14px;
            border-radius: 6px;
            transition: all 0.2s;
            text-transform: uppercase;
        }

        html.light-mode .btn-view-store {
            background: rgba(2, 132, 199, 0.1);
            border-color: rgba(2, 132, 199, 0.3);
            color: var(--admin-primary);
        }

        .btn-view-store:hover {
            opacity: 0.85;
        }

        /* Body Area */
        .admin-content-body {
            padding: 30px;
            flex: 1;
        }

        /* Flash Message Alert */
        .alert-flash {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success {
            background: rgba(0, 230, 118, 0.15);
            border: 1px solid var(--admin-success);
            color: var(--admin-success);
        }

        .alert-danger {
            background: rgba(255, 82, 82, 0.15);
            border: 1px solid var(--admin-danger);
            color: var(--admin-danger);
        }

        .alert-info {
            background: rgba(0, 242, 254, 0.15);
            border: 1px solid var(--admin-primary);
            color: var(--admin-primary);
        }

        /* Admin UI Components */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--admin-text-main);
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--admin-text-muted);
            margin-top: 4px;
        }

        /* Standard Buttons (No icons/emojis) */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-admin-primary {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #0b0e14;
            box-shadow: 0 4px 12px var(--admin-primary-glow);
        }

        html.light-mode .btn-admin-primary {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
        }

        .btn-admin-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px var(--admin-primary-glow);
        }

        .btn-admin-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--admin-text-main);
            border: 1px solid var(--admin-border);
        }

        html.light-mode .btn-admin-secondary {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-admin-secondary:hover {
            opacity: 0.9;
        }

        .btn-admin-danger {
            background: rgba(255, 82, 82, 0.15);
            color: var(--admin-danger);
            border: 1px solid rgba(255, 82, 82, 0.3);
        }

        .btn-admin-danger:hover {
            background: var(--admin-danger);
            color: #fff;
        }

        .btn-admin-sm {
            padding: 6px 12px;
            font-size: 11px;
            border-radius: 4px;
        }

        /* Cards & Tables */
        .admin-card {
            background: var(--admin-bg-surface);
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            margin-bottom: 25px;
            transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        html.light-mode .admin-card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        .admin-table th {
            background: rgba(255, 255, 255, 0.03);
            color: var(--admin-text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--admin-border);
        }

        html.light-mode .admin-table th {
            background: #f8fafc;
            color: #475569;
        }

        .admin-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--admin-border);
            color: var(--admin-text-main);
            vertical-align: middle;
        }

        .admin-table tbody tr:hover {
            background: rgba(0, 242, 254, 0.03);
        }

        html.light-mode .admin-table tbody tr:hover {
            background: rgba(2, 132, 199, 0.04);
        }

        .badge-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-active {
            background: rgba(0, 230, 118, 0.15);
            color: var(--admin-success);
            border: 1px solid rgba(0, 230, 118, 0.3);
        }

        .badge-hidden {
            background: rgba(255, 82, 82, 0.15);
            color: var(--admin-danger);
            border: 1px solid rgba(255, 82, 82, 0.3);
        }

        .badge-admin {
            background: rgba(0, 242, 254, 0.15);
            color: var(--admin-primary);
            border: 1px solid rgba(0, 242, 254, 0.3);
        }

        .badge-user {
            background: rgba(255, 255, 255, 0.1);
            color: var(--admin-text-muted);
            border: 1px solid var(--admin-border);
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--admin-text-main);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            background: var(--admin-input-bg);
            border: 1px solid var(--admin-input-border);
            border-radius: 6px;
            color: var(--admin-text-main);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .form-control:focus {
            border-color: var(--admin-primary);
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-col {
            flex: 1;
        }

        .form-hint {
            font-size: 11px;
            color: var(--admin-text-muted);
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        <!-- Sidebar Điều Hướng -->
        <?php require APP_PATH . '/Views/partials/admin_sidebar.php'; ?>

        <!-- Vùng Nội Dung Chính -->
        <div class="admin-main">
            <!-- Thanh Topbar -->
            <header class="admin-topbar">
                <div class="topbar-title">
                    <?= htmlspecialchars($pageTitle ?? 'BẢNG ĐIỀU KHIỂN QUẢN TRỊ') ?>
                </div>

                <div class="topbar-actions">
                    <!-- Nút Chuyển Đổi Giao Diện Sáng / Tối với Icon Lightbulb -->
                    <button id="themeToggleBtn" type="button" class="btn-theme-toggle" onclick="toggleAdminTheme()" title="Chuyển chế độ sáng / tối" aria-label="Chuyển chế độ sáng / tối">
                        <i class="fa-regular fa-lightbulb"></i>
                    </button>

                    <a href="<?= BASE_URL ?>" target="_blank" class="btn-view-store">
                        Xem Cửa Hàng
                    </a>
                </div>
            </header>

            <!-- Vùng Hiển Thị Nội Dung -->
            <main class="admin-content-body">
                <?php if (isset($_SESSION['flash'])): ?>
                    <div class="alert-flash alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?>">
                        <span><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
                    </div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <!-- Script Xử Lý Chuyển Đổi Theme Sáng / Tối -->
    <script>
        function toggleAdminTheme() {
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
    </script>
</body>
</html>
