<?php
/**
 * Stonk Games Store - Order Detail & CD-Key Activation View
 * 
 * @var array $order
 * @var array $items
 * @var bool $isAuthorized
 */
?>
<div class="order-detail-container" style="max-width: 1050px; margin: 0 auto; padding: 10px 0 50px;">

    <!-- Breadcrumb & Navigation -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted);">
            <a href="<?= BASE_URL ?>/" style="color: var(--text-secondary);"><i class="fa-solid fa-house"></i> Trang Chủ</a>
            <span style="opacity: 0.5;"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
            <a href="<?= BASE_URL ?>/order/history" style="color: var(--text-secondary);">Lịch Sử Mua Hàng</a>
            <span style="opacity: 0.5;"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
            <span style="color: var(--primary); font-weight: 600;">Đơn Hàng #<?= $order['id'] ?></span>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= BASE_URL ?>/order/history" class="btn-gaming btn-outline" style="padding: 8px 16px; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Danh Sách Đơn
            </a>
            <a href="<?= BASE_URL ?>/order/invoice/<?= $order['id'] ?>" target="_blank" class="btn-gaming btn-primary" style="padding: 8px 18px; font-size: 13px;">
                <i class="fa-solid fa-print"></i> Xem & In Hóa Đơn
            </a>
        </div>
    </div>

    <!-- Hero Card Đơn Hàng -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 26px 30px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.25);">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 58px; height: 58px; border-radius: 12px; background: linear-gradient(135deg, rgba(0, 242, 254, 0.15) 0%, rgba(79, 172, 254, 0.1) 100%); border: 1px solid var(--primary); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <h1 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0 0 6px;">
                    Chi Tiết Đơn Hàng #<?= $order['id'] ?>
                </h1>
                <div style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <span><i class="fa-regular fa-clock"></i> Khởi tạo: <?= date('d/m/Y - H:i:s', strtotime($order['created_at'])) ?></span>
                    <span>&bull;</span>
                    <span><i class="fa-regular fa-user"></i> Người mua: <strong style="color: var(--text-primary);"><?= htmlspecialchars($order['customer_name']) ?></strong></span>
                </div>
            </div>
        </div>

        <div>
            <?php if ($order['status'] === 'COMPLETED'): ?>
                <span style="padding: 8px 18px; border-radius: 30px; font-size: 13px; font-weight: 800; background: rgba(0, 230, 118, 0.15); color: var(--accent-green); border: 1px solid var(--accent-green); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-check"></i> ĐÃ HOÀN TẤT & ĐÃ CẤP KEY
                </span>
            <?php else: ?>
                <span style="padding: 8px 18px; border-radius: 30px; font-size: 13px; font-weight: 800; background: rgba(255, 171, 0, 0.15); color: #ffab00; border: 1px solid #ffab00; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-clock"></i> CHỜ XỬ LÝ THANH TOÁN
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stepper Tiến Trình 3 Bước -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 25px 35px; margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; position: relative;">
        <!-- Bước 1 -->
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; z-index: 2; min-width: 110px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--primary); color: #0b0e14; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin-bottom: 8px; box-shadow: 0 0 16px var(--primary-glow);">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">1. Chọn Game</span>
            <span style="font-size: 11px; color: var(--text-muted);"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
        </div>

        <div style="flex: 1; height: 3px; background: linear-gradient(90deg, var(--primary), var(--accent-green)); margin: 0 -10px 25px -10px; z-index: 1;"></div>

        <!-- Bước 2 -->
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; z-index: 2; min-width: 110px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--accent-green); color: #0b0e14; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin-bottom: 8px; box-shadow: 0 0 16px rgba(0, 230, 118, 0.4);">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">2. Thanh Toán</span>
            <span style="font-size: 11px; color: var(--text-muted);"><?= htmlspecialchars($order['payment_method'] ?? 'VIETQR') ?></span>
        </div>

        <div style="flex: 1; height: 3px; background: <?= $order['status'] === 'COMPLETED' ? 'var(--accent-green)' : 'var(--border-color)' ?>; margin: 0 -10px 25px -10px; z-index: 1;"></div>

        <!-- Bước 3 -->
        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; z-index: 2; min-width: 110px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: <?= $order['status'] === 'COMPLETED' ? 'var(--accent-green)' : 'rgba(255, 255, 255, 0.08)' ?>; color: <?= $order['status'] === 'COMPLETED' ? '#0b0e14' : 'var(--text-muted)' ?>; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; margin-bottom: 8px; border: 2px solid <?= $order['status'] === 'COMPLETED' ? 'var(--accent-green)' : 'var(--border-color)' ?>;">
                <i class="fa-solid fa-key"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700; color: <?= $order['status'] === 'COMPLETED' ? 'var(--accent-green)' : 'var(--text-muted)' ?>;">
                3. Nhận Mã CD-Key
            </span>
            <span style="font-size: 11px; color: var(--text-muted);"><?= $order['status'] === 'COMPLETED' ? 'Đã kích hoạt' : 'Đang xử lý' ?></span>
        </div>
    </div>

    <!-- Grid Nội Dung 2 Cột: Danh sách Game & Key (Trái) + Thông Tin & Hóa Đơn (Phải) -->
    <div style="display: grid; grid-template-columns: 1.7fr 1fr; gap: 25px; align-items: flex-start;">
        
        <!-- CỘT TRÁI: DANH SÁCH GAME ĐÃ MUA & MÃ BẢN QUYỀN CD-KEY -->
        <div>
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-cubes" style="color: var(--primary);"></i>
                        Danh Sách Game & Giấy Phép Bản Quyền (<?= count($items) ?>)
                    </h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php foreach ($items as $idx => $item): ?>
                        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 18px; display: flex; flex-direction: column; gap: 14px;">
                            <div style="display: flex; gap: 16px; align-items: center;">
                                <div style="width: 80px; height: 80px; border-radius: 8px; overflow: hidden; background: var(--bg-surface); border: 1px solid var(--border-color); flex-shrink: 0;">
                                    <img src="<?= get_game_cover_url($item['cover_image'] ?? '') ?>" 
                                         alt="<?= htmlspecialchars($item['game_title']) ?>" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0 0 6px;">
                                        <a href="<?= BASE_URL ?>/games/detail?slug=<?= $item['game_slug'] ?? '' ?>" style="color: inherit; text-decoration: none;">
                                            <?= htmlspecialchars($item['game_title']) ?>
                                        </a>
                                    </h4>
                                    <div style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 10px;">
                                        <span>Bản Quyền Toàn Cầu (Global License)</span>
                                        <span>&bull;</span>
                                        <strong style="color: var(--accent-green); font-size: 15px;">
                                            <?= number_format($item['price'], 0, ',', '.') ?> đ
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Khối Hiển Thị Mã Bản Quyền CD-Key Gaming -->
                            <div style="background: rgba(0, 0, 0, 0.4); border: 1px dashed var(--primary); border-radius: 8px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fa-solid fa-key" style="color: var(--primary); font-size: 15px;"></i>
                                    <div>
                                        <div style="font-size: 10px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">Mã Giấy Phép Kích Hoạt (CD-Key)</div>
                                        <div id="cdkey-<?= $item['id'] ?>" style="font-family: 'Courier New', Courier, monospace; font-size: 15px; font-weight: 800; color: #00f2fe; letter-spacing: 1px; margin-top: 2px;">
                                            <?= htmlspecialchars($item['cd_key']) ?>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" onclick="copyCdKey('<?= htmlspecialchars($item['cd_key']) ?>', this)" 
                                        class="btn-gaming btn-outline" style="padding: 6px 14px; font-size: 12px; border-color: var(--primary); color: var(--primary);">
                                    <i class="fa-regular fa-copy"></i> Sao Chép Key
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tổng kết tài chính -->
                <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 10px;">
                    <div style="display: flex; justify-content: space-between; font-size: 14px; color: var(--text-secondary);">
                        <span>Tạm tính tiền game:</span>
                        <span><?= number_format($order['total_amount'], 0, ',', '.') ?> đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 14px; color: var(--text-secondary);">
                        <span>Phí kích hoạt & cấp key bản quyền:</span>
                        <span style="color: var(--accent-green); font-weight: 600;">Miễn phí 100%</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: var(--text-primary); border-top: 1px dashed var(--border-color); padding-top: 14px; margin-top: 5px;">
                        <span>Tổng cộng thanh toán:</span>
                        <span style="color: var(--accent-green); font-size: 20px;">
                            <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                        </span>
                    </div>
                </div>
            </div>

            <!-- Hướng Dẫn Kích Hoạt Nhanh -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 25px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0 0 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i>
                    Hướng Dẫn Kích Hoạt Mã CD-Key Trên Các Nền Tảng
                </h3>
                <ol style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--text-secondary); line-height: 1.8;">
                    <li>Mở ứng dụng <strong>Steam</strong>, <strong>Epic Games Launcher</strong> hoặc <strong>EA App</strong> trên máy tính của bạn.</li>
                    <li>Vào mục <strong>Games / Trò chơi</strong> &rarr; Chọn <strong>Activate a Product / Kích hoạt sản phẩm trên Steam</strong>.</li>
                    <li>Dán mã <strong>CD-Key</strong> đã sao chép ở trên vào ô xác nhận và nhấn Tiếp tục.</li>
                    <li>Sau khi kích hoạt thành công, tựa game sẽ vĩnh viễn thuộc về thư viện tài khoản của bạn!</li>
                </ol>
            </div>
        </div>

        <!-- CỘT PHẢI: THÔNG TIN KHÁCH HÀNG & PHƯƠNG THỨC THANH TOÁN -->
        <div>
            <!-- Thông tin khách hàng -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; margin-bottom: 25px; box-shadow: 0 4px 16px rgba(0,0,0,0.2);">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0 0 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-address-card" style="color: var(--primary);"></i>
                    Thông Tin Người Nhận
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Họ và Tên:</div>
                        <div style="color: var(--text-primary); font-weight: 700; margin-top: 2px;">
                            <?= htmlspecialchars($order['customer_name']) ?>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Email Nhận Key:</div>
                        <div style="color: var(--primary); font-weight: 700; margin-top: 2px;">
                            <?= htmlspecialchars($order['customer_email']) ?>
                        </div>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Mã Khách Hàng:</div>
                        <div style="color: var(--text-secondary); margin-top: 2px;">
                            <?= !empty($order['user_id']) ? "Thành viên #{$order['user_id']}" : "Khách vãng lai (Guest)" ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phương thức thanh toán -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px; margin-bottom: 25px;">
                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0 0 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-credit-card" style="color: var(--primary);"></i>
                    Phương Thức Thanh Toán
                </h3>
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-weight: 700; color: var(--text-primary); font-size: 13px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-qrcode" style="color: var(--primary);"></i>
                            <?= htmlspecialchars($order['payment_method'] ?? 'VIETQR') ?>
                        </span>
                        <span style="font-size: 11px; padding: 3px 8px; border-radius: 12px; background: rgba(0, 230, 118, 0.15); color: var(--accent-green); font-weight: 700;">
                            ĐÃ THANH TOÁN
                        </span>
                    </div>
                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
                        Giao dịch số đã được xác thực an toàn qua cổng chuyển khoản tự động.
                    </p>
                </div>
            </div>

            <!-- Thẻ Hỗ Trợ -->
            <div style="background: linear-gradient(135deg, rgba(0, 242, 254, 0.05) 0%, rgba(79, 172, 254, 0.05) 100%); border: 1px solid rgba(0, 242, 254, 0.2); border-radius: 14px; padding: 22px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <i class="fa-solid fa-headset" style="font-size: 24px; color: var(--primary);"></i>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0;">Hỗ Trợ Kỹ Thuật 24/7</h4>
                        <span style="font-size: 12px; color: var(--text-muted);">Hotline: 1900 8899</span>
                    </div>
                </div>
                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
                    Nếu gặp khó khăn khi kích hoạt key, vui lòng gửi email về <strong>support@stonkgames.vn</strong> kèm mã đơn <strong>#<?= $order['id'] ?></strong>.
                </p>
            </div>
        </div>

    </div>
</div>

<script>
function copyCdKey(key, btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(key).then(function() {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Đã Chép!';
            btn.style.background = 'var(--accent-green)';
            btn.style.borderColor = 'var(--accent-green)';
            btn.style.color = '#0b0e14';
            setTimeout(function() {
                btn.innerHTML = originalHtml;
                btn.style.background = 'transparent';
                btn.style.borderColor = 'var(--primary)';
                btn.style.color = 'var(--primary)';
            }, 2000);
        });
    } else {
        alert("Mã Key của bạn: " + key);
    }
}
</script>
