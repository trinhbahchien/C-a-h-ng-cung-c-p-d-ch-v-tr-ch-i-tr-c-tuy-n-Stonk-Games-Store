<?php
/**
 * Test HTTP các trang Giai đoạn 5
 */

$baseUrl = 'http://localhost/StonkGamesStore';

$urls = [
    'Catalog Mặc Định'          => $baseUrl . '/catalog',
    'Catalog Tìm Kiếm Game'     => $baseUrl . '/catalog?search=Elden',
    'Catalog Tìm Kiếm Studio'   => $baseUrl . '/catalog?search=FromSoftware',
    'Catalog Lọc Giá <700k'     => $baseUrl . '/catalog?max_price=700000',
    'Catalog Lọc Free'          => $baseUrl . '/catalog?free=1',
    'Catalog Sắp Xếp Bán Chạy'  => $baseUrl . '/catalog?sort=bestselling',
    'News Hub Tất Cả'           => $baseUrl . '/news',
    'News Hub Thông Báo Hệ Thống' => $baseUrl . '/news?type=system',
    'News Hub Cập Nhật Game'    => $baseUrl . '/news?type=game',
    'News Hub Chuyên Mục Thể Loại' => $baseUrl . '/news?type=genre',
    'News Hub Tìm Kiếm Từ Khóa' => $baseUrl . '/news?search=Summer',
    'News Chi Tiết Bài #1'      => $baseUrl . '/news/detail?id=1',
    'News Chi Tiết Bài #2'      => $baseUrl . '/news/detail?id=2',
    'News Test Endpoint'        => $baseUrl . '/public/run_news_test.php',
];

echo "============================================================\n";
echo "   KIỂM THỬ TRUY CẬP HTTP CÁC TRANG GIAI ĐOẠN 5             \n";
echo "============================================================\n\n";

$allPassed = true;

foreach ($urls as $name => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode === 200) {
        $len = strlen($response);
        echo "[200 OK] {$name} ({$len} bytes)\n";
    } else {
        echo "[ERROR {$httpCode}] {$name} - URL: {$url}\n";
        if ($error) echo "   Lỗi cURL: {$error}\n";
        $allPassed = false;
    }
}

echo "\n============================================================\n";
if ($allPassed) {
    echo ">> KẾT QUẢ: 100% CÁC TRANG GIAI ĐOẠN 5 PHẢN HỒI HTTP 200 OK!\n";
} else {
    echo ">> KẾT QUẢ: CÓ LỖI XẢY RA TRONG QUÁ TRÌNH KIỂM THỬ!\n";
}
echo "============================================================\n";
