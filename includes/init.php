<?php
if (!defined('IN_ECS')) {die('Hacking attempt'); }

define('ROOT_PATH', str_replace('includes/init.php', '', str_replace('\\', '/', __FILE__)));

if (DIRECTORY_SEPARATOR == '\\')
{
    @ini_set('include_path', '.;' . ROOT_PATH);
}
else
{
    @ini_set('include_path', '.:' . ROOT_PATH);
}
require(ROOT_PATH . 'includes/config.php');
date_default_timezone_set($timezone);

if (!defined('DEBUG_MODE')){
    define('DEBUG_MODE', 0);
}
error_reporting(E_ALL | E_STRICT);
if (DEBUG_MODE && DEBUG_MODE == 1){
    ini_set('display_errors', php_sapi_name() === 'cli');
    /* for debug all error */
    require(ROOT_PATH . 'includes/cls_logger.php');
    require(ROOT_PATH . 'includes/cls_error_handle.php');
    $errorh =  new ErrorHandler;
    $errorh->register();
}elseif(DEBUG_MODE && DEBUG_MODE == 2){
    ini_set('display_errors', 1);
}else{
    ini_set('display_errors', 0);
}

@ini_set('memory_limit',          '640M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);





$requri = filter_var(ltrim($_SERVER['REQUEST_URI'],"/"), FILTER_SANITIZE_URL);
$_device = '';
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$uachar = "/(nokia|asus|sony|ericsson|microsoft|samsung|oppo|lg|philips|panasonic|alcatel|lenovo|iphone|htc|mobile|android)/i";

if (!defined('IS_AJAX'))
{
    //$requri = ltrim($_SERVER['REQUEST_URI'],"/");
    /** Xử lý link chuyển hưởng mobile/Desktop */
    $_redirect = $requri;
    if(preg_match('/((\?|\&)client=(mobile|full))/',$requri, $m)){
        $_redirect = str_replace($m[1], '', $requri);
    }
    $vtri = strpos($_redirect, 'client')?:0;
    $hoi = preg_match('/\?/',$_redirect);
    $_redirect = $hoi && $vtri <> 1 ?  $_redirect.'&' :  $_redirect.'?';
    $swich_mobile = $_redirect;
}

/** Xử lí chuyển hướng thiết bị */
$client = isset($_GET['client']) ? $_GET['client'] : 'pc';
$_device = '';
if((!isset($_COOKIE['WEB_VERSION']) && $client == 'mobile') || preg_match($uachar, $ua)){
    setcookie('WEB_VERSION', 'mobile', time()+(84000*30), $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
    $_device = '_mobile';
}elseif (isset($_COOKIE['WEB_VERSION']) && $_COOKIE['WEB_VERSION'] == 'mobile') {
    $_device = '_mobile';
}
$module = isset($module) ? $module : 'index';
define('CURRENT_TEMPLATE', $module.$_device);
/* end */

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1))
{
    $php_self .= 'index.php';
}
define('PHP_SELF', $php_self);

/* dung cho alert bên dưới và hàm show_message() */
if((isset($_COOKIE['WEB_VERSION']) && $_COOKIE['WEB_VERSION'] == 'mobile') || preg_match($uachar, $ua)){
    $_ismobile = '_mobile';
}else{
    $_ismobile = '';
}

/** Khoi tao cac dir chua file temp */
if (!file_exists(ROOT_PATH.'temp/caches'))
    @mkdir(ROOT_PATH.'temp/caches', 0755, true);

if (!file_exists(ROOT_PATH.'temp/compiled'))
    @mkdir(ROOT_PATH.'temp/compiled', 0755, true);

if (!file_exists(ROOT_PATH.'temp/static_caches'))
    @mkdir(ROOT_PATH.'temp/static_caches', 0755, true);

if (!file_exists(ROOT_PATH.'temp/query_caches'))
    @mkdir(ROOT_PATH.'temp/query_caches', 0755, true);

if (!file_exists(ROOT_PATH.'temp/backup'))
    @mkdir(ROOT_PATH.'temp/backup', 0755, true);

if (!file_exists(ROOT_PATH . 'static/disc/js'))
    @mkdir(ROOT_PATH . 'static/disc/js', 0755, true);

require(ROOT_PATH . 'includes/inc_constant.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_error.php');
require(ROOT_PATH . 'includes/lib_time.php');
require(ROOT_PATH . 'includes/lib_base.php');
require(ROOT_PATH . 'includes/lib_common.php');
require(ROOT_PATH . 'includes/lib_main.php');
require(ROOT_PATH . 'includes/lib_insert.php');
require(ROOT_PATH . 'includes/lib_goods.php');
require(ROOT_PATH . 'includes/lib_article.php');
/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }
    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}
/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);
define('DATA_DIR', 'data');
define('IMAGE_DIR', CDN_PATH1.'/images');
/* 初始化数据库类 */
require(ROOT_PATH . 'includes/cls_mysqli.php');
$db = new cls_mysqli($db_host, $db_user, $db_pass, $db_name);
$db->set_disable_cache_tables(array($ecs->table('sessions'), $ecs->table('sessions_data'), $ecs->table('cart')));
$db_host = $db_user = $db_pass = $db_name = NULL;

