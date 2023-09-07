<?php
// Kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = "kuqpxtvxhosting_minhngoc1";
    $password = "kuqpxtvxhosting_minhngoc1";
    $dbname = "kuqpxtvxhosting_minhngoc1";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

function extract_all_image_src($content) {
    $pattern = '/<img[^>]*src=[\'"]([^\'"]+)[\'"]/i';
    preg_match_all($pattern, $content, $matches);

    $srcs = array();

    if (!empty($matches[1])) {
        foreach ($matches[1] as $src) {
            $decoded_src = rawurldecode($src);
            $srcs[] = $decoded_src;
        }
    }

    return $srcs;
}



$sql = "SELECT goods_id, goods_desc FROM nb_goods";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $image_srcs = extract_all_image_src($row['goods_desc']);
        foreach ($image_srcs as $image_src) {
            $insert_sql = "INSERT INTO nb_agency (agency_id,agency_name) VALUES (NULL, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("s", $image_src);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "Trích xuất và lưu thành công!";
} else {
    echo "Không tìm thấy dữ liệu trong bảng nb_goods.";
}

$conn->close();
?>
