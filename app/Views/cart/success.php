<?php
/**
 * Stonk Games Store - Order Success & CD-Key Activation View
 * Hiển thị hóa đơn điện tử, danh sách mã CD-Key bản quyền và hướng dẫn kích hoạt
 * 
 * @var array $order Dữ liệu đơn hàng chi tiết và danh sách vật phẩm CD-Key
 */
$order = $order ?? [];
$items = $order['items'] ?? [];
?>

<div style="max-width: 1000px; margin: 0 auto; padding: 10px 0 50px;">

    <!-- Thanh tiến trình đơn hàng (Step Tracker - Bước 3 Hoàn tất) -->
    <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 35px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px; color: var(--accent-green); font-size: 13px; font-weight: 700;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(0, 230, 118, 0.2); border: 1px solid var(--accent-green); display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-check" style="font-size: 11px;"></i>
            </span>
            <span>1. Giỏ Hàng</span>
        </div>
        <div style="width: 40px; height: 2px; background: var(--accent-green);"></div>
        <div style="display: flex; align-items: center; gap: 8px; color: var(--accent-green); font-size: 13px; font-weight: 700;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(0, 230, 118, 0.2); border: 1px solid var(--accent-green); display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-check" style="font-size: 11px;"></i>
            </span>
            <span>2. Thanh Toán & VietQR</span>
        </div>
        <div style="width: 40px; height: 2px; background: var(--accent-green);"></div>
        <div style="display: flex; align-items: center; gap: 8px; color: var(--accent-green); font-size: 13px; font-weight: 800;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(0, 230, 118, 0.25); border: 2px solid var(--accent-green); display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-key" style="font-size: 11px;"></i>
            </span>
            <span>3. Nhận Mã CD-Key & Kích Hoạt</span>
        </div>
    </div>

    <!-- Banner Chúc Mừng & Thông Báo Thành Công -->
    <div style="background: linear-gradient(135deg, rgba(0, 230, 118, 0.12) 0%, rgba(0, 242, 254, 0.08) 100%); border: 1px solid var(--accent-green); border-radius: 16px; padding: 35px 25px; text-align: center; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0, 230, 118, 0.1);">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(0, 230, 118, 0.2); border: 2px solid var(--accent-green); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--accent-green); font-size: 36px; box-shadow: 0 0 25px rgba(0, 230, 118, 0.4);">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h1 style="font-size: 26px; font-weight: 800; color: var(--text-primary); margin: 0 0 8px;">
            Giao Dịch Thành Công &bull; Cấp Giấy Phép Bản Quyền!
        </h1>
        <p style="font-size: 15px; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
            Hệ thống Stonk Games Store đã ghi nhận thanh toán và khởi tạo mã <strong>CD-Key</strong> bản quyền toàn cầu cho tài khoản của bạn.
        </p>
    </div>

    <!-- Khối Thông Tin Hóa Đơn Tóm Tắt -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap; gap: 10px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-receipt" style="color: var(--primary);"></i>
                Thông Tin Hóa Đơn #<?= $order['id'] ?>
            </h3>
            <span style="font-size: 12px; padding: 4px 12px; border-radius: 20px; background: rgba(0, 230, 118, 0.15); color: var(--accent-green); font-weight: 800;">
                <i class="fa-solid fa-circle-check"></i> <?= $order['status'] === 'COMPLETED' ? 'ĐÃ HOÀN TẤT & ĐÃ CẤP KEY' : 'ĐANG XỬ LÝ' ?>
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; font-size: 13px;">
            <div>
                <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Họ và Tên Khách Hàng</div>
                <div style="font-weight: 700; color: var(--text-primary); margin-top: 3px; font-size: 14px;">
                    <?= htmlspecialchars($order['customer_name']) ?>
                </div>
            </div>

            <div>
                <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Email Nhận Key</div>
                <div style="font-weight: 700; color: var(--primary); margin-top: 3px; font-size: 14px;">
                    <?= htmlspecialchars($order['customer_email']) ?>
                </div>
            </div>

            <div>
                <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Thời Gian Đặt Hàng</div>
                <div style="font-weight: 600; color: var(--text-secondary); margin-top: 3px;">
                    <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                </div>
            </div>

            <div>
                <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Phương Thức</div>
                <div style="font-weight: 600; color: var(--text-secondary); margin-top: 3px;">
                    <?= htmlspecialchars($order['payment_method'] ?? 'VIETQR') ?>
                </div>
            </div>

            <div>
                <div style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Tổng Thanh Toán</div>
                <div style="font-weight: 800; color: var(--accent-green); margin-top: 3px; font-size: 16px;">
                    <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                </div>
            </div>
        </div>
    </div>

    <!-- KHỐI MÃ BẢN QUYỀN CD-KEY CHO TỪNG GAME (TRỌNG TÂM GIAI ĐOẠN 6) -->
    <div style="margin-bottom: 35px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-key" style="color: var(--primary);"></i>
                    Danh Sách Giấy Phép Bản Quyền (<?= count($items) ?> Game)
                </h2>
                <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                    Sao chép mã CD-Key dưới đây và kích hoạt ngay trên ứng dụng Steam hoặc Epic Games Launcher
                </p>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 18px;">
            <?php foreach ($items as $idx => $item): ?>
                <?php 
                    $coverUrl = get_game_cover_url($item['cover_image']);
                ?>
                <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 22px; display: flex; gap: 22px; align-items: center; flex-wrap: wrap;">
                    
                    <!-- Ảnh game -->
                    <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($item['game_title']) ?>"
                         style="width: 120px; height: 75px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);"
                         onerror="this.src='<?= MEDIA_URL ?>/img/games/elden_ring.jpg';">

                    <!-- Thông tin game -->
                    <div style="flex: 1; min-width: 200px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="font-size: 11px; font-weight: 800; background: rgba(0, 242, 254, 0.15); color: var(--primary); padding: 2px 7px; border-radius: 4px; border: 1px solid rgba(0, 242, 254, 0.3);">
                                <i class="fa-brands fa-steam"></i> STEAM / EPIC GLOBAL
                            </span>
                            <span style="font-size: 12px; color: var(--accent-green); font-weight: 700;">
                                <i class="fa-solid fa-shield-halved"></i> Chính Hãng 100%
                            </span>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px;">
                            <?= htmlspecialchars($item['game_title']) ?>
                        </h3>
                        <div style="font-size: 13px; color: var(--text-muted);">
                            Giá kích hoạt: <strong style="color: var(--text-primary);"><?= number_format($item['price'], 0, ',', '.') ?> đ</strong>
                        </div>
                    </div>

                    <!-- Khung Hiển Thị CD-Key & Nút Sao Chép -->
                    <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end; min-width: 440px; flex-shrink: 0;">
                        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
                            Mã Kích Hoạt CD-Key:
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 8px; width: 100%; justify-content: flex-end;">
                            <input type="text" readonly value="<?= htmlspecialchars($item['cd_key']) ?>" id="key_<?= $idx ?>"
                                   class="cdkey-input-box"
                                   onclick="this.select();">
                            
                            <button type="button" onclick="copyCdKey('<?= htmlspecialchars($item['cd_key']) ?>', this)" 
                                    class="btn-gaming btn-primary" style="padding: 10px 16px; font-size: 13px; font-weight: 700; white-space: nowrap;">
                                <i class="fa-regular fa-copy"></i> Sao Chép
                            </button>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 4px;">
                            <a href="steam://open/activateproduct" class="btn-gaming btn-outline" style="padding: 5px 12px; font-size: 11.5px;">
                                <i class="fa-brands fa-steam"></i> Mở Nhanh Steam PC
                            </a>
                            <a href="<?= BASE_URL ?>/game/detail?id=<?= $item['game_id'] ?>" class="btn-gaming btn-outline" style="padding: 5px 12px; font-size: 11.5px;">
                                <i class="fa-solid fa-circle-info"></i> Xem Trang Game
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Hướng Dẫn Kích Hoạt Bản Quyền 3 Bước Chuẩn -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 28px; margin-bottom: 30px;">
        <h3 style="font-size: 16px; font-weight: 800; color: var(--text-primary); margin: 0 0 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
            <i class="fa-solid fa-circle-question" style="color: var(--primary);"></i>
            Hướng Dẫn Kích Hoạt Game Trên Máy Tính (3 Bước Đơn Giản)
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: #0b0e14; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 13px;">1</span>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0;">Mở Ứng Dụng Steam</h4>
                </div>
                <p style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                    Khởi động phần mềm <strong>Steam</strong> trên máy tính của bạn và đăng nhập vào tài khoản cá nhân. (Nếu chưa có, tải miễn phí tại store.steampowered.com).
                </p>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: #0b0e14; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 13px;">2</span>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0;">Chọn Kích Hoạt Key</h4>
                </div>
                <p style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                    Ở góc dưới cùng bên trái màn hình Steam, nhấn <strong>"Thêm trò chơi" (+ Add a Game)</strong> &rarr; Chọn <strong>"Kích hoạt sản phẩm trên Steam..."</strong>.
                </p>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--accent-green); color: #0b0e14; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 13px;">3</span>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0;">Dán Mã & Tải Game</h4>
                </div>
                <p style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                    Dán mã <strong>CD-Key</strong> đã sao chép ở trên vào ô nhập, chọn <em>Tiếp tục</em>. Game sẽ nằm vĩnh viễn trong <strong>Thư viện (Library)</strong> để tải về chơi!
                </p>
            </div>
        </div>
    </div>

    <!-- Thanh Điều Hướng Dưới Cùng -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; gap: 12px;">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>/profile" class="btn-gaming btn-primary" style="padding: 12px 22px;">
                    <i class="fa-solid fa-gamepad"></i> Xem Tủ Game Cá Nhân
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/catalog" class="btn-gaming btn-outline" style="padding: 12px 22px;">
                <i class="fa-solid fa-store"></i> Tiếp Tục Mua Sắm Game
            </a>
        </div>

        <button type="button" onclick="window.print()" class="btn-gaming btn-outline" style="padding: 12px 20px;">
            <i class="fa-solid fa-print"></i> In Hóa Đơn Điện Tử
        </button>
    </div>

