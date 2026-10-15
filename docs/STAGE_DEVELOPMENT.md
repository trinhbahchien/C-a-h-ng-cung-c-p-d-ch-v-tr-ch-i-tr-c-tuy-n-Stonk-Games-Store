# KE HOACH TRIEN KHAI DU AN THEO GIAI DOAN (STAGE DEVELOPMENT)
> **Dự án:** Game Shop - Website Kinh Doanh Game & Tin Tức (Steam & Epic Games Style)  
> **Kiến trúc:** Pure PHP MVC | MySQL PDO | Vanilla CSS & JS | XAMPP

---

## BANG TONG QUAN CAC GIAI DOAN (ROADMAP)

| Giai Đoạn | Tên Giai Đoạn | Trọng Tâm Phát Triển | Trạng Thái |
| :---: | :--- | :--- | :---: |
| **STAGE 1** | **Nền Tảng MVC & Database** | CSDL 10 bảng hoàn chỉnh + Bộ khung Core MVC (PDO Singleton, Router, Base Model/Controller) | [HOÀN THÀNH] |
| **STAGE 2** | **Trung Tâm Quản Trị Admin (Full CRUD)** | Giao diện Admin Dashboard, CRUD Games (ảnh, cấu hình, trailer), CRUD Tin tức, CRUD Studio & Thể loại, Quản lý User | [HOÀN THÀNH] |
| **STAGE 3** | **Trang Chủ & Master Layout Gaming** | Header/Footer gaming, Đa ngôn ngữ (VI/EN), Dark/Light theme, Hero Spotlight Carousel, Top Sellers, New Releases, Free Games | [HOÀN THÀNH] |
| **STAGE 4** | **Trang Chi Tiết Game & Bảng Xếp Hạng** | Trang Game (Trailer, Screenshots, Bảng Cấu hình, Tags, Reviews, Patch notes) + Trang Bảng xếp hạng Steam Charts (`/rankings`) | [HOÀN THÀNH] |
| **STAGE 5** | **Tìm Kiếm & Bộ Lọc Epic Games + Tin Tức Hub** | Cửa hàng (`/catalog`) với Search kép (Game/Studio) & Bộ lọc sidebar + Trang tin tức (`/news`) phân loại đa cấp | [HOÀN THÀNH] |
| **STAGE 6** | **Giỏ Hàng, Thanh Toán Cấp Key & Đóng Gói** | Giỏ hàng, Trang thanh toán VietQR demo, Tự động cấp mã CD-Key lưu đơn hàng, Kiểm thử tổng thể & Hướng dẫn cài đặt | [HOÀN THÀNH] |

---

## QUY TẮC PHÁT TRIỂN & TIÊU CHUẨN KIỂM THỬ BẮT BUỘC (MANDATORY TESTING RULE)

> [!IMPORTANT]
> **LUẬT BẮT BUỘC:** Bất kỳ chức năng quản lý nào chứa các thao tác **Thêm, Sửa, Xóa (Create - Update - Delete / Soft Delete)** sau khi build hoàn chỉnh **BẮT BUỘC PHẢI TEST BẰNG CÁC BẢN GHI TEST TRƯỚC** khi nghiệm thu:
> 1. **Thêm (Create Test):** Tạo 1 bản ghi thử nghiệm (tiền tố `[TEST]`) để xác nhận dữ liệu được lưu đúng vào MySQL qua Prepared Statements và flash message thành công.
> 2. **Sửa (Update Test):** Cập nhật dữ liệu bản ghi thử nghiệm để kiểm chứng tính toàn vẹn dữ liệu và logic xử lý form.
> 3. **Xóa (Delete / Soft Delete Test):** Thực hiện xóa bản ghi thử nghiệm (xóa mềm `isdelete = 1` đối với User; xóa an toàn đối với Game/News/Category/Studio) để kiểm tra không vi phạm ràng buộc khóa ngoại và bảo toàn dữ liệu gốc.
> 4. **Xác minh giao diện & CSDL:** Kiểm tra trực quan kết quả trên trình duyệt (ở cả Light Mode & Dark Mode) và kiểm tra dữ liệu thực tế trong CSDL.

---

## CHI TIẾT TỪNG GIAI ĐOẠN

---

