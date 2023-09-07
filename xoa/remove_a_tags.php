<?php
ini_set('memory_limit', '128M');
function remove_a_tags($input) {
    $decoded_input = html_entity_decode($input, ENT_QUOTES, 'UTF-8');
    $pattern = '/<\/?a[^>]*>/i';
    $replacement = '';
    $output = preg_replace($pattern, $replacement, $decoded_input);
    return $output;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = "kuqpxtvxhosting_minhngoc1";
    $password = "kuqpxtvxhosting_minhngoc1";
    $dbname = "kuqpxtvxhosting_minhngoc1";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Lấy tất cả các hàng trong bảng goods
    $sql = "SELECT cat_id, long_desc FROM nb_category";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Duyệt qua từng hàng và cập nhật giá trị trong cột thongso
        while($row = $result->fetch_assoc()) {
            $id = $row["cat_id"];
            $thongso = $row["long_desc"];

            // Xoá thẻ <a> và cập nhật giá trị trong cột thongso
            $new_thongso = remove_a_tags($thongso);
            $update_sql = "UPDATE nb_category SET long_desc = ? WHERE cat_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $new_thongso, $id);
            $stmt->execute();
        }
        echo "Đã xoá thẻ &lt;a&gt; thành công trong cột thongso của bảng goods.";
    } else {
        echo "Không tìm thấy hàng nào trong bảng goods.";
    }

    $conn->close();
}
?>
