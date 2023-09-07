<?php

$smarty->caching = false;

/** Xử lí Submit */
$act = isset($_POST['action']) ? $_POST['action'] : '';

if($act == '_payment'){
     if($_POST['csrf_token'] != $_SESSION['csrf_token']){
       show_message('Token không hợp lệ');
    }

    require(ROOT_PATH . 'alepay/config.php');
    require(ROOT_PATH . 'alepay/Alepay.php');

    $alepay = new Alepay($config);
    $data = array();
    parse_str(file_get_contents('php://input'), $params); // Lấy thông tin dữ liệu bắn vào
    /* Validate */
    if(empty($params['goodsName'])){
        show_message('Tên sản phẩm dịch vụ không thể để trống');
    }
    $params['amount'] = str_replace(",", "", $params['amount']);
    /* Ràng buộc Dữ liệu */
    if($params['amount'] < 3000000){
        show_message('Sản phẩm có giá > 3 triệu mới đủ điều kiện trả góp');
    }
    if(is_email($params['buyerEmail']) == false){
        show_message('Email không hợp lệ');
    }
    if(is_tel($params['buyerPhone']) == false){
        show_message('Số điện thoại không hợp lệ');
    }

    /* Khai báo tham số truyền */
    $data['cancelUrl'] = $base_path;
    $data['amount'] = intval($params['amount']);
    $data['orderCode'] = 'JPS-'.date('dmY') .'_'. uniqid();
    $data['currency'] = 'VND';
    //$data['orderDescription'] = $params['goodsName'].'-'.price_format($data['amount']);
    $data['orderDescription'] = $params['goodsName'];
    $data['totalItem'] = 1;
    $data['checkoutType'] = intval($params['checkoutType']); // Thanh toán trả góp
    $data['buyerName'] = trim($params['buyerName']);
    $data['buyerEmail'] = trim($params['buyerEmail']);
    $data['buyerPhone'] = trim($params['buyerPhone']);
    $data['buyerAddress'] = trim($params['buyerAddress']);
    $data['buyerCity'] = trim($params['buyerCity']);
    $data['buyerCountry'] = 'Việt Nam';
    //$data['month'] = 3;
    $data['paymentHours'] = 48; //48 tiếng :  Thời gian cho phép thanh toán (tính bằng giờ)

    /* Buộc data phải điền */
    foreach ($data as $k => $v) {
        if (empty($v)) {
            //$alepay->return_json("NOK", "Bắt buộc phải nhập/chọn tham số [ " . $k . " ]");
            show_message("Bắt buộc phải nhập/chọn tham số [ " . $k . " ]");
            //die();
        }
    }

    /* gui request thanh toan */
    $result = $alepay->sendOrderToAlepay($data);
    if (isset($result) && !empty($result->checkoutUrl)) {
        /* Save Đơn hàng trả góp vào CSDL */
         $tragop_data = array(
            'orderCode'=>$data['orderCode'],
            'amount'=>$data['amount'],
            'orderDescription'=>$data['orderDescription'],
            'totalItem'=>$data['totalItem'],
            'checkoutType'=>$data['checkoutType'],
            'buyerName'=>$data['buyerName'],
            'buyerEmail'=>$data['buyerEmail'],
            'buyerPhone'=>$data['buyerPhone'],
            'buyerAddress'=>$data['buyerAddress'],
            'buyerCity'=>$data['buyerCity'],
            'addTime'=> time()
         );
        $db->autoExecute($ecs->table('alepay'), $tragop_data, 'INSERT');
        /* send mail */
        if ( $GLOBALS['_CFG']['send_service_email'] &&  $GLOBALS['_CFG']['service_email'] != '')
        {
            $content = "Trả góp Alepay từ ".$data['buyerName']." ĐT: ".$data['buyerPhone']." Mua: - ".$data['orderDescription'];
            send_mail( $GLOBALS['_CFG']['shop_name'],  $GLOBALS['_CFG']['service_email'], 'Thông báo có đơn hàng trả góp Alepay', $content, 1);
        }
        //setcookie('TG_Alepay', $data['orderCode'], time()+(84000*30), $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
        /* Chuyển hướng thanh toán */
        //$alepay->return_json('OK', 'Thành công', $result->checkoutUrl);
        echo '<meta http-equiv="refresh" content="0;url=' . $result->checkoutUrl. '">';
    } else {
        show_message($result->errorDescription);
    }
    exit;
}

/* Layout */
assign_template();
$smarty->assign('page_title',  'Cổng thanh toán thẻ Quốc Tế Alepay');
$smarty->assign('ur_here',   'Cổng thanh toán Alepay');
$smarty->assign('keywords',  '');
$smarty->assign('description',   'Cổng thanh toán thẻ Quốc Tế Alepay');

/* lấy sản phẩm giỏ hàng để trả góp */
if(isset($_SESSION['flow_type']) && isset($_GET['getcart']) && $_GET['getcart'] == 1){
    require(ROOT_PATH . 'includes/lib_order.php');
    $cart_goods = get_cart_goods();
    $goodsName = '';
    foreach ($cart_goods['goods_list'] as $key => $goods) {
        $goodsName .= $goods['goods_name'].'-SL'.$goods['goods_number'].':';
    }
    $price = $cart_goods['total']['goods_amount'];
}
else{
    $gid = isset($_REQUEST['gid']) ? intval($_REQUEST['gid']) : 0;
    if($gid > 0){
        $goods_info = get_goods_info($gid);
        $goodsName = $goods_info['goods_name'];
        $price =  $goods_info['final_price'];
    }else{
        show_message('Sản phẩm không tồn tại');
        exit;
    }

}
if($price > 0){
    $smarty->assign('hideprice',  1);
}
$smarty->assign('goodsName',  $goodsName);
$smarty->assign('finalPrice',  $price);
$smarty->assign('finalPrice_f',  price_format($price));
 /** Lấy danh sách tỉnh thành */
$region = get_regions(1,1);
$region_list = array();
foreach ($region as $key => $row) {
    $region_list[$key]['region_id'] = $row['region_id'];
    $region_list[$key]['region_name'] = $row['region_name'];
}

$smarty->assign('region_list', $region_list);

$_SESSION['csrf_token'] = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].mt_rand());
$smarty->assign('csrf_token', $_SESSION['csrf_token']);

$smarty->display('alepay'.$_device.'.dwt');
?>