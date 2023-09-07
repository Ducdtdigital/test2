<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);
$exc   = new exchange($ecs->table("ad"), $db, 'ad_id', 'ad_name');
$allow_suffix = array('gif', 'jpg', 'png', 'jpeg', 'bmp','swf');
/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

/*------------------------------------------------------ */
//-- 广告列表页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $pid = !empty($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;

    $smarty->assign('ur_here',     $_LANG['ad_list']);
    $smarty->assign('action_link', array('text' => $_LANG['ads_add'], 'href' => 'ads.php?act=add'));
    $smarty->assign('pid',         $pid);
     $smarty->assign('full_page',  1);

    $ads_list = get_adslist();

    $smarty->assign('ads_list',     $ads_list['ads']);
    $smarty->assign('filter',       $ads_list['filter']);
    $smarty->assign('record_count', $ads_list['record_count']);
    $smarty->assign('page_count',   $ads_list['page_count']);

    $sort_flag  = sort_flag($ads_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('ads_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $ads_list = get_adslist();

    $smarty->assign('ads_list',     $ads_list['ads']);
    $smarty->assign('filter',       $ads_list['filter']);
    $smarty->assign('record_count', $ads_list['record_count']);
    $smarty->assign('page_count',   $ads_list['page_count']);

    $sort_flag  = sort_flag($ads_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('ads_list.htm'), '',
        array('filter' => $ads_list['filter'], 'page_count' => $ads_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加新广告页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    admin_priv('ad_manage');

    $ad_link = empty($_GET['ad_link']) ? '' : trim($_GET['ad_link']);
    $ad_name = empty($_GET['ad_name']) ? '' : trim($_GET['ad_name']);

    $start_time = local_date('Y-m-d');
    $end_time   = local_date('Y-m-d', gmtime() + 3600 * 24 * 30);  // 默认结束时间为1个月以后

    $smarty->assign('ads',
        array('ad_link' => $ad_link, 'ad_name' => $ad_name, 'start_time' => $start_time,
            'end_time' => $end_time, 'enabled' => 1));

    $smarty->assign('ur_here',       $_LANG['ads_add']);
    $smarty->assign('action_link',   array('href' => 'ads.php?act=list', 'text' => $_LANG['ad_list']));
    $smarty->assign('position_list', get_position_list());

    $smarty->assign('form_act', 'insert');
    $smarty->assign('action',   'add');
    $smarty->assign('cfg_lang', $_CFG['lang']);

    assign_query_info();
    $smarty->display('ads_info.htm');
}

/*------------------------------------------------------ */
//-- 新广告的处理
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    admin_priv('ad_manage');

    /* 初始化变量 */
    $id      = !empty($_POST['id'])      ? intval($_POST['id'])    : 0;
    $type    = !empty($_POST['type'])    ? intval($_POST['type'])  : 0;
    $ad_name = !empty($_POST['ad_name']) ? trim($_POST['ad_name']) : '';
    $sort    = !empty($_POST['sort'])    ? trim(intval($_POST['sort'])) : 1;

    if ($_POST['media_type'] == '0')
    {
        $ad_link = !empty($_POST['ad_link']) ? trim($_POST['ad_link']) : '';
    }
    else
    {
        $ad_link = !empty($_POST['ad_link2']) ? trim($_POST['ad_link2']) : '';
    }

    /* 获得广告的开始时期与结束日期 */
    $start_time = local_strtotime($_POST['start_time']);
    $end_time   = local_strtotime($_POST['end_time']);

    /* 查看广告名称是否有重复 */
    $sql = "SELECT COUNT(*) FROM " .$ecs->table('ad'). " WHERE ad_name = '$ad_name'";
    if ($db->getOne($sql) > 0)
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['ad_name_exist'], 0, $link);
    }

    /* 添加图片类型的广告 */
    if ($_POST['media_type'] == '0')
    {
        /* hình bản desktop */
        if ((isset($_FILES['ad_img']['error']) && $_FILES['ad_img']['error'] == 0) || (!isset($_FILES['ad_img']['error']) && isset($_FILES['ad_img']['tmp_name'] ) &&$_FILES['ad_img']['tmp_name'] != 'none'))
        {
            $img_new_name = build_slug($_POST['ad_name']).'-'. gmtime();
            $ad_code = basename($image->upload_image($_FILES['ad_img'], CDN_PATH.'/afficheimg',  $img_new_name));
        }
        if (!empty($_POST['img_url']))
        {
            $ad_code = $_POST['img_url'];
        }
        if (((isset($_FILES['ad_img']['error']) && $_FILES['ad_img']['error'] > 0) || (!isset($_FILES['ad_img']['error']) && isset($_FILES['ad_img']['tmp_name']) && $_FILES['ad_img']['tmp_name'] == 'none')) && empty($_POST['img_url']))
        {
            $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
            sys_msg($_LANG['js_languages']['ad_photo_empty'], 0, $link);
        }
        /* hình bản mobile */
        if ((isset($_FILES['ad_img_mobile']['error']) && $_FILES['ad_img_mobile']['error'] == 0) || (!isset($_FILES['ad_img_mobile']['error']) && isset($_FILES['ad_img_mobile']['tmp_name'] ) &&$_FILES['ad_img_mobile']['tmp_name'] != 'none'))
        {
            $img_new_name = build_slug($_POST['ad_name']).'-m-'. gmtime();
            $ad_code_mobile = basename($image->upload_image($_FILES['ad_img_mobile'], CDN_PATH.'/afficheimg',  $img_new_name));
        }
        if (!empty($_POST['img_url_mobile']))
        {
            $ad_code_mobile = $_POST['img_url_mobile'];
        }

    }

    /* 如果广告类型为代码广告 */
    elseif ($_POST['media_type'] == '2')
    {
        if (!empty($_POST['ad_code']))
        {
            $ad_code = $_POST['ad_code'];
            $ad_code_mobile = $_POST['ad_code_mobile'];
        }
        else
        {
            $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
            sys_msg($_LANG['js_languages']['ad_code_empty'], 0, $link);
        }
    }

    /* 广告类型为文本广告 */
    elseif ($_POST['media_type'] == '3')
    {
        if (!empty($_POST['ad_text']))
        {
            $ad_code = $_POST['ad_text'];
            $ad_code_mobile = $_POST['ad_text_mobile'];
        }
        else
        {
            $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
            sys_msg($_LANG['js_languages']['ad_text_empty'], 0, $link);
        }
    }

    /* 插入数据 */
    $sql = "INSERT INTO ".$ecs->table('ad'). " (position_id,media_type,ad_name,ad_link,ad_code,ad_code_mobile,start_time,end_time,link_man,link_email,link_phone,click_count,enabled,ad_sort)
    VALUES ('$_POST[position_id]',
            '$_POST[media_type]',
            '$ad_name',
            '$ad_link',
            '$ad_code',
            '$ad_code_mobile',
            '$start_time',
            '$end_time',
            '',
            '',
            '',
            '0',
            '1',
            $sort)";

    $db->query($sql);
    /* 记录管理员操作 */
    admin_log($_POST['ad_name'], 'add', 'ads');

    clear_cache_files(); // 清除缓存文件

    /* 提示信息 */

    $link[0]['text'] = $_LANG['show_ads_template'];
    $link[0]['href'] = 'template.php?act=setup';

    $link[1]['text'] = $_LANG['back_ads_list'];
    $link[1]['href'] = 'ads.php?act=list';

    $link[2]['text'] = $_LANG['continue_add_ad'];
    $link[2]['href'] = 'ads.php?act=add';
    sys_msg($_LANG['add'] . "&nbsp;" .$_POST['ad_name'] . "&nbsp;" . $_LANG['attradd_succed'],0, $link);

}

