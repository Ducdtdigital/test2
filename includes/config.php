<?php
$db_host   = "localhost";
$db_name   = "thbvietnam_1s";
$db_user   = "thbvietnam_1s";
$db_pass   = "thbvietnam_1s";
$prefix    = "nb_";
$timezone    = "Asia/Ho_Chi_Minh";
/** Cookie setting */
$cookie_path    = "/";
$cookie_domain    = "thbvietnam.com";
$cookie_secure = isset($_SERVER['HTTPS']);
$cookie_http_only = false;
/** Session setting */
$session = "3600";
/** Base path */
$base_path = 'https://thbvietnam.com/';
$base_cdn = "https://thbvietnam.com/cdn/"; //hoặc subdomain https://cdn.domain.com/
$base_cdn1 = "https://thbvietnam.com/cdn1/"; //hoặc subdomain https://cdn1.domain.com/
/** Facebook App ID */
$fb_app_id = '632829977218463';
/** Google Analytics ID for AMP */
$google_analytics_id = '';
$open_time = '7:30 - 22:00';
/**
 * KHÔNG THAY ĐỔI TỪ ĐÂY XUỐNG
 */
define('CDN_PATH', 'cdn'); /* Ueditor Upload, Banner quảng cáo, Logo brand */
define('CDN_PATH1', 'cdn1'); /* goods_thumb, article_thumb */
define('EC_CHARSET','utf-8');
define('ADMIN_PATH','dashboard');
define('AUTH_KEY', 'this is a key');
define('OLD_AUTH_KEY', '');
define('API_TIME', '2017-07-22 11:26:21');
define('FRENDLY_MSG', 'Have something were wrong, please notify to the Administrator ! <br/><b>admin@webmaster.com</b>');
define('MAIL_ADMIN', 'admin@webmaster.com');
?>