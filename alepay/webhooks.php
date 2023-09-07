<?php
define('IN_ECS', true);
define('IS_AJAX', true);
define('INIT_NO_SMARTY', true);
require __DIR__. '/../includes/init.php';

    $input = file_get_contents('php://input');
    /** Ghi Log response */
    $file = 'response.txt';
    $current = file_get_contents($file);
    $current .= $input;
    $current .= "\n";
    file_put_contents($file, $current);

    /* Lấy Data */
    $obj = json_decode($input);
    $orderCode        = $obj->transactionInfo->orderCode;
    $transactionCode  = $obj->transactionInfo->transactionCode;
    $status           = $obj->transactionInfo->status;
    $errorDescription = $status == '155' ? 'Giao dịch đang chờ duyệt' : $obj->transactionInfo->message;

    /* GD thành công */
   if($status == '000'){
        $installment     = $obj->transactionInfo->installment;
        $month           = $obj->transactionInfo->month;
        $bankCode        = $obj->transactionInfo->bankCode;
        $method          = $obj->transactionInfo->method;
        $transactionTime = $obj->transactionInfo->transactionTime;
        $successTime     = $obj->transactionInfo->successTime;
        $merchantFee     = $obj->transactionInfo->merchantFee;
        $payerFee        = $obj->transactionInfo->payerFee;
        $cardNumber      = $obj->transactionInfo->cardNumber;
        if($installment == true){
            /// Giao dịch thanh toán trả góp thành công => cập nhật db với
            $sql = "UPDATE ".$ecs->table('alepay'). " SET ".
                " errorCode = '000', errorDescription = 'Thành công', ".
                " month = '$month', bankCode = '$bankCode', paymentMethod = '$method', ".
                " transactionTime = '$transactionTime', successTime = '$successTime', ".
                " merchantFee = '$merchantFee', payerFee = '$payerFee', cardNumber = '$cardNumber' " .
                " WHERE  orderCode = '$orderCode'";
            $db->query($sql);
        }
        else{
        // Giao dịch thanh toán chuyển thanh toán ngay thành công => cập nhật db với mã đơn hàng $orderCode = $obj -> transactionInfo -> orderCode;
        }
    }
    /* Các trạng tháo khác */
    else {
        $sql = "UPDATE ".$ecs->table('alepay'). " SET errorCode = '$status', errorDescription = '$errorDescription', transactionCode = '$transactionCode' WHERE  orderCode = '$orderCode'";
        $db->query($sql);
    }

    // Điều kiện refund hiện chưa hoàn thiện, sẽ cập nhật sau
    /** ghi log */
    $error_log = $db->error();
    $File = "error_log.txt";
    if($error_log){
        $Handle = fopen($File, 'a');
        fwrite($Handle, $sql."\n".$error_log);
        fclose($Handle);
    }


    function sendPostData($url, $post){
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6');
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post)
            )
      );

      $result = curl_exec($ch);
      curl_close($ch);  // Seems like good practice
      return $result;
    }
?>