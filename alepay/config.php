<?php

define('URL_CALLBACK', $base_path . 'alepay/result.php'); // URL đón nhận kết quả
/* For Live */
$config = array(
    "apiKey" => "IOWcvHzGzwls87j853JROxbIBxCd4D",
    //Là Tokenkey dùng để xác định tài khoản nào đang được sử dụng.
    "encryptKey" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCv7VurnsI5pOKHGdLfvVRhVhE+EG12f2nGkYZTanlmjLG5aKKoF3FgqF8VOMpDBp+icEBaSi9+GlZaDu7mTvZRDlaqzsxOWIncVHB8wZG2/ZthY774oC55UzgLXZvB+AU7s7bpe+JGlWzqK6JYJRr+Bi8iKYsEVydvBbgP25P1lwIDAQAB",
    //Là key dùng để mã hóa dữ liệu truyền tới Alepay.
    "checksumKey" => "FD2tPlDXp0baVkMuAgQdwXZmgoUGGj",
    //Là key dùng để tạo checksum data.
    "callbackUrl" => URL_CALLBACK,
    "env" => "live",
);

/* For Test */
// $config = array(
//     "apiKey" => "48I3hJXQvbzToWuSrRNzyoWduYnTW2",
//     //Là Tokenkey dùng để xác định tài khoản nào đang được sử dụng.
//     "encryptKey" => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCqXkBx6+5ebqJRKv8v4dNylzR4TN5+iXdE5uS4exOmcSOrfVt1zSQAZ/tc42Qaq4HXfmVQE+nXjBENQ5Z4Te3fkWSXT2wsjFeUeL7GTy4clEa8q1VcdrAvP/u50kXj8ER7XJYVfd2KtBKM34eFjMQgph8wkTLJJCdC4sVg4xg1BQIDAQAB",
//     //Là key dùng để mã hóa dữ liệu truyền tới Alepay.
//     "checksumKey" => "7vIWUIFys59Mtrmls13OFwOUHtfufW",
//     //Là key dùng để tạo checksum data.
//     "callbackUrl" => URL_CALLBACK,
//     "env" => "test",
// );
?>