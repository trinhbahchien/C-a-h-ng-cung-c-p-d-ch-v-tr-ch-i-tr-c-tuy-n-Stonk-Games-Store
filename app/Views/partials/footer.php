<?php
/**
 * Stonk Games Store - Gaming Footer Partial
 * Chan trang gaming chuyen nghiep ho tro ca Dark Mode & Light Mode
 */
?>
<footer class="footer-gaming">
    <div class="footer-container" style="max-width: 1320px; margin: 0 auto; padding: 60px 20px 30px;">
        
        <!-- Top Footer Grid (4 Cot) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 40px; margin-bottom: 50px;">
            
            <!-- Cot 1: Thuong Hieu & Gioi Thieu -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                    <span style="font-size: 22px; font-weight: 800; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                        STONK GAMES
                    </span>
                    <span class="brand-badge">STORE</span>
                </div>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 20px;">
                    Cổng phân phối bản quyền trò chơi kỹ thuật số và tin tức gaming đỉnh cao. Đồ án mẫu chuẩn đại học HUBT xây dựng trên kiến trúc Pure PHP MVC & PDO Singleton.
                </p>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <a href="https://store.steampowered.com" target="_blank" title="Steam Community" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-primary); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                        <i class="fa-brands fa-steam"></i>
                    </a>
                    <a href="https://discord.com" target="_blank" title="Discord Community" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-primary); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                        <i class="fa-brands fa-discord"></i>
                    </a>
                    <a href="https://github.com" target="_blank" title="GitHub Repository" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-primary); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                        <i class="fa-brands fa-github"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" title="YouTube Gaming" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-primary); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-primary)'">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Cot 2: Kham Pha Cua Hang -->
            <div>
                <h4 style="font-size: 14px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 18px;">
                    Khám Phá Cửa Hàng
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <li><a href="<?= BASE_URL ?>/catalog" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Toàn Bộ Kho Game</a></li>
                    <li><a href="<?= BASE_URL ?>/rankings" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Bảng Xếp Hạng Bán Chạy</a></li>
                    <li><a href="<?= BASE_URL ?>/catalog?free=1" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Game Miễn Phí (Free-to-Play)</a></li>
                    <li><a href="<?= BASE_URL ?>/catalog?sale=1" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Khuyến Mãi Đặc Biệt</a></li>
                    <li><a href="<?= BASE_URL ?>/cart" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Giỏ Hàng Của Bạn</a></li>
                </ul>
            </div>

            <!-- Cot 3: The Loai & Studio -->
            <div>
                <h4 style="font-size: 14px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 18px;">
                    Thể Loại Phổ Biến
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <li><a href="<?= BASE_URL ?>/catalog?category=1" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Hành Động & Phiêu Lưu</a></li>
                    <li><a href="<?= BASE_URL ?>/catalog?category=2" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Nhập Vai Thế Giới Mở (RPG)</a></li>
                    <li><a href="<?= BASE_URL ?>/catalog?category=8" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Bắn Súng FPS / TPS</a></li>
                    <li><a href="<?= BASE_URL ?>/catalog?category=5" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Chiến Thuật RTS & MOBA</a></li>
                    <li><a href="<?= BASE_URL ?>/news" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-secondary)'">Cổng Tin Tức Trò Chơi</a></li>
                </ul>
            </div>

            <!-- Cot 4: Ho Tro & Cam Ket -->
            <div>
                <h4 style="font-size: 14px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 18px;">
                    Cam Kết & Bảo Mật
                </h4>
                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13px; color: var(--text-secondary);">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fa-solid fa-key" style="color: var(--primary); margin-top: 3px;"></i>
                        <span><strong>Cấp CD-Key Tức Thì:</strong> Kích hoạt tự động qua email & hồ sơ sau khi thanh toán.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fa-solid fa-qrcode" style="color: var(--accent-green); margin-top: 3px;"></i>
                        <span><strong>VietQR Chuẩn Napas:</strong> Quét mã chuyển khoản ngân hàng tự động 24/7.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fa-solid fa-shield-halved" style="color: #a29bfe; margin-top: 3px;"></i>
                        <span><strong>Bảo Mật BCrypt:</strong> Mật khẩu và thông tin cá nhân mã hóa an toàn 100%.</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Middle Section: Doi Tac & Cong Thanh Toan -->
        <div style="border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 20px 0; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                Cổng Thanh Toán & Hỗ Trợ Kích Hoạt
            </div>
            <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-qrcode" style="color: var(--accent-green); margin-right: 5px;"></i> VietQR Napas247
                </span>
                <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-brands fa-steam" style="margin-right: 5px;"></i> Steam Key
                </span>
                <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-shield" style="color: var(--primary); margin-right: 5px;"></i> Epic Games Store
                </span>
                <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary); background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 6px;">
                    <i class="fa-solid fa-lock" style="color: #fdcb6e; margin-right: 5px;"></i> SSL 256-Bit
                </span>
            </div>
        </div>

        <!-- Bottom Copyright & Tech Stack -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; font-size: 12px; color: var(--text-muted);">
            <div>
                <p>&copy; <?= date('Y') ?> <strong><?= APP_NAME ?></strong>. Nền tảng phân phối trò chơi điện tử trực tuyến.</p>
                <p style="margin-top: 4px;">Đồ án tốt nghiệp môn Phát triển ứng dụng Web &bull; Đại học Kinh Doanh và Công Nghệ Hà Nội (HUBT).</p>
            </div>
            <div class="footer-tech" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <span class="tech-pill">Pure PHP MVC</span>
                <span class="tech-pill">PDO Singleton</span>
                <span class="tech-pill">Prepared Statements</span>
                <span class="tech-pill">MySQL 3NF</span>
            </div>
        </div>

    </div>
</footer>
