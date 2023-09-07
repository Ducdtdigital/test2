<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
//require_once(ROOT_PATH . "includes/fckeditor/fckeditor.php");
require_once(ROOT_PATH . 'includes/cls_image.php');
/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("article"), $db, 'article_id', 'title');
//$image = new cls_image();
/* 允许上传的文件类型 */
$allow_file_types = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|';
$allow_image_types = array("gif", "jpg", "jpeg", "png", "bmp");
/*------------------------------------------------------ */
//-- 文章列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('cat_select',  article_cat_list(0));
    $smarty->assign('ur_here',      $_LANG['03_article_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['article_add'], 'href' => 'article.php?act=add'));
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);
    $article_list = get_articleslist();
    $smarty->assign('article_list',    $article_list['arr']);
    $smarty->assign('filter',          $article_list['filter']);
    $smarty->assign('record_count',    $article_list['record_count']);
    $smarty->assign('page_count',      $article_list['page_count']);
    $sort_flag  = sort_flag($article_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
    assign_query_info();
    $smarty->display('article_list.htm');
}
/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('article_manage');
    $article_list = get_articleslist();
    $smarty->assign('article_list',    $article_list['arr']);
    $smarty->assign('filter',          $article_list['filter']);
    $smarty->assign('record_count',    $article_list['record_count']);
    $smarty->assign('page_count',      $article_list['page_count']);
    $sort_flag  = sort_flag($article_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
    make_json_result($smarty->fetch('article_list.htm'), '',
        array('filter' => $article_list['filter'], 'page_count' => $article_list['page_count']));
}
/*------------------------------------------------------ */
//-- 添加文章
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('article_manage');
    /* 创建 html editor */
    //create_html_editor('content', '', 'FCKeditor', 'Normal', '100%', '500');
    CKeditor('content', '');

    /*初始化*/
    $article = array();
    $article['is_open'] = 1;
    /* 取得分类、品牌 */
    $smarty->assign('goods_cat_list', cat_list());
    $smarty->assign('brand_list',     get_brand_list());
    /* 清理关联商品 */
    $sql = "DELETE FROM " . $ecs->table('goods_article4') . " WHERE article_id = 0";
    $db->query($sql);
    if (isset($_GET['id']))
    {
        $smarty->assign('cur_id',  $_GET['id']);
    }
    $goods_article_list = array();
    $sql = "DELETE FROM " . $ecs->table('goods_article4') .
                " WHERE goods_id = 0 AND admin_id = '$_SESSION[admin_id]'";
    $db->query($sql);
    $smarty->assign('goods_article_list', $goods_article_list);
    $smarty->assign('article',     $article);
    $smarty->assign('cat_select',  article_cat_list(0));
    $smarty->assign('ur_here',     $_LANG['article_add']);
    $smarty->assign('action_link', array('text' => $_LANG['03_article_list'], 'href' => 'article.php?act=list'));
    $smarty->assign('form_action', 'insert');
    assign_query_info();
    $smarty->display('article_info.htm');
}
/*------------------------------------------------------ */
//-- 添加文章
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('article_manage');
    /*检查是否重复*/
    $is_only = $exc->is_only('title', $_POST['title'],0, " cat_id ='$_POST[article_cat]'");
    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['title_exist'], stripslashes($_POST['title'])), 1);
    }
    /* 取得文件地址 */
    $file_url = '';
    if ((isset($_FILES['file']['error']) && $_FILES['file']['error'] == 0) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none'))
    {
        // 检查文件格式
        if (!check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types))
        {
            sys_msg($_LANG['invalid_file']);
        }
        // 复制文件
        $res = upload_article_file($_FILES['file']);
        if ($res != false)
        {
            $file_url = $res;
        }
    }

    if ($file_url == '')
    {
        $open_type = 0;
         $file_url = $_POST['file_url'];
    }
    else
    {
        $open_type = $_POST['content'] == '' ? 1 : 2;
    }

    // thumnail nobj
    $thumb = upload_thumb();

    /* filter */
    $add_time = gmtime();
    $post_time = !empty($_POST['post_time']) ? local_strtotime($_POST['post_time']) : 0;
    $title = !empty($_POST['title']) ? filter_var($_POST['title'], FILTER_SANITIZE_STRING) : '';
    $title_display = !empty($_POST['title_display']) ? filter_var($_POST['title_display'], FILTER_SANITIZE_STRING) : '';

    if (empty($_POST['cat_id']))
    {
        $_POST['cat_id'] = 0;
    }
    $sql = "INSERT INTO ".$ecs->table('article')."(title, title_display, article_thumb, article_sthumb, cat_id, article_type, is_open, is_hot, author, ".
                "author_email, keywords, content, add_time, post_time, file_url, open_type, link, description, ar_canonical) ".
            "VALUES ('$title', '$title_display','$thumb[thumb]', '$thumb[sthumb]', '$_POST[article_cat]', '$_POST[article_type]', '$_POST[is_open]', '$_POST[is_hot]', ".
                "'$_POST[author]', '$_POST[author_email]', '$_POST[keywords]', '$_POST[content]', ".
                "'$add_time', '$post_time', '$file_url', '$open_type', '$_POST[link_url]', '$_POST[description]', '$_POST[ar_canonical]')";
    $db->query($sql);
    /* San pham lien quan den tin tuc */
    $article_id = $db->insert_id();
    $sql = "UPDATE " . $ecs->table('goods_article4') . " SET article_id = '$article_id' WHERE article_id = 0";
    $db->query($sql);
    /* tin tuc lie quan den tin tuc */
    handle_goods_article($article_id);
    //Insert slug
    create_slug($article_id, 'article');
    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'article.php?act=add';
    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'article.php?act=list';
    admin_log($_POST['title'],'add','article');
    clear_cache_files(); // 清除相关的缓存文件
    sys_msg($_LANG['articleadd_succeed'],0, $link);
}
/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('article_manage');
    /* 取文章数据 */
    $sql = "SELECT * FROM " .$ecs->table('article'). " WHERE article_id='$_REQUEST[id]'";
    $article = $db->GetRow($sql);

    $article['post_time'] = local_date('Y-m-d H:i:s', $article['post_time']);
    /* html editor */
    CKeditor('content', html_entity_decode($article['content']));
    /* 取得分类、品牌 */
    $smarty->assign('goods_cat_list', cat_list());
    $smarty->assign('brand_list', get_brand_list());
    $slug = get_slug($_REQUEST['id'], 'article');
    $smarty->assign('slug', $slug);
    /* 取得关联商品 */
    $goods_list = get_article_goods($_REQUEST['id']);
    /* bài viết liên quan */
    $goods_article_list = get_goods_articles($_REQUEST['id']);
        $goods_article_list = get_goods_articles3($_REQUEST['id']);
    $smarty->assign('goods_article_list', $goods_article_list);
    $smarty->assign('goods_list', $goods_list);
    $smarty->assign('article',     $article);
    $smarty->assign('cat_select',  article_cat_list(0, $article['cat_id']));
    $smarty->assign('ur_here',     $_LANG['article_edit']);
    $smarty->assign('action_link', array('text' => $_LANG['03_article_list'], 'href' => 'article.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');
    assign_query_info();
    $smarty->display('article_info.htm');
}
if ($_REQUEST['act'] =='update')
{
    /* 权限判断 */
    admin_priv('article_manage');
    /*检查文章名是否相同*/
    $is_only = $exc->is_only('title', $_POST['title'], $_POST['id'], "cat_id = '$_POST[article_cat]'");
    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['title_exist'], stripslashes($_POST['title'])), 1);
    }
    if (empty($_POST['cat_id']))
    {
        $_POST['cat_id'] = 0;
    }
    /* 取得文件地址 */
    $file_url = '';
    if (empty($_FILES['file']['error']) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none'))
    {
        // 检查文件格式
        if (!check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types))
        {
            sys_msg($_LANG['invalid_file']);
        }
        // 复制文件
        $res = upload_article_file($_FILES['file']);
        if ($res != false)
        {
            $file_url = $res;
        }
    }
    if ($file_url == '')
    {
        $file_url = $_POST['file_url'];
    }
    /* 计算文章打开方式 */
    if ($file_url == '')
    {
        $open_type = 0;
    }
    else
    {
        $open_type = $_POST['content'] == '' ? 1 : 2;
    }
    $is_hot = isset($_POST['is_hot']) ? intval($_POST['is_hot']) : 0;
    $post_time = !empty($_POST['post_time']) ? local_strtotime($_POST['post_time']) : 0;
    $modify_time = gmtime();
    $title = !empty($_POST['title']) ? filter_var($_POST['title'], FILTER_SANITIZE_STRING) : '';
    $title_display = !empty($_POST['title_display']) ? filter_var($_POST['title_display'], FILTER_SANITIZE_STRING) : '';

    /* 如果 file_url 跟以前不一样，且原来的文件是本地文件，删除原来的文件 */
    $sql = "SELECT file_url FROM " . $ecs->table('article') . " WHERE article_id = '$_POST[id]'";
    $old_url = $db->getOne($sql);
    if ($old_url != '' && $old_url != $file_url && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false)
    {
        @unlink(ROOT_PATH . $old_url);
    }

    update_slug($_POST['id'], 'article');

    if (isset($_FILES['article_thumb']) && $_FILES['article_thumb']['tmp_name'] != '' &&
        isset($_FILES['article_thumb']['tmp_name']) &&$_FILES['article_thumb']['tmp_name'] != 'none')
    {
        $thumb = upload_thumb();
        unlink_old_thumb();
        $sql_update = "article_thumb ='$thumb[thumb]', article_sthumb ='$thumb[sthumb]', ";
    }else{
        $sql_update = '';
    }

    if ($exc->edit("title='$title', title_display='$title_display', {$sql_update} cat_id='$_POST[article_cat]', article_type='$_POST[article_type]', is_open='$_POST[is_open]', post_time='$post_time', author='$_POST[author]', author_email='$_POST[author_email]', keywords ='$_POST[keywords]', file_url ='$file_url', open_type='$open_type', is_hot='$is_hot', content='$_POST[content]', link='$_POST[link_url]', description = '$_POST[description]', ar_canonical = '$_POST[ar_canonical]', modify_time = '$modify_time'", $_POST['id']))
    {
        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'article.php?act=list&' . list_link_postfix();
        $note = sprintf($_LANG['articleedit_succeed'], stripslashes($_POST['title']));
        admin_log($_POST['title'], 'edit', 'article');
        clear_cache_files();
        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}
