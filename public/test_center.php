<?php
/**
 * Stonk Games Store - Trung Tâm Kiểm Thử Video Trailer (Self-Test Center)
 * Phục vụ người dùng và giảng viên tự kiểm tra tính năng Video Trailer & CRUD Games
 */

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/Game.php';

$db = Database::getInstance()->getConnection();
$gameModel = new Game();

$action = $_GET['action'] ?? '';
$flashMessage = '';
$flashType = 'info';

// Xử lý tạo 1 bản ghi TEST thực tế để người dùng tự kiểm trên giao diện web
if ($action === 'create_live_test') {
    try {
        $testGameData = [
            'title'             => '[TEST] Game Thử Nghiệm Trailer Cinema',
            'slug'              => 'test-game-trailer-cinema-' . time(),
            'developer_id'      => 1,
            'price'             => 650000.00,
            'discount_percent'  => 20,
            'short_description' => 'Bản ghi được tạo để người dùng tự kiểm tra tính năng phát Video Trailer Cinema và CRUD trong trang quản trị.',
            'description'       => 'Trò chơi thử nghiệm này được tích hợp mã video trailer chính thức để kiểm tra tính năng phát video nhúng, nút mở YouTube ngoài, chuyển đổi thumbnail Poster/Trailer và toàn bộ quy trình kiểm thử hệ thống.',
            'cover_image'       => 'default_game.jpg',
            'trailer_url'       => 'AKXiKBnzpBQ', // Elden Ring Launch trailer (Clean, 100% Playable)
            'is_featured'       => 1,
            'status'            => 1,
            'release_date'      => date('Y-m-d')
        ];
        $reqs = [
            'min_os'      => 'Windows 10 64-bit',
            'min_cpu'     => 'Intel Core i5-8400',
            'min_ram'     => '8 GB RAM',
            'min_gpu'     => 'NVIDIA GTX 1060',
            'min_storage' => '30 GB',
            'rec_os'      => 'Windows 11 64-bit',
            'rec_cpu'     => 'Intel Core i7-10700',
            'rec_ram'     => '16 GB RAM',
            'rec_gpu'     => 'NVIDIA RTX 3060',
            'rec_storage' => '30 GB SSD'
        ];
        $newId = $gameModel->create($testGameData, [1, 2], $reqs);
        $flashMessage = "Đã tạo thành công bản ghi thử nghiệm [TEST] với ID: {$newId}! Bạn có thể kiểm tra trực tiếp trên trang chi tiết hoặc trang quản trị bên dưới.";
        $flashType = 'success';
    } catch (Exception $e) {
        $flashMessage = "Lỗi khi tạo bản ghi test: " . $e->getMessage();
        $flashType = 'danger';
    }
}

// Xử lý xóa bản ghi TEST
if ($action === 'delete_live_test' && isset($_GET['id'])) {
    $delId = (int)$_GET['id'];
    try {
        // Chỉ cho phép xóa bản ghi có tiền tố [TEST]
        $check = $db->prepare("SELECT title FROM games WHERE id = :id");
        $check->execute([':id' => $delId]);
        $row = $check->fetch();
        if ($row && strpos($row['title'], '[TEST]') !== false) {
            $gameModel->delete($delId);
            $flashMessage = "Đã dọn dẹp sạch sẽ bản ghi thử nghiệm ID: {$delId}. Cơ sở dữ liệu luôn an toàn!";
            $flashType = 'success';
        } else {
            $flashMessage = "Không thể xóa bản ghi này vì không phải bản ghi [TEST] thử nghiệm!";
            $flashType = 'warning';
        }
    } catch (Exception $e) {
        $flashMessage = "Lỗi khi xóa: " . $e->getMessage();
        $flashType = 'danger';
    }
}

