<?php
/**
 * Stonk Games Store - Order History View
 * Lịch sử đơn hàng game, bộ lọc trạng thái và form tra cứu nhanh
 * 
 * @var array|null $user
 * @var array $orders
 * @var string $currentTab
 * @var array $counts
 * @var string|null $lookupError
 */
?>
<div class="order-history-container" style="max-width: 1100px; margin: 0 auto; padding: 10px 0 50px;">

    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-bottom: 25px;">
        <a href="<?= BASE_URL ?>/" style="color: var(--text-secondary);"><i class="fa-solid fa-house"></i> Trang Chủ</a>
        <span style="opacity: 0.5;"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span style="color: var(--primary); font-weight: 600;">Lịch Sử Mua Hàng</span>
    </div>

    <!-- Page Header -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 28px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); margin: 0 0 6px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-receipt" style="color: var(--primary);"></i>
                Lịch Sử Mua Hàng & Hóa Đơn
            </h1>
            <p style="font-size: 14px; color: var(--text-secondary); margin: 0;">
                Quản lý toàn bộ giao dịch, kiểm tra mã kích hoạt CD-Key và xuất hóa đơn bán lẻ điện tử
            </p>
        </div>

        <?php if (!empty($user)): ?>
            <div style="display: flex; align-items: center; gap: 12px; background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800; color: #0b0e14;">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                </div>
                <div style="display: flex; flex-direction: column;">
                    <strong style="font-size: 14px; color: var(--text-primary);"><?= htmlspecialchars($user['username']) ?></strong>
                    <span style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($user['email']) ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($user)): ?>
        <!-- KHÁCH CHƯA ĐĂNG NHẬP: HIỂN THỊ BANNER ĐĂNG NHẬP & FORM TRA CỨU NHANH -->
        <div style="background: linear-gradient(135deg, rgba(0, 242, 254, 0.08) 0%, rgba(79, 172, 254, 0.04) 100%); border: 1px dashed var(--primary); border-radius: 14px; padding: 25px; margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(0, 242, 254, 0.15); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                    <i class="fa-solid fa-user-lock"></i>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px;">
                        Bạn chưa đăng nhập tài khoản?
                    </h3>
                    <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                        Đăng nhập để tự động lưu trữ, bảo lưu vĩnh viễn kho key game và quản lý toàn bộ hóa đơn của bạn.
                    </p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/auth/login" class="btn-gaming btn-primary" style="padding: 10px 22px;">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập Ngay
            </a>
        </div>

        <!-- Khối Tra Cứu Đơn Hàng Dành Cho Khách -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 32px; margin-bottom: 40px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 50%; background: rgba(0, 242, 254, 0.12); color: var(--primary); font-size: 20px; margin-bottom: 12px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0 0 6px;">
                    Tra Cứu Nhanh Đơn Hàng & Giấy Phép Bản Quyền
                </h2>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">
                    Nhập Mã đơn hàng (#ID) và Địa chỉ Email nhận mã bản quyền đã dùng khi thanh toán
                </p>
            </div>

            <?php if (!empty($lookupError)): ?>
                <div style="background: rgba(255, 82, 82, 0.12); border: 1px solid #ff5252; color: #ff5252; padding: 12px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 22px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= htmlspecialchars($lookupError) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/order/lookup" method="POST" style="max-width: 650px; margin: 0 auto;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">
                            <i class="fa-solid fa-hashtag" style="color: var(--primary);"></i> Mã Đơn Hàng (ID) <span style="color: #ff5252;">*</span>
                        </label>
                        <input type="number" name="order_id" placeholder="Ví dụ: 12" required
                               style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; outline: none;"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">
                            <i class="fa-regular fa-envelope" style="color: var(--primary);"></i> Email Nhận Key <span style="color: #ff5252;">*</span>
                        </label>
                        <input type="email" name="email" placeholder="Ví dụ: gamer@gmail.com" required
                               style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; outline: none;"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>
                </div>

                <button type="submit" class="btn-gaming btn-primary" style="width: 100%; padding: 13px; font-size: 14px; justify-content: center;">
                    <i class="fa-solid fa-magnifying-glass"></i> Tra Cứu Đơn Hàng Ngay
                </button>
            </form>
        </div>
    <?php else: ?>
        <!-- USER ĐÃ ĐĂNG NHẬP: BỘ LỌC TABS & DANH SÁCH ĐƠN HÀNG -->

        <!-- Filter Tabs -->
        <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; overflow-x: auto;">
            <a href="<?= BASE_URL ?>/order/history?status=all" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s; background: <?= $currentTab === 'all' ? 'var(--primary)' : 'rgba(255, 255, 255, 0.04)' ?>; color: <?= $currentTab === 'all' ? '#0b0e14' : 'var(--text-secondary)' ?>; border: 1px solid <?= $currentTab === 'all' ? 'var(--primary)' : 'var(--border-color)' ?>;">
                <span>Tất Cả</span>
                <span style="background: rgba(0, 0, 0, 0.25); color: inherit; padding: 2px 7px; border-radius: 10px; font-size: 11px;">
                    <?= $counts['all'] ?>
                </span>
            </a>

            <a href="<?= BASE_URL ?>/order/history?status=completed" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s; background: <?= $currentTab === 'completed' ? 'var(--accent-green)' : 'rgba(255, 255, 255, 0.04)' ?>; color: <?= $currentTab === 'completed' ? '#0b0e14' : 'var(--text-secondary)' ?>; border: 1px solid <?= $currentTab === 'completed' ? 'var(--accent-green)' : 'var(--border-color)' ?>;">
                <span>Hoàn Tất & Đã Cấp Key</span>
                <span style="background: rgba(0, 0, 0, 0.25); color: inherit; padding: 2px 7px; border-radius: 10px; font-size: 11px;">
                    <?= $counts['completed'] ?>
                </span>
            </a>

            <a href="<?= BASE_URL ?>/order/history?status=pending" 
               style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all 0.2s; background: <?= $currentTab === 'pending' ? '#ffab00' : 'rgba(255, 255, 255, 0.04)' ?>; color: <?= $currentTab === 'pending' ? '#0b0e14' : 'var(--text-secondary)' ?>; border: 1px solid <?= $currentTab === 'pending' ? '#ffab00' : 'var(--border-color)' ?>;">
                <span>Chờ Xử Lý</span>
                <span style="background: rgba(0, 0, 0, 0.25); color: inherit; padding: 2px 7px; border-radius: 10px; font-size: 11px;">
                    <?= $counts['pending'] ?>
                </span>
            </a>
        </div>

        <?php if (empty($orders)): ?>
            <!-- Trạng thái trống -->
            <div style="background: var(--bg-surface); border: 1px dashed var(--border-color); border-radius: 14px; padding: 60px 20px; text-align: center;">
                <div style="font-size: 50px; color: var(--text-muted); margin-bottom: 15px;">
                    <i class="fa-regular fa-folder-open"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px;">
                    Chưa có đơn hàng nào trong danh mục này
                </h3>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 0 0 20px;">
                    Khám phá kho game bản quyền đồ sộ với mức giá ưu đãi nhất tại Stonk Games Store
                </p>
                <a href="<?= BASE_URL ?>/catalog" class="btn-gaming btn-primary">
                    <i class="fa-solid fa-gamepad"></i> Khám Phá Kho Game Ngay
                </a>
            </div>
        <?php else: ?>
            <!-- Danh Sách Thẻ Đơn Hàng -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($orders as $o): ?>
                    <?php 
                        $isCompleted = ($o['status'] === 'COMPLETED');
                        $statusBadgeColor = $isCompleted ? 'var(--accent-green)' : '#ffab00';
                        $statusBg = $isCompleted ? 'rgba(0, 230, 118, 0.12)' : 'rgba(255, 171, 0, 0.12)';
                        $statusText = $isCompleted ? 'ĐÃ HOÀN TẤT & ĐÃ CẤP KEY' : 'CHỜ XÁC NHẬN';
                    ?>
                    <div class="order-card-gaming" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; transition: all 0.25s ease; box-shadow: 0 4px 16px rgba(0,0,0,0.2);">
                        <!-- Header thẻ đơn -->
                        <div style="background: rgba(255, 255, 255, 0.03); padding: 14px 22px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                                <span style="font-weight: 700; color: var(--text-muted);">ĐƠN HÀNG:</span>
                                <span style="font-weight: 800; color: var(--primary); font-size: 15px; letter-spacing: 0.5px;">#<?= $o['id'] ?></span>
                                <span style="color: var(--border-color);">|</span>
                                <span style="color: var(--text-muted); font-size: 12px;">
                                    <i class="fa-regular fa-clock"></i> <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
                                </span>
                            </div>

                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; background: <?= $statusBg ?>; color: <?= $statusBadgeColor ?>; border: 1px solid <?= $statusBadgeColor ?>;">
                                <i class="fa-solid <?= $isCompleted ? 'fa-circle-check' : 'fa-hourglass-half' ?>"></i> <?= $statusText ?>
                            </span>
                        </div>

                        <!-- Thân thẻ đơn -->
                        <div style="padding: 20px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 16px; flex: 1; min-width: 280px;">
                                <div style="width: 70px; height: 70px; border-radius: 8px; overflow: hidden; background: var(--bg-card); border: 1px solid var(--border-color); flex-shrink: 0;">
                                    <img src="<?= get_game_cover_url($o['first_game_cover'] ?? '') ?>" 
                                         alt="<?= htmlspecialchars($o['first_game_title'] ?? 'Game') ?>"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <h4 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0 0 6px;">
                                        <?= htmlspecialchars($o['first_game_title'] ?? 'Game Bản Quyền') ?>
                                    </h4>
                                    <div style="font-size: 13px; color: var(--text-secondary); display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <?php if ((int)$o['item_count'] > 1): ?>
                                            <span>và <strong><?= (int)$o['item_count'] - 1 ?></strong> game khác</span>
                                        <?php else: ?>
                                            <span>Tổng số: 1 tựa game</span>
                                        <?php endif; ?>
                                        <span style="color: var(--text-muted);">&bull;</span>
                                        <span style="color: var(--primary); font-weight: 600;">
                                            <i class="fa-solid fa-wallet"></i> <?= htmlspecialchars($o['payment_method'] ?? 'VIETQR') ?>
                                        </span>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                        <i class="fa-regular fa-envelope"></i> Gửi đến: <?= htmlspecialchars($o['customer_email']) ?>
                                    </div>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Tổng thanh toán</div>
                                <div style="font-size: 20px; font-weight: 800; color: var(--accent-green); margin-top: 2px;">
                                    <?= number_format($o['total_amount'], 0, ',', '.') ?> đ
                                </div>
                            </div>
                        </div>

                        <!-- Chân thẻ đơn -->
                        <div style="background: rgba(255, 255, 255, 0.02); border-top: 1px solid var(--border-color); padding: 12px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                            <div style="font-size: 12px; color: var(--text-muted);">
                                <?php if ($isCompleted): ?>
                                    <span style="color: var(--accent-green);"><i class="fa-solid fa-key"></i> Đã cấp giấy phép bản quyền số</span>
                                <?php else: ?>
                                    <span style="color: #ffab00;"><i class="fa-solid fa-clock"></i> Đang chờ xác nhận chuyển khoản</span>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; gap: 10px; align-items: center;">
                                <a href="<?= BASE_URL ?>/order/invoice/<?= $o['id'] ?>" target="_blank" class="btn-gaming btn-outline" style="padding: 7px 14px; font-size: 12px;">
                                    <i class="fa-solid fa-print"></i> In Hóa Đơn
                                </a>
                                <a href="<?= BASE_URL ?>/order/detail/<?= $o['id'] ?>" class="btn-gaming btn-primary" style="padding: 7px 16px; font-size: 12px;">
                                    <i class="fa-solid fa-eye"></i> Xem Chi Tiết & Key
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>
