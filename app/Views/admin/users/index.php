<div class="page-header">
    <div>
        <h1 class="page-title"><?= __('admin_manage_users') ?></h1>
        <div class="page-subtitle">Quản lý danh sách tài khoản người dùng và phân quyền quản trị viên</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px;">
    <!-- Left Column: Add / Edit User Form -->
    <div>
        <div class="admin-card">
            <h3 id="userFormTitle" style="font-size: 15px; font-weight: 700; color: #fff; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px;">
                Thêm Tài Khoản Mới
            </h3>

            <form id="userForm" action="<?= BASE_URL ?>/admin/users/create" method="POST">
                <input type="hidden" name="id" id="userId" value="">
                <input type="hidden" name="hidden_role" id="hiddenUserRole" value="">

                <div class="form-group">
                    <label class="form-label">Tên Đăng Nhập (Username) *</label>
                    <input type="text" name="username" id="userUsername" required class="form-control" placeholder="Ví dụ: gamer_pro">
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" id="userEmail" required class="form-control" placeholder="gamer@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Mật Khẩu *</label>
                    <input type="password" name="password" id="userPassword" class="form-control" placeholder="Tối thiểu 6 ký tự">
                    <div id="passwordHint" class="form-hint" style="display: none;">Để trống nếu giữ nguyên mật khẩu cũ</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phân Quyền (Role) *</label>
                    <select name="role" id="userRole" required class="form-control">
                        <option value="user">USER (Người Chơi Thường)</option>
                        <option value="admin">ADMIN (Quản Trị Viên Toàn Quyền)</option>
                    </select>
                    <div id="roleLockedHint" class="form-hint" style="display: none; color: var(--admin-primary); font-weight: 600;">
                        Tài khoản Admin gốc không thể thay đổi vai trò.
                    </div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="submit" id="userSubmitBtn" class="btn-admin btn-admin-primary" style="flex: 1;">
                        Lưu Tài Khoản
                    </button>
                    <button type="button" id="userResetBtn" onclick="resetUserForm()" class="btn-admin btn-admin-secondary" style="display: none;">
                        Hủy Bỏ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Users Table -->
    <div>
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Tên Đăng Nhập</th>
                            <th>Email</th>
                            <th>Vai Trò</th>
                            <th>Ngày Tạo</th>
                            <th style="text-align: right; width: 140px;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td style="font-weight: 700; color: var(--admin-text-muted);">
                                        <?= $u['id'] ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #fff;">
                                            <?= htmlspecialchars($u['username']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-size: 13px; color: var(--admin-text-muted);">
                                            <?= htmlspecialchars($u['email']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($u['role'] === 'admin'): ?>
                                            <span class="badge-status badge-admin">ADMIN</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-user">USER</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span style="font-size: 12px; color: var(--admin-text-muted);">
                                            <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" 
                                                class="btn-admin btn-admin-secondary btn-admin-sm"
                                                onclick="editUser(<?= htmlspecialchars(json_encode($u)) ?>)">
                                            Sửa
                                        </button>
                                        <?php if ($u['username'] !== 'admin' && $u['id'] != 1 && $u['id'] != ($_SESSION['user']['id'] ?? 0)): ?>
                                            <a href="<?= BASE_URL ?>/admin/users/delete?id=<?= $u['id'] ?>" 
                                               class="btn-admin btn-admin-danger btn-admin-sm"
                                               onclick="return confirm('Bạn có chắc chắn muốn chuyển tài khoản <?= htmlspecialchars(addslashes($u['username'])) ?> sang trạng thái đã xóa (Soft Delete)?');">
                                                Xóa
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 25px; color: var(--admin-text-muted);">
                                    Chưa có tài khoản nào.
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
function editUser(u) {
    document.getElementById('userFormTitle').innerText = 'Chỉnh Sửa: ' + u.username;
    document.getElementById('userForm').action = '<?= BASE_URL ?>/admin/users/edit';
    document.getElementById('userId').value = u.id;
    document.getElementById('userUsername').value = u.username;
    document.getElementById('userEmail').value = u.email;
    document.getElementById('userRole').value = u.role;
    document.getElementById('userPassword').required = false;
    document.getElementById('passwordHint').style.display = 'block';

    // Khóa không cho phép sửa role của tài khoản admin
    if (u.username === 'admin' || u.id == 1) {
        document.getElementById('userRole').disabled = true;
        document.getElementById('hiddenUserRole').value = 'admin';
        document.getElementById('roleLockedHint').style.display = 'block';
    } else {
        document.getElementById('userRole').disabled = false;
        document.getElementById('hiddenUserRole').value = '';
        document.getElementById('roleLockedHint').style.display = 'none';
    }

    document.getElementById('userSubmitBtn').innerText = 'Cập Nhật';
    document.getElementById('userResetBtn').style.display = 'inline-block';
}

function resetUserForm() {
    document.getElementById('userFormTitle').innerText = 'Thêm Tài Khoản Mới';
    document.getElementById('userForm').action = '<?= BASE_URL ?>/admin/users/create';
    document.getElementById('userId').value = '';
    document.getElementById('userUsername').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('userRole').disabled = false;
    document.getElementById('userRole').value = 'user';
    document.getElementById('hiddenUserRole').value = '';
    document.getElementById('roleLockedHint').style.display = 'none';
    document.getElementById('passwordHint').style.display = 'none';
    document.getElementById('userSubmitBtn').innerText = 'Lưu Tài Khoản';
    document.getElementById('userResetBtn').style.display = 'none';
}
</script>
