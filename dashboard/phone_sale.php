<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$exc = new exchange($ecs->table("phone_sale"), $db, 'id', 'phone');

/*------------------------------------------------------ */
//-- 品牌列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      $_LANG['11_phone_sale']);
    $smarty->assign('action_link',  array('text' => $_LANG['11_phone_sale_add'], 'href' => 'phone_sale.php?act=add'));
    $smarty->assign('full_page',    1);

    $phone_sale_list = get_brandlist();

    $smarty->assign('brand_list',   $phone_sale_list['phone_sale']);
    $smarty->assign('filter',       $phone_sale_list['filter']);
    $smarty->assign('record_count', $phone_sale_list['record_count']);
    $smarty->assign('page_count',   $phone_sale_list['page_count']);

    assign_query_info();
    $smarty->display('phone_sale_list.htm');
}

/*------------------------------------------------------ */
//-- 添加品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('phone_sale');

    $smarty->assign('action_link', array('text' => $_LANG['11_phone_sale'], 'href' => 'phone_sale.php?act=list'));
    $smarty->assign('form_action', 'insert');
    $smarty->display('phone_sale_info.htm');
}
elseif ($_REQUEST['act'] == 'insert')
{
    /*检查品牌名是否重复*/
    admin_priv('phone_sale');


    $is_only = $exc->is_only('phone', $_POST['phone']);

    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['brandname_exist'], stripslashes($_POST['phone'])), 1);
    }

    /*对描述处理*/

     /*处理URL*/
    /*插入数据*/

    $sql = "INSERT INTO ".$ecs->table('phone_sale')."(phone, name,khu_vuc) ".
           "VALUES ('$_POST[phone]', '$_POST[name]', '$_POST[khu_vuc]')";
    $db->query($sql);

    $id = $db->insert_id();

    admin_log($_POST['phone'],'add','phone_sale');

    /* 清除缓存 */
    clear_cache_files();

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'phone_sale.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'phone_sale.php?act=list';

    sys_msg("Thêm thành công", 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('phone_sale');
    $sql = "SELECT id, phone, name, khu_vuc " .
       "FROM " . $ecs->table('phone_sale') . " WHERE id='$_REQUEST[id]'";
    $phone_sale = $db->GetRow($sql);



    $smarty->assign('ur_here',     $_LANG['brand_edit']);
    $smarty->assign('action_link', array('text' => $_LANG['11_phone_sale'], 'href' => 'phone_sale.php?act=list&' . list_link_postfix()));
    $smarty->assign('phone_sale',       $phone_sale);
    $smarty->assign('form_action', 'updata');

    assign_query_info();
    $smarty->display('phone_sale_info.htm');
}
elseif ($_REQUEST['act'] == 'updata')
{
    admin_priv('phone_sale');
    if ($_POST['phone'] != $_POST['old_phone'])
    {
        /*检查品牌名是否相同*/
        $is_only = $exc->is_only('phone', $_POST['phone'], $_POST['id']);

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['brandname_exist'], stripslashes($_POST['phone'])), 1);
        }
    }

    /*对描述处理*/
    if (!empty($_POST['name']))
    {
        $_POST['name'] = $_POST['name'];
    }
        if (!empty($_POST['khu_vuc']))
    {
        $_POST['khu_vuc'] = $_POST['khu_vuc'];
    }

     /*处理URL*/

    $param = "phone = '$_POST[phone]', name='$_POST[name]', khu_vuc='$_POST[khu_vuc]' ";


    if ($exc->edit($param,  $_POST['id']))
    {
        $sql = "UPDATE " . $ecs->table('catphone') . " SET phone='$_POST[phone]', name='$_POST[name]', khu_vuc='$_POST[khu_vuc]' WHERE phone_id='$_POST[id]'";
        //var_dump($sql);die;
        $db->query($sql);
        /* 清除缓存 */
        clear_cache_files();
        admin_log($_POST['phone'], 'edit', 'phone_sale');

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'phone_sale.php?act=list&' . list_link_postfix();
        $note = vsprintf("Chỉnh sửa thành công", $_POST['phone']);
        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑品牌名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_brand_name')
{
    check_authz_json('phone_sale');

    $id     = intval($_POST['id']);
    $name   = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("phone",$name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['brandname_exist'], $name));
    }
    else
    {
        if ($exc->edit("phone = '$name'", $id))
        {
            // Update slug

            admin_log($name,'edit','phone_sale');
            make_json_result(stripslashes($name));
        }
        else
        {
            make_json_result(sprintf($_LANG['brandedit_fail'], $name));
        }
    }
}