</div>

<style>
.cdkey-input-box {
    width: 320px;
    box-sizing: border-box;
    padding: 10px 14px;
    background: #090d16;
    border: 1px solid var(--primary);
    border-radius: 6px;
    color: #00f2fe;
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-align: center;
    outline: none;
    box-shadow: 0 0 12px rgba(0, 242, 254, 0.25);
    transition: all 0.2s ease;
}

html.light-mode .cdkey-input-box {
    background: #f8fafc;
    border-color: #0284c7;
    color: #0369a1;
    box-shadow: 0 0 8px rgba(2, 132, 199, 0.15);
}
</style>

<!-- Toast Feedback Sao Chép Mã Key -->
<div id="copyToast" style="position: fixed; bottom: 30px; right: 30px; background: #0b0e14; border: 1px solid var(--primary); box-shadow: 0 8px 30px rgba(0, 242, 254, 0.4); color: #ffffff; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 10px; z-index: 9999; transform: translateY(100px); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); pointer-events: none;">
    <i class="fa-solid fa-circle-check" style="color: var(--accent-green); font-size: 18px;"></i>
    <span id="copyToastText">Đã sao chép mã CD-Key vào bộ nhớ tạm!</span>
</div>

<script>
function copyCdKey(key, btnElement) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(key).then(() => showToast(key, btnElement));
    } else {
        const textArea = document.createElement("textarea");
        textArea.value = key;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            showToast(key, btnElement);
        } catch (err) {
            console.error('Không thể sao chép: ', err);
        }
        document.body.removeChild(textArea);
    }
}

function showToast(key, btnElement) {
    const toast = document.getElementById('copyToast');
    const toastText = document.getElementById('copyToastText');
    toastText.innerText = 'Đã sao chép CD-Key: ' + key;
    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';

    if (btnElement) {
        const originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fa-solid fa-check"></i> Đã Chép!';
        setTimeout(() => {
            btnElement.innerHTML = originalHtml;
        }, 2000);
    }

    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        toast.style.opacity = '0';
    }, 2500);
}
</script>
