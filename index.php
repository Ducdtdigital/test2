<?php
define('IN_ECS', true);
$protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
require_once(dirname(__FILE__) . '/bootstrap/xss.php');
require_once(dirname(__FILE__) . '/bootstrap/request.php');
$getRequest = getRequest($_SERVER['REQUEST_URI'], $_SERVER['PHP_SELF']);
$getPath = $getRequest['getPath'];
$url = $getRequest['getUrl'];
$module = 'index'; //module default


if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.' || $protocol == 'http://') {
    $uri = $getRequest['getBaseUrl'].$url;
    withRedirect($uri);
}
//
/** TrailingSlash middleware giống của Slim Framework */
if ($getPath != '/' && substr($getPath, -1) == '/') {
    $uri = $getRequest['getBaseUrl'].substr($getPath, 0, -1);
    withRedirect($uri);
}

/** Route */
require_once(dirname(__FILE__) . '/bootstrap/dynamic_route.php');

// var_dump($getRequest,$module);
// exit;
/** Ecshop Init */
require_once(dirname(__FILE__) . '/includes/init.php');
/*include module*/
if(file_exists(ROOT_PATH. 'modules/'.$module.'.php')){
    // $ts = gmdate("D, d M Y H:i:s") . " GMT";
    // header("Expires: $ts");
    // header("Last-Modified: $ts");
    // header("Pragma: no-cache");
    // header("Cache-Control: no-cache, must-revalidate");
    $smarty->assign('pname', $module);
    include_once ROOT_PATH. 'modules/'.$module.'.php';
}else{
    include_once ROOT_PATH. 'modules/404.php';
}
