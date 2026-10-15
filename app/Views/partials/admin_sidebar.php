<?php
$currentRoute = $_GET['url'] ?? 'admin';
$currentRoute = trim($currentRoute, '/');
?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <a href="<?= BASE_URL ?>/admin" class="brand-link">
            STONK ADMIN <span class="badge-portal">PORTAL</span>
        </a>
    </div>

    <div class="admin-profile-box">
        <div class="admin-avatar-text">
            <?= strtoupper(substr($_SESSION['user']['username'] ?? 'A', 0, 1)) ?>
        </div>
        <div class="admin-profile-info">
            <span class="admin-name"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?></span>
            <span class="admin-role-badge">QUẢN TRỊ VIÊN</span>
        </div>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-section-label">HỆ THỐNG QUẢN TRỊ</div>

        <a href="<?= BASE_URL ?>/admin/dashboard" 
           class="menu-item <?= ($currentRoute === 'admin' || $currentRoute === 'admin/dashboard') ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Bảng Điều Khiển</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/games" 
           class="menu-item <?= (strpos($currentRoute, 'admin/games') === 0) ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Quản Lý Game</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/news" 
           class="menu-item <?= (strpos($currentRoute, 'admin/news') === 0) ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Quản Lý Tin Tức</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/categories" 
           class="menu-item <?= (strpos($currentRoute, 'admin/categories') === 0) ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Quản Lý Thể Loại</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/developers" 
           class="menu-item <?= (strpos($currentRoute, 'admin/developers') === 0) ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Quản Lý Studio</span>
        </a>

        <div class="menu-section-label">GIAO DỊCH & NGƯỜI DÙNG</div>

        <a href="<?= BASE_URL ?>/admin/users" 
           class="menu-item <?= (strpos($currentRoute, 'admin/users') === 0) ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Quản Lý Tài Khoản</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/orders" 
           class="menu-item <?= (strpos($currentRoute, 'admin/orders') === 0) ? 'active' : '' ?>">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Quản Lý Đơn Hàng</span>
        </a>

        <div class="menu-section-label">ĐIỀU HƯỚNG NGOÀI</div>

        <a href="<?= BASE_URL ?>" class="menu-item" target="_blank">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Xem Cửa Hàng</span>
        </a>

        <a href="<?= BASE_URL ?>/auth/logout" class="menu-item menu-logout">
            <span class="menu-bullet">•</span>
            <span class="menu-text">Đăng Xuất</span>
        </a>
    </nav>
</aside>
