<?php
ini_set('max_execution_time', 99999); // Thời gian chạy tối đa là 300 giây
ini_set('memory_limit', '256M'); // Giới hạn bộ nhớ là 256 MB

$servername = "localhost";
$username = "kuqpxtvxhosting_minhngoc1";
$password = "kuqpxtvxhosting_minhngoc1";
$dbname = "kuqpxtvxhosting_minhngoc1";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$limit = 1000; // Giới hạn số lượng ảnh được xử lý trong mỗi lần chạy

// Lấy tất cả các đường dẫn ảnh từ cột src trong bảng nb_images
$query = "SELECT agency_name FROM nb_agency LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

$counter = 0;

// Tải về và lưu ảnh vào thư mục img
if (mysqli_num_rows($result) > 0) {
    echo "<script>document.title = 'Đang tải ảnh...';</script>";
    while ($row = mysqli_fetch_assoc($result)) {
        $src = $row['agency_name'];
        $image_data = get_image_data_curl($src);
        $filename = basename($src);
        $path = "imgs/" . $filename;
        if (file_put_contents($path, $image_data)) {
            $counter++;
            echo "<script>document.title = 'Đã tải xuống {$counter} ảnh';</script>";
        } else {
            echo "<script>document.title = 'Lỗi khi tải xuống và lưu ảnh: {$filename}';</script>";
        }
        echo str_repeat(' ', 4096); // Đưa ra 4 KB khoảng trắng để đẩy kết quả ra màn hình
        flush();
        ob_flush();
    }

    echo "Đã tải xuống $counter ảnh";
} else {
    echo "Không tìm thấy dữ liệu ảnh";
}

// Đóng kết nối đến cơ sở dữ liệu
mysqli_close($conn);

function get_image_data_curl($url) {
$url = mb_convert_encoding($url, "UTF-8", "auto");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Lỗi tải ảnh: ' . curl_error($ch) . ' - URL: ' . $url . '<br>';
    }

    curl_close($ch);
    return $data;
}



?>
