<?php
/**
 * Stonk Games Store - Cart View
 * 
 * @var array $items Danh sach game trong gio hang
 * @var float $totalOriginal Tong tien goc
 * @var float $totalDiscounted Tong tien sau giam gia
 * @var float $totalSavings So tien tiet kiem duoc
 */
$items = $items ?? [];
$totalOriginal = $totalOriginal ?? 0;
$totalDiscounted = $totalDiscounted ?? 0;
$totalSavings = $totalSavings ?? 0;
?>

<div style="max-width: 1080px; margin: 0 auto; padding: 10px 0 40px;">
    
    <!-- Flash Notification -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="padding: 14px 20px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; background: <?= $_SESSION['flash']['type'] === 'success' ? 'rgba(0, 230, 118, 0.15)' : 'rgba(0, 242, 254, 0.15)' ?>; border: 1px solid <?= $_SESSION['flash']['type'] === 'success' ? '#00e676' : 'var(--primary)' ?>; color: <?= $_SESSION['flash']['type'] === 'success' ? '#00e676' : 'var(--primary)' ?>;">
            <i class="fa-solid <?= $_SESSION['flash']['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-info' ?>"></i>
            <span><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 800; color: var(--text-primary); margin: 0;">
                Giỏ Hàng Của Bạn
            </h1>
            <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                Quản lý các tựa game bản quyền đã chọn trước khi tiến hành thanh toán
            </p>
        </div>
        <a href="<?= BASE_URL ?>/home" class="btn-gaming btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Tiếp Tục Chọn Game
        </a>
    </div>

    <?php if (empty($items)): ?>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 60px 20px; text-align: center;">
            <div style="font-size: 54px; color: var(--text-muted); margin-bottom: 16px;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                Giỏ Hàng Đang Trống
            </h3>
            <p style="font-size: 14px; color: var(--text-secondary); max-width: 480px; margin: 0 auto 24px;">
                Bạn chưa thêm bất kỳ trò chơi nào vào giỏ hàng. Hãy khám phá kho game bản quyền phong phú tại Stonk Games Store!
            </p>
            <a href="<?= BASE_URL ?>/home" class="btn-gaming btn-primary" style="padding: 12px 28px;">
                Khám Phá Game Nổi Bật
            </a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: flex-start;">
            
            <!-- Danh Sach Game Trong Gio -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">
                        Danh Sách Trò Chơi (<?= count($items) ?> sản phẩm)
                    </h3>
                    <a href="<?= BASE_URL ?>/cart/clear" 
                       onclick="return confirm('Bạn có chắc chắn muốn dọn sạch toàn bộ giỏ hàng?');" 
                       style="font-size: 12px; color: #ff5252; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; padding: 4px 8px; border-radius: 4px; transition: background 0.2s;"
                       onmouseover="this.style.background='rgba(255, 82, 82, 0.1)'"
                       onmouseout="this.style.background='transparent'">
                        <i class="fa-solid fa-trash-can"></i> Xóa Giỏ Hàng
                    </a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <?php foreach ($items as $item): ?>
                        <?php 
                            $game = $item['game'];
                            $coverUrl = get_game_cover_url($game['cover_image']);
                        ?>
                        <div style="display: flex; gap: 18px; align-items: center; padding: 14px; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 8px;">
                            <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>"
                                 style="width: 100px; height: 60px; object-fit: cover; border-radius: 6px;"
                                 onerror="this.src='<?= MEDIA_URL ?>/img/games/elden_ring.jpg';">
                            
                            <div style="flex: 1;">
                                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px;">
                                    <?= htmlspecialchars($game['title']) ?>
                                </h4>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    <?= htmlspecialchars($game['developer_name'] ?? 'Publisher') ?> &bull; Kích hoạt mã số tự động
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <?php if ($game['price'] == 0): ?>
                                    <div style="font-size: 16px; font-weight: 800; color: var(--accent-green);">
                                        MIỄN PHÍ
                                    </div>
                                <?php else: ?>
                                    <?php if ($game['discount_percent'] > 0): ?>
                                        <div style="display: flex; align-items: center; gap: 6px; justify-content: flex-end; margin-bottom: 2px;">
                                            <span style="background: var(--accent-green); color: #000; font-size: 11px; font-weight: 800; padding: 1px 5px; border-radius: 3px;">
                                                -<?= $game['discount_percent'] ?>%
                                            </span>
                                            <span style="font-size: 12px; color: var(--text-muted); text-decoration: line-through;">
                                                <?= number_format($game['price'], 0, ',', '.') ?> đ
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <div style="font-size: 16px; font-weight: 800; color: var(--text-primary);">
                                        <?= number_format($item['final_price'], 0, ',', '.') ?> đ
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div>
                                <a href="<?= BASE_URL ?>/cart/remove?id=<?= $game['id'] ?>" 
                                   title="Xóa khỏi giỏ hàng"
                                   style="color: var(--danger); font-size: 14px; padding: 8px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: background 0.2s;"
                                   onmouseover="this.style.background='rgba(255, 82, 82, 0.15)'"
                                   onmouseout="this.style.background='transparent'">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tong Tien Va Thanh Toan -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; position: sticky; top: 90px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    Tóm Tắt Đơn Hàng
                </h3>

                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--text-secondary);">
                    <span>Giá niêm yết:</span>
                    <span><?= number_format($totalOriginal, 0, ',', '.') ?> đ</span>
                </div>

                <?php if ($totalSavings > 0): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--accent-green);">
                        <span>Khuyến mãi tiết kiệm:</span>
                        <span>-<?= number_format($totalSavings, 0, ',', '.') ?> đ</span>
                    </div>
                <?php endif; ?>

                <div style="border-top: 1px dashed var(--border-color); padding-top: 16px; margin-top: 16px; display: flex; justify-content: space-between; align-items: baseline;">
                    <span style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Tổng thanh toán:</span>
                    <span style="font-size: 24px; font-weight: 800; color: var(--primary);">
                        <?= number_format($totalDiscounted, 0, ',', '.') ?> đ
                    </span>
                </div>

                <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 10px;">
                    <a href="<?= BASE_URL ?>/cart/checkout" class="btn-gaming btn-primary" style="padding: 14px; text-align: center; font-size: 15px; font-weight: 700; justify-content: center;">
                        <i class="fa-solid fa-credit-card"></i> Tiến Hành Thanh Toán
                    </a>
                </div>

                <div style="margin-top: 20px; font-size: 12px; color: var(--text-muted); line-height: 1.5; border-top: 1px solid var(--border-color); padding-top: 14px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                        <i class="fa-solid fa-bolt" style="color: var(--accent-green);"></i>
                        <span>Cấp mã CD-Key tức thì sau khi quét VietQR</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i>
                        <span>Bảo mật 100% bản quyền kích hoạt chính hãng</span>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>
