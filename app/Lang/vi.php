<?php
/**
 * Stonk Games Store - Từ điển ngôn ngữ Tiếng Việt (Chỉ áp dụng Giao diện Hệ thống)
 * Quy tắc:
 * 1. Tiếng Việt có dấu chuẩn xác cho toàn bộ nút bấm, tiêu đề và nhãn hệ thống.
 * 2. Nội dung Games và Tin tức giữ nguyên Tiếng Anh gốc (không dịch tự động méo mó).
 * 3. Tuyệt đối không dùng icon hoặc emoji.
 */

return [
    // Header & Navigation
    'nav_home'          => 'Trang Chủ',
    'nav_catalog'       => 'Cửa Hàng',
    'nav_rankings'      => 'Bảng Xếp Hạng',
    'nav_news'          => 'Tin Tức',
    'nav_cart'          => 'Giỏ Hàng',
    'nav_admin'         => 'Quản Trị Admin',
    'nav_login'         => 'Đăng Nhập',
    'nav_register'      => 'Đăng Ký',
    'nav_logout'        => 'Đăng Xuất',
    'nav_search_placeholder' => 'Tìm kiếm theo tên game hoặc nhà phát triển...',

    // Common Buttons & Actions
    'btn_view_detail'   => 'Xem Chi Tiết',
    'btn_add_to_cart'   => 'Thêm Vào Giỏ',
    'btn_buy_now'       => 'Mua Ngay',
    'btn_checkout'      => 'Thanh Toán',
    'btn_apply'         => 'Áp Dụng',
    'btn_filter'        => 'Lọc Dữ Liệu',
    'btn_save'          => 'Lưu Thông Tin',
    'btn_cancel'        => 'Hủy Bỏ',
    'btn_delete'        => 'Xóa',
    'btn_edit'          => 'Chỉnh Sửa',
    'btn_create_new'    => 'Thêm Mới',
    'btn_back'          => 'Quay Lại',
    'btn_copy_key'      => 'Sao Chép Mã CD-Key',

    // Home Storefront Labels
    'home_stage_ready'  => 'GIAI ĐOẠN 1: NỀN TẢNG MVC VÀ CƠ SỞ DỮ LIỆU ĐÃ SẴN SÀNG',
    'home_stage_desc'   => 'Bộ khung Pure PHP MVC, kết nối PDO Singleton (100% Prepared Statements) và CSDL 10 bảng chuẩn hóa 3NF hỗ trợ UTF-8 Unicode hoạt động hoàn hảo.',
    'home_badge_games'  => 'Games trong CSDL',
    'home_badge_users'  => 'Tài khoản',
    'home_badge_driver' => 'PDO Driver',
    'home_spotlight_sub'=> 'Khám Phá Siêu Phẩm',
    'home_spotlight_title' => 'TỰA GAME NỔI BẬT ĐÃ NẠP TỪ CƠ SỞ DỮ LIỆU',
    'home_view_all'     => 'Xem tất cả trong Cửa hàng',
    'home_free_to_play' => 'CHƠI MIỄN PHÍ',
    'home_spotlight_tag'=> 'Nổi Bật',

    // Game Detail Labels
    'game_developer'    => 'Nhà Phát Triển',
    'game_release_date' => 'Ngày Phát Hành',
    'game_categories'   => 'Thể Loại',
    'game_sys_req'      => 'Cấu Hình Hệ Thống',
    'game_req_min'      => 'Cấu Hình Tối Thiểu',
    'game_req_rec'      => 'Cấu Hình Đề Nghị',
    'game_os'           => 'Hệ Điều Hành',
    'game_cpu'          => 'Bộ Xử Lý (CPU)',
    'game_ram'          => 'Bộ Nhớ (RAM)',
    'game_gpu'          => 'Card Đồ Họa (GPU)',
    'game_storage'      => 'Dung Lượng Trống',
    'game_reviews'      => 'Đánh Giá Cộng Đồng',
    'game_rating'       => 'Điểm Đánh Giá',
    'game_write_review' => 'Viết Đánh Giá Của Bạn',

    // Catalog & Filters
    'catalog_title'     => 'Cửa Hàng Trò Chơi',
    'catalog_filter_genre' => 'Lọc Theo Thể Loại',
    'catalog_filter_price' => 'Mức Giá',
    'catalog_filter_all'   => 'Tất Cả',
    'catalog_price_free'   => 'Miễn Phí',
    'catalog_price_under_500k' => 'Dưới 500.000 đ',
    'catalog_price_under_1m'   => 'Dưới 1.000.000 đ',
    'catalog_sort_by'      => 'Sắp Xếp Theo',
    'catalog_sort_newest'  => 'Mới Nhất',
    'catalog_sort_price_asc'  => 'Giá Tăng Dần',
    'catalog_sort_price_desc' => 'Giá Giảm Dần',
    'catalog_sort_bestseller' => 'Bán Chạy Nhất',

    // Rankings (Steam Charts)
    'rank_title'        => 'Bảng Xếp Hạng Doanh Số (Steam Charts)',
    'rank_tab_top_sold' => 'Top Bán Chạy Toàn Sàn',
    'rank_tab_by_genre' => 'Top Theo Thể Loại',
    'rank_col_rank'     => 'Hạng',
    'rank_col_game'     => 'Tựa Game',
    'rank_col_sold'     => 'Bán Ra',
    'rank_col_refund'   => 'Hoàn Tiền',
    'rank_col_positive' => 'Đánh Giá Tích Cực',
    'rank_col_price'    => 'Giá Hiện Tại',

    // News Hub
    'news_hub_title'    => 'Cổng Thông Tin Tin Tức Game',
    'news_tab_all'      => 'Tất Cả Bài Viết',
    'news_tab_system'   => 'Thông Báo Hệ Thống',
    'news_tab_game'     => 'Tin Theo Game',
    'news_tab_genre'    => 'Tin Theo Thể Loại',
    'news_read_more'    => 'Đọc Tiếp',

    // Cart & Checkout
    'cart_title'        => 'Giỏ Hàng Của Bạn',
    'cart_empty'        => 'Giỏ hàng đang trống.',
    'cart_col_product'  => 'Sản Phẩm',
    'cart_col_price'    => 'Giá',
    'cart_total'        => 'Tổng Tiền',
    'checkout_title'    => 'Thanh Toán Đơn Hàng',
    'checkout_customer_name'  => 'Họ và Tên Người Nhận',
    'checkout_customer_email' => 'Email Nhận Mã CD-Key',
    'checkout_vietqr_desc'    => 'Quét mã VietQR bằng bất kỳ ứng dụng ngân hàng để thanh toán tự động.',
    'checkout_confirm'  => 'Xác Nhận Thanh Toán Và Nhận Key',
    'checkout_success_title' => 'Thanh Toán Thành Công',
    'checkout_cdkey_desc'    => 'Mã CD-Key bản quyền đã được tạo và lưu vào đơn hàng của bạn:',

    // Admin Labels
    'admin_dashboard'   => 'Bảng Điều Khiển Tổng Quan',
    'admin_manage_games'=> 'Quản Lý Games',
    'admin_manage_news' => 'Quản Lý Tin Tức',
    'admin_manage_cats' => 'Quản Lý Thể Loại',
    'admin_manage_devs' => 'Quản Lý Studio',
    'admin_manage_users'=> 'Quản Lý Tài Khoản',
    'admin_manage_orders'=> 'Quản Lý Đơn Hàng',

    // Footer
    'footer_copyright'  => 'Đồ án Lập trình Web - ĐH Kinh Doanh và Công Nghệ Hà Nội (HUBT).',
    'footer_design'     => 'Thiết kế giao diện Gaming phong cách Steam và Epic Games Store.'
];
