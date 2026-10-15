<?php
/**
 * Stonk Games Store - Order Not Found View
 */
?>
<div style="max-width: 600px; margin: 60px auto; padding: 40px 30px; text-align: center; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
    <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(255, 82, 82, 0.15); color: #ff5252; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
        <i class="fa-solid fa-magnifying-glass"></i>
    </div>
    
    <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0 0 10px;">
        Không Tìm Thấy Đơn Hàng
    </h2>
    
    <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6; margin: 0 0 25px;">
        Mã đơn hàng <strong>#<?= (int)($orderId ?? 0) ?></strong> không tồn tại trong hệ thống hoặc bạn không có quyền truy cập vào thông tin đơn hàng này.
    </p>

    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/order/history" class="btn-gaming btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Về Lịch Sử Mua Hàng
        </a>
        <a href="<?= BASE_URL ?>/catalog" class="btn-gaming btn-primary">
            <i class="fa-solid fa-gamepad"></i> Khám Phá Kho Game
        </a>
    </div>
</div>
