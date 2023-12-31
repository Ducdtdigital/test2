<?php

define('IN_ECS', true);

define('IS_AJAX', true);



require __DIR__. '/../includes/init.php';

require(ROOT_PATH . 'includes/cls_json.php');



if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'price')
{
    $json   = new JSON;
    $res    = array('err_msg' => '', 'result' => '', 'qty' => 1);
    $attr_id    = isset($_REQUEST['attr']) ? explode(',', $_REQUEST['attr']) : array();
    $number     = (isset($_REQUEST['number'])) ? intval($_REQUEST['number']) : 1;
    $goods_id   = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    if ($goods_id == 0)
    {
        $res['err_msg'] = $_LANG['err_change_attr'];
        $res['err_no']  = 1;
    }
    else
    {
        if ($number == 0)
        {
            $res['qty'] = $number = 1;
        }
        else
        {
            $res['qty'] = $number;
        }
        $shop_price  = get_final_price($goods_id, $number, true, $attr_id);
        $res['result'] = price_format($shop_price * $number);
    }
    die($json->encode($res));
}

/*------------------------------------------------------ */

//-- 商品购买记录ajax处理

/*------------------------------------------------------ */

if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'gotopage')

{

    $json   = new JSON;

    $res    = array('err_msg' => '', 'result' => '');

    $goods_id   = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

    $page    = (isset($_REQUEST['page'])) ? intval($_REQUEST['page']) : 1;

    if (!empty($goods_id))

    {

        $need_cache = $GLOBALS['smarty']->caching;

        $need_compile = $GLOBALS['smarty']->force_compile;

        $GLOBALS['smarty']->caching = false;

        $GLOBALS['smarty']->force_compile = true;

        /* 商品购买记录 */

        $sql = 'SELECT u.user_name, og.goods_number, oi.add_time, IF(oi.order_status IN (2, 3, 4), 0, 1) AS order_status ' .

               'FROM ' . $ecs->table('order_info') . ' AS oi LEFT JOIN ' . $ecs->table('users') . ' AS u ON oi.user_id = u.user_id, ' . $ecs->table('order_goods') . ' AS og ' .

               'WHERE oi.order_id = og.order_id AND ' . time() . ' - oi.add_time < 2592000 AND og.goods_id = ' . $goods_id . ' ORDER BY oi.add_time DESC LIMIT ' . (($page > 1) ? ($page-1) : 0) * 5 . ',5';

        $bought_notes = $db->getAll($sql);

        foreach ($bought_notes as $key => $val)

        {

            $bought_notes[$key]['add_time'] = local_date("Y-m-d G:i:s", $val['add_time']);

        }

        $sql = 'SELECT count(*) ' .

               'FROM ' . $ecs->table('order_info') . ' AS oi LEFT JOIN ' . $ecs->table('users') . ' AS u ON oi.user_id = u.user_id, ' . $ecs->table('order_goods') . ' AS og ' .

               'WHERE oi.order_id = og.order_id AND ' . time() . ' - oi.add_time < 2592000 AND og.goods_id = ' . $goods_id;

        $count = $db->getOne($sql);

        /* 商品购买记录分页样式 */

        $pager = array();

        $pager['page']         = $page;

        $pager['size']         = $size = 5;

        $pager['record_count'] = $count;

        $pager['page_count']   = $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;;

        $pager['page_first']   = "javascript:gotoBuyPage(1,$goods_id)";

        $pager['page_prev']    = $page > 1 ? "javascript:gotoBuyPage(" .($page-1). ",$goods_id)" : 'javascript:;';

        $pager['page_next']    = $page < $page_count ? 'javascript:gotoBuyPage(' .($page + 1) . ",$goods_id)" : 'javascript:;';

        $pager['page_last']    = $page < $page_count ? 'javascript:gotoBuyPage(' .$page_count. ",$goods_id)"  : 'javascript:;';

        $smarty->assign('notes', $bought_notes);

        $smarty->assign('pager', $pager);

        $res['result'] = $GLOBALS['smarty']->fetch('library/bought_notes.lbi');

        $GLOBALS['smarty']->caching = $need_cache;

        $GLOBALS['smarty']->force_compile = $need_compile;

    }

    die($json->encode($res));

}



/* Đặt hàng trước */

if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'booking')

{



    $res    = ['err_msg' => '', 'err_no' => 1];

    include_once(ROOT_PATH . 'includes/lib_clips.php');



    $booking = array(

        'goods_id'     => isset($_POST['id'])      ? intval($_POST['id'])     : 0,

        'sex'     => isset($_POST['sex'])     ? intval($_POST['sex'])     : 0,

        'goods_amount' => 1,

        'desc'         => '',

        'linkman'      => isset($_POST['linkman']) ? trim($_POST['linkman'])  : '',

        'email'        => isset($_POST['email'])   ? trim($_POST['email'])    : '',

        'tel'          => isset($_POST['tel'])     ? trim($_POST['tel'])      : '',

    );

    /** Validate booking input data */

    if($booking['email'] != '' && !is_email($booking['email'])){

        $res['err_msg'] = 'Email không hợp lệ';

    }



    if(!is_tel($booking['tel']))

    {

        $res['err_msg'] = 'Số điện thoại không hợp lệ';

    }



    if($res['err_msg'] == ''){

        if (add_booking($booking))

        {

            $res['err_msg'] = 'Đặt trước thành công, chúng tôi sẽ liên hệ ngay khi có hàng.';

            $res['err_no']  = 0;

        }else{

            $res['err_msg'] = 'Đặt trước không thành công.';

        }

    }





    $json = new JSON;

    echo $json->encode($res);

    exit;

}







?>