# QUY TẮC PHÁT TRIỂN & KIỂM THỬ DỰ ÁN (PROJECT AGENT RULES)
*Dự án: Website Kinh Doanh Game Bản Quyền & Tin Tức Trò Chơi (Stonk Games Store)*

---

## 1. QUY TẮC KIỂM THỬ BẮT BUỘC ĐỐI VỚI CÁC CHỨC NĂNG QUẢN LÝ (CRUD TESTING RULE)

> [!IMPORTANT]
> **Quy định nghiêm ngặt:** Bất kỳ chức năng quản lý nào chứa các thao tác **Thêm, Sửa, Xóa (Create - Read - Update - Delete)** sau khi build hoàn chỉnh **BẮT BUỘC PHẢI TEST BẰNG CÁC BẢN GHI TEST TRƯỚC** khi hoàn tất và bàn giao.

### Quy trình kiểm thử chuẩn (Standard CRUD Test Workflow):
1. **Kiểm thử THÊM MỚI (Create Test):**
   - Tạo 1 bản ghi thử nghiệm với tiền tố nhận diện `[TEST]` (ví dụ: `[TEST] Game Demo`, `[TEST] Tin Tức Demo`, `[TEST] Thể Loại Demo`, `test_user_demo`).
   - Kiểm tra dữ liệu được ghi nhận chính xác vào bảng MySQL tương ứng thông qua Prepared Statements.
   - Kiểm tra thông báo flash message thành công và chuyển hướng giao diện chuẩn xác.

2. **Kiểm thử CHỈNH SỬA (Update Test):**
   - Chỉnh sửa thông tin bản ghi test vừa tạo (ví dụ đổi tên thành `[TEST - ĐÃ CẬP NHẬT]`, thay đổi giá, đổi email, đổi mô tả...).
   - Xác minh dữ liệu mới đã được cập nhật chính xác trong CSDL và phản ánh ngay lập tức trên giao diện.

3. **Kiểm thử XÓA (Delete / Soft Delete Test):**
   - Thực hiện thao tác xóa bản ghi test.
   - Đối với tài khoản người dùng: Kiểm tra cơ chế **Xóa Mềm (Soft Delete)** chuyển cờ `isdelete = 1`, không xóa vật lý bản ghi, đảm bảo không vi phạm ràng buộc khóa ngoại với đơn hàng.
   - Đối với các phân hệ khác (Game, Tin tức, Thể loại, Studio): Kiểm tra cơ chế xóa an toàn, bảo đảm dọn dẹp sạch bản ghi test và không ảnh hưởng tới dữ liệu gốc ban đầu.

4. **Nghiệm thu thực tế trên trình duyệt:**
   - Sử dụng công cụ kiểm thử trình duyệt (Browser Subagent) hoặc xác minh trực tiếp để chụp ảnh/ghi nhận kết quả thành công ở cả Chế độ Sáng (Light Mode) và Chế độ Tối (Dark Mode).

---

## 2. CÁC QUY TẮC HỆ THỐNG BỔ TRỢ
- **Ngôn ngữ giao diện:** 100% Tiếng Việt có dấu thuần, chuẩn sư phạm, dễ hiểu cho sinh viên HUBT.
- **Dữ liệu Games & Tin tức:** Giữ nguyên tên gốc và bài viết tiếng Anh chuẩn của ngành game.
- **Bảo mật:** 100% PDO Prepared Statements, mật khẩu băm BCrypt, chặn xóa hoặc hạ quyền tài khoản `admin`.