/*------------------------------------------------------ */
//-- 编辑文章主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_title')
{
    check_authz_json('article_manage');
    $id    = intval($_POST['id']);
    $title = json_str_iconv(trim($_POST['val']));
    /* 检查文章标题是否重复 */
    if ($exc->num("title", $title, $id) != 0)
    {
        make_json_error(sprintf($_LANG['title_exist'], $title));
    }
    else
    {
        if ($exc->edit("title = '$title'", $id))
        {
            clear_cache_files();
            admin_log($title, 'edit', 'article');
            make_json_result(stripslashes($title));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}
elseif ($_REQUEST['act'] == 'sort_article')
{
    check_authz_json('article_manage');
    $id    = intval($_POST['id']);
    $sort_article = intval($_POST['val']);
    /* 检查文章标题是否重复 */
    if ($exc->edit("sort_article = '$sort_article'",$id))
    {
        clear_cache_files();
        make_json_result($sort_article);
    }

}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_show')
{
    check_authz_json('article_manage');
    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);
    $exc->edit("is_open = '$val'", $id);
    clear_cache_files();
    make_json_result($val);
}
elseif ($_REQUEST['act'] == 'toggle_hot')
{
    check_authz_json('article_manage');
    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);
    $exc->edit("is_hot = '$val'", $id);
    clear_cache_files();
    make_json_result($val);
}
/*------------------------------------------------------ */
//-- 切换文章重要性
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_type')
{
    check_authz_json('article_manage');
    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);
    $exc->edit("article_type = '$val'", $id);
    clear_cache_files();
    make_json_result($val);
}
/*------------------------------------------------------ */
//-- 删除文章主题
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('article_manage');
    $id = intval($_GET['id']);
    /* 删除原来的文件 */
    $sql = "SELECT file_url, article_thumb, article_sthumb FROM " . $ecs->table('article') . " WHERE article_id = '$id'";
    $rm = $db->getRow($sql);
    if ($rm['file_url'] != '' && strpos($rm['file_url'], 'http://') === false && strpos($rm['file_url'], 'https://') === false)
    {
        @unlink(ROOT_PATH . $rm['file_url']);
    }
    @unlink(ROOT_PATH . CDN_PATH1.'/'.$rm['article_thumb']);
    @unlink(ROOT_PATH . CDN_PATH1.'/'.$rm['article_sthumb']);

    $name = $exc->get_name($id);
    if ($exc->drop($id))
    {
        del_slug($id, 'article');
        $db->query("DELETE FROM " . $ecs->table('comment') . " WHERE " . "comment_type = 1 AND id_value = $id");

        admin_log(addslashes($name),'remove','article');
        clear_cache_files();
    }
    $url = 'article.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");
    exit;
}
/*------------------------------------------------------ */
//-- 将商品加入关联
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add_link_goods')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    check_authz_json('article_manage');
    $add_ids = $json->decode($_GET['add_ids']);
    $args = $json->decode($_GET['JSON']);
    $article_id = $args[0];
    if ($article_id == 0)
    {
        $article_id = $db->getOne('SELECT MAX(article_id)+1 AS article_id FROM ' .$ecs->table('article'));
    }
    foreach ($add_ids AS $key => $val)
    {
        $sql = 'INSERT INTO ' . $ecs->table('goods_article4') . ' (goods_id, article_id) '.
               "VALUES ('$val', '$article_id')";
        $db->query($sql, 'SILENT') or make_json_error($db->error());
    }
    /* 重新载入 */
    $arr = get_article_goods($article_id);
    $opt = array();
    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value'  => $val['goods_id'],
                        'text'  => $val['goods_name'],
                        'data'  => '');
    }
    make_json_result($opt);
}
/*------------------------------------------------------ */
//-- 将商品删除关联
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_link_goods')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    check_authz_json('article_manage');
    $drop_goods     = $json->decode($_GET['drop_ids']);
    $arguments      = $json->decode($_GET['JSON']);
    $article_id     = $arguments[0];
    if ($article_id == 0)
    {
        $article_id = $db->getOne('SELECT MAX(article_id)+1 AS article_id FROM ' .$ecs->table('article'));
    }
    $sql = "DELETE FROM " . $ecs->table('goods_article4').
            " WHERE article_id = '$article_id' AND goods_id " .db_create_in($drop_goods);
    $db->query($sql, 'SILENT') or make_json_error($db->error());
    /* 重新载入 */
    $arr = get_article_goods($article_id);
    $opt = array();
    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value'  => $val['goods_id'],
                        'text'  => $val['goods_name'],
                        'data'  => '');
    }
    make_json_result($opt);
}
/*------------------------------------------------------ */
//-- 搜索商品
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'get_goods_list')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    $filters = $json->decode($_GET['JSON']);
    $arr = get_goods_list($filters);
    $opt = array();
    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value' => $val['goods_id'],
                        'text' => $val['goods_name'],
                        'data' => $val['shop_price']);
    }
    make_json_result($opt);
}
/* Dùng chung với sp liên quan của goods,
    set riêng col mod=1 làm đấu dấu
*/
elseif ($_REQUEST['act'] == 'add_goods_article3')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    check_authz_json('article_manage');
    $articles   = $json->decode($_GET['add_ids']);
    $arguments  = $json->decode($_GET['JSON']);
    $goods_id   = $arguments[0];
    foreach ($articles AS $val)
    {
        $sql = "INSERT INTO " . $ecs->table('goods_article3') . " (goods_id, article_id, admin_id, mod_type) " .
                "VALUES ('$goods_id', '$val', '$_SESSION[admin_id]', '1')";
        $db->query($sql);
    }
    $arr = get_goods_articles3($goods_id);
    $opt = array();
    foreach ($arr AS $val)
    {
        $opt[] = array('value'      => $val['article_id'],
                        'text'      => $val['title'],
                        'data'      => '');
    }
    clear_cache_files();
    make_json_result($opt);
}
/* Dùng chung với sp liên quan của goods,
    set riêng col mod=1 làm đấu dấu
*/
elseif ($_REQUEST['act'] == 'drop_goods_article3')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    check_authz_json('article_manage');
    $articles   = $json->decode($_GET['drop_ids']);
    $arguments  = $json->decode($_GET['JSON']);
    $goods_id   = $arguments[0];
    $sql = "DELETE FROM " .$ecs->table('goods_article3') . " WHERE " . db_create_in($articles, "article_id") . " AND mod_type = 1 AND goods_id = '$goods_id'";
    $db->query($sql);
    $arr = get_goods_articles3($goods_id);
    $opt = array();
    foreach ($arr AS $val)
    {
        $opt[] = array('value'      => $val['article_id'],
                        'text'      => $val['title'],
                        'data'      => '');
    }
    clear_cache_files();
    make_json_result($opt);
}
elseif ($_REQUEST['act'] == 'get_article_list')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    $filters =(array) $json->decode(json_str_iconv($_GET['JSON']));
    $where = " WHERE cat_id > 0 ";
    if (!empty($filters['title']))
    {
        $keyword  = trim($filters['title']);
        $where   .=  " AND title LIKE '%" . mysql_like_quote($keyword) . "%' ";
    }
    /* để khi act = update thì ko thể tìm ra id của tin hiện tại */
    if (!empty($filters['id'])){
        $where   .= " AND article_id <> ".$filters['id']." ";
    }
    $sql        = 'SELECT article_id, title FROM ' .$ecs->table('article'). $where.
                  'ORDER BY article_id DESC LIMIT 50';
    $res        = $db->query($sql);
    $arr        = array();
    while ($row = $db->fetchRow($res))
    {
        $arr[]  = array('value' => $row['article_id'], 'text' => $row['title'], 'data'=>'');
    }
    make_json_result($arr);
}
/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 批量删除 */
    if (isset($_POST['type']))
    {
        if ($_POST['type'] == 'button_remove')
        {
            admin_priv('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
            {
                sys_msg($_LANG['no_select_article'], 1);
            }
            /* 删除原来的文件 */
            $sql = "SELECT file_url, article_thumb, article_sthumb FROM " . $ecs->table('article') .
                    " WHERE article_id " . db_create_in(join(',', $_POST['checkboxes'])) .
                    " AND file_url <> ''";
            $res = $db->query($sql);
            while ($row = $db->fetchRow($res))
            {
                $old_url = $row['file_url'];
                if (strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false)
                {
                    @unlink(ROOT_PATH . $old_url);
                }
                @unlink(ROOT_PATH . $row['article_thumb']);
                @unlink(ROOT_PATH . $row['article_sthumb']);
            }
            foreach ($_POST['checkboxes'] AS $key => $id)
            {
                del_slug($id, 'article');

                if ($exc->drop($id))
                {
                    $name = $exc->get_name($id);
                    admin_log(addslashes($name),'remove','article');
                }
            }
        }
        /* 批量隐藏 */
        if ($_POST['type'] == 'button_hide')
        {
            check_authz_json('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
            {
                sys_msg($_LANG['no_select_article'], 1);
            }
            foreach ($_POST['checkboxes'] AS $key => $id)
            {
              $exc->edit("is_open = '0'", $id);
            }
        }
        /* 批量显示 */
        if ($_POST['type'] == 'button_show')
        {
            check_authz_json('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
            {
                sys_msg($_LANG['no_select_article'], 1);
            }
            foreach ($_POST['checkboxes'] AS $key => $id)
            {
              $exc->edit("is_open = '1'", $id);
            }
        }
        /* 批量移动分类 */
        if ($_POST['type'] == 'move_to')
        {
            check_authz_json('article_manage');
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']) )
            {
                sys_msg($_LANG['no_select_article'], 1);
            }
            if(!$_POST['target_cat'])
            {
                sys_msg($_LANG['no_select_act'], 1);
            }
            foreach ($_POST['checkboxes'] AS $key => $id)
            {
              $exc->edit("cat_id = '".$_POST['target_cat']."'", $id);
            }
        }
    }
    /* 清除缓存 */
    clear_cache_files();
    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'article.php?act=list');
    sys_msg($_LANG['batch_handle_ok'], 0, $lnk);
}
/* 把商品删除关联 */
function drop_link_goods($goods_id, $article_id)
{
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('goods_article4') .
            " WHERE goods_id = '$goods_id' AND article_id = '$article_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
    create_result(true, '', $goods_id);
}
/* 取得文章关联商品 */
function get_article_goods($article_id)
{
    $list = array();
    $sql  = 'SELECT g.goods_id, g.goods_name'.
            ' FROM ' . $GLOBALS['ecs']->table('goods_article4') . ' AS ga'.
            ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = ga.goods_id'.
            " WHERE ga.article_id = '$article_id'";
    $list = $GLOBALS['db']->getAll($sql);
    return $list;
}
/* 获得文章列表 */
function get_articleslist()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['cat_id'] = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'a.article_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $where = '';
        if (!empty($filter['keyword']))
        {
            $where = " AND a.title LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }
        if ($filter['cat_id'])
        {
            $where .= " AND a." . get_article_children($filter['cat_id']);
        }
        /* 文章总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('article'). ' AS a '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('article_cat'). ' AS ac ON ac.cat_id = a.cat_id '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        $filter = page_and_size($filter);
        /* 获取文章数据 */
        $sql = 'SELECT a.* , ac.cat_name '.
               ' FROM ' .$GLOBALS['ecs']->table('article'). ' AS a '.
               ' LEFT JOIN ' .$GLOBALS['ecs']->table('article_cat'). ' AS ac ON ac.cat_id = a.cat_id '.
               ' WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $arr = array();
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $rows['date'] = local_date($GLOBALS['_CFG']['time_format'], $rows['add_time']);
        $rows['url'] = build_uri('article', array('aid'=>$rows['article_id']), $rows['title']);
        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}
/* 上传文件 */
function upload_article_file($upload)
{
    if (!make_dir("../" . DATA_DIR . "/article"))
    {
        /* 创建目录失败 */
        return false;
    }
    $filename = cls_image::random_filename() . substr($upload['name'], strpos($upload['name'], '.'));
    $path     = ROOT_PATH. DATA_DIR . "/article/" . $filename;
    if (move_upload_file($upload['tmp_name'], $path))
    {
        return DATA_DIR . "/article/" . $filename;
    }
    else
    {
        return false;
    }
}
function upload_thumb(){
    $thumb = array('thumb' => '', 'sthumb' =>'');
    if (isset($_FILES['article_thumb']) && $_FILES['article_thumb']['tmp_name'] != '' &&
        isset($_FILES['article_thumb']['tmp_name']) &&$_FILES['article_thumb']['tmp_name'] != 'none')
    {
        if (!check_type_allow($_FILES['article_thumb']['name'], $GLOBALS['allow_image_types']))
        {
            sys_msg($GLOBALS['_LANG']['invalid_file']);
        }

        $image = new cls_image($GLOBALS['_CFG']['bgcolor']);
        $filename = build_slug($_POST['title']).'-thumb-'.gmtime();
        $filename_small = build_slug($_POST['title']).'-sthumb-'.gmtime();
        $dir = ROOT_PATH .CDN_PATH1.'/images/' . date('Ym').'/thumb_article/';
        $thumbnail = $image->make_thumb($_FILES['article_thumb']['tmp_name'], $GLOBALS['_CFG']['article_thumb_width'],$GLOBALS['_CFG']['article_thumb_height'], $dir, '', $filename);
        $small_thumb = $image->make_thumb($_FILES['article_thumb']['tmp_name'], $GLOBALS['_CFG']['article_small_thumb_width'],$GLOBALS['_CFG']['article_small_thumb_height'], $dir, '', $filename_small);
        $thumbnail = str_replace(CDN_PATH1.'/', '', $thumbnail);
        $small_thumb = str_replace(CDN_PATH1.'/', '', $small_thumb);
        if ($thumbnail == false)
        {
            sys_msg($image->error_msg(), 1, array(), false);
        }
        if ($small_thumb == false)
        {
            sys_msg($image->error_msg(), 1, array(), false);
        }
        $thumb = array('thumb' => $thumbnail, 'sthumb' => $small_thumb);
    }
    return $thumb;
}
function unlink_old_thumb(){
    @unlink(ROOT_PATH.CDN_PATH1.'/'. $_POST['old_thumb']);
    @unlink(ROOT_PATH. CDN_PATH1.'/'.$_POST['old_sthumb']);
}

function handle_goods_article($goods_id)
{
    $sql = "UPDATE " . $GLOBALS['ecs']->table('goods_article4') . " SET " .
            " goods_id = '$goods_id' " .
            " WHERE goods_id = '0'" .
            " AND admin_id = '$_SESSION[admin_id]'";
    $GLOBALS['db']->query($sql);
}
?>