/*------------------------------------------------------ */
//-- 广告编辑页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('ad_manage');

    /* 获取广告数据 */
    $sql = "SELECT * FROM " .$ecs->table('ad'). " WHERE ad_id='".intval($_REQUEST['id'])."'";
    $ads_arr = $db->getRow($sql);

    $ads_arr['ad_name'] = htmlspecialchars($ads_arr['ad_name']);
    /* 格式化广告的有效日期 */
    $ads_arr['start_time']  = local_date('Y-m-d', $ads_arr['start_time']);
    $ads_arr['end_time']    = local_date('Y-m-d', $ads_arr['end_time']);

    if ($ads_arr['media_type'] == '0')
    {
        if (strpos($ads_arr['ad_code'], 'http://') === false && strpos($ads_arr['ad_code'], 'https://') === false)
        {
            $src =  CDN_PATH . '/afficheimg/'. $ads_arr['ad_code'];
            $smarty->assign('img_src', $src);
        }
        else
        {
            $src = $ads_arr['ad_code'];
            $smarty->assign('url_src', $src);
        }
        /* mobile */
        if (strpos($ads_arr['ad_code_mobile'], 'http://') === false && strpos($ads_arr['ad_code_mobile'], 'https://') === false)
        {
            $src_mobile =  CDN_PATH . '/afficheimg/'. $ads_arr['ad_code_mobile'];
            $smarty->assign('img_src_mobile', $src_mobile);
        }
        else
        {
            $src_mobile = $ads_arr['ad_code_mobile'];
            $smarty->assign('url_src_mobile', $src_mobile);
        }
    }

    if ($ads_arr['media_type'] == 0)
    {
        $smarty->assign('media_type', $_LANG['ad_img']);
    }

    elseif ($ads_arr['media_type'] == 2)
    {
        $smarty->assign('media_type', $_LANG['ad_html']);
    }
    elseif ($ads_arr['media_type'] == 3)
    {
        $smarty->assign('media_type', $_LANG['ad_text']);
    }

    $smarty->assign('ur_here',       $_LANG['ads_edit']);
    $smarty->assign('action_link',   array('href' => 'ads.php?act=list', 'text' => $_LANG['ad_list']));
    $smarty->assign('form_act',      'update');
    $smarty->assign('action',        'edit');
    $smarty->assign('position_list', get_position_list());
    $smarty->assign('ads',           $ads_arr);

    assign_query_info();
    $smarty->display('ads_info.htm');
}