elseif($_REQUEST['act'] == 'add_brand')
{
    $phone_sale = empty($_REQUEST['phone_sale']) ? '' : json_str_iconv(trim($_REQUEST['phone_sale']));

    if(brand_exists($phone_sale))
    {
        make_json_error($_LANG['brand_name_exist']);
    }
    else
    {
        $sql = "INSERT INTO " . $ecs->table('phone_sale') . "(phone)" .
               "VALUES ( '$phone_sale')";

        $db->query($sql);
        $id = $db->insert_id();

        $arr = array("id"=>$id, "phone_sale"=>$phone_sale);

        make_json_result($arr);
    }
}
/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_sort_order')
{
    check_authz_json('phone_sale');

    $id     = intval($_POST['id']);
    $order  = intval($_POST['val']);
    $name   = $exc->get_name($id);

    if ($exc->edit("sort_order = '$order'", $id))
    {
        admin_log(addslashes($name),'edit','phone_sale');

        make_json_result($order);
    }
    else
    {
        make_json_error(sprintf($_LANG['brandedit_fail'], $name));
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_show')
{
    check_authz_json('phone_sale');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_show='$val'", $id);

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('phone_sale');

    $id = intval($_GET['id']);
    
    /* 删除该品牌的图标 */


    $exc->drop($id);
   $sql = "DELETE FROM " . $ecs->table('catphone') . " WHERE phone_id='$id'";
    $db->query($sql);
    /* 更新商品的品牌编号 */
    $sql = "UPDATE " .$ecs->table('goods'). " SET id=0 WHERE id='$id'";
    $db->query($sql);
  //var_dump($id);die;
    // del slug

    $url = 'phone_sale.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 删除品牌图片
/*------------------------------------------------------ */

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $phone_sale_list = get_brandlist();
    $smarty->assign('brand_list',   $phone_sale_list['phone_sale']);
    $smarty->assign('filter',       $phone_sale_list['filter']);
    $smarty->assign('record_count', $phone_sale_list['record_count']);
    $smarty->assign('page_count',   $phone_sale_list['page_count']);

    make_json_result($smarty->fetch('phone_sale_list.htm'), '',
        array('filter' => $phone_sale_list['filter'], 'page_count' => $phone_sale_list['page_count']));
}

/**
 * 获取品牌列表
 *
 * @access  public
 * @return  array
 */
function get_brandlist()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 分页大小 */
        $filter = array();

        /* 记录总数以及页数 */
        if (isset($_POST['phone']))
        {
            $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('phone_sale') .' WHERE phone = \''.$_POST['phone'].'\'';
        }
        else
        {
            $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('phone_sale');
        }

        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 查询记录 */
        if (isset($_POST['phone']))
        {
            if(strtoupper(EC_CHARSET) == 'GBK')
            {
                $keyword = iconv("UTF-8", "gb2312", $_POST['phone']);
            }
            else
            {
                $keyword = $_POST['phone'];
            }
            $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('phone_sale')." WHERE phone like '%{$keyword}%' ORDER BY id DESC";
        }
        else
        {
            $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('phone_sale')." ORDER BY id DESC";
        }

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


        $arr[] = $rows;
    }

    return array('phone_sale' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>