$err = new ecs_error('message'.$_ismobile.'.dwt');

$_CFG = load_config();

require(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/common.php');
include(ROOT_PATH . 'themes/' . $_CFG['template'].'/lang/' . $_CFG['lang'] . '/common.php');
if ($_CFG['shop_closed'] == 1)
{

    header('Content-type: text/html; charset='.EC_CHARSET);
    die('<div style="margin: 150px; text-align: center; font-size: 14px"><p>' . $_LANG['shop_closed'] . '</p><p>' . $_CFG['close_comment'] . '</p></div>');
}

if (!defined('INIT_NO_USERS'))
{
    /* 初始化session */
    include(ROOT_PATH . 'includes/cls_session.php');
    $sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'));
    define('SESS_ID', $sess->get_session_id());
}
if(isset($_SERVER['PHP_SELF']))
{
    $_SERVER['PHP_SELF']=htmlspecialchars($_SERVER['PHP_SELF']);
}

if (!defined('INIT_NO_SMARTY'))
{
    /*  Smarty Lite*/
    require(ROOT_PATH . 'includes/cls_template.php');
    $smarty = new cls_template();
    $smarty->cache_lifetime = $_CFG['cache_time'];
    $smarty->template_dir   = ROOT_PATH . 'themes/' . $_CFG['template'];
    $smarty->cache_dir      = ROOT_PATH . 'temp/caches';
    $smarty->compile_dir    = ROOT_PATH . 'temp/compiled';
    $smarty->baseUrl        = $base_path;
    $smarty->assets_dir     = ROOT_PATH . 'static';
    $smarty->direct_output  = (DEBUG_MODE & 2) == 2 ? true :false;
    $smarty->force_compile  = (DEBUG_MODE & 2) == 2 ? true :false;

    $smarty->assign('lang', $_LANG);
    $smarty->assign('ecs_charset', EC_CHARSET);
    $smarty->assign('request_uri', $requri);
    $smarty->assign('fb_app_id', $fb_app_id);
    $smarty->assign('gg_id', $google_analytics_id);
    $smarty->assign('base_cdn', $base_cdn);
    $smarty->assign('ismobile', $_device);
    $smarty->assign('opentime', $open_time);

    if (!defined('IS_AJAX'))
    {
        $smarty->assign('swich_mobile', $swich_mobile);
        if($client == 'full'){
            setcookie('WEB_VERSION', 'mobile', time()-(84000*30),$cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
            $_redirect = $hoi && $vtri <> 1 ? rtrim($_redirect, '&') : rtrim($_redirect, '?');
            ecs_header("Location: /$_redirect");
        }
        $smarty->assign('canonical', $base_path.$url);
    }

}
if (!defined('INIT_NO_USERS'))
{
    /* 会员信息 */
    $user =& init_users();
    if (!isset($_SESSION['user_id']))
    {
        /* 获取投放站点的名称 */
        $site_name = isset($_GET['from'])   ? $_GET['from'] : addslashes($_LANG['self_site']);
        $from_ad   = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0;
        $_SESSION['from_ad'] = $from_ad; // 用户点击的广告ID
        $_SESSION['referer'] = stripslashes($site_name); // 用户来源
        unset($site_name);
        // if (!defined('INGORE_VISIT_STATS'))
        // {
        //     visit_stats();
        // }
    }
    if (empty($_SESSION['user_id']))
    {
        if ($user->get_cookie())
        {
            /* 如果会员已经登录并且还没有获得会员的帐户余额、积分以及优惠券 */
            if ($_SESSION['user_id'] > 0)
            {
                update_user_info();
            }
        }
        else
        {
            $_SESSION['user_id']     = 0;
            $_SESSION['user_name']   = '';
            $_SESSION['email']       = '';
            $_SESSION['user_rank']   = 0;
            $_SESSION['discount']    = 1.00;
            if (!isset($_SESSION['login_fail']))
            {
                $_SESSION['login_fail'] = 0;
            }
        }
    }
    /* 设置推荐会员 */
    // if (isset($_GET['u']))
    // {
    //     set_affiliate();
    // }
    /* session 不存在，检查cookie */
    if (!empty($_COOKIE['ECS']['user_id']) && !empty($_COOKIE['ECS']['password']))
    {
        // 找到了cookie, 验证cookie信息
        $sql = 'SELECT user_id, user_name, password ' .
                ' FROM ' .$ecs->table('users') .
                " WHERE user_id = '" . intval($_COOKIE['ECS']['user_id']) . "' AND password = '" .$_COOKIE['ECS']['password']. "'";
        $row = $db->GetRow($sql);
        if (!$row)
        {
            // 没有找到这个记录
           $time = time() - 3600;
           setcookie("ECS[user_id]",  '', $time, $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
           setcookie("ECS[password]", '', $time, $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
        }
        else
        {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            update_user_info();
        }
    }
    if (isset($smarty))
    {
        $smarty->assign('ecs_session', $_SESSION);
    }
}


/* 判断是否支持 Gzip 模式 */
if (!defined('INIT_NO_SMARTY') && gzip_enabled())
{
    ob_start('ob_gzhandler');
}
else
{
    ob_start();
}
/* Shopiy */
@include(ROOT_PATH.'themes/'.$_CFG['template'].'/functions.php');
?>