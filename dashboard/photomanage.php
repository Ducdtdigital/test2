<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

$act = isset($_REQUEST['act']) && !empty($_REQUEST['act']) ? $_REQUEST['act'] : 'upload';

if($act == 'upload'){
    $smarty->assign('full_page',    1);
    $smarty->assign('ur_here',      'Upload hình ảnh');
    $smarty->assign('action_link',  array());


    $smarty->display('photo_upload.htm');
}

?>