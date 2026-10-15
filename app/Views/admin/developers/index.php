<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_manage_devs') ?></h1>
        <div class="page-subtitle">Quản lý studio và các hãng phát triển trò chơi hàng đầu thế giới</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px;">
    <!-- Cột trái: Form Thêm / Sửa Studio -->
    <div>
        <div class="admin-card">
            <h3 id="devFormTitle" style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                Thêm Studio Mới
            </h3>

            <form id="developerForm" action="<?= BASE_URL ?>/admin/developers/create" method="POST">
                <input type="hidden" name="id" id="devId" value="">

                <div class="form-group">
                    <label class="form-label">Tên Studio Phát Triển *</label>
                    <input type="text" name="name" id="devName" required class="form-control" placeholder="Ví dụ: Bethesda Softworks">
                </div>

                <div class="form-group">
                    <label class="form-label">Đường Dẫn Tĩnh (Slug)</label>
                    <input type="text" name="slug" id="devSlug" class="form-control" placeholder="Tự động tạo từ tên studio">
                </div>

                <div class="form-group">
                    <label class="form-label">Tên Tệp Logo (Logo Filename)</label>
                    <input type="text" name="logo" id="devLogo" class="form-control" placeholder="bethesda.png">
                    <div class="form-hint">Logo trong thư mục public/assets/uploads/developers/</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô Tả Studio</label>
                    <textarea name="description" id="devDesc" rows="4" class="form-control" placeholder="Lịch sử phát triển, thương hiệu tiêu biểu của hãng..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" id="devSubmitBtn" class="btn-admin btn-admin-primary" style="flex: 1;">
                        Lưu Studio
                    </button>
                    <button type="button" id="devResetBtn" onclick="resetDevForm()" class="btn-admin btn-admin-secondary" style="display: none;">
                        Hủy Bỏ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cột phải: Bảng Studio -->
    <div>
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Mã</th>
                            <th>Tên Studio</th>
                            <th>Đường Dẫn Slug</th>
                            <th>Mô Tả</th>
                            <th style="text-align: right; width: 140px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($developers)): ?>
                            <?php foreach ($developers as $dev): ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--admin-text-muted);">
                                        <?= $dev['id'] ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">
                                            <?= htmlspecialchars($dev['name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <code style="color: var(--admin-primary); background: rgba(0, 242, 254, 0.1); padding: 2px 6px; border-radius: 4px; font-size: 11px;">
                                            <?= htmlspecialchars($dev['slug']) ?>
                                        </code>
                                    </td>
                                    <td>
                                        <span style="font-size: 12px; color: var(--admin-text-muted);">
                                            <?= htmlspecialchars(mb_substr($dev['description'] ?? '', 0, 60)) ?>...
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" 
                                                class="btn-admin btn-admin-secondary btn-admin-sm"
                                                onclick="editDev(<?= htmlspecialchars(json_encode($dev)) ?>)">
                                            Sửa
                                        </button>
                                        <a href="<?= BASE_URL ?>/admin/developers/delete?id=<?= $dev['id'] ?>" 
                                           class="btn-admin btn-admin-danger btn-admin-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa studio <?= htmlspecialchars(addslashes($dev['name'])) ?>?');">
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 25px; color: var(--admin-text-muted);">
                                    Chưa có studio nào.
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
function editDev(dev) {
    document.getElementById('devFormTitle').innerText = 'Chỉnh Sửa Studio: ' + dev.name;
    document.getElementById('developerForm').action = '<?= BASE_URL ?>/admin/developers/edit';
    document.getElementById('devId').value = dev.id;
    document.getElementById('devName').value = dev.name;
    document.getElementById('devSlug').value = dev.slug;
    document.getElementById('devLogo').value = dev.logo || '';
    document.getElementById('devDesc').value = dev.description || '';
    document.getElementById('devSubmitBtn').innerText = 'Cập Nhật';
    document.getElementById('devResetBtn').style.display = 'inline-block';
}

function resetDevForm() {
    document.getElementById('devFormTitle').innerText = 'Thêm Studio Mới';
    document.getElementById('developerForm').action = '<?= BASE_URL ?>/admin/developers/create';
    document.getElementById('devId').value = '';
    document.getElementById('devName').value = '';
    document.getElementById('devSlug').value = '';
    document.getElementById('devLogo').value = '';
    document.getElementById('devDesc').value = '';
    document.getElementById('devSubmitBtn').innerText = 'Lưu Studio';
    document.getElementById('devResetBtn').style.display = 'none';
}
</script>
