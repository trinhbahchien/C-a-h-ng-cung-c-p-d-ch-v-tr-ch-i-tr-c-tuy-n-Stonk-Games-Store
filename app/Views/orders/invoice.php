<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Hóa Đơn Bán Hàng - Stonk Games Store') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0f141c;
            color: #1e293b;
            padding: 30px 15px;
            min-height: 100vh;
        }

        .no-print-bar {
            max-width: 800px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-back {
            background: #1e293b;
            color: #f8fafc;
            border: 1px solid #334155;
        }

        .btn-back:hover {
            background: #334155;
        }

        .btn-print {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.4);
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.5);
        }

        .invoice-sheet {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 45px 50px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
            position: relative;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #0284c7;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .company-info {
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
            margin-top: 8px;
        }

        .invoice-title-block {
            text-align: right;
        }

        .invoice-title {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-code {
            font-size: 14px;
            font-weight: 700;
            color: #0284c7;
            margin-top: 4px;
        }

        .invoice-date {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .info-block h4 {
            font-size: 11px;
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
            color: #334155;
        }

        .info-row span:first-child {
            width: 130px;
            color: #64748b;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .items-table th {
            background: #0f172a;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            padding: 10px 14px;
            text-align: left;
        }

        .items-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
        }

        .table-totals {
            margin-left: auto;
            width: 320px;
            font-size: 13px;
            margin-bottom: 35px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            color: #475569;
        }

        .totals-grand {
            border-top: 2px solid #0f172a;
            padding-top: 10px;
            margin-top: 4px;
            font-size: 16px;
            font-weight: 800;
            color: #0284c7;
        }

        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
        }

        .sign-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #0f172a;
        }

        .sign-note {
            font-size: 11px;
            font-style: italic;
            color: #94a3b8;
            margin-top: 4px;
        }

        .sign-space {
            height: 70px;
        }

        .sign-name {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }

        .invoice-footer-note {
            border-top: 1px dashed #cbd5e1;
            padding-top: 18px;
            margin-top: 35px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .invoice-sheet {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 20px 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bar điều khiển chỉ hiển thị trên màn hình -->
    <div class="no-print-bar">
        <a href="<?= BASE_URL ?>/order/detail/<?= $order['id'] ?>" class="btn-action btn-back">
            <i class="fa-solid fa-arrow-left"></i> Quay Lại Đơn Hàng
        </a>
        <button type="button" onclick="window.print()" class="btn-action btn-print">
            <i class="fa-solid fa-print"></i> In Hóa Đơn / Lưu PDF
        </button>
    </div>

    <!-- Tờ Hóa Đơn Chuẩn Khổ A4 -->
    <div class="invoice-sheet">
        <!-- Header Hóa Đơn -->
        <div class="invoice-header">
            <div>
                <div class="brand-name">STONK GAMES <span style="font-weight: 400; color: #0284c7;">STORE</span></div>
                <div class="brand-subtitle">CỔNG BẢN QUYỀN TRÒ CHƠI ĐIỆN TỬ TOÀN CẦU</div>
                <div class="company-info">
                    <strong>CÔNG TY CỔ PHẦN CÔNG NGHỆ VÀ TRÒ CHƠI STONK VIỆT NAM</strong><br>
                    Tòa nhà Stonk Games Tower, Khu Công Nghệ Cao, Hà Nội<br>
                    Hotline: 1900 8899 &bull; Email: support@stonkgames.vn<br>
                    Mã số thuế: 0109988776
                </div>
            </div>

            <div class="invoice-title-block">
                <div class="invoice-title">HÓA ĐƠN BÁN HÀNG</div>
                <div class="invoice-code">MÃ ĐƠN: #<?= $order['id'] ?></div>
                <div class="invoice-date">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                <div class="invoice-date">Ngày xuất HĐ: <?= date('d/m/Y H:i') ?></div>
            </div>
        </div>

        <!-- Khối Thông Tin Giao Dịch -->
        <div class="info-grid">
            <div class="info-block">
                <h4>Thông Tin Khách Hàng</h4>
                <div class="info-row">
                    <span>Họ tên:</span>
                    <strong><?= htmlspecialchars($order['customer_name']) ?></strong>
                </div>
                <div class="info-row">
                    <span>Email nhận key:</span>
                    <strong><?= htmlspecialchars($order['customer_email']) ?></strong>
                </div>
                <div class="info-row">
                    <span>Tài khoản:</span>
                    <span><?= !empty($order['username']) ? htmlspecialchars($order['username']) : 'Khách Vãng Lai' ?></span>
                </div>
            </div>

            <div class="info-block">
                <h4>Chi Tiết Thanh Toán</h4>
                <div class="info-row">
                    <span>Phương thức:</span>
                    <strong>Chuyển khoản VietQR NAPAS247</strong>
                </div>
                <div class="info-row">
                    <span>Trạng thái:</span>
                    <strong style="color: #16a34a;"><?= ($order['status'] === 'COMPLETED') ? 'ĐÃ HOÀN TẤT & ĐÃ CẤP KEY' : 'CHỜ XÁC NHẬN' ?></strong>
                </div>
                <div class="info-row">
                    <span>Cơ chế cấp phép:</span>
                    <span>Bản quyền số tự động (Digital Key)</span>
                </div>
            </div>
        </div>

        <!-- Bảng Mặt Hàng & Mã CD-Key Bản Quyền -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 45px; text-align: center;">STT</th>
                    <th>Tên Tựa Game / Giấy Phép Bản Quyền</th>
                    <th style="width: 220px;">Mã CD-Key Kích Hoạt</th>
                    <th style="text-align: right; width: 140px;">Đơn Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $idx => $it): ?>
                    <tr>
                        <td style="text-align: center; color: #64748b; font-weight: 600;"><?= $idx + 1 ?></td>
                        <td>
                            <strong style="color: #0f172a; font-size: 14px;"><?= htmlspecialchars($it['game_title']) ?></strong>
                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Giấy phép bản quyền số toàn cầu (Global PC License)</div>
                        </td>
                        <td>
                            <span style="font-family: 'Courier New', monospace; font-size: 13px; font-weight: 700; color: #0284c7; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; border: 1px solid #cbd5e1;">
                                <?= htmlspecialchars($it['cd_key']) ?>
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a;">
                            <?= number_format($it['price'], 0, ',', '.') ?> đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Bảng Tính Tổng Tiền -->
        <div class="table-totals">
            <div class="totals-row">
                <span>Cộng tiền hàng:</span>
                <strong style="color: #0f172a;"><?= number_format($order['total_amount'], 0, ',', '.') ?> đ</strong>
            </div>
            <div class="totals-row">
                <span>Phí dịch vụ & cấp phép:</span>
                <span style="color: #16a34a; font-weight: 600;">0 đ (Miễn phí)</span>
            </div>
            <div class="totals-row totals-grand">
                <span>TỔNG THANH TOÁN:</span>
                <span><?= number_format($order['total_amount'], 0, ',', '.') ?> đ</span>
            </div>
        </div>

        <!-- 3 Ô Chữ Ký Trách Nhiệm -->
        <div class="signatures-grid">
            <div>
                <div class="sign-title">Khách Hàng</div>
                <div class="sign-note">(Ký & ghi rõ họ tên)</div>
                <div class="sign-space"></div>
                <div class="sign-name"><?= htmlspecialchars($order['customer_name']) ?></div>
            </div>

            <div>
                <div class="sign-title">Nhân Viên Giao Dịch</div>
                <div class="sign-note">(Ký & ghi rõ họ tên)</div>
                <div class="sign-space"></div>
                <div class="sign-name">Hệ Thống Tự Động</div>
            </div>

            <div>
                <div class="sign-title">Đại Diện Cửa Hàng</div>
                <div class="sign-note">(Ký, đóng dấu số)</div>
                <div class="sign-space"></div>
                <div class="sign-name">Stonk Games Store</div>
            </div>
        </div>

        <!-- Chân Hóa Đơn -->
        <div class="invoice-footer-note">
            Cảm ơn quý khách đã tin tưởng mua sắm game bản quyền tại Stonk Games Store!<br>
            Hóa đơn điện tử được tạo lập tự động và có giá trị xác nhận quyền sở hữu giấy phép bản quyền game.
        </div>
    </div>

</body>
</html>
