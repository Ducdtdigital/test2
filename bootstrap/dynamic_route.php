<?php
require __DIR__. '/../includes/init.php';
include_once(ROOT_PATH.'includes/cls_json.php');

$linkNew = "";
if( $url != ''){
    $sql = "SELECT rd_id, link_old, link_new ".
            " FROM " .$ecs->table('redirect'). " WHERE link_old = '".$url."' ";
    $res =  $db->GetRow($sql);
    $linkNew = $res['link_new'];

}

/**
 * Static Route
 * Thay đổi nếu thực sự cần thiết, nếu thiếu có thể bổ sung
 * Luôn đặt trước Dynamic Route
 */
if($url == ''){
    $module = 'index';
       
}

//đoạn này là để cài chuyển hướng
elseif($linkNew != ''){
  header("HTTP/1.1 301 Moved Permanently");
  header( "Location: $linkNew");
  exit();

}
  
//end chuyển hướng
elseif($url == 'video'){
    $module = 'article_cat';
    $slug = $active_url = $url;
}elseif($url == 'bao-hanh'){    
$module = 'article_cat';    $slug = $active_url = $url;}
elseif($url == 'tuyen-dung'){
    $module = 'article_cat'; $slug  = $url;
}
elseif($url == 'lien-he'){
    $module = 'message'; $slug = $url;
}
elseif($url == 'thanh-vien'){
    $module = 'user'; $slug = $url;
}elseif($url == 'thanh-toan'){
    $module = 'alepay';
}
elseif($url == 'gio-hang'){
    $module = 'flow'; $slug = $url;
}elseif($url == 'tim-kiem'){
    $module = 'search';
    $params = getQueryParams($getRequest['getQuery']);
}elseif($url == 'khuyen-mai'){
    $module = 'topic';
}elseif($url == 'tracking'){
    $module = 'ads';
}elseif($url == 'thuong-hieu'){
    $module = 'brand'; $slug = '';
}elseif($url == 'sosanh'){
    $module = 'compare';
}

/**
 * Dynamic Route - Route động thay đổi theo slug
 * Trật tự bên dưới là có tính toán, xếp trước là match trước
 * Luôn đặt sau Static Route
 */
elseif(preg_match("/^tin-tuc\/([a-z0-9_-]+)$/", $url, $match)){
   $module = 'article_cat'; $slug = $match[1]; $active_url = 'tin-tuc';

}elseif(preg_match("/^bao-hanh\/([a-z0-9_-]+)$/", $url, $match)){   $module = 'article_cat'; $slug = $match[1]; $active_url = 'bao-hanh';

}elseif(preg_match("/^chinh-sach-doi-tra-hang.html$/", $url, $match)){    $module = 'article'; $id = 8; $amp = false;

}elseif(preg_match("/^chinh-sach-bao-mat.html$/", $url, $match)){
    $module = 'article'; $id = 2; $amp = false;
}elseif(preg_match("/^chinh-sach-van-chuyen.html$/", $url, $match)){
    $module = 'article'; $id = 4; $amp = false;
}elseif(preg_match("/^chinh-sach-bao-hanh.html$/", $url, $match)){
    $module = 'article'; $id = 3; $amp = false; 
}elseif(preg_match("/^quy-dinh-su-dung.html$/", $url, $match)){
    $module = 'article'; $id = 1; $amp = false;
}elseif(preg_match("/^gioi-thieu.html$/", $url, $match)){
    $module = 'article'; $id = 5;
}elseif(preg_match("/^huong-dan-giao-hang.html$/", $url, $match)){
    $module = 'article'; $id = 6; $amp = false;
}elseif(preg_match("/^huong-dan-mua-hang-thanh-toan.html$/", $url, $match)){
    $module = 'article'; $id = 7; $amp = false;
}elseif(preg_match("/^chinh-sach-doi-tra.html$/", $url, $match)){
    $module = 'article'; $id = 8; $amp = false;
}elseif(preg_match("/^tin-tuc\/([a-z0-9_-]+)-([0-9]+).html$/", $url, $match)){
    $module = 'article'; $amp = false; $id = $match[2]; $active_url = 'tin-tuc';
}elseif(preg_match("/^bao-hanh\/([a-z0-9_-]+)-([0-9]+).html$/", $url, $match)){    $module = 'article'; $amp = false; $id = $match[2]; $active_url = 'bao-hanh';}
elseif(preg_match("/^khuyen-mai\/([a-z0-9_-]+).html$/", $url, $match)){
   $module = 'topic'; $slug = $match[1];
}
elseif(preg_match("/^thuong-hieu-([a-z0-9_-]+).html$/", $url, $match)){
    $slug = $match[1];
    // Kiểm tra xem slug có tồn tại trong bảng slug không
    $id = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE slug = '".$slug."'");

    // Nếu id > 0, tức là slug tồn tại trong bảng, ngược lại chuyển hướng đến trang 404
    if ($id > 0) {
        $module = 'brand'; 
        $slug_brand = '';
    } else {
        $module = '404';
    }
}
elseif(preg_match("/^([a-z0-9_-]+)\/thuong-hieu-([a-z0-9_-]+).html$/", $url, $match)){
   $module = 'brand'; $slug = $match[2]; $slug_brand = $match[1];
}

elseif(preg_match("/^([a-z0-9_-]+)-thuong-hieu-([a-z0-9_-]+).html$/", $url, $match)){
    $module = 'category'; $slug = $match[1]; $slug_brand = $match[2];
    $active_url = $match[1].'.html';
         
}

elseif (preg_match("/^([a-z0-9_-]+).html$/", $url, $match) || preg_match("/^([a-z0-9_-]+)-([0-9]+).html$/", $url, $match)) {
    $slug = $match[1];

    // Kiểm tra xem có phải là goods hay không
    $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='goods' AND slug = '".$slug."'");
    if($id > 0) {
        $module = 'goods';
        $tragop = 0;
    } else {
        // Kiểm tra xem có phải là category hay không
        $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='category' AND slug = '".$slug."'");
        if($id > 0) {
            $module = 'category';
            $active_url = $match[0];
        }
        else {
            // Nếu không khớp với bất kỳ quy tắc nào, chuyển hướng đến trang 404
            $module = '404';
        }
    }
}
elseif (preg_match("/^([a-z0-9_-]+).html$/", $url, $match) || preg_match("/^([a-z0-9_-]+)-([0-9]+).html$/", $url, $match)) {
    $slug = $match[1];

    // Kiểm tra xem có phải là goods hay không
    $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='goods' AND slug = '".$slug."'");
    if($id > 0) {
        $module = 'goods';
        $tragop = 0;
    } else {
        // Kiểm tra xem có phải là category hay không
        $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='category' AND slug = '".$slug."'");
        if($id > 0) {
            $module = 'category';
            $active_url = $match[0];
        }
                else {
            // Nếu không khớp với bất kỳ quy tắc nào, chuyển hướng đến trang 404
            $module = '404';
        }
    }
}


elseif (preg_match("/^([a-z0-9_-]+)\/tra-gop-([a-z0-9_-]+).html$/", $url, $match)) {
    $module = 'goods';
    $slug = $match[2];
    $tragop = 1;
} elseif (preg_match("/^404$/", $url, $match)) {
    $module = '404';
    $slug = $match[0];
}
elseif($url == 'tin-tuc'){
    $module = 'article_cat';
    $slug = $active_url = $url;
}else {
    // Nếu không khớp với bất kỳ quy tắc nào, chuyển hướng đến trang 404
    $module = '404';
}