<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_manage_orders') ?></h1>
        <div class="page-subtitle">Quản lý giao dịch bản quyền, kiểm tra thanh toán và tra cứu mã CD-Key</div>
    </div>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Email Nhận Key</th>
                    <th>Số Sản Phẩm</th>
                    <th>Tổng Tiền</th>
                    <th>Phương Thức</th>
                    <th>Trạng Thái</th>
                    <th>Thời Gian</th>
                    <th style="text-align: right; width: 150px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/orders/detail?id=<?= $o['id'] ?>" 
                                   style="font-weight: 800; color: var(--admin-primary); font-size: 14px;">
                                    #<?= $o['id'] ?>
                                </a>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #fff;">
                                    <?= htmlspecialchars($o['customer_name']) ?>
                                </div>
                                <?php if (!empty($o['username'])): ?>
                                    <div style="font-size: 11px; color: var(--admin-primary);">
                                        Tài khoản: <?= htmlspecialchars($o['username']) ?>
                                    </div>
                                <?php else: ?>
                                    <div style="font-size: 11px; color: var(--admin-text-muted);">
                                        Khách Vãng Lai (Guest)
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: var(--admin-text-muted);">
                                    <?= htmlspecialchars($o['customer_email']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #fff;">
                                    <?= $o['item_count'] ?> game
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: var(--admin-success); font-size: 14px;">
                                    <?= number_format($o['total_amount'], 0, ',', '.') ?> đ
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 12px; font-weight: 700; color: var(--admin-secondary);">
                                    <?= htmlspecialchars($o['payment_method']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($o['status'] === 'COMPLETED'): ?>
                                    <span class="badge-status badge-active">HOÀN TẤT</span>
                                <?php else: ?>
                                    <span class="badge-status badge-hidden">CHỜ XỬ LÝ</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-size: 12px; color: var(--admin-text-muted);">
                                    <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= BASE_URL ?>/admin/orders/detail?id=<?= $o['id'] ?>" 
                                   class="btn-admin btn-admin-primary btn-admin-sm">
                                    Chi Tiết & Key
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: var(--admin-text-muted);">
                            Chưa có đơn hàng nào trong hệ thống.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