/*------------------------------------------------------ */
//-- 广告编辑的处理
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update')
{
    admin_priv('ad_manage');

    /* 初始化变量 */
    $id   = !empty($_POST['id'])   ? intval($_POST['id'])   : 0;
    $type = !empty($_POST['media_type']) ? intval($_POST['media_type']) : 0;
    $sort = !empty($_POST['sort'])    ? trim(intval($_POST['sort'])) : 1;

    if ($_POST['media_type'] == '0')
    {
        $ad_link = !empty($_POST['ad_link']) ? trim($_POST['ad_link']) : '';
    }
    else
    {
        $ad_link = !empty($_POST['ad_link2']) ? trim($_POST['ad_link2']) : '';
    }

    /* 获得广告的开始时期与结束日期 */
    $start_time = local_strtotime($_POST['start_time']);
    $end_time   = local_strtotime($_POST['end_time']);

    if ($type == 0)
    {
        if ((isset($_FILES['ad_img']['error']) && $_FILES['ad_img']['error'] == 0) || (!isset($_FILES['ad_img']['error']) && isset($_FILES['ad_img']['tmp_name']) && $_FILES['ad_img']['tmp_name'] != 'none'))
        {
            $img_new_name = build_slug($_POST['ad_name']).'-'. gmtime();
            $ad_code = basename($image->upload_image($_FILES['ad_img'], CDN_PATH.'/afficheimg',  $img_new_name));
            //Edit by Nobj unlink old img
            @unlink(ROOT_PATH. CDN_PATH . '/afficheimg/'.$_POST['old_ad_img']);
        }
        else
        {
            $ad_code = $_POST['old_ad_img'];
        }

        if (!empty($_POST['img_url']))
        {
            $ad_code = trim($_POST['img_url']);
        }

        /* mobile */
        if ((isset($_FILES['ad_img_mobile']['error']) && $_FILES['ad_img_mobile']['error'] == 0) || (!isset($_FILES['ad_img_mobile']['error']) && isset($_FILES['ad_img_mobile']['tmp_name']) && $_FILES['ad_img_mobile']['tmp_name'] != 'none'))
        {
            $img_new_name = build_slug($_POST['ad_name']).'-m-'. gmtime();
            $ad_code_mobile = basename($image->upload_image($_FILES['ad_img_mobile'], CDN_PATH.'/afficheimg',  $img_new_name));
            //Edit by Nobj unlink old img
            @unlink(ROOT_PATH. CDN_PATH . '/afficheimg/'.$_POST['old_ad_img_mobile']);
        }
        else
        {
            $ad_code_mobile = $_POST['old_ad_img_mobile'];
        }

        if (!empty($_POST['img_url_mobile']))
        {
            $ad_code_mobile = trim($_POST['img_url_mobile']);
        }

        $ad_code = str_replace('../' . CDN_PATH . '/afficheimg/', '', $ad_code);
        $ad_code_mobile = str_replace('../' . CDN_PATH . '/afficheimg/', '', $ad_code_mobile);

    }
    elseif ($type == 2)
    {
        $ad_code = $_POST['ad_code'];
        $ad_code_mobile = $_POST['ad_code_mobile'];
    }
    elseif ($type == 3)
    {
        $ad_code = $_POST['ad_text'];
        $ad_code_mobile = $_POST['ad_text_mobile'];
    }



    //var_dump($ad_code,$ad_code_mobile);exit;

    $sql = "UPDATE " .$ecs->table('ad'). " SET ".
            "position_id = '$_POST[position_id]', ".
            "ad_name     = '$_POST[ad_name]', ".
            "ad_link     = '$ad_link', ".
            "ad_code     = '$ad_code', ".
            "ad_code_mobile     = '$ad_code_mobile', ".
            "start_time  = '$start_time', ".
            "end_time    = '$end_time', ".
            "enabled     = '$_POST[enabled]', ".
            "ad_sort     = $sort ".
            "WHERE ad_id = '$id'";

    $db->query($sql);

   admin_log($_POST['ad_name'], 'edit', 'ads');

   clear_cache_files();

   $href[] = array('text' => $_LANG['back_ads_list'], 'href' => 'ads.php?act=list');
   sys_msg($_LANG['edit'] .' '.$_POST['ad_name'].' '. $_LANG['attradd_succed'], 0, $href);

}

