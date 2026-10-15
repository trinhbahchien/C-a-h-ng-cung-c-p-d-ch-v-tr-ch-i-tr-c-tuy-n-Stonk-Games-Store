# DANH SÁCH TÀI KHOẢN HỆ THỐNG (USERS & CREDENTIALS)
**Đồ án:** Website Kinh Doanh Game Bản Quyền & Tin Tức Trò Chơi (Stonk Games Store)  
**Kiến trúc:** Pure PHP MVC - Tương thích XAMPP Windows  
**Cơ sở dữ liệu:** MariaDB / MySQL (`stonk_games_store`)  
**Mật khẩu mặc định cho toàn bộ tài khoản:** `123456`

---

## 1. TÀI KHOẢN QUẢN TRỊ VIÊN (ADMIN & MODERATOR)

Những tài khoản này có quyền truy cập vào **Trung Tâm Quản Trị** `/admin` để thực hiện toàn bộ chức năng CRUD (Games, Tin tức, Thể loại, Studio, Người dùng, Đơn hàng).

| Mã | Tên Đăng Nhập (Username) | Mật Khẩu | Vai Trò (Role) | Email | Mục Đích Sử Dụng |
| :---: | :--- | :--- | :---: | :--- | :--- |
| 1 | `admin` | `123456` | `admin` | `admin@stonkgames.vn` | Quản trị viên tối cao (Toàn quyền hệ thống, được khóa bảo vệ không cho sửa role/xóa) |
| 2 | `moderator_huy` | `123456` | `admin` | `huy.mod@stonkgames.vn` | Kiểm duyệt viên nội dung và quản lý kho game |

---

## 2. TÀI KHOẢN NGƯỜI CHƠI (REGISTERED GAMERS)

Những tài khoản này đại diện cho các game thủ đã đăng ký tài khoản, dùng để đăng nhập mua game trên Cửa hàng, quản lý giỏ hàng, viết đánh giá review và nhận mã CD-Key bản quyền.

| Mã | Tên Đăng Nhập (Username) | Mật Khẩu | Vai Trò (Role) | Email | Ghi Chú Hoạt Động |
| :---: | :--- | :--- | :---: | :--- | :--- |
| 3 | `gamer01` | `123456` | `user` | `gamer01@gmail.com` | Game thủ tích cực (Đã mua Elden Ring, đánh giá 5 sao) |
| 4 | `hoang_long_pro` | `123456` | `user` | `long.hoang@gmail.com` | Game thủ hardcore (Đã mua Cyberpunk 2077) |
| 5 | `trang_streamer` | `123456` | `user` | `trang.gaming@hotmail.com` | Streamer (Đã mua God of War Ragnarok) |
| 6 | `cyber_ninja99` | `123456` | `user` | `ninja99@yahoo.com` | Người chơi thể thao (Đã mua FC 24) |
| 7 | `dark_souls_fan` | `123456` | `user` | `tuan.darksouls@outlook.com` | Tín đồ Souls-like (Đã mua Resident Evil 4 Remake) |
| 8 | `minh_tri_fps` | `123456` | `user` | `tri.fps@gmail.com` | Game thủ bắn súng FPS (Đã mua GTA V, đánh giá CS2) |
| 9 | `cloud_strife_vn` | `123456` | `user` | `cloud.vn@gmail.com` | Người chơi RPG (Đã mua Red Dead Redemption 2) |
| 10 | `geralt_rivia` | `123456` | `user` | `geralt.witcher@gmail.com` | Người chơi The Witcher 3 Wild Hunt |
| 11 | `lan_anh_cozy` | `123456` | `user` | `lananh.cozy@gmail.com` | Người chơi Baldur's Gate 3 |
| 12 | `viet_anh_hardcore` | `123456` | `user` | `vietanh.gamer@gmail.com` | Người chơi Sekiro Shadows Die Twice |

---

## 3. HƯỚNG DẪN ĐĂNG NHẬP VÀ PHÂN QUYỀN

### 3.1. Đăng nhập quản trị (Admin Login)
1. Truy cập đường dẫn: `http://localhost/StonkGamesStore/auth/login` (hoặc bấm nút "Quản Trị Admin" trên Header).
2. Nhập thông tin:
   - **Tên đăng nhập:** `admin`
   - **Mật khẩu:** `123456`
3. Hệ thống tự động nhận diện quyền `admin` và điều hướng trực tiếp đến Dashboard: `http://localhost/StonkGamesStore/admin`.

### 3.2. Đăng nhập người dùng mua hàng (User Login)
1. Truy cập đường dẫn: `http://localhost/StonkGamesStore/auth/login`.
2. Nhập thông tin:
   - **Tên đăng nhập:** `gamer01`
   - **Mật khẩu:** `123456`
3. Hệ thống điều hướng về Trang chủ để bắt đầu chọn game, thêm vào giỏ hàng và thanh toán.

---

## 4. CƠ CHẾ BẢO MẬT & BẢO VỆ TÀI KHOẢN
- **Mã hóa mật khẩu:** Lưu trữ trong bảng `users` được băm bằng thuật toán **BCrypt** (`PASSWORD_BCRYPT` chuẩn PHP 8.x) với cost factor = 10.
- **Khóa bảo vệ tài khoản `admin`:**
  - Hệ thống áp dụng cơ chế khóa cố định quyền cho tài khoản `admin` (ID 1), không thể bị hạ quyền xuống `user`.
  - Trên giao diện quản trị, ô chọn quyền của tài khoản `admin` sẽ tự động bị vô hiệu hóa (disabled) kèm ghi chú giải thích.
  - Nút "Xóa" tài khoản `admin` bị ẩn hoàn toàn để đảm bảo an toàn tuyệt đối cho hệ thống.
