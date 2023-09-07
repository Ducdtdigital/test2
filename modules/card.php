<?php
if ((DEBUG_MODE & 2) != 2) $smarty->caching = false;

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';

/* Ajax load card by cat_id */
if ($act == 'loadcard')
{
    define('INIT_NO_USERS', true);
    define('INIT_NO_SMARTY', true);
    define('IS_AJAX', true);

    $result   = array('message' => '', 'error' => 1, 'data'=> '');
    require(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    $cat_id   =  isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;

    if($cat_id == 0){
        $result['message'] = 'Có gì đó không hợp lệ';
        die($json->encode($result));
    }

    $sql = "SELECT goods_sn, goods_id, goods_name, shop_price FROM ". $ecs->table('goods') ." WHERE cat_id = '$cat_id' ";
    $arr = $db->getAll($sql);
    $err = $db->error();
    if($err){
        $result['message'] = 'Lỗi không xác định';
        die($json->encode($result));
    }
    $html = '';
    foreach ($arr as $key => $rows) {
        $rows['fprice'] = price_format($rows['shop_price']);
        $rows['shop_price'] = round($rows['shop_price']);
        $html .= '<option data-fprice="'.$rows['fprice'].'" data-price="'.$rows['shop_price'].'" value="'.$rows['goods_sn'].'">'.$rows['goods_name'].'</option>';
    }
    $result['message'] = 'Thành công !';
    $result['error'] = 0;
    $result['data'] = $html;
    die($json->encode($result));

}
elseif ($act == 'order') {
    define('INIT_NO_USERS', true);
    define('INIT_NO_SMARTY', true);
    define('IS_AJAX', true);
    $result   = array('message' => '', 'error' => 1, 'payment'=> '');
    require(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
        /**
         * Cần lấy thông tin nào để trả về respon thì đưa vào  tham số order_code
         * khi truyển qua cổng thanh toán
         *
         */
        $cemail = isset($_POST['cemail']) ? addslashes($_POST['cemail']) : '';
        $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
        $goods_sn = isset($_POST['goods_sn']) ? addslashes($_POST['goods_sn']) : '';
        $soluong = isset($_POST['soluong']) ? intval($_POST['soluong']) : 1;
        $order_amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

        $error_msg = '';
        if($goods_sn == ''){
            $error_msg = "Mã thẻ không hợp lệ \n";
        }
        if($cemail == ''){
            $error_msg = "Email khách hàng không hợp lệ \n";
        }
        if($cat_id == 0){
             $error_msg = "Danh mục thẻ game không hợp lệ \n";
        }
        if($order_amount == 0){
             $error_msg = "Giá trị đơn hàng không hợp lệ để thanh toán \n";
        }
        if($soluong < 1 && $soluong > 10){
            $error_msg = "Bạn chỉ được mua từ 1 đến 10 thẻ \n";
        }

        if($error_msg != ''){
            $result['message'] = $error_msg;
            die($json->encode($result));
        }

        $order_code = "GAMECARD{$goods_sn}";
        /* Tao Url Chuyen huong thanh toan*/
        $return_url       = $base_path.'the-cao?act=respond';
        $notify_url       = $base_path.'the-cao';
        $cancel_url       = $base_path;

        $data_tranfer = array(
            'return_url'        =>  $return_url,
            'transaction_info'  =>  "GAMECARD{$goods_sn}-{$soluong}-{$cat_id}-{$cemail}",
            'order_code'        =>  $order_code,
            'price'             =>  $order_amount
        );
        /*Tạo link thanh toán đến nganluong.vn*/
        $url_payment = buildCheckoutUrl($data_tranfer);
        /** Chuyển hướng sang ngân lượng thanh toán sau khi hoàn thành đặt hàng */
        $url_payment .='&cancel_url='. $cancel_url;
        //Mặc định forcus vào phương thức Ngân Hàng
        $url_payment .='&option_payment=bank_online';

       $result['payment'] = $url_payment;
       $result['error'] = 0;
       $result['message'] = 'Thành công !';
       die($json->encode($result));
}
elseif ($act == 'respond') {
    $transaction_info = $_GET['transaction_info'];
    $price            = $_GET['price'];
    $payment_id       = $_GET['payment_id'];
    $payment_type     = $_GET['payment_type'];
    $error_text       = $_GET['error_text'];
    $secure_code      = $_GET['secure_code'];
    $token_nl         = $_GET['token_nl'];
    $order_code       = $_GET['order_code'];

    $file = 'response.txt';
    $current = file_get_contents($file);
    $current .= $transaction_info.'-'.$price.'-'.$error_text.'-'.$order_code;
    $current .= "\n";
    file_put_contents($file, $current);

    $arr = explode('-',ltrim($transaction_info,'GAMECARD'));
    $goods_sn = $arr[0];
    $soluong = $arr[1];
    $cat_id = $arr[2];
    $cemail = $arr[3];
    /**
     * dựa trên Số lượng thẻ và res ko lỗi
     * Lấy ra số lượng thẻ tương ứng mã thẻ, cat_id
     * Cập nhật đã bán, cemail KH
     */

    /* Gửi email All mã thẻ cho KH, Đồng thời gửi email cho SHop */

    show_message('Thanh toán thành công ! Chúng tôi sẻ gửi mã thẻ qua email ('.$cemail.') cho bạn.', $_LANG['back_home'], './', 'success', false);
    exit;
}

assign_template();

/* render template */
$smarty->assign('cat_list', getCatCard());
$smarty->display('card.dwt');


    function getCatCard($parent_id = 29){
        $res = $GLOBALS['db']->getAll("SELECT cat_id, cat_name FROM " .$GLOBALS['ecs']->table('category'). " WHERE parent_id = '$parent_id'");
        $arr = array();
        foreach ($res as $key => $rows) {
            $arr[$key]['cat_id'] = $rows['cat_id'];
            $arr[$key]['cat_name'] = $rows['cat_name'];
        }
        return $arr;
    }

    /**
     * HÀM TẠO ĐƯỜNG LINK THANH TOÁN QUA NGÂNLƯỢNG.VN VỚI THAM SỐ CƠ BẢN
     *
     * Live: https://www.nganluong.vn/checkout.php
     * Sanbox test: https://sandbox.nganluong.vn:8088/nl30/checkout.php
     *
     * @param array $data: Mảng chưa data cần thiết để tạo thông tin thanh toán
     * @return string
     */
    function buildCheckoutUrl($data = [])
    {
        $nganluong_merchant_id = '45447';
        $nganluong_secure_key = '467521a756a8511a63d707869e5d263e';
        $nganluong_email = 'nhannn@bachkhoashop.com';
        $nganluong_url = 'https://sandbox.nganluong.vn:8088/nl30/checkout.php';
        // Bước 1. Mảng các tham số chuyển tới nganluong.vn
        $arr_param = array(
            'merchant_site_code'=>  strval($nganluong_merchant_id),
            'return_url'        =>  strtolower(urlencode($data['return_url'])),
            'receiver'          =>  strval($nganluong_email),
            'transaction_info'  =>  strval($data['transaction_info']),
            'order_code'        =>  strval($data['order_code']),
            'price'             =>  strval($data['price'])
        );
        $secure_code ='';
        $secure_code = implode(' ', $arr_param) . ' ' . $nganluong_secure_key;
        $arr_param['secure_code'] = md5($secure_code);
        $arr_param['order_description'] = strval('Mua The Game - Muagame.vn');
        $arr_param['comments'] = strval('Mua The Game - Muagame.vn');
        /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
        $redirect_url =  $nganluong_url;
        if (strpos($redirect_url, '?') === false)
        {
            $redirect_url .= '?';
        }
        else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
        {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }

        /* Bước 3. tạo url*/
        $url = '';
        foreach ($arr_param as $key=>$value)
        {
            if ($key != 'return_url') $value = urlencode($value);

            if ($url == '')
                $url .= $key . '=' . $value;
            else
                $url .= '&' . $key . '=' . $value;
        }
        return $redirect_url.$url;
    }

    /**
     * HÀM KIỂM TRA TÍNH ĐÚNG ĐẮN CỦA ĐƯỜNG LINK KẾT QUẢ TRẢ VỀ TỪ NGÂNLƯỢNG.VN
     *
     * @param string $transaction_info: Thông tin về giao dịch, Giá trị do website gửi sang
     * @param string $order_code: Mã hoá đơn/tên sản phẩm
     * @param string $price: Tổng tiền đã thanh toán
     * @param string $payment_id: Mã giao dịch tại NgânLượng.vn
     * @param int $payment_type: Hình thức thanh toán: 1 - Thanh toán ngay (tiền đã chuyển vào tài khoản NgânLượng.vn của người bán); 2 - Thanh toán Tạm giữ (tiền người mua đã thanh toán nhưng NgânLượng.vn đang giữ hộ)
     * @param string $error_text: Giao dịch thanh toán có bị lỗi hay không. $error_text == "" là không có lỗi. Nếu có lỗi, mô tả lỗi được chứa trong $error_text
     * @param string $secure_code: Mã checksum (mã kiểm tra)
     * @return unknown
     */

     function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
    {
        $nganluong_merchant_id = '45447';
        $nganluong_secure_key = '467521a756a8511a63d707869e5d263e';
        // Tạo mã xác thực từ chủ web
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($payment_id);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($error_text);
        $str .= ' ' . strval($nganluong_merchant_id);
        $str .= ' ' . strval($nganluong_secure_key);

        // Mã hóa các tham số
        $verify_secure_code = '';
        $verify_secure_code = md5($str);

        // Xác thực mã của chủ web với mã trả về từ nganluong.vn
        if ($verify_secure_code === $secure_code) return true;
        else return false;
    }



 ?>