### STAGE 1: NEN TANG MVC & CO SO DU LIEU (DATABASE FOUNDATION)
*Mục tiêu: Thiết lập toàn bộ cấu trúc thư mục MVC chuẩn, cơ chế kết nối PDO an toàn và khởi tạo cơ sở dữ liệu với đầy đủ dữ liệu mẫu phong phú.*

#### 1. Công việc cần thực hiện (Checklist):
- [x] **Task 1.1:** Viết file SQL CSDL mới `docs/stonk_games_store.sql` gồm 10 bảng:
  - `users` (tài khoản admin mặc định: `admin` / `123456` đã mã hóa BCrypt).
  - `developers` (FromSoftware, CD Projekt Red, Santa Monica, EA Sports, miHoYo, Capcom).
  - `categories` (Hành động, Nhập vai RPG, Thế giới mở, Thể thao, Chiến thuật, Miễn phí, Kinh dị).
  - `games` (Elden Ring, Cyberpunk 2077, God of War Ragnarok, FC 24, Genshin Impact, Resident Evil 4).
  - `game_categories` (bảng liên kết nhiều-nhiều).
  - `system_requirements` (cấu hình Tối thiểu & Đề nghị cho từng game).
  - `game_analytics` (số lượng bán ra, số lượng hoàn trả, % hoàn trả, % tích cực).
  - `news` (tin tức web, tin theo game, tin theo thể loại).
  - `orders` & `order_items` (lưu đơn hàng và mã CD-Key).
- [x] **Task 1.2:** Tạo file cấu hình `app/Config/config.php` (DB credentials, BASE_URL `http://localhost/StonkGamesStore`).
- [x] **Task 1.3:** Xây dựng `app/Core/Database.php` (Singleton Pattern, bắt lỗi PDO an toàn 100% Prepared Statements).
- [x] **Task 1.4:** Xây dựng `app/Core/App.php` (Front Controller Router phân tích Clean URL hoặc query param `?url=...` cho 24 routes).
- [x] **Task 1.5:** Xây dựng `app/Core/Controller.php` (Base Controller: nạp `view()`, `model()`, `json()`, `redirect()`, session).
- [x] **Task 1.6:** Xây dựng `app/Core/Model.php` (Base Model: các phương thức truy vấn tiện lợi `query()`, `fetchAll()`, `fetch()`, `execute()`).
- [x] **Task 1.7:** Tạo `public/index.php` và `.htaccess` làm cổng vào duy nhất kết nối toàn bộ hệ thống.

#### 2. Tiêu chí nghiệm thu (Acceptance Criteria):
- Chạy file `docs/stonk_games_store.sql` vào MySQL không có lỗi cú pháp, đã nạp thành công 10 bảng.
- Truy cập `http://localhost/StonkGamesStore` hiển thị thông báo kết nối PDO thành công và router hoạt động trơn tru.

---

### STAGE 2: TRUNG TAM QUAN TRI ADMIN (ADMIN PORTAL - FULL CRUD)
*Mục tiêu: Đưa phân hệ Admin làm trọng tâm, giúp người quản trị dễ dàng thêm, sửa, xóa Games, Tin tức, Thể loại và Tài khoản mà không cần can thiệp database.*

#### 1. Công việc cần thực hiện (Checklist):
- [x] **Task 2.1:** Xây dựng `app/Controllers/AdminController.php` & Auth Middleware kiểm tra quyền admin.
- [x] **Task 2.2:** Xây dựng Layout Admin `app/Views/layouts/admin.php` và Sidebar điều hướng chuyên nghiệp.
- [x] **Task 2.3:** Trang Dashboard tổng quan: số liệu Game, Bài viết, Doanh thu, User.
- [x] **Task 2.4:** **CRUD Games:**
  - Danh sách game (ảnh, tên, giá, % sale, studio, lượt bán).
  - Form Thêm game mới (nhập tên, giá, giảm giá, trailer YouTube, upload ảnh bìa, chọn studio, chọn thể loại, nhập cấu hình Tối thiểu & Đề nghị).
  - Form Sửa game.
  - Xóa game an toàn.
- [x] **Task 2.5:** **CRUD Tin tức:**
  - Danh sách bài viết.
  - Form Đăng bài mới (chọn loại: tin web / tin game / tin thể loại, upload ảnh, viết nội dung).
  - Sửa và xóa bài viết.
