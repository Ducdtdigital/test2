<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$exc = new exchange($ecs->table('codecard'), $db, 'id', 'serial_number');


/*------------------------------------------------------ */
//-- Danh sach sim
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      'Danh sách Code Game');
    $smarty->assign('action_link',  array('text' => 'Thêm Code Game', 'href' => 'codecard_manage.php?act=add'));
    $smarty->assign('full_page',    1);

    $codecard_list = get_codecardlist();
    $smarty->assign('codecard_list',  $codecard_list['codecard']);
    $smarty->assign('filter',       $codecard_list['filter']);
    $smarty->assign('record_count', $codecard_list['record_count']);
    $smarty->assign('page_count',   $codecard_list['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($codecard_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('codecard_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $codecard_list = get_codecardlist();
    $smarty->assign('codecard_list',  $codecard_list['codecard']);
    $smarty->assign('filter',       $codecard_list['filter']);
    $smarty->assign('record_count', $codecard_list['record_count']);
    $smarty->assign('page_count',   $codecard_list['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($codecard_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('codecard_list.htm'), '',
        array('filter' => $codecard_list['filter'], 'page_count' => $codecard_list['page_count']));
}


/*------------------------------------------------------ */
//-- Xoa tung cai
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('codecard_manage');

    $id = intval($_GET['id']);
    $name = $exc->get_name($id);
    $exc->drop($id);

    //admin_log($name, 'remove', 'codecard');
    clear_cache_files();
    $url = 'codecard_manage.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}


/*------------------------------------------------------ */
//-- Them va chinh sua
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    admin_priv('codecard_manage');

    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('form_action', $is_add ? 'insert' : 'update');

    if ($is_add)
    {
        $codecard = array(
            'id'     => 0,
            'cat_id'     => 0,
            'goods_sn'   => '',
            'encrypt'   => '',
            'serial_number' => '',
            'note'   => '',
            'is_sold'   => 0
        );
    }
    else
    {
        if (empty($_GET['id']))
        {
            sys_msg('invalid param');
        }

        $id = $_GET['id'];
        $sql = "SELECT co.*, g.goods_name FROM " . $ecs->table('codecard') . " AS co ".
        " LEFT JOIN ".$ecs->table('goods'). " AS g ON g.goods_sn = co.goods_sn  ".
        " WHERE co.id = '$id'";
        $codecard = $db->getRow($sql);

        if (empty($codecard))
        {
            sys_msg('codecard does not exist');
        }

        if($codecard['is_sold'] == 1){
            $codecard['time_sold'] =date('d-m-Y H:s:i',$codecard['time_sold']);
        }
        $codecard['encrypt'] = ende('decrypt',$codecard['encrypt']);


    }
    $smarty->assign('cat_list', getCatCard());
    $smarty->assign('codecard', $codecard);

    if ($is_add)
    {
        $smarty->assign('ur_here', 'Thêm Code Game');
    }
    else
    {
        $smarty->assign('ur_here', 'Sửa Code Game');
    }
    if ($is_add)
    {
        $href = 'codecard_manage.php?act=list';
    }
    else
    {
        $href = 'codecard_manage.php?act=list&' . list_link_postfix();
    }
    $smarty->assign('action_link', array('href' => $href, 'text' => 'Danh sách Code Game'));
    assign_query_info();
    $smarty->display('codecard_info.htm');
}

/*------------------------------------------------------ */
//-- Them moi, Update
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    admin_priv('codecard_manage');

    $is_add = $_REQUEST['act'] == 'insert';

    $codecard = array(
            'id'     => intval($_POST['id']),
            'cat_id'     => isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0,
            'goods_sn'   => filter_var($_POST['goods_sn'], FILTER_SANITIZE_STRING),
            'note'   => filter_var($_POST['note'], FILTER_SANITIZE_STRING),
            'encrypt'   => filter_var($_POST['encrypt'], FILTER_SANITIZE_STRING),
            'serial_number'   => filter_var($_POST['serial_number'], FILTER_SANITIZE_STRING),
            'is_sold'   => isset($_POST['is_sold']) ? intval($_POST['is_sold']) : 0
    );

    /* Ma hoa encrypt */

    if($codecard['is_sold'] == 1){
        $codecard['time_sold'] = time();
    }

    if (!$exc->is_only('serial_number', $codecard['serial_number'], $codecard['id']))
    {
        sys_msg('Code Game này đã tồn tại');
    }

    if (empty($codecard['cat_id']))
    {
        sys_msg('Chưa chọn danh mục thẻ');
    }

    if ($is_add && empty($codecard['goods_sn']))
    {
        sys_msg('Chưa chọn mã thẻ');
    }

    $codecard['encrypt'] = ende('encrypt',$codecard['encrypt']);

    if ($is_add){
        $codecard['add_time'] = time();
        $db->autoExecute($ecs->table('codecard'), $codecard, 'INSERT');
        $codecard['id'] = $db->insert_id();
    }else{

        $old_goods_sn = filter_var($_POST['old_goods_sn'], FILTER_SANITIZE_STRING);
        $codecard['goods_sn'] =  $codecard['goods_sn'] == 0 ? $old_goods_sn : $codecard['goods_sn'];

        $db->autoExecute($ecs->table('codecard'), $codecard, 'UPDATE', "id = '$codecard[id]'");
    }

    // if ($is_add)
    //     admin_log($codecard['codecard_name'], 'add', 'codecard');
    // else
    //     admin_log($codecard['codecard_name'], 'edit', 'codecard');

    clear_cache_files();

    if ($is_add)
    {
        $links = array(
            array('href' => 'codecard_manage.php?act=add', 'text' => 'Tiếp tục thêm Code Game'),
            array('href' => 'codecard_manage.php?act=list', 'text' => 'Danh sách Code Game')
        );
        sys_msg('Thêm Code Game thành công !', 0, $links);
    }
    else
    {
        $links = array(
            array('href' => 'codecard_manage.php?act=list&' . list_link_postfix(), 'text' => 'Danh sách Code Game')
        );
        sys_msg('Chỉnh sửa Code Game thành công !', 0, $links);
    }
}
elseif ($_REQUEST['act'] == 'toggle_is_sold')
{
    check_authz_json('codecard_manage');
    $id       = intval($_POST['id']);
    $is_sold        = intval($_POST['val']);
    if ($exc->edit("is_sold = '$is_sold', time_sold=" .time(), $id))
    {
        clear_cache_files();
        make_json_result($is_sold);
    }
}