/*------------------------------------------------------ */
//--生成广告的JS代码
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add_js')
{
    admin_priv('ad_manage');

    /* 编码 */
    $lang_list = array(
        'UTF8'   => $_LANG['charset']['utf8']
    );

    $js_code  = "<script type=".'"'."text/javascript".'"';
    $js_code .= ' src='.'"'.$ecs->url().'affiche.php?act=js&type='.$_REQUEST['type'].'&ad_id='.intval($_REQUEST['id']).'"'.'></script>';

    $site_url = $ecs->url().'tracking?act=js&type='.$_REQUEST['type'].'&ad_id='.intval($_REQUEST['id']);

    $smarty->assign('ur_here',     $_LANG['add_js_code']);
    $smarty->assign('action_link', array('href' => 'ads.php?act=list', 'text' => $_LANG['ad_list']));
    $smarty->assign('url',         $site_url);
    $smarty->assign('js_code',     $js_code);
    $smarty->assign('lang_list',   $lang_list);

    assign_query_info();
    $smarty->display('ads_js.htm');
}

/*------------------------------------------------------ */
//-- 编辑广告名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_ad_name')
{
    check_authz_json('ad_manage');

    $id      = intval($_POST['id']);
    $ad_name = json_str_iconv(trim($_POST['val']));

    /* 检查广告名称是否重复 */
    if ($exc->num('ad_name', $ad_name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['ad_name_exist'], $ad_name));
    }
    else
    {
        if ($exc->edit("ad_name = '$ad_name'", $id))
        {
            admin_log($ad_name,'edit','ads');
            make_json_result(stripslashes($ad_name));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}
elseif ($_REQUEST['act'] == 'edit_ad_sort')
{
    check_authz_json('ad_manage');

    $id      = intval($_POST['id']);
    $sort = json_str_iconv(trim($_POST['val']));

    if ($exc->edit("ad_sort = '$sort'", $id))
    {
        admin_log($sort,'edit','ads');
        make_json_result(stripslashes($sort));
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 删除广告位置
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('ad_manage');

    $id = intval($_GET['id']);
    $img = $exc->get_name($id, 'ad_code');
    $img_mobile = $exc->get_name($id, 'ad_code_mobile');

    $exc->drop($id);

    if ((strpos($img, 'http://') === false) && (strpos($img, 'https://') === false) && get_file_suffix($img, $allow_suffix))
    {
        $img_name = basename($img);
        @unlink(ROOT_PATH. CDN_PATH . '/afficheimg/'.$img_name);
    }

    if ((strpos($img_mobile, 'http://') === false) && (strpos($img_mobile, 'https://') === false) && get_file_suffix($img_mobile, $allow_suffix))
    {
        $img_name = basename($img_mobile);
        @unlink(ROOT_PATH. CDN_PATH . '/afficheimg/'.$img_name);
    }

    admin_log('', 'remove', 'ads');

    $url = 'ads.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/* 获取广告数据列表 */
function get_adslist()
{
    /* 过滤查询 */
    $pid = !empty($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;

    $filter = array();
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'ad.ad_name' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $where = 'WHERE 1 ';
    if ($pid > 0)
    {
        $where .= " AND ad.position_id = '$pid' ";
    }

    /* 获得总记录数据 */
    $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('ad'). ' AS ad ' . $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    /* 获得广告数据 */
    $arr = array();
    $sql = 'SELECT ad.*, COUNT(o.order_id) AS ad_stats, p.position_name '.
            'FROM ' .$GLOBALS['ecs']->table('ad'). 'AS ad ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('ad_position'). ' AS p ON p.position_id = ad.position_id '.
            'LEFT JOIN ' . $GLOBALS['ecs']->table('order_info'). " AS o ON o.from_ad = ad.ad_id $where " .
            'GROUP BY ad.ad_id '.
            'ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
         /* 广告类型的名称 */
         $rows['type']  = ($rows['media_type'] == 0) ? $GLOBALS['_LANG']['ad_img']   : '';
         $rows['type'] .= ($rows['media_type'] == 1) ? $GLOBALS['_LANG']['ad_flash'] : '';
         $rows['type'] .= ($rows['media_type'] == 2) ? $GLOBALS['_LANG']['ad_html']  : '';
         $rows['type'] .= ($rows['media_type'] == 3) ? $GLOBALS['_LANG']['ad_text']  : '';

         /* 格式化日期 */
         $rows['start_date']    = local_date($GLOBALS['_CFG']['date_format'], $rows['start_time']);
         $rows['end_date']      = local_date($GLOBALS['_CFG']['date_format'], $rows['end_time']);

         $arr[] = $rows;
    }

    return array('ads' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>