- [x] **Task 2.6:** **CRUD Thể loại & Studio:** Thêm/sửa/xóa Categories và Developers.
- [x] **Task 2.7:** **Quản lý Tài khoản & Đơn hàng:** 
  - Xem danh sách tài khoản, đổi quyền, xem đơn hàng và mã CD-Key.
  - **Cơ chế Xóa mềm (Soft Delete) với cột `isdelete TINYINT(1)`:** Khi xóa tài khoản không xóa bản ghi vật lý khỏi CSDL mà chỉ chuyển `isdelete = 1` nhằm bảo toàn toàn vẹn dữ liệu đơn hàng và lịch sử thanh toán.
  - Bảo vệ tuyệt đối tài khoản quản trị viên `admin` (khóa cứng role và chặn thao tác xóa).

#### 2. Tiêu chí nghiệm thu:
- Admin đăng nhập thành công vào `/admin`. Thử thêm 1 game mới và 1 bài tin tức mới thành công, dữ liệu lưu chuẩn vào MySQL.

---

### STAGE 3: MASTER LAYOUT GAMING VA TRANG CHU (STOREFRONT)
*Mục tiêu: Thiết kế giao diện phong cách Steam/Epic Games đỉnh cao với Dark/Light Theme linh hoạt, Menu người dùng tích hợp và Trang chủ sinh động.*

#### 1. Công việc cần thực hiện (Checklist):
- [x] **Task 3.1:** Xây dựng CSS Gaming Design System (màu Steam Navy, Neon Cyan, Glassmorphism, Responsive, hỗ trợ mượt mà cả Dark Mode & Light Mode).
- [x] **Task 3.2:** Xây dựng Navigation Bar thông minh:
  - Logo GameShop, Menu điều hướng (Cửa hàng, Bảng xếp hạng, Tin tức).
  - Nút chuyển đổi Dark / Light Theme với icon bóng đèn FontAwesome 6 (`fa-regular fa-lightbulb` & `fa-solid fa-lightbulb`).
  - **Menu Người Dùng Tích Hợp (User Dropdown):**
    - Giấu nút Giỏ hàng và nút Quản trị độc lập vào trong menu tài khoản.
    - Hiển thị huy hiệu thông báo số lượng game trong giỏ ngay góc trên cùng bên phải tên người dùng (Shopping Cart Badge).
    - Tùy chọn "Trang Quản Trị Hệ Thống" chỉ hiển thị với tài khoản có quyền `role = admin`.
