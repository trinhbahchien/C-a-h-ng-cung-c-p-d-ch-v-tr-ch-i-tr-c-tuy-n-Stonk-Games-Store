<div class="page-header">
    <div>
        <h1 class="page-title">Chi Tiết Đơn Hàng #<?= $order['id'] ?></h1>
        <div class="page-subtitle">Thời gian đặt hàng: <?= date('d/m/Y H:i:s', strtotime($order['created_at'])) ?></div>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/admin/orders" class="btn-admin btn-admin-secondary">
            Quay Lại Danh Sách Đơn
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px;">
    
    <!-- Cột trái: Thông tin khách hàng & Trạng thái -->
    <div>
        <div class="admin-card">
            <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                Thông Tin Khách Hàng
            </h3>

            <div style="margin-bottom: 15px;">
                <div style="font-size: 11px; color: var(--admin-text-muted); text-transform: uppercase; font-weight: 700;">Họ và Tên</div>
                <div style="font-size: 15px; font-weight: 700; color: #fff; margin-top: 3px;">
                    <?= htmlspecialchars($order['customer_name']) ?>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <div style="font-size: 11px; color: var(--admin-text-muted); text-transform: uppercase; font-weight: 700;">Email Nhận Mã Key</div>
                <div style="font-size: 14px; color: var(--admin-primary); margin-top: 3px; font-weight: 600;">
                    <?= htmlspecialchars($order['customer_email']) ?>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <div style="font-size: 11px; color: var(--admin-text-muted); text-transform: uppercase; font-weight: 700;">Tài Khoản Hệ Thống</div>
                <div style="font-size: 14px; color: #c9d1d9; margin-top: 3px;">
                    <?= !empty($order['username']) ? htmlspecialchars($order['username']) : 'Khách Vãng Lai (Guest)' ?>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="font-size: 11px; color: var(--admin-text-muted); text-transform: uppercase; font-weight: 700;">Phương Thức Thanh Toán</div>
                <div style="font-size: 14px; font-weight: 700; color: var(--admin-secondary); margin-top: 3px;">
                    <?= htmlspecialchars($order['payment_method']) ?> (Chuyển Khoản Tự Động)
                </div>
            </div>

            <div style="margin-bottom: 25px; padding-top: 15px; border-top: 1px solid var(--admin-border);">
                <div style="font-size: 11px; color: var(--admin-text-muted); text-transform: uppercase; font-weight: 700;">Tổng Giá Trị Đơn Hàng</div>
                <div style="font-size: 26px; font-weight: 800; color: var(--admin-success); margin-top: 4px;">
                    <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                </div>
            </div>

            <!-- Form Cập nhật trạng thái đơn hàng -->
            <form action="<?= BASE_URL ?>/admin/orders/status" method="POST" style="border-top: 1px solid var(--admin-border); padding-top: 20px;">
                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                <div class="form-group">
                    <label class="form-label">Cập Nhật Trạng Thái Đơn Hàng</label>
                    <select name="status" class="form-control" style="margin-bottom: 12px;">
                        <option value="COMPLETED" <?= ($order['status'] === 'COMPLETED') ? 'selected' : '' ?>>
                            HOÀN TẤT (COMPLETED)
                        </option>
                        <option value="PENDING" <?= ($order['status'] === 'PENDING') ? 'selected' : '' ?>>
                            CHỜ XỬ LÝ (PENDING)
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn-admin btn-admin-primary" style="width: 100%;">
                    Lưu Trạng Thái
                </button>
            </form>
        </div>
    </div>

    <!-- Cột phải: Danh sách Game & Mã CD-Key đã cấp -->
    <div>
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid var(--admin-border);">
                <h3 style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin: 0;">
                    Danh Sách Game Và Mã CD-Key Bản Quyền
                </h3>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Ảnh Bìa</th>
                            <th>Tựa Game</th>
                            <th>Giá Mua</th>
                            <th>Mã CD-Key Bản Quyền</th>
                            <th style="text-align: right; width: 110px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($order['items'])): ?>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <img src="<?= get_game_cover_url($item['cover_image']) ?>" 
                                             alt="<?= htmlspecialchars($item['game_title']) ?>"
                                             style="width: 45px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);"
                                             onerror="this.src='<?= ASSETS_URL ?>/uploads/games/default_game.jpg'">
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff; font-size: 14px;">
                                            <?= htmlspecialchars($item['game_title']) ?>
                                        </div>
                                        <div style="font-size: 11px; color: var(--admin-text-muted);">
                                            Mã sản phẩm: #<?= $item['id'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">
                                            <?= number_format($item['price'], 0, ',', '.') ?> đ
                                        </div>
                                    </td>
                                    <td>
                                        <div style="background: rgba(0, 242, 254, 0.08); border: 1px dashed rgba(0, 242, 254, 0.4); padding: 8px 12px; border-radius: 6px; display: inline-block;">
                                            <code id="key_<?= $item['id'] ?>" style="font-family: 'Consolas', 'Courier New', monospace; font-size: 13px; font-weight: 700; color: var(--admin-primary); letter-spacing: 1px;">
                                                <?= htmlspecialchars($item['cd_key']) ?>
                                            </code>
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" 
                                                class="btn-admin btn-admin-secondary btn-admin-sm"
                                                onclick="copyKey('<?= htmlspecialchars($item['cd_key']) ?>', this)">
                                            Sao Chép
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 25px; color: var(--admin-text-muted);">
                                    Không có sản phẩm nào trong đơn hàng này.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
function copyKey(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        var originalText = btn.innerText;
        btn.innerText = 'Đã Chép!';
        btn.style.color = 'var(--admin-primary)';
        setTimeout(function() {
            btn.innerText = originalText;
            btn.style.color = '';
        }, 2000);
    });
}
</script>
