<?php
/**
 * Stonk Games Store - Checkout View
 * Cổng thanh toán hóa đơn bản quyền game số & tạo mã VietQR Napas247 tự động
 * 
 * @var array $items Danh sách trò chơi trong đơn hàng
 * @var float $totalOriginal Giá gốc
 * @var float $totalDiscounted Tổng tiền cần thanh toán
 * @var float $totalSavings Số tiền tiết kiệm
 * @var string $transferCode Mã nội dung chuyển khoản duy nhất
 * @var array|null $user Thông tin người dùng đăng nhập
 */
$items = $items ?? [];
$totalOriginal = $totalOriginal ?? 0;
$totalDiscounted = $totalDiscounted ?? 0;
$totalSavings = $totalSavings ?? 0;
$transferCode = $transferCode ?? ('STONK' . rand(10000, 99999));
$user = $user ?? null;

$qrAmount = (int)round($totalDiscounted);
$vietQrUrl = "https://img.vietqr.io/image/MB-0988888888-compact2.png?amount={$qrAmount}&addInfo=" . urlencode($transferCode) . "&accountName=STONK+GAMES+STORE";
?>

<div style="max-width: 1140px; margin: 0 auto; padding: 10px 0 50px;">

    <!-- Flash Alert -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="padding: 14px 20px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; background: <?= $_SESSION['flash']['type'] === 'danger' ? 'rgba(255, 82, 82, 0.15)' : 'rgba(0, 242, 254, 0.15)' ?>; border: 1px solid <?= $_SESSION['flash']['type'] === 'danger' ? 'var(--danger)' : 'var(--primary)' ?>; color: <?= $_SESSION['flash']['type'] === 'danger' ? 'var(--danger)' : 'var(--primary)' ?>;">
            <i class="fa-solid <?= $_SESSION['flash']['type'] === 'danger' ? 'fa-triangle-exclamation' : 'fa-circle-info' ?>"></i>
            <span><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Thanh tiến trình đơn hàng (Step Tracker) -->
    <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 35px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px; color: var(--accent-green); font-size: 13px; font-weight: 700;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(0, 230, 118, 0.2); border: 1px solid var(--accent-green); display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-check" style="font-size: 11px;"></i>
            </span>
            <span>1. Giỏ Hàng</span>
        </div>
        <div style="width: 40px; height: 2px; background: var(--accent-green);"></div>
        <div style="display: flex; align-items: center; gap: 8px; color: var(--primary); font-size: 13px; font-weight: 700;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(0, 242, 254, 0.2); border: 1px solid var(--primary); display: flex; align-items: center; justify-content: center;">
                2
            </span>
            <span>2. Thanh Toán & VietQR</span>
        </div>
        <div style="width: 40px; height: 2px; background: var(--border-color);"></div>
        <div style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 13px; font-weight: 600;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: var(--bg-card); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center;">
                3
            </span>
            <span>3. Nhận Mã CD-Key</span>
        </div>
    </div>

    <!-- Tiêu đề trang -->
    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 26px; font-weight: 800; color: var(--text-primary); margin: 0 0 6px;">
            Thanh Toán Bản Quyền Trò Chơi
        </h1>
        <p style="font-size: 14px; color: var(--text-muted); margin: 0;">
            Hoàn tất chuyển khoản để hệ thống tự động khởi tạo và cấp phát mã kích hoạt CD-Key tức thì
        </p>
    </div>

    <form action="<?= BASE_URL ?>/cart/process-checkout" method="POST" id="checkoutForm">
        <div style="display: grid; grid-template-columns: 1.35fr 1fr; gap: 30px; align-items: flex-start;">
            
            <!-- CỘT TRÁI: THÔNG TIN KHÁCH HÀNG & CỔNG VIETQR -->
            <div style="display: flex; flex-direction: column; gap: 25px;">

                <!-- Khối 1: Thông tin người nhận bản quyền -->
                <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-user-check" style="color: var(--primary);"></i>
                            Thông Tin Người Nhận Game
                        </h3>
                        <?php if ($user): ?>
                            <span style="font-size: 12px; padding: 3px 10px; border-radius: 20px; background: rgba(0, 230, 118, 0.15); color: var(--accent-green); font-weight: 700;">
                                <i class="fa-solid fa-circle-check"></i> Đã đăng nhập
                            </span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/auth/login" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600;">
                                Đăng nhập tài khoản &rarr;
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if ($user): ?>
                        <div style="margin-bottom: 16px; padding: 12px 14px; background: rgba(0, 242, 254, 0.06); border: 1px solid rgba(0, 242, 254, 0.2); border-radius: 8px; font-size: 13px; color: var(--text-secondary);">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary); margin-right: 6px;"></i>
                            Đơn hàng và mã CD-Key sẽ được tự động lưu vĩnh viễn vào <strong>Tủ Game Cá Nhân (Profile)</strong> của tài khoản <strong><?= htmlspecialchars($user['username']) ?></strong>.
                        </div>
                    <?php else: ?>
                        <div style="margin-bottom: 16px; padding: 12px 14px; background: rgba(255, 171, 0, 0.08); border: 1px solid rgba(255, 171, 0, 0.25); border-radius: 8px; font-size: 13px; color: var(--text-secondary);">
                            <i class="fa-solid fa-bolt" style="color: #ffab00; margin-right: 6px;"></i>
                            Bạn đang mua hàng ở chế độ <strong>Khách vãng lai</strong>. Mã CD-Key sẽ được hiển thị ngay trên màn hình và gửi về Email bên dưới!
                        </div>
                    <?php endif; ?>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-transform: uppercase;">
                                Họ và Tên <span style="color: #ff5252;">*</span>
                            </label>
                            <input type="text" name="customer_name" required
                                   value="<?= htmlspecialchars($user['fullname'] ?? $user['username'] ?? '') ?>"
                                   placeholder="Ví dụ: Nguyễn Văn An"
                                   style="width: 100%; box-sizing: border-box; padding: 11px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 14px; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; text-transform: uppercase;">
                                Email Nhận Key <span style="color: #ff5252;">*</span>
                            </label>
                            <input type="email" name="customer_email" required
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                   placeholder="gamer@gmail.com"
                                   style="width: 100%; box-sizing: border-box; padding: 11px 14px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 14px; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                    </div>
                </div>

                <!-- Khối 2: Phương thức thanh toán & Chi tiết VietQR -->
                <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0 0 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-qrcode" style="color: var(--primary);"></i>
                        Phương Thức Thanh Toán Tự Động
                    </h3>

                    <!-- Các Tab lựa chọn phương thức -->
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 22px;">
                        <label style="border: 2px solid var(--primary); background: rgba(0, 242, 254, 0.08); border-radius: 8px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 6px; position: relative;">
                            <input type="radio" name="payment_method" value="VIETQR" checked style="position: absolute; opacity: 0;">
                            <i class="fa-solid fa-qrcode" style="font-size: 20px; color: var(--primary);"></i>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">VietQR Napas247</span>
                            <span style="font-size: 10px; font-weight: 800; background: var(--accent-green); color: #000; padding: 1px 6px; border-radius: 10px;">Khuyên Dùng</span>
                        </label>

                        <label style="border: 1px solid var(--border-color); background: rgba(255, 255, 255, 0.03); border-radius: 8px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 6px; opacity: 0.85;"
                               onclick="document.getElementById('qrSection').scrollIntoView({behavior: 'smooth'})">
                            <input type="radio" name="payment_method" value="ATM_VISA" style="position: absolute; opacity: 0;">
                            <i class="fa-brands fa-cc-visa" style="font-size: 20px; color: #4facfe;"></i>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">ATM / Visa Card</span>
                            <span style="font-size: 10px; color: var(--text-muted);">Cổng Nội Địa</span>
                        </label>

                        <label style="border: 1px solid var(--border-color); background: rgba(255, 255, 255, 0.03); border-radius: 8px; padding: 12px; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 6px; opacity: 0.85;"
                               onclick="document.getElementById('qrSection').scrollIntoView({behavior: 'smooth'})">
                            <input type="radio" name="payment_method" value="MOMO" style="position: absolute; opacity: 0;">
                            <i class="fa-solid fa-wallet" style="font-size: 20px; color: #e91e63;"></i>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Ví MoMo Pay</span>
                            <span style="font-size: 10px; color: var(--text-muted);">Quét Mã Ví</span>
                        </label>
                    </div>

                    <!-- Khung Quét Mã VietQR Napas247 Tự Động -->
                    <div id="qrSection" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 22px;">
                        
                        <!-- Header trạng thái và Đồng hồ đếm ngược -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #00e676; box-shadow: 0 0 10px #00e676; animation: pulseRadar 1.5s infinite;"></span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">
                                    Cổng Thanh Toán Napas 24/7 Tự Động
                                </span>
                                <span style="font-size: 10px; font-weight: 800; background: rgba(0, 230, 118, 0.15); color: #00e676; padding: 2px 7px; border-radius: 4px; border: 1px solid #00e676;">
                                    LIVE / TRỰC TUYẾN
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #ffab00; background: rgba(255, 171, 0, 0.12); padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(255, 171, 0, 0.3);">
                                <i class="fa-regular fa-clock"></i>
                                <span>Hết hạn sau: <span id="countdown">15:00</span></span>
                            </div>
                        </div>

                        <!-- Lưới hiển thị Mã QR + Chi tiết tài khoản ngân hàng -->
                        <div style="display: grid; grid-template-columns: 240px 1fr; gap: 24px; align-items: center;">
                            
                            <!-- Ảnh QR Code -->
                            <div style="text-align: center; background: #ffffff; padding: 12px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);">
                                <img src="<?= $vietQrUrl ?>" 
                                     alt="VietQR MBBank Payment" 
                                     id="vietQrImage"
                                     style="width: 100%; height: auto; display: block; border-radius: 4px;"
                                     onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode('MBBank 0988888888 ' . $transferCode . ' ' . $qrAmount) ?>';">
                                <div style="font-size: 11px; font-weight: 700; color: #0b0e14; margin-top: 6px; text-transform: uppercase;">
                                    Quét bằng mọi ứng dụng ngân hàng
                                </div>
                            </div>

                            <!-- Bảng thông tin chuyển khoản với nút Copy -->
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                
                                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px;">
                                    <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Ngân Hàng Tiếp Nhận</div>
                                    <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">
                                        MBBank (Ngân hàng TMCP Quân Đội)
                                    </div>
                                </div>

                                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Số Tài Khoản</div>
                                        <div style="font-size: 16px; font-weight: 800; color: var(--primary); letter-spacing: 0.5px; margin-top: 2px;">
                                            0988888888
                                        </div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('0988888888', this)" class="btn-gaming btn-outline" style="padding: 6px 12px; font-size: 12px;">
                                        <i class="fa-regular fa-copy"></i> Sao Chép
                                    </button>
                                </div>

                                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px;">
                                    <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Chủ Tài Khoản</div>
                                    <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">
                                        STONK GAMES STORE
                                    </div>
                                </div>

                                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Số Tiền Thanh Toán</div>
                                        <div style="font-size: 18px; font-weight: 800; color: var(--accent-green); margin-top: 2px;">
                                            <?= number_format($totalDiscounted, 0, ',', '.') ?> đ
                                        </div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('<?= (int)$totalDiscounted ?>', this)" class="btn-gaming btn-outline" style="padding: 6px 12px; font-size: 12px;">
                                        <i class="fa-regular fa-copy"></i> Sao Chép
                                    </button>
                                </div>

                                <div style="background: rgba(0, 242, 254, 0.08); border: 1px solid var(--primary); border-radius: 6px; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-size: 11px; color: var(--primary); text-transform: uppercase; font-weight: 700;">Nội Dung Chuyển Khoản (Bắt buộc)</div>
                                        <div style="font-size: 16px; font-weight: 800; color: #ffffff; letter-spacing: 1px; margin-top: 2px;">
                                            <?= htmlspecialchars($transferCode) ?>
                                        </div>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('<?= htmlspecialchars($transferCode) ?>', this)" class="btn-gaming btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                        <i class="fa-regular fa-copy"></i> Sao Chép
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--border-color); font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary);"></i>
                            <span>Hệ thống áp dụng công nghệ nhận diện thanh toán thời gian thực. Sau khi chuyển khoản thành công, nhấn nút xác nhận bên dưới để nhận mã kích hoạt game ngay lập tức.</span>
                        </div>

                    </div>

                    <!-- Nút bấm xác nhận đặt hàng -->
                    <div style="margin-top: 22px;">
                        <button type="submit" class="btn-gaming btn-primary" style="width: 100%; padding: 16px; font-size: 16px; font-weight: 800; text-transform: uppercase; justify-content: center; box-shadow: 0 4px 20px var(--primary-glow);">
                            <i class="fa-solid fa-circle-check"></i> Xác Nhận Đã Chuyển Khoản &bull; Nhận Mã Key Ngay
                        </button>
                        <div style="text-align: center; margin-top: 10px; font-size: 12px; color: var(--text-muted);">
                            <i class="fa-solid fa-shield-halved" style="color: var(--accent-green);"></i> Cam kết bản quyền chính hãng 100% &bull; Hoàn tiền nếu mã key không hoạt động
                        </div>
                    </div>

                </div>

            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG (STICKY) -->
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; position: sticky; top: 90px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">
                        Tóm Tắt Đơn Hàng (<?= count($items) ?> game)
                    </h3>
                    <a href="<?= BASE_URL ?>/cart" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600;">
                        Sửa giỏ hàng
                    </a>
                </div>

                <!-- Danh sách game thu nhỏ -->
                <div style="display: flex; flex-direction: column; gap: 14px; max-height: 280px; overflow-y: auto; padding-right: 4px; margin-bottom: 20px;">
                    <?php foreach ($items as $item): ?>
                        <?php 
                            $game = $item['game'];
                            $coverUrl = get_game_cover_url($game['cover_image']);
                        ?>
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <img src="<?= $coverUrl ?>" alt="<?= htmlspecialchars($game['title']) ?>"
                                 style="width: 65px; height: 42px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);"
                                 onerror="this.src='<?= MEDIA_URL ?>/img/games/elden_ring.jpg';">
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 13px; font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars($game['title']) ?>
                                </div>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    Bản quyền số &bull; 1 CD-Key
                                </div>
                            </div>
                            <div style="font-size: 13px; font-weight: 700; color: var(--text-primary); text-align: right;">
                                <?= number_format($item['price'], 0, ',', '.') ?> đ
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Chi tiết tính toán chi phí -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 16px; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; color: var(--text-secondary);">
                        <span>Giá niêm yết:</span>
                        <span><?= number_format($totalOriginal, 0, ',', '.') ?> đ</span>
                    </div>

                    <?php if ($totalSavings > 0): ?>
                        <div style="display: flex; justify-content: space-between; color: var(--accent-green);">
                            <span>Khuyến mãi tiết kiệm:</span>
                            <span>-<?= number_format($totalSavings, 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-between; color: var(--text-secondary);">
                        <span>Phí kích hoạt & cấp Key:</span>
                        <span style="color: var(--accent-green); font-weight: 700;">MIỄN PHÍ</span>
                    </div>

                    <div style="border-top: 1px dashed var(--border-color); padding-top: 14px; margin-top: 6px; display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-size: 15px; font-weight: 800; color: var(--text-primary);">Tổng thanh toán:</span>
                        <span style="font-size: 24px; font-weight: 800; color: var(--primary);">
                            <?= number_format($totalDiscounted, 0, ',', '.') ?> đ
                        </span>
                    </div>
                </div>

                <div style="margin-top: 24px; padding: 14px; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 8px; font-size: 12px; color: var(--text-muted); line-height: 1.6;">
                    <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Quyền lợi khách hàng tại Stonk Games:</div>
                    <div>&bull; Nhận CD-Key bản quyền toàn cầu (Global Key).</div>
                    <div>&bull; Kích hoạt trực tiếp trên Steam / Epic Games PC.</div>
                    <div>&bull; Hỗ trợ kỹ thuật và đổi key 1-đổi-1 trong 24 giờ nếu có sự cố.</div>
                </div>

            </div>

        </div>
    </form>

</div>

<!-- Toast Feedback Thông Báo Copy -->
<div id="copyToast" style="position: fixed; bottom: 30px; right: 30px; background: #0b0e14; border: 1px solid var(--primary); box-shadow: 0 8px 30px rgba(0, 242, 254, 0.4); color: #ffffff; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 10px; z-index: 9999; transform: translateY(100px); opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); pointer-events: none;">
    <i class="fa-solid fa-circle-check" style="color: var(--accent-green); font-size: 18px;"></i>
    <span id="copyToastText">Đã sao chép vào bộ nhớ tạm!</span>
</div>

<style>
@keyframes pulseRadar {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
}
</style>

<script>
// Hàm sao chép vào Clipboard & hiển thị Toast
function copyToClipboard(text, btnElement) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => showToast(text, btnElement));
    } else {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            showToast(text, btnElement);
        } catch (err) {
            console.error('Không thể sao chép: ', err);
        }
        document.body.removeChild(textArea);
    }
}

function showToast(text, btnElement) {
    const toast = document.getElementById('copyToast');
    const toastText = document.getElementById('copyToastText');
    toastText.innerText = 'Đã sao chép: ' + text;
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

// Đồng hồ đếm ngược 15 phút
(function startTimer() {
    let timeLeft = 15 * 60; // 15 phút
    const display = document.getElementById('countdown');
    
    const interval = setInterval(function () {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        const formattedMin = minutes < 10 ? '0' + minutes : minutes;
        const formattedSec = seconds < 10 ? '0' + seconds : seconds;

        if (display) {
            display.textContent = formattedMin + ':' + formattedSec;
        }

        if (--timeLeft < 0) {
            clearInterval(interval);
            if (display) {
                display.textContent = 'Hết Giờ';
                display.style.color = '#ff5252';
            }
        }
    }, 1000);
})();
</script>