- [x] **Task 3.3:** Xây dựng `app/Views/partials/footer.php` gaming:
  - Cấu trúc 4 cột chuẩn công nghiệp: Giới thiệu & Mạng xã hội, Khám phá cửa hàng, Thể loại phổ biến, Cam kết bản quyền & Cấp key tự động.
  - Thanh đối tác và phương thức thanh toán VietQR Napas247, Steam Key, Epic Games, SSL 256-Bit.
  - Bản quyền đồ án HUBT và bộ huy hiệu công nghệ Pure PHP MVC, PDO Singleton, Prepared Statements, MySQL 3NF.
  - Tích hợp hoàn hảo vào Layout chính [layouts/main.php](file:///c:/xampp/htdocs/StonkGamesStore/app/Views/layouts/main.php), tương thích Dark Mode & Light Mode.
- [x] **Task 3.4:** Xây dựng **Trang Chủ (`/`)**:
  - **Hero Spotlight Carousel:** Trình chiếu động phong cách Steam/Epic Games với 5 game nổi bật (`is_featured = 1`), hiệu ứng chuyển đổi mượt mà, thanh đếm thời gian tiến trình (Progress bar 5 giây), tự động xoay vòng và tạm dừng khi rê chuột.
  - **Thanh Điểm Nhấn Sàn (Benefits Bar):** 4 cam kết giá trị (Cấp CD-Key tức thì, VietQR 24/7, 100% bản quyền gốc, hỗ trợ game thủ 24/7 & bảo hành 1 đổi 1).
  - **Top Game Bán Chạy (Top Sellers - Steam Style):** Bảng xếp hạng 6 game bán chạy nhất với huy hiệu hạng #1 (Gold), #2 (Silver), #3 (Bronze), lượt bán, % đánh giá tích cực và nút thêm giỏ hàng.
  - **Games Mới Phát Hành (New Releases):** Lưới thẻ 6 game mới nhất kèm giá gốc, giá giảm, studio và nút mua.
  - **Games Miễn Phí (Free-to-Play):** Khung banner nổi bật trưng bày các tựa game `price = 0` (Dota 2, Genshin Impact, CS2) với nhãn **FREE TO PLAY** xanh neon rực rỡ.
  - **Tin tức nổi bật theo game & hệ thống:** 4 bài viết tin tức mới nhất từ cơ sở dữ liệu kèm ảnh thumbnail, huy hiệu phân loại và ngày phát hành.
- [x] **Task 3.5:** **Trang Quản Lý Thông Tin Người Dùng (`/profile`):**
  - Xem thông tin cá nhân: Tên đăng nhập, Vai trò tài khoản, Ngày tham gia, Trạng thái tài khoản.
  - Cho phép điều chỉnh địa chỉ Email nhận thông tin đơn hàng và mã kích hoạt game.
  - Đổi mật khẩu tài khoản an toàn với xác thực mật khẩu hiện tại (BCrypt hashing).
  - Hiển thị bảng tổng hợp lịch sử đơn hàng cá nhân đã mua kèm trạng thái và mã đơn.

---

### STAGE 4: TRANG CHI TIET GAME VA TRANG BANG XEP HANG (STEAM CHARTS)
*Mục tiêu: Cung cấp trải nghiệm khám phá sản phẩm chi tiết như Steam và trang bảng xếp hạng độc đáo.*

#### 1. Công việc cần thực hiện (Checklist):
- [x] **Task 4.1:** Xây dựng **Trang Chi Tiết Game (`/game/detail?id=...`)**:
  - Trình chiếu Video Trailer (YouTube Cinema Player) & Screenshots poster.
  - Hộp thông tin Buy Box (Giá gốc, giảm giá %, nút Thêm vào giỏ / Mua ngay, cam kết bản quyền).
  - **Bảng Cấu hình máy tính:** Đối chiếu chi tiết Tối thiểu vs Đề nghị (CPU, GPU, RAM, Ổ cứng, OS).
  - Thẻ Tags thể loại bấm được để lọc game cùng loại qua catalog.
  - Khu vực Đánh giá (Reviews): Điểm sao trung bình, % hài lòng Steam style và bình luận của người dùng + form gửi review mới.
  - Mục Tin tức/Cập nhật riêng của tựa game đang xem.
- [x] **Task 4.2:** Xây dựng **Trang Bảng Xếp Hạng (`/rankings`)**:
  - Tab 1: Bán chạy toàn sàn (Hạng 1 Vàng, Hạng 2 Bạc, Hạng 3 Đồng trên bục Podium).
  - Tab 2: Top game theo từng danh mục thể loại (Hành động, RPG, Thể thao...).
  - Cột hiển thị: Lượt bán, % Hoàn trả, % Tích cực, Giá tiền.
  - Bấm vào game mở trực tiếp trang chi tiết.

---

### STAGE 5: CUA HANG (CATALOG) BO LOC EPIC STYLE VA TRANG TIN TUC HUB
*Mục tiêu: Cho phép người dùng tìm kiếm, lọc game thông minh và đọc tin tức theo nhiều chủ đề.*

#### 1. Công việc cần thực hiện (Checklist):
- [x] **Task 5.1:** Xây dựng **Trang Cửa Hàng & Bộ Lọc (`/catalog`)**:
  - Thanh tìm kiếm kép: Tìm theo Tên Game HOẶC Tên Studio/Nhà phát triển.
  - Bộ lọc Sidebar: Lọc theo Thể loại, Mức giá (Miễn phí, <300k, <700k, <1M, <1.5M, Giảm giá), Sắp xếp (Giá tăng/giảm, Mới nhất, Bán chạy, Đánh giá cao).
- [x] **Task 5.2:** Xây dựng **Trang Tin Tức Tổng Hợp (`/news`)**:
  - Bộ lọc Tab: Tất cả / Thông báo hệ thống (`system`) / Tin theo game (`game`) / Chuyên mục thể loại (`genre`).
  - Thanh tìm kiếm bài viết theo tiêu đề và nội dung.
  - Thẻ Hero Featured Article bài viết tiêu điểm nổi bật phía trên.
  - Lưới bài viết gaming cards đa cột, hiển thị badge phân loại, thời gian đăng, liên kết game.
  - Trang đọc bài viết chi tiết (`/news/detail?id=...` hoặc `?slug=...`) với typography chuẩn, hero cover sắc nét, hộp giới thiệu mua game (Buy Box mini card) nếu có liên kết game, và danh sách bài viết liên quan.
- [x] **Quy chuẩn kiểm thử CRUD (.agents/AGENTS.md):** Đã chạy tự động kịch bản Thêm Mới `[TEST]` -> Cập Nhật `[TEST - ĐÃ CẬP NHẬT]` -> Xóa An Toàn, đạt 100% Passed. Đã tích hợp nút test trực tiếp trên Web Test Center.

---

### STAGE 6: GIỎ HÀNG, THANH TOÁN CẤP KEY VÀ HOÀN THIỆN ĐỒ ÁN (COMPLETED)
*Mục tiêu: Hoàn thiện luồng mua hàng số, cấp CD-Key tự động và đóng gói đồ án.*

#### 1. Công việc đã thực hiện (Checklist Hoàn Thành 100%):
- [x] **Task 6.1:** Hoàn thiện Giỏ hàng (`/cart`):
  - Thêm game vào giỏ, xóa từng game, dọn sạch toàn bộ giỏ hàng (`/cart/clear`).
  - Tính toán chi tiết: Giá gốc niêm yết, số tiền tiết kiệm khuyến mãi và tổng tiền thanh toán thực tế.
  - Hỗ trợ nút "Mua Ngay (Thanh Toán Nhanh)" tại trang chi tiết game (`/game/detail?slug=...`) chuyển thẳng đến cổng thanh toán.
  - Huy hiệu số lượng giỏ hàng trên thanh điều hướng header (hỗ trợ cả tài khoản thành viên lẫn khách vãng lai).
- [x] **Task 6.2:** Trang Thanh toán (`/cart/checkout`):
  - Form nhập thông tin khách hàng nhận game (tự động điền dữ liệu nếu đã đăng nhập hoặc mua nhanh dạng khách).
  - Tự động sinh mã **VietQR** Napas 24/7 (MBBank) theo số tiền thực tế và mã giao dịch duy nhất `STONK*****`.
  - Hiển thị đầy đủ thông tin: Ngân hàng, Số tài khoản, Chủ tài khoản, Số tiền, Nội dung chuyển khoản kèm nút **Sao Chép 1-chạm** và thông báo Toast.
  - Tích hợp đồng hồ đếm ngược 15:00 phút mô phỏng phiên giao dịch ngân hàng thực tế.
  - Nút xác nhận thanh toán ➔ Lưu đơn hàng vào bảng `orders`, tự động sinh mã bản quyền **CD-Key** chuẩn 25 ký tự (`STONK-XXXX-YYYY-ZZZZ-WWWW`) lưu vào `order_items`, tự động đồng bộ thống kê doanh số `game_analytics`.
- [x] **Task 6.3:** Trang Cấp phép Bản quyền & Hóa đơn (`/cart/success`):
  - Thông báo chúc mừng giao dịch thành công, hiển thị đầy đủ thông tin hóa đơn điện tử.
  - Danh sách game đã mua hiển thị khung **CD-Key** nổi bật (chuẩn monospace, neon glow), nút Sao Chép nhanh và shortcut mở trực tiếp ứng dụng Steam (`steam://open/activateproduct`).
  - Hướng dẫn 3 bước kích hoạt game bản quyền trên Steam/Epic Games PC.
  - Nút In hóa đơn (`window.print()`), Xem tủ game cá nhân (`/profile`) và Mua sắm tiếp.
- [x] **Task 6.4:** Kiểm tra tương thích và tuân thủ quy chuẩn:
  - Kiểm thử tự động CRUD theo quy chuẩn nghiêm ngặt `.agents/AGENTS.md` thông qua kịch bản `tests/test_stage6_checkout.php` đạt 100% Passed (Thêm mới đơn test -> Kiểm tra CD-Key & Analytics -> Cập nhật trạng thái -> Xóa an toàn & Cascade Delete).
  - Nghiệm thu thực tế trên trình duyệt bằng Browser Subagent, đạt chất lượng thị giác cao ở cả Chế độ Sáng (Light Mode) và Chế độ Tối (Dark Mode).

