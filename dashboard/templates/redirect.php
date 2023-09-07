<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$exc = new exchange($ecs->table("redirect"), $db, 'rd_id', 'link_old');

/*------------------------------------------------------ */
//-- 品牌列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      $_LANG['11_redirect']);
    $smarty->assign('action_link',  array('text' => $_LANG['11_redirect_add'], 'href' => 'redirect.php?act=add'));
    $smarty->assign('full_page',    1);

    $redirect_list = get_brandlist();

    $smarty->assign('brand_list',   $redirect_list['redirect']);
    $smarty->assign('filter',       $redirect_list['filter']);
    $smarty->assign('record_count', $redirect_list['record_count']);
    $smarty->assign('page_count',   $redirect_list['page_count']);

    assign_query_info();
    $smarty->display('redirect_list.htm');
}

/*------------------------------------------------------ */
//-- 添加品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('redirect');

    $smarty->assign('action_link', array('text' => $_LANG['11_redirect'], 'href' => 'redirect.php?act=list'));
    $smarty->assign('form_action', 'insert');
    $smarty->display('redirect_info.htm');
}
elseif ($_REQUEST['act'] == 'insert')
{
    /*检查品牌名是否重复*/
    admin_priv('redirect');


    $is_only = $exc->is_only('link_old', $_POST['link_old']);

    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['brandname_exist'], stripslashes($_POST['link_old'])), 1);
    }

    /*对描述处理*/

     /*处理URL*/
    /*插入数据*/

    $sql = "INSERT INTO ".$ecs->table('redirect')."(link_old, link_new) ".
           "VALUES ('$_POST[link_old]', '$_POST[link_new]')";
    $db->query($sql);

    $rd_id = $db->insert_id();

    admin_log($_POST['link_old'],'add','redirect');

    /* 清除缓存 */
    clear_cache_files();

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'redirect.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'redirect.php?act=list';

    sys_msg("Thêm thành công", 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('redirect');
    $sql = "SELECT rd_id, link_old, link_new ".
            "FROM " .$ecs->table('redirect'). " WHERE rd_id='$_REQUEST[id]'";
    $redirect = $db->GetRow($sql);



    $smarty->assign('ur_here',     $_LANG['brand_edit']);
    $smarty->assign('action_link', array('text' => $_LANG['11_redirect'], 'href' => 'redirect.php?act=list&' . list_link_postfix()));
    $smarty->assign('redirect',       $redirect);
    $smarty->assign('form_action', 'updata');

    assign_query_info();
    $smarty->display('redirect_info.htm');
}
elseif ($_REQUEST['act'] == 'updata')
{
    admin_priv('redirect');
    if ($_POST['link_old'] != $_POST['old_link_old'])
    {
        /*检查品牌名是否相同*/
        $is_only = $exc->is_only('link_old', $_POST['link_old'], $_POST['id']);

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['brandname_exist'], stripslashes($_POST['link_old'])), 1);
        }
    }

    /*对描述处理*/
    if (!empty($_POST['link_new']))
    {
        $_POST['link_new'] = $_POST['link_new'];
    }

     /*处理URL*/

    $param = "link_old = '$_POST[link_old]', link_new='$_POST[link_new]' ";


    if ($exc->edit($param,  $_POST['id']))
    {
        /* 清除缓存 */
        clear_cache_files();

        admin_log($_POST['link_old'], 'edit', 'redirect');

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'redirect.php?act=list&' . list_link_postfix();
        $note = vsprintf($_LANG['brandedit_succed'], $_POST['link_old']);
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
    check_authz_json('redirect');

    $id     = intval($_POST['id']);
    $name   = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("link_old",$name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['brandname_exist'], $name));
    }
    else
    {
        if ($exc->edit("link_old = '$name'", $id))
        {
            // Update slug

            admin_log($name,'edit','redirect');
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
    $redirect = empty($_REQUEST['redirect']) ? '' : json_str_iconv(trim($_REQUEST['redirect']));

    if(brand_exists($redirect))
    {
        make_json_error($_LANG['brand_name_exist']);
    }
    else
    {
        $sql = "INSERT INTO " . $ecs->table('redirect') . "(link_old)" .
               "VALUES ( '$redirect')";

        $db->query($sql);
        $rd_id = $db->insert_id();

        $arr = array("id"=>$rd_id, "redirect"=>$redirect);

        make_json_result($arr);
    }
}
/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_sort_order')
{
    check_authz_json('redirect');

    $id     = intval($_POST['id']);
    $order  = intval($_POST['val']);
    $name   = $exc->get_name($id);

    if ($exc->edit("sort_order = '$order'", $id))
    {
        admin_log(addslashes($name),'edit','redirect');

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
    check_authz_json('redirect');

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
    check_authz_json('redirect');

    $id = intval($_GET['id']);

    /* 删除该品牌的图标 */


    $exc->drop($id);

    /* 更新商品的品牌编号 */
    $sql = "UPDATE " .$ecs->table('goods'). " SET rd_id=0 WHERE rd_id='$id'";
    $db->query($sql);

    // del slug

    $url = 'redirect.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

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
    $redirect_list = get_brandlist();
    $smarty->assign('brand_list',   $redirect_list['redirect']);
    $smarty->assign('filter',       $redirect_list['filter']);
    $smarty->assign('record_count', $redirect_list['record_count']);
    $smarty->assign('page_count',   $redirect_list['page_count']);

    make_json_result($smarty->fetch('redirect_list.htm'), '',
        array('filter' => $redirect_list['filter'], 'page_count' => $redirect_list['page_count']));
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
        if (isset($_POST['link_old']))
        {
            $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('redirect') .' WHERE link_old = \''.$_POST['link_old'].'\'';
        }
        else
        {
            $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('redirect');
        }

        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 查询记录 */
        if (isset($_POST['link_old']))
        {
            if(strtoupper(EC_CHARSET) == 'GBK')
            {
                $keyword = iconv("UTF-8", "gb2312", $_POST['link_old']);
            }
            else
            {
                $keyword = $_POST['link_old'];
            }
            $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('redirect')." WHERE link_old like '%{$keyword}%' ORDER BY rd_id DESC";
        }
        else
        {
            $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('redirect')." ORDER BY rd_id DESC";
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

    return array('redirect' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>