elseif ($_REQUEST['act'] == 'loadcard')
{
    check_authz_json('codecard_manage');
    $cat_id       = intval($_GET['cat_id']);

    $sql = "SELECT goods_sn, goods_id, goods_name FROM ". $ecs->table('goods') ." WHERE cat_id = '$cat_id' ";
    $arr = $db->getAll($sql);

    make_json_result($arr);

}



function getCatCard($parent_id = 29){
    $res = $GLOBALS['db']->getAll("SELECT cat_id, cat_name FROM " .$GLOBALS['ecs']->table('category'). " WHERE parent_id = '$parent_id'");
    $arr = array();
    foreach ($res as $key => $rows) {
        $arr[$rows['cat_id']] = $rows['cat_name'];
    }
    return $arr;
}
/**
 * Lay sanh sach sim
 * @return  array
 */
function get_codecardlist()
{
    $result = get_filter();
    if ($result === false)
    {

        $filter = array();
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'co.id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['serial_number']    = empty($_REQUEST['serial_number']) ? '' : filter_var($_REQUEST['serial_number'], FILTER_SANITIZE_STRING);
        $filter['is_sold']    = empty($_REQUEST['is_sold']) ? '' : intval($_REQUEST['is_sold']);

        $where = '';
        if (!empty($filter['serial_number']))
        {
            $where .= " AND co.serial_number LIKE '%" . mysql_like_quote($filter['serial_number']) . "%' ";
        }
        if (!empty($filter['is_sold']))
        {
            $where .= " AND co.is_sold = $filter[is_sold] ";
        }

        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('codecard'). " AS co ".
        " LEFT JOIN ".$GLOBALS['ecs']->table('category'). " AS c ON c.cat_id = co.cat_id ".
        " LEFT JOIN ".$GLOBALS['ecs']->table('goods'). " AS g ON g.goods_sn = co.goods_sn  ".
        " WHERE 1 $where ";
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        $filter = page_and_size($filter);

        $sql = "SELECT co.*, c.cat_name, g.goods_name FROM " . $GLOBALS['ecs']->table('codecard'). " AS co ".
        " LEFT JOIN ".$GLOBALS['ecs']->table('category'). " AS c ON c.cat_id = co.cat_id ".
        " LEFT JOIN ".$GLOBALS['ecs']->table('goods'). " AS g ON g.goods_sn = co.goods_sn  ".
        " WHERE 1 $where ";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['add_time'] = date('d-m-Y H:s:i', $rows['add_time']);
        $arr[] = $rows;
    }

    return array('codecard' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>