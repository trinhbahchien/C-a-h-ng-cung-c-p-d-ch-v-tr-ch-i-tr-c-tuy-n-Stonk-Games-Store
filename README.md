# STONK GAMES STORE - CỔNG PHÂN PHỐI GAME BẢN QUYỀN & TIN TỨC TRÒ CHƠI
> **Đề tài đồ án:** Xây Dựng Website Kinh Doanh Game Bản Quyền & Cổng Tin Tức Gaming  
> **Kiến trúc phát triển:** Pure PHP MVC | MySQL PDO Singleton | Vanilla CSS & JavaScript | XAMPP  
> **Đơn vị đào tạo:** Trường Đại Học Kinh Doanh và Công Nghệ Hà Nội (HUBT)  
> **Khoa:** Công Nghệ Thông Tin  

---

## 1. TÍNH TƯƠNG THÍCH & ĐẢM BẢO HOẠT ĐỘNG TRÊN MÁY TÍNH KHÁC

Dự án được thiết kế theo tiêu chuẩn **Zero-Configuration Portability** (Khả năng di chuyển giữa các máy tính độc lập mà không phát sinh lỗi):
- **100% Pure PHP MVC:** Không dùng Composer, không dùng npm/Node.js, không sợ thiếu thư viện `vendor` hay `node_modules` khi sao chép sang máy khác.
- **Tự động nhận diện đường dẫn (Dynamic Path & URL):** 
  - `ROOT_PATH` tự nhận diện thông qua `dirname(__DIR__, 2)`, bất kể đặt ở ổ đĩa `C:`, `D:`, `E:`.
  - `BASE_URL` tự động bóc tách từ `SCRIPT_NAME` trên máy chủ web, tương thích mọi tên thư mục.
- **Thông số kết nối CSDL chuẩn mặc định XAMPP:** `localhost`, `port: 3306`, `user: root`, `password: ''`.
- **Tài nguyên ảnh & media độc lập:** Toàn bộ ảnh bìa, avatar, logo được lưu trữ cục bộ trong `public/assets/`, không phụ thuộc vào đường dẫn máy ban đầu.

---

## 2. HƯỚNG DẪN CÀI ĐẶT & CHẠY TRÊN MÁY MỚI (3 BƯỚC NHANH CHÓNG)

### Yêu cầu môi trường:
- Cài đặt phần mềm **XAMPP** (Hỗ trợ PHP 7.4, 8.0, 8.1, 8.2 trở lên).
- Khởi động 2 dịch vụ **Apache** và **MySQL** trên XAMPP Control Panel.

### Các bước thực hiện:

#### Bước 1: Sao chép mã nguồn
- Copy toàn bộ thư mục `StonkGamesStore` dán vào thư mục `htdocs` của XAMPP:
  ```
  C:\xampp\htdocs\StonkGamesStore
  ```

#### Bước 2: Nhập Cơ Sở Dữ Liệu (Import Database)
1. Mở trình duyệt web truy cập: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Chọn thẻ **Import (Nhập)** trên thanh menu trên cùng.
3. Nhấn **Choose File (Chọn tệp)** và chọn file `database.sql` nằm ngay tại thư mục gốc của dự án:
   ```
   C:\xampp\htdocs\StonkGamesStore\database.sql
   ```
   *(Hoặc file tại: `C:\xampp\htdocs\StonkGamesStore\docs\stonk_games_store.sql`)*
4. Cuộn xuống cuối trang và nhấn nút **Import (Nhập / Thực hiện)**.  
   *(Hệ thống sẽ tự động tạo cơ sở dữ liệu `stonk_games_store` và nạp toàn bộ 10 bảng dữ liệu hoàn chỉnh).*

#### Bước 3: Mở ứng dụng trên trình duyệt
- **Trang Cửa Hàng (Storefront):**
  [http://localhost/StonkGamesStore](http://localhost/StonkGamesStore)
- **Trang Quản Trị Hệ Thống (Admin Portal):**
  [http://localhost/StonkGamesStore/admin](http://localhost/StonkGamesStore/admin)

---

## 3. THÔNG TIN TÀI KHOẢN ĐĂNG NHẬP SẴN

| Vai trò (Role) | Tên đăng nhập (Username) | Mật khẩu (Password) | Chức năng chính |
| :--- | :--- | :--- | :--- |
| **Quản Trị Viên (Admin)** | `admin` | `123456` | Toàn quyền Dashboard, Quản lý Game, Tin tức, Thể loại, Studio, Người dùng, Đơn hàng |
| **Biên Tập Viên (Admin)** | `moderator_huy` | `123456` | Quản trị nội dung và duyệt bài |
| **Khách Hàng (Gamer)** | `gamer01` | `123456` | Mua hàng, Giỏ hàng, Thanh toán VietQR, Quản lý CD-Key đã mua |
| **Khách Hàng (Gamer)** | `hoang_long_pro` | `123456` | Trải nghiệm mua sắm game |

---

## 4. DANH MỤC TÀI LIỆU DỰ ÁN (DOCUMENTATION)

Tất cả tài liệu kỹ thuật được lưu trong thư mục `docs/`:
1. [docs/SYSTEM_TEST_CASES.md](docs/SYSTEM_TEST_CASES.md): Kịch bản kiểm thử toàn diện 6 giai đoạn (40 Test Cases đạt 100% PASSED).
2. [docs/PROJECT_ARCHITECTURE.md](docs/PROJECT_ARCHITECTURE.md): Kiến trúc Pure PHP MVC, sơ đồ Router, Singleton CSDL.
3. [docs/STAGE_DEVELOPMENT.md](docs/STAGE_DEVELOPMENT.md): Nhật ký phát triển chi tiết từ Giai đoạn 1 đến Giai đoạn 6.
4. [docs/BAO_CAO_DO_AN_REQUIREMENTS.md](docs/BAO_CAO_DO_AN_REQUIREMENTS.md): Khung đề cương chuẩn viết Báo cáo đồ án HUBT.
