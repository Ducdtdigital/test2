<?php
include(ROOT_PATH . 'alepay/Utils/AlepayUtils.php');
/*
 * Alepay class
 * Implement with Alepay service
 */

class Alepay {

    protected $alepayUtils;
    protected $publicKey = "";
    protected $checksumKey = "";
    protected $apiKey = "";
    protected $callbackUrl = "";
    protected $env = "live";
    protected $baseURL = array(
        'dev' => 'localhost:8080',
        'test' => 'https://alepay-sandbox.nganluong.vn',
        'live' => 'https://alepay.vn'
    );
    protected $URI = array(
        'requestPayment' => '/checkout/v1/request-order',
        'calculateFee' => '/checkout/v1/calculate-fee',
        'getTransactionInfo' => '/checkout/v1/get-transaction-info',
        'getTransactionHistory' => '/checkout/v1/get-transaction-history',
        'requestCardLink' => '/checkout/v1/request-profile',
        'tokenizationPayment' => '/checkout/v1/request-tokenization-payment',
        'tokenizationPaymentDomestic' => '/checkout/v1/request-tokenization-payment-domestic',
        'cancelCardLink' => '/checkout/v1/cancel-profile',
        'requestCardLinkDomestic' => '/alepay-card-domestic/request-profile',
    );

    public function __construct($opts) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

        /*
         * Require curl and json extension
         */
        if (!function_exists('curl_init')) {
            throw new Exception('Alepay needs the CURL PHP extension.');
        }
        if (!function_exists('json_decode')) {
            throw new Exception('Alepay needs the JSON PHP extension.');
        }

        // set KEY
        if (isset($opts) && !empty($opts["apiKey"])) {
            $this->apiKey = $opts["apiKey"];
        } else {
            throw new Exception("API key is required !");
        }
        if (isset($opts) && !empty($opts["encryptKey"])) {
            $this->publicKey = $opts["encryptKey"];
        } else {
            throw new Exception("Encrypt key is required !");
        }
        if (isset($opts) && !empty($opts["checksumKey"])) {
            $this->checksumKey = $opts["checksumKey"];
        } else {
            throw new Exception("Checksum key is required !");
        }
        if (isset($opts) && !empty($opts["callbackUrl"])) {
            $this->callbackUrl = $opts["callbackUrl"];
        }
         if (isset($opts) && !empty($opts["env"])) {
            $this->env = $opts["env"];
        }
        $this->alepayUtils = new AlepayUtils();
    }

    /*
     * sendOrder - Send order information to Alepay service
     * @param array|null $data
     */

    public function sendOrderToAlepay($data) {
        // get demo data
        // $data = $this->createCheckoutData();
        $data['returnUrl'] = $this->callbackUrl;
        // $data['cancelUrl'] = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/demo-alepay';
        $url = $this->baseURL[$this->env] . $this->URI['requestPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if (isset($result) && $result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendOrderToAlepayDomestic($data) {
        // get demo data
        $data = $this->createCheckoutDomesticData();
        $data['returnUrl'] = $this->callbackUrl;
        //  $data['cancelUrl'] = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/demo-alepay';
        $url = $this->baseURL[$this->env] . $this->URI['requestPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            echo json_encode($result);
        }
    }

    /*
     * get transaction info from Alepay
     * @param array|null $data
     */

    public function getTransactionInfo($transactionCode) {

        // demo data
        $data = array('transactionCode' => $transactionCode);
        $url = $this->baseURL[$this->env] . $this->URI['getTransactionInfo'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return  $dataDecrypted;
        } else {
            return json_encode($result);
        }
    }

    /*
     * get transaction History from Alepay
     * @param array|null $data
     */

    public function getTransactionHistory($data) {
        $url = $this->baseURL[$this->env] . $this->URI['getTransactionHistory'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return  $dataDecrypted;
        } else {
            return json_encode($result);
        }
    }
    /*
     * sendCardLinkRequest - Send user's profile info to Alepay service
     * return: cardlink url
     * @param array|null $data
     */

    public function sendCardLinkRequest($data) {
        // get demo data
        $data = $this->createRequestCardLinkData();
        $url = $this->baseURL[$this->env] . $this->URI['requestCardLink'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendCardLinkDomesticRequest() {
        // get demo data
        $data = $this->createRequestCardLinkDataDomestic();
        $url = $this->baseURL[$this->env] . $this->URI['requestCardLinkDomestic'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendTokenizationPayment($tokenization) {

        $data = $this->createTokenizationPaymentData($tokenization);
        $url = $this->baseURL[$this->env] . $this->URI['tokenizationPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendTokenizationPaymentDomestic($tokenization) {
        $data = $this->createTokenizationPaymentDomesticData($tokenization);
        $url = $this->baseURL[$this->env] . $this->URI['tokenizationPaymentDomestic'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function cancelCardLink($alepayToken) {
        $params = array('alepayToken' => $alepayToken);
        $url = $this->baseURL[$this->env] . $this->URI['cancelCardLink'];
        $result = $this->sendRequestToAlepay($params, $url);
        echo json_encode($result);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            echo $dataDecrypted;
        }
    }

    private function sendRequestToAlepay($data, $url) {
        $dataEncrypt = $this->alepayUtils->encryptData(json_encode($data), $this->publicKey);
        $checksum = md5($dataEncrypt . $this->checksumKey);
        $items = array(
            'token' => $this->apiKey,
            'data' => $dataEncrypt,
            'checksum' => $checksum
        );
        $data_string = json_encode($items);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        // echo $result;
        return json_decode($result);
    }

    public function return_json($error, $message = "", $data = array()) {
        header('Content-Type: application/json');
        echo json_encode(array(
            "error" => $error,
            "message" => $message,
            "data" => $data
        ));
    }

    public function decryptCallbackData($data) {
        return $this->alepayUtils->decryptCallbackData($data, $this->publicKey);
    }

    public function getErrorMsg($errCode){
        $error = array(
            '000'=> 'Thành công !',
            '101'=> 'Checksum không hợp lệ !',
            '102'=> 'Mã hóa không hợp lệ !',
            '103'=>'IP Không được phép truy cập !',
            '104'=>'Dữ liệu không hợp lệ !',
            '105'=>'Token không hợp lệ !',
            '106'=>'Token thanh toán Alepay không tồn tại hoặc đã bị hũy !',
            '107'=>'Giao dịch đang được xử lí !',
            '110'=>'Phải có email hoặc số điện thoại người mua',
            '111'=>'Giao dịch thất bại',
            '125'=>'Tên người mua không đúng định dạng',
            '126'=>'Email người mua không đúng định dạng',
            '127'=>'SĐT người mua không đúng định dạng',
            '128'=>'Địa chỉ người mua không hợp lệ',
            '129'=>'Tỉnh/TP người mua không hợp lệ',
            '132'=>'Email không hợp lệ',
            '133'=>'Thông tin thẻ không hợp lệ',
            '134'=>'Thẻ hết hạn mức thanh toán',
            '135'=>'GD bị từ chối do ngân hàng phát hành thẻ',
            '137'=>'Giao dịch không hợp lệ',
            '138'=>'Tài khoản Merchant không tồn tại',
            '139'=>'Tài khoản Merchant không hoạt động',
            '140'=>'Tài khoản Merchant không hợp lệ',
            '155'=>'Đợi người mua xác nhận trả góp',
        );
        return isset($error[$errCode]) ? $error[$errCode] : 'Mã lỗi không xác định: '.$errCode;
    }

}

?>