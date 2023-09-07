<?php
/**
 *  Name: Nganluong Method Payment
 *  Version: 2.0
 *  Auth: Nobj Nguyen
 *  Website: https://fb.me/duc0126
 *  Update: 02.12.2017
 */
if (!defined('IN_ECS')) {die('Hacking attempt'); }
$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/nganluong.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    $modules[$i]['code']    = basename(__FILE__, '.php');
    $modules[$i]['desc']    = 'nganluong_desc';
    $modules[$i]['is_cod']  = '0';
    $modules[$i]['is_online']  = '1';
    $modules[$i]['author']  = 'Agency1s.com';
    $modules[$i]['website'] = 'Agency1s.com';
    $modules[$i]['version'] = '2.0';
    $modules[$i]['config']  = array(
        array('name' => 'nganluong_merchant_id', 'type' => 'text', 'value' => ''),
        array('name' => 'nganluong_email',       'type' => 'text', 'value' => ''),
        array('name' => 'nganluong_secure_key',       'type' => 'text', 'value' => ''),
        array('name' => 'nganluong_comments',     'type' => 'text', 'value' => '')
    );

    return;
}
/**
 * Tạo Phương thức thanh toán nganluong cho Ecshop
 * Giá trị nhỏ nhất chấp nhận thanh toán là 2.000vnđ
 */
class nganluong
{
    /**
     * Live: https://www.nganluong.vn/checkout.php
     * Sanbox test: https://sandbox.nganluong.vn:8088/nl30/checkout.php
     */
    public $nganluong_url = 'https://www.nganluong.vn/checkout.php'; // Địa chỉ thanh toán hoá đơn của NgânLượng.vn
    public $payment = [];

    function __construct()
    {
        $this->nganluong();
    }
    /**
     * Lấy thông tin từ cấu hình
     */
    function nganluong() {
        $this->payment            = get_payment('nganluong');
    }

    function get_code($order)
    {

        $return_url       = return_url(basename(__FILE__, '.php'));
        $notify_url       = return_url(basename(__FILE__, '.php'));
        $cancel_url       = $GLOBALS['ecs']->url();

        $data_tranfer = array(
            'return_url'        =>  $return_url,
            'transaction_info'  =>  "Thong tin giao dich",
            'order_code'        =>  $order['order_sn'].'-'.$order['log_id'],
            'price'             =>  $order['order_amount']
        );
        /*Tạo link thanh toán đến nganluong.vn*/
        $url= $this->buildCheckoutUrl($data_tranfer);
        /** Chuyển hướng sang ngân lượng thanh toán sau khi hoàn thành đặt hàng */
        if($order['order_sn'] != ''){
            //Link bấm nút hủy giao dịch
            $url .='&cancel_url='. $cancel_url;
            //Mặc định forcus vào phương thức Ngân Hàng
            $url .='&option_payment=bank_online';

           // header("Location: $url\n");
            //echo '<meta http-equiv="refresh" content="0; url='.$url.'" >';
        }

        $html = '<meta http-equiv="refresh" content="2; url='.$url.'" >';
        $html .= '<div class="payment_note error_box" style="text-align:center">
            <p>Số tiền bạn cần thanh toán là: <strong>'.price_format($order['order_amount']).'</strong></p>
            <p>Trình duyệt sẻ <strong>Chuyển hướng thanh toán sau 2s nữa. Vui lòng không đóng trình duyệt, tab này.</strong></p>
            </div>';
        return $html;

    }

    function respond()
    {
        /** Lay thong tin tu request tra ve tu nganluong.vn */
        $transaction_info = $_GET['transaction_info'];
        $price            = $_GET['price'];
        $payment_id       = filter_var($_GET['payment_id'], FILTER_SANITIZE_STRING);
        $payment_type     = intval($_GET['payment_type']);
        $error_text       = $_GET['error_text'];
        $secure_code      = $_GET['secure_code'];
        $token_nl         = $_GET['token_nl'];
        $order_code       = filter_var($_GET['order_code'], FILTER_SANITIZE_STRING);
        $arr = explode('-',$order_code);
        $order_sn =  $arr[0];
        $log_id   =  intval($arr[1]);

        /* Check số tiền chuyển có khớp không */
        $money_ok = check_money($log_id, $price);
        if(!$money_ok){
            $note = 'Trạng thái: Thanh toán Thành công, nhưng không đúng số tiền cần thanh toán. Mã GD: '.$payment_id;
        }

        /* xác thực từ chủ web*/
        $verify = $this->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
        /** pay success */
        if ($money_ok == true && $verify == true && $payment_type == 1)
        {
            $status = $GLOBALS['_LANG']['nganluong_pay_success_status'].'- ID Thanh toán: '.$payment_id;
            order_paid($log_id, 2, $status);
            return true;
        }elseif($money_ok == true && $verify == true && $payment_type == 2){
            order_paid($log_id, 2, $GLOBALS['_LANG']['nganluong_keep_money']);
            return true;
        }else{
            $note = 'Trạng thái: Thanh toán thất bại. Xác thực không hợp lệ. Mã GD: '.$payment_id;
        }
        order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $note, $GLOBALS['_LANG']['buyer']);
       return false;
    }


    /**
     * HÀM TẠO ĐƯỜNG LINK THANH TOÁN QUA NGÂNLƯỢNG.VN VỚI THAM SỐ CƠ BẢN
     *
     * @param array $data: Mảng chưa data cần thiết để tạo thông tin thanh toán
     * @return string
     */
    public function buildCheckoutUrl($data = [])
    {

        // Bước 1. Mảng các tham số chuyển tới nganluong.vn
        $arr_param = array(
            'merchant_site_code'=>  strval($this->payment['nganluong_merchant_id']),
            'return_url'        =>  strtolower(urlencode($data['return_url'])),
            'receiver'          =>  strval($this->payment['nganluong_email']),
            'transaction_info'  =>  strval($data['transaction_info']),
            'order_code'        =>  strval($data['order_code']),
            'price'             =>  strval($data['price'])
        );
        $secure_code ='';
        $secure_code = implode(' ', $arr_param) . ' ' . $this->payment['nganluong_secure_key'];
        $arr_param['secure_code'] = md5($secure_code);
        $arr_param['order_description'] = strval($this->payment['nganluong_comments']);
        $arr_param['comments'] = strval($this->payment['nganluong_comments']);
        /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
        $redirect_url = $this->nganluong_url;
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

    public function verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
    {
        // Tạo mã xác thực từ chủ web
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($payment_id);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($error_text);
        $str .= ' ' . strval($this->payment['nganluong_merchant_id']);
        $str .= ' ' . strval($this->payment['nganluong_secure_key']);

        // Mã hóa các tham số
        $verify_secure_code = '';
        $verify_secure_code = md5($str);

        // Xác thực mã của chủ web với mã trả về từ nganluong.vn
        if ($verify_secure_code === $secure_code) return true;
        else return false;
    }

}

?>