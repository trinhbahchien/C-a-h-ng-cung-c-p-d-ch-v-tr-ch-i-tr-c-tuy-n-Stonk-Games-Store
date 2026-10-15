<div style="max-width: 440px; margin: 40px auto; padding: 35px 30px; background: #161b22; border: 1px solid rgba(0, 242, 254, 0.2); border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);">
    <div style="text-align: center; margin-bottom: 25px;">
        <h2 style="font-size: 26px; color: #fff; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 800;">
            <?= __('nav_register') ?>
        </h2>
        <p style="color: #8b949e; font-size: 14px; margin: 0;">
            TẠO TÀI KHOẢN NGƯỜI CHƠI MỚI
        </p>
    </div>

    <?php if (!empty($error)): ?>
        <div style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; font-size: 14px; background: rgba(255, 75, 75, 0.15); border: 1px solid #ff4b4b; color: #ff6b6b;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/auth/register" method="POST">
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #c9d1d9; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">
                Tên Đăng Nhập *
            </label>
            <input type="text" name="username" value="<?= htmlspecialchars($data['username'] ?? '') ?>" required
                   placeholder="Ví dụ: game_master"
                   style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #fff; font-size: 15px; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#00f2fe'" onblur="this.style.borderColor='#30363d'">
        </div>

        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #c9d1d9; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">
                Địa Chỉ Email *
            </label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required
                   placeholder="gamer@example.com"
                   style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #fff; font-size: 15px; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#00f2fe'" onblur="this.style.borderColor='#30363d'">
        </div>

        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #c9d1d9; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">
                Mật Khẩu *
            </label>
            <input type="password" name="password" required
                   placeholder="Tối thiểu 6 ký tự"
                   style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #fff; font-size: 15px; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#00f2fe'" onblur="this.style.borderColor='#30363d'">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; color: #c9d1d9; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">
                Xác Nhận Mật Khẩu *
            </label>
            <input type="password" name="confirm_password" required
                   placeholder="Nhập lại mật khẩu"
                   style="width: 100%; box-sizing: border-box; padding: 12px 14px; background: #0d1117; border: 1px solid #30363d; border-radius: 6px; color: #fff; font-size: 15px; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#00f2fe'" onblur="this.style.borderColor='#30363d'">
        </div>

        <button type="submit" 
                style="width: 100%; padding: 14px; background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); color: #0b0e14; border: none; border-radius: 6px; font-size: 15px; font-weight: 700; text-transform: uppercase; cursor: pointer; letter-spacing: 0.5px; transition: transform 0.2s, box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 242, 254, 0.4)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <?= __('nav_register') ?>
        </button>
    </form>

    <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #21262d; text-align: center; font-size: 14px; color: #8b949e;">
        Đã có tài khoản? 
        <a href="<?= BASE_URL ?>/auth/login" style="color: #00f2fe; text-decoration: none; font-weight: 600; margin-left: 5px;">
            <?= __('nav_login') ?>
        </a>
    </div>
</div>
