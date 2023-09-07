<?php
ini_set('max_execution_time', 99999); // Thời gian chạy tối đa là 300 giây
ini_set('memory_limit', '256M'); // Giới hạn bộ nhớ là 256 MB

$servername = "localhost";
$username = "kuqpxtvxhosting_minhngoc1";
$password = "kuqpxtvxhosting_minhngoc1";
$dbname = "kuqpxtvxhosting_minhngoc1";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

function extract_filename_from_src($src) {
    return basename($src);
}

function update_image_src($content) {
    $pattern = '/<img[^>]*src=[\'"]([^\'"]+)[\'"]/i';
    preg_match_all($pattern, $content, $matches);

    if (!empty($matches[0])) {
        for ($i = 0; $i < count($matches[0]); $i++) {
            $old_src = $matches[1][$i];
            $new_src = "/images/04/" . extract_filename_from_src($old_src);
            $old_img_tag = $matches[0][$i];
            $new_img_tag = str_replace($old_src, $new_src, $old_img_tag);
            $content = str_replace($old_img_tag, $new_img_tag, $content);
        }
    }

    return $content;
}


$sql = "SELECT goods_id, goods_desc FROM nb_goods";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $updated_goods_desc = update_image_src($row['goods_desc']);
        $update_sql = "UPDATE nb_goods SET goods_desc = ? WHERE goods_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $updated_goods_desc, $row['goods_id']);
        $stmt->execute();
        $stmt->close();
    }
    echo "Cập nhật thành công!";
} else {
    echo "Không tìm thấy dữ liệu trong bảng nb_goods.";
}

$conn->close();
?>
