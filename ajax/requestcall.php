<?php

define('IN_ECS', true);
define('IS_AJAX', true);
require __DIR__. '/../includes/init.php';
if(isset($_POST['tel']))
{
    $tel =  addslashes($_POST['tel']);
    $ref = filter_var($_POST['url'], FILTER_SANITIZE_URL);
    $ip=$_SERVER["REMOTE_ADDR"];
    if(reismobile())$device='mobile';else $device='pc';

    $msg_content = "Đã ĐK tại link: <a target='_blank' href='$ref'>$ref</a> ($device | IP: $ip)";
    $feedback = array(
        'parent_id' => 0,
        'user_id' => 0,
        'user_tel' => $tel,
        'user_name' => 'Khách lạ',
        'user_email' => '',
        'msg_title' => 'Tôi muốn gọi lại tư vấn',
        'msg_typ'=> 2,
        'msg_content' => addslashes($msg_content),
        'msg_time'=> time(),
        'message_img' => '',
        'order_id' => 0,
        'msg_area' => 0
    );
    $db->autoExecute($ecs->table('feedback'), $feedback, 'INSERT');

    $feedback['err'] = $db->error();
    die(json_encode($feedback));
}
function reismobile()
{
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    $uachar = "/(nokia|asus|sony|ericsson|microsoft|samsung|oppo|lg|philips|panasonic|alcatel|lenovo|iphone|htc|mobile|android)/i";
    if((isset($_COOKIE['WEB_VERSION']) && $_COOKIE['WEB_VERSION'] == 'mobile') || preg_match($uachar, $ua)) return true;return false;
}
?>