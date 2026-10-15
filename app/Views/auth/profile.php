<?php
/**
 * Stonk Games Store - User Profile View
 * 
 * @var array $user Thông tin tài khoản người dùng
 * @var array $orders Danh sách đơn hàng cá nhân
 */
$user = $user ?? ($_SESSION['user'] ?? [
    'id' => 0,
    'username' => '',
    'email' => '',
    'role' => 'user',
    'created_at' => date('Y-m-d H:i:s')
]);
$orders = $orders ?? [];
?>
<div style="max-width: 980px; margin: 0 auto; padding: 10px 0 40px;">

    <!-- Flash Message Notification -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="padding: 14px 20px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; background: <?= $_SESSION['flash']['type'] === 'success' ? 'rgba(0, 230, 118, 0.15)' : 'rgba(255, 82, 82, 0.15)' ?>; border: 1px solid <?= $_SESSION['flash']['type'] === 'success' ? '#00e676' : '#ff5252' ?>; color: <?= $_SESSION['flash']['type'] === 'success' ? '#00e676' : '#ff5252' ?>;">
            <i class="fa-solid <?= $_SESSION['flash']['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
            <span><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Header Hồ Sơ Người Dùng -->
    <div class="profile-hero-card" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; color: #0b0e14; box-shadow: 0 4px 16px rgba(0, 242, 254, 0.35);">
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                    <h1 style="font-size: 24px; font-weight: 800; margin: 0; color: var(--text-primary);">
                        <?= htmlspecialchars($user['username']) ?>
                    </h1>
                    <?php if ($user['role'] === 'admin'): ?>
                        <span style="background: rgba(0, 242, 254, 0.15); border: 1px solid var(--primary); color: var(--primary); font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">
                            QUẢN TRỊ VIÊN
                        </span>
                    <?php else: ?>
                        <span style="background: rgba(0, 230, 118, 0.15); border: 1px solid var(--accent-green); color: var(--accent-green); font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">
                            THÀNH VIÊN
                        </span>
                    <?php endif; ?>
                </div>
                <div style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <span><i class="fa-regular fa-envelope" style="margin-right: 5px;"></i> <?= htmlspecialchars($user['email']) ?></span>
                    <span><i class="fa-regular fa-calendar-days" style="margin-right: 5px;"></i> Tham gia: <?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <?php if ($user['role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin" class="btn-gaming btn-outline" style="border-color: var(--primary); color: var(--primary);">
                    <i class="fa-solid fa-shield-halved"></i> Vào Trang Quản Trị
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/cart" class="btn-gaming btn-outline">
                <i class="fa-solid fa-cart-shopping"></i> Giỏ Hàng
            </a>
        </div>
    </div>

    <!-- Khối 3 Chỉ Số Nhanh -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px;">
            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                Trạng Thái Tài Khoản
            </div>
            <div style="font-size: 18px; font-weight: 800; color: var(--accent-green);">
                <i class="fa-solid fa-circle-check" style="font-size: 15px; margin-right: 6px;"></i> Đang Hoạt Động
            </div>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px;">
            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                Đơn Hàng Đã Ghi Nhận
            </div>
            <div style="font-size: 24px; font-weight: 800; color: var(--primary);">
                <?= $orderCount ?? 0 ?> đơn
            </div>
        </div>

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px;">
            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                Bảo Mật Tài Khoản
            </div>
            <div style="font-size: 18px; font-weight: 800; color: var(--text-primary);">
                Mã Hóa BCrypt
            </div>
        </div>
    </div>

    <!-- Form Chỉnh Sửa Thông Tin -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; margin-bottom: 30px;">
        <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
            Cập Nhật Thông Tin Cá Nhân
        </h2>
        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 25px;">
            Quản lý địa chỉ email nhận mã CD-Key và thay đổi mật khẩu bảo mật tài khoản
        </p>

        <form action="<?= BASE_URL ?>/profile/update" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                        Tên Đăng Nhập (Cố Định)
                    </label>
                    <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled
                           style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-muted); font-size: 14px; cursor: not-allowed;">
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                        Vai Trò Hệ Thống
                    </label>
                    <input type="text" value="<?= $user['role'] === 'admin' ? 'Quản Trị Viên (Admin)' : 'Game Thủ Thành Viên (User)' ?>" disabled
                           style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-muted); font-size: 14px; cursor: not-allowed;">
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase;">
                    Địa Chỉ Email Nhận Game <span style="color: #ff5252;">*</span>
                </label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                       style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 14px; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                <span style="display: block; font-size: 12px; color: var(--text-muted); margin-top: 5px;">
                    Email dùng để nhận thông báo kích hoạt và lưu trữ hóa đơn điện tử
                </span>
            </div>

            <div style="border-top: 1px dashed var(--border-color); padding-top: 25px; margin-top: 25px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
                    Thay Đổi Mật Khẩu (Tùy chọn)
                </h3>
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 18px;">
                    Để trống nếu bạn muốn giữ nguyên mật khẩu đăng nhập hiện tại
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-transform: uppercase;">
                            Mật Khẩu Hiện Tại
                        </label>
                        <input type="password" name="current_password" placeholder="Nhập để xác nhận đổi"
                               style="width: 100%; box-sizing: border-box; padding: 11px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 14px; outline: none;"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-transform: uppercase;">
                            Mật Khẩu Mới
                        </label>
                        <input type="password" name="new_password" placeholder="Tối thiểu 6 ký tự"
                               style="width: 100%; box-sizing: border-box; padding: 11px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 14px; outline: none;"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-transform: uppercase;">
                            Xác Nhận Mật Khẩu Mới
                        </label>
                        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới"
                               style="width: 100%; box-sizing: border-box; padding: 11px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 14px; outline: none;"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 14px; align-items: center;">
                <button type="submit" class="btn-gaming btn-primary" style="padding: 12px 28px; font-size: 14px;">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
                </button>
                <a href="<?= BASE_URL ?>/home" class="btn-gaming btn-outline">
                    Hủy Bỏ
                </a>
            </div>
        </form>
    </div>

    <!-- Lịch Sử Đơn Hàng Gần Đây Của Người Dùng -->
    <?php if (!empty($orders)): ?>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; margin: 0;">
                    Lịch Sử Mua Hàng Của Bạn
                </h2>
                <div style="display: flex; gap: 15px; align-items: center;">
                    <a href="<?= BASE_URL ?>/order/history" style="font-size: 13px; color: var(--primary); font-weight: 700; text-decoration: none;">
                        <i class="fa-solid fa-list-check"></i> Xem Tất Cả Đơn Hàng &rarr;
                    </a>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 10px 14px;">Mã Đơn</th>
                            <th style="padding: 10px 14px;">Ngày Đặt</th>
                            <th style="padding: 10px 14px;">Tổng Tiền</th>
                            <th style="padding: 10px 14px;">Số Game</th>
                            <th style="padding: 10px 14px;">Phương Thức</th>
                            <th style="padding: 10px 14px;">Trạng Thái</th>
                            <th style="padding: 10px 14px; text-align: right;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                                <td style="padding: 12px 14px; font-weight: 700;">
                                    <a href="<?= BASE_URL ?>/order/detail/<?= $order['id'] ?>" 
                                       title="Xem chi tiết đơn hàng và mã CD-Key"
                                       style="color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-weight: 700;">
                                        #<?= $order['id'] ?> <i class="fa-solid fa-key" style="font-size: 11px; opacity: 0.8;"></i>
                                    </a>
                                </td>
                                <td style="padding: 12px 14px; color: var(--text-secondary);">
                                    <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </td>
                                <td style="padding: 12px 14px; font-weight: 700; color: var(--text-primary);">
                                    <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                                </td>
                                <td style="padding: 12px 14px; color: var(--text-secondary);">
                                    <?= $order['item_count'] ?? 1 ?> game
                                </td>
                                <td style="padding: 12px 14px;">
                                    <span style="padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; background: rgba(0, 242, 254, 0.1); color: var(--primary);">
                                        <?= htmlspecialchars($order['payment_method'] ?? 'VIETQR') ?>
                                    </span>
                                </td>
                                <td style="padding: 12px 14px;">
                                    <?php if ($order['status'] === 'COMPLETED'): ?>
                                        <span style="padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; background: rgba(0, 230, 118, 0.15); color: var(--accent-green);">
                                            HOÀN TẤT
                                        </span>
                                    <?php else: ?>
                                        <span style="padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; background: rgba(255, 171, 0, 0.15); color: #ffab00;">
                                            CHỜ XỬ LÝ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px 14px; text-align: right;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <a href="<?= BASE_URL ?>/order/detail/<?= $order['id'] ?>" class="btn-gaming btn-primary" style="padding: 5px 10px; font-size: 11px;">
                                            <i class="fa-solid fa-eye"></i> Chi Tiết
                                        </a>
                                        <a href="<?= BASE_URL ?>/order/invoice/<?= $order['id'] ?>" target="_blank" class="btn-gaming btn-outline" style="padding: 5px 10px; font-size: 11px;">
                                            <i class="fa-solid fa-print"></i> Hóa Đơn
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>
