<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_manage_cats') ?></h1>
        <div class="page-subtitle">Quản lý danh mục các thể loại game (Hành động, Nhập vai RPG, Thế giới mở, v.v.)</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px;">
    <!-- Cột trái: Form Thêm / Sửa Thể Loại -->
    <div>
        <div class="admin-card">
            <h3 id="formTitle" style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                Thêm Thể Loại Mới
            </h3>

            <form id="categoryForm" action="<?= BASE_URL ?>/admin/categories/create" method="POST">
                <input type="hidden" name="id" id="catId" value="">

                <div class="form-group">
                    <label class="form-label">Tên Thể Loại *</label>
                    <input type="text" name="name" id="catName" required class="form-control" placeholder="Ví dụ: MOBA, Chiến thuật">
                </div>

                <div class="form-group">
                    <label class="form-label">Đường Dẫn Tĩnh (Slug)</label>
                    <input type="text" name="slug" id="catSlug" class="form-control" placeholder="Tự động tạo từ tên thể loại">
                </div>

                <div class="form-group">
                    <label class="form-label">Mô Tả Thể Loại</label>
                    <textarea name="description" id="catDesc" rows="4" class="form-control" placeholder="Mô tả đặc trưng và phong cách lối chơi của thể loại..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" id="submitBtn" class="btn-admin btn-admin-primary" style="flex: 1;">
                        Lưu Thể Loại
                    </button>
                    <button type="button" id="resetBtn" onclick="resetCatForm()" class="btn-admin btn-admin-secondary" style="display: none;">
                        Hủy Bỏ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cột phải: Bảng Thể Loại -->
    <div>
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Mã</th>
                            <th>Tên Thể Loại</th>
                            <th>Đường Dẫn Slug</th>
                            <th>Mô Tả</th>
                            <th style="text-align: right; width: 140px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--admin-text-muted);">
                                        <?= $cat['id'] ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <code style="color: var(--admin-primary); background: rgba(0, 242, 254, 0.1); padding: 2px 6px; border-radius: 4px; font-size: 11px;">
                                            <?= htmlspecialchars($cat['slug']) ?>
                                        </code>
                                    </td>
                                    <td>
                                        <span style="font-size: 12px; color: var(--admin-text-muted);">
                                            <?= htmlspecialchars(mb_substr($cat['description'] ?? '', 0, 60)) ?>...
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" 
                                                class="btn-admin btn-admin-secondary btn-admin-sm"
                                                onclick="editCat(<?= htmlspecialchars(json_encode($cat)) ?>)">
                                            Sửa
                                        </button>
                                        <a href="<?= BASE_URL ?>/admin/categories/delete?id=<?= $cat['id'] ?>" 
                                           class="btn-admin btn-admin-danger btn-admin-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại <?= htmlspecialchars(addslashes($cat['name'])) ?>?');">
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 25px; color: var(--admin-text-muted);">
                                    Chưa có thể loại nào.
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
function editCat(cat) {
    document.getElementById('formTitle').innerText = 'Chỉnh Sửa Thể Loại: ' + cat.name;
    document.getElementById('categoryForm').action = '<?= BASE_URL ?>/admin/categories/edit';
    document.getElementById('catId').value = cat.id;
    document.getElementById('catName').value = cat.name;
    document.getElementById('catSlug').value = cat.slug;
    document.getElementById('catDesc').value = cat.description || '';
    document.getElementById('submitBtn').innerText = 'Cập Nhật';
    document.getElementById('resetBtn').style.display = 'inline-block';
}

function resetCatForm() {
    document.getElementById('formTitle').innerText = 'Thêm Thể Loại Mới';
    document.getElementById('categoryForm').action = '<?= BASE_URL ?>/admin/categories/create';
    document.getElementById('catId').value = '';
    document.getElementById('catName').value = '';
    document.getElementById('catSlug').value = '';
    document.getElementById('catDesc').value = '';
    document.getElementById('submitBtn').innerText = 'Lưu Thể Loại';
    document.getElementById('resetBtn').style.display = 'none';
}
</script>