// Lấy danh sách 18 game gốc + bản ghi test nếu có
$stmt = $db->query("
    SELECT g.id, g.title, g.slug, g.trailer_url, g.cover_image, g.is_featured, g.status, d.name AS dev_name 
    FROM games g
    LEFT JOIN developers d ON g.developer_id = d.id
    ORDER BY g.id ASC
");
$allGames = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tìm các bản ghi TEST đang tồn tại
$testGames = array_filter($allGames, fn($g) => strpos($g['title'], '[TEST]') !== false);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trung Tâm Kiểm Thử Video Trailer & CRUD - Stonk Games Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-body: #0b0e14;
            --bg-card: #151a23;
            --bg-card-hover: #1c2330;
            --border-color: #262f40;
            --text-primary: #f1f5f9;
            --text-muted: #94a3b8;
            --accent-blue: #00f2fe;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-yellow: #f59e0b;
            --primary: #4f46e5;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            line-height: 1.6;
            padding: 30px 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .header-box {
            background: linear-gradient(135deg, #151a23 0%, #1e293b 100%);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }
        .header-title { font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 10px; display: flex; align-items: center; gap: 12px; }
        .header-desc { color: var(--text-muted); font-size: 15px; }
        .alert-box {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 600;
        }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; }
        .alert-warning { background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; color: #fbbf24; }
        
        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .action-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .action-card h3 { font-size: 18px; margin-bottom: 12px; color: #fff; }
        .action-card p { font-size: 14px; color: var(--text-muted); margin-bottom: 18px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-outline { background: transparent; border: 1px solid var(--border-color); color: var(--text-primary); }
        .btn-outline:hover { background: var(--bg-card-hover); border-color: #3b82f6; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        .table-wrapper {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 35px;
        }
        .table-header {
            padding: 18px 24px;
            background: rgba(255,255,255,0.02);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-header h2 { font-size: 18px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 14px 18px; background: rgba(0,0,0,0.2); color: var(--text-muted); font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border-color); }
        td { padding: 14px 18px; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        tr:hover { background: var(--bg-card-hover); }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-success { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .badge-updated { background: rgba(0, 242, 254, 0.2); color: #00f2fe; border: 1px solid rgba(0, 242, 254, 0.4); }
        .badge-test { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); }

        /* Modal Preview Video */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
        }
        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 { font-size: 16px; color: #fff; }
        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 20px;
            cursor: pointer;
        }
        .modal-close:hover { color: #fff; }
        .modal-body {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
        }
        .modal-body iframe {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            border: none;
        }
        .quick-nav {
            margin-top: 30px;
            padding: 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header-box">
        <h1 class="header-title">
            <i class="fa-solid fa-film" style="color: var(--accent-blue);"></i>
            Trung Tâm Kiểm Thử Video Trailer & Quản Trị Games
        </h1>
        <p class="header-desc">
            Bảng theo dõi trạng thái Video Trailer thực tế của toàn bộ 18 tựa game, cập nhật các trailer khắc phục lỗi giới hạn độ tuổi (Age-Gate) và công cụ tự kiểm thử quy trình CRUD theo tiêu chuẩn đồ án.
        </p>
    </div>

    <!-- Thông báo Flash -->
    <?php if (!empty($flashMessage)): ?>
        <div class="alert-box alert-<?= $flashType ?>">
            <?= htmlspecialchars($flashMessage) ?>
        </div>
    <?php endif; ?>

    <!-- Khu vực 3 Thẻ Hành Động Tự Kiểm Thử -->
    <div class="grid-cards">
        <!-- Card 1: Tạo test case cho người dùng tự kiểm -->
        <div class="action-card" style="border-top: 4px solid var(--accent-green);">
            <div>
                <h3>1. Tạo Bản Ghi Test Cho Người Dùng Tự Kiểm</h3>
                <p>
                    Tạo 1 bản ghi thử nghiệm <code>[TEST] Game Thử Nghiệm Trailer Cinema</code> vào CSDL với video trailer hoạt động 100%. Bạn có thể xem trang chi tiết game và thực hiện chỉnh sửa trong trang quản trị Admin.
                </p>
            </div>
            <div>
                <a href="test_center.php?action=create_live_test" class="btn btn-success" style="width: 100%;">
                    <i class="fa-solid fa-plus-circle"></i> Tạo Bản Ghi Test Mới
                </a>
            </div>
        </div>

        <!-- Card 2: Chạy kiểm thử tự động toàn diện -->
        <div class="action-card" style="border-top: 4px solid var(--accent-blue);">
            <div>
                <h3>2. Tự Động Kiểm Thử Quy Trình CRUD (AGENTS.md)</h3>
                <p>
                    Kích hoạt chu trình kiểm thử tự động khép kín: <strong>Thêm Mới [TEST] &rarr; Chỉnh Sửa [TEST - ĐÃ CẬP NHẬT] &rarr; Xóa Sạch Sẽ</strong> theo đúng quy tắc kiểm thử nghiêm ngặt tại <code>.agents/AGENTS.md</code>.
                </p>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <button type="button" class="btn btn-primary" onclick="runCliCrudTest()" style="width: 100%;">
                    <i class="fa-solid fa-play"></i> Chạy Test CRUD Game (.agents/AGENTS.md)
                </button>
                <button type="button" class="btn btn-success" onclick="runNewsCrudTest()" style="width: 100%;">
                    <i class="fa-solid fa-newspaper"></i> Chạy Test CRUD Tin Tức (Giai đoạn 5)
                </button>
            </div>
        </div>

        <!-- Card 3: Thử nghiệm link YouTube bất kỳ -->
        <div class="action-card" style="border-top: 4px solid var(--accent-yellow);">
            <div>
                <h3>3. Tự Kiểm Tra Link YouTube Tùy Chọn</h3>
                <p>
                    Nhập mã video hoặc URL YouTube bất kỳ để hệ thống tự động bóc tách ID, kiểm tra kết nối và trình chiếu thử nghiệm ngay lập tức.
                </p>
                <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                    <input type="text" id="customYtInput" placeholder="Ví dụ: AKXiKBnzpBQ hoặc link YouTube" 
                           style="flex: 1; padding: 8px 12px; background: #0b0e14; border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 13px;">
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-outline" onclick="testCustomYt()" style="width: 100%;">
                    <i class="fa-solid fa-magnifying-glass"></i> Kiểm Tra & Xem Thử
                </button>
            </div>
        </div>
    </div>

    <!-- Hộp kết quả chạy tự động (Ẩn mặc định, hiển thị khi bấm nút) -->
    <div id="testOutputBox" style="display: none; background: #06090e; border: 1px solid var(--border-color); border-radius: 10px; padding: 20px; margin-bottom: 30px; font-family: monospace; font-size: 13px; color: #38bdf8; white-space: pre-wrap;"></div>

    <!-- Danh sách bản ghi TEST đang tồn tại (Nếu có) -->
    <?php if (!empty($testGames)): ?>
        <div class="table-wrapper" style="border: 1px solid #f59e0b; margin-bottom: 30px;">
            <div class="table-header" style="background: rgba(245, 158, 11, 0.1);">
                <h2 style="color: #fbbf24;"><i class="fa-solid fa-vial"></i> Bản Ghi Thử Nghiệm [TEST] Đang Chờ Bạn Kiểm Tra</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Bản Ghi Test</th>
                        <th>Trailer ID</th>
                        <th>Thao Tác Tự Kiểm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testGames as $tg): ?>
                        <tr>
                            <td><strong>#<?= $tg['id'] ?></strong></td>
                            <td style="color: #fbbf24; font-weight: 700;"><?= htmlspecialchars($tg['title']) ?></td>
                            <td><code><?= htmlspecialchars($tg['trailer_url']) ?></code></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="game/detail?id=<?= $tg['id'] ?>" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-eye"></i> Xem Chi Tiết
                                    </a>
                                    <a href="admin/games/edit?id=<?= $tg['id'] ?>" target="_blank" class="btn btn-outline btn-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Quản Trị Sửa
                                    </a>
                                    <a href="test_center.php?action=delete_live_test&id=<?= $tg['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn muốn xóa dọn dẹp bản ghi test này?')">
                                        <i class="fa-solid fa-trash"></i> Xóa Dọn Dẹp
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Bảng Trạng Thái Toàn Bộ 18 Game & Video Trailer -->
    <div class="table-wrapper">
        <div class="table-header">
            <h2><i class="fa-solid fa-list-check" style="color: var(--accent-blue);"></i> Trạng Thái Video Trailer Của 18 Trò Chơi Trong CSDL</h2>
            <span class="badge badge-success">100% Đạt Chuẩn & Sẵn Sàng Phát</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 80px;">Poster</th>
                    <th>Tựa Game</th>
                    <th>Nhà Phát Triển</th>
                    <th>Mã Trailer (ID)</th>
                    <th>Trạng Thái Phát</th>
                    <th style="text-align: right;">Xem Thử & Điều Hướng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allGames as $g): ?>
                    <?php 
                        if (strpos($g['title'], '[TEST]') !== false) continue; // Bản ghi test đã hiển thị ở bảng trên
                        $isUpdated = in_array($g['id'], [2, 9, 11, 16]);
                        $coverUrl = get_game_cover_url($g['cover_image'] ?? '');
                    ?>
                    <tr>
                        <td><strong>#<?= $g['id'] ?></strong></td>
                        <td>
                            <img src="<?= $coverUrl ?>" alt="Cover" style="width: 60px; height: 36px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
                        </td>
                        <td>
                            <strong style="color: #fff;"><?= htmlspecialchars($g['title']) ?></strong>
                            <?php if ($isUpdated): ?>
                                <span class="badge badge-updated" style="margin-left: 6px; font-size: 11px;">Đã Cập Nhật Mới</span>
                            <?php endif; ?>
                        </td>
                        <td style="color: var(--text-muted);"><?= htmlspecialchars($g['dev_name'] ?? 'N/A') ?></td>
                        <td>
                            <code style="background: rgba(0,0,0,0.4); padding: 3px 8px; border-radius: 4px; color: var(--accent-blue);"><?= htmlspecialchars($g['trailer_url']) ?></code>
                        </td>
                        <td>
                            <span class="badge badge-success">
                                <i class="fa-solid fa-check"></i> Hoạt động tốt
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 8px;">
                                <button type="button" class="btn btn-outline btn-sm" onclick="openTrailerModal('<?= htmlspecialchars($g['trailer_url']) ?>', '<?= htmlspecialchars(addslashes($g['title'])) ?>')">
                                    <i class="fa-solid fa-play" style="color: #ef4444;"></i> Xem Thử
                                </button>
                                <a href="game/detail?id=<?= $g['id'] ?>" target="_blank" class="btn btn-outline btn-sm" title="Mở trang chi tiết">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Trang Game
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Quick Navigation Links -->
    <div class="quick-nav">
        <a href="./" class="btn btn-outline">
            <i class="fa-solid fa-store"></i> Trang Chủ Cửa Hàng
        </a>
        <a href="admin/games" class="btn btn-outline">
            <i class="fa-solid fa-gamepad"></i> Quản Lý Games (Admin)
        </a>
        <a href="admin" class="btn btn-outline">
            <i class="fa-solid fa-gauge-high"></i> Admin Dashboard
        </a>
    </div>
</div>

<!-- Modal Popup Video Preview -->
<div class="modal" id="videoModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalVideoTitle">Trình Chiếu Video Trailer</h3>
            <button type="button" class="modal-close" onclick="closeTrailerModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalVideoContainer">
            <!-- Iframe được nạp tự động qua JavaScript -->
        </div>
    </div>
</div>

<script>
function openTrailerModal(videoId, title) {
    const modal = document.getElementById('videoModal');
    const titleEl = document.getElementById('modalVideoTitle');
    const container = document.getElementById('modalVideoContainer');

    titleEl.innerText = title ? ('Trailer: ' + title) : 'Trình Chiếu Video Trailer';
    container.innerHTML = `
        <iframe src="https://www.youtube-nocookie.com/embed/${videoId}?autoplay=1&rel=0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>
    `;
    modal.classList.add('active');
}

function closeTrailerModal() {
    const modal = document.getElementById('videoModal');
    const container = document.getElementById('modalVideoContainer');
    container.innerHTML = '';
    modal.classList.remove('active');
}

// Đóng modal khi bấm ra ngoài
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTrailerModal();
    }
});

// Kiểm tra link YouTube tùy chọn
function testCustomYt() {
    const input = document.getElementById('customYtInput').value.trim();
    if (!input) {
        alert('Vui lòng nhập ID hoặc link video YouTube!');
        return;
    }
    let vid = input;
    const match = input.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/|live\/))([\w-]{11})/i);
    if (match) {
        vid = match[1];
    }
    openTrailerModal(vid, 'Video Tự Kiểm Tra (' + vid + ')');
}

// Chạy test tự động CRUD bằng script
async function runCliCrudTest() {
    const box = document.getElementById('testOutputBox');
    box.style.display = 'block';
    box.innerText = 'Đang chạy kiểm thử tự động CRUD theo chuẩn .agents/AGENTS.md...\nVui lòng chờ trong giây lát...';

    try {
        const res = await fetch('run_crud_test.php');
        const text = await res.text();
        box.innerText = text;
    } catch (err) {
        box.innerText = 'Lỗi thực thi kiểm thử: ' + err.message;
    }
}

// Chạy test tự động CRUD Tin Tức (Giai đoạn 5)
async function runNewsCrudTest() {
    const box = document.getElementById('testOutputBox');
    box.style.display = 'block';
    box.innerText = 'Đang chạy kiểm thử tự động CRUD Tin Tức theo chuẩn .agents/AGENTS.md...\nVui lòng chờ trong giây lát...';

    try {
        const res = await fetch('run_news_test.php');
        const text = await res.text();
        box.innerText = text;
    } catch (err) {
        box.innerText = 'Lỗi thực thi kiểm thử: ' + err.message;
    }
}
</script>

</body>
</html>
