<?php

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . '/includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}
/* 配置风格颜色选项 */
$topic_style_color = array(
                        '0'         => '008080',
                        '1'         => '008000',
                        '2'         => 'ffa500',
                        '3'         => 'ff0000',
                        '4'         => 'ffff00',
                        '5'         => '9acd32',
                        '6'         => 'ffd700'
                          );
$allow_suffix = array('gif', 'jpg', 'png', 'jpeg', 'bmp', 'swf');

if ($_REQUEST['act'] == 'list')
{
    admin_priv('topic_manage');
    $smarty->assign('ur_here',     $_LANG['09_topic']);
    $smarty->assign('full_page',   1);
    $list = get_topic_list();
    $smarty->assign('topic_list',   $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
    assign_query_info();
    $smarty->assign('action_link', array('text' => $_LANG['topic_add'], 'href' => 'topic.php?act=add'));
    $smarty->display('topic_list.htm');
}
/* 添加,编辑 */
if ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    admin_priv('topic_manage');
    $isadd     = $_REQUEST['act'] == 'add';
    $smarty->assign('isadd', $isadd);
    $topic_id  = empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']);

    $smarty->assign('ur_here',     $_LANG['09_topic']);
    $smarty->assign('action_link', list_link($isadd));
    $smarty->assign('cat_list',   cat_list(0, 1));
    $smarty->assign('brand_list', get_brand_list());
    $smarty->assign('cfg_lang',   $_CFG['lang']);
    $smarty->assign('topic_style_color',   $topic_style_color);
    $width_height = get_toppic_width_height();
    if(isset($width_height['pic']['width']) && isset($width_height['pic']['height']))
    {
        $smarty->assign('width_height', sprintf($_LANG['tips_width_height'], $width_height['pic']['width'], $width_height['pic']['height']));
    }
    if(isset($width_height['title_pic']['width']) && isset($width_height['title_pic']['height']))
    {
        $smarty->assign('title_width_height', sprintf($_LANG['tips_title_width_height'], $width_height['title_pic']['width'], $width_height['title_pic']['height']));
    }
    if (!$isadd)
    {
        $sql = "SELECT * FROM " . $ecs->table('topic') . " WHERE topic_id = '$topic_id'";
        $topic = $db->getRow($sql);
        $topic['start_time'] = local_date('Y-m-d', $topic['start_time']);
        $topic['end_time']   = local_date('Y-m-d', $topic['end_time']);

       CKEditor('topic_intro', $topic['intro']);
        /** edit by nobj */
        if(!empty($topic['data'])){
            require(ROOT_PATH . 'includes/cls_json.php');
            $json          = new JSON;
            $topic['data'] = addcslashes($topic['data'], "'");
            $topic['data'] = $json->encode(@unserialize($topic['data']));
            $topic['data'] = addcslashes($topic['data'], "'");
        }
        if (empty($topic['topic_img']) && empty($topic['htmls']))
        {
            $topic['topic_type'] = 0;
        }
        elseif ($topic['htmls'] != '')
        {
            $topic['topic_type'] = 2;
        }
        elseif (preg_match('/.swf$/i', $topic['topic_img']))
        {
            $topic['topic_type'] = 1;
        }
        else
        {
            $topic['topic_type'] = '';
        }

        $slug = get_slug($topic_id, 'topic');
        $smarty->assign('slug', $slug);

        $smarty->assign('topic', $topic);
        $smarty->assign('act',   "update");
    }
    else
    {
        $topic = array('topic_type' => 0, 'intro' => '', 'url' => '', 'remote_url' => '');
        $smarty->assign('topic', $topic);
        //create_html_editor('topic_intro', $topic['intro'],'FCKeditor','Normal', '100%', '300');
        CKEditor('topic_intro', $topic['intro']);
        $smarty->assign('act', "insert");
    }
    $smarty->display('topic_edit.htm');
}
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    admin_priv('topic_manage');
    $is_insert = $_REQUEST['act'] == 'insert';
    $topic_id  = empty($_POST['topic_id']) ? 0 : intval($_POST['topic_id']);
    $topic_type= empty($_POST['topic_type']) ? 0 : intval($_POST['topic_type']);
    $remote_url  = empty($_POST['remote_url']) ? '' : $_POST['remote_url'];

    if (isset($_POST['topic_name']) && empty($_POST['topic_name']))
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg('Tiêu đề không thể để trống', 0, $link);
    }

    if (empty($_POST['start_time']) || empty($_POST['end_time']))
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg('Chưa chọn ngày bắt đầu hoặc kết thúc', 0, $link);
    }

    switch ($topic_type)
    {
        case '0':
        case '1':
            // 主图上传
            if ($_FILES['topic_img']['name'] && $_FILES['topic_img']['size'] > 0)
            {
                /* 检查文件合法性 */
                if(!get_file_suffix($_FILES['topic_img']['name'], $allow_suffix))
                {
                    sys_msg($_LANG['invalid_type']);
                }

                $img_new_name = $_POST['slug'].'-img-'. gmtime();
                $topic_img = basename($image->upload_image($_FILES['topic_img'], CDN_PATH.'/afficheimg',  $img_new_name));
                $topic_img = 'afficheimg/'.$topic_img;
            }
            else if (!empty($_REQUEST['url']))
            {
                /* 来自互联网图片 不可以是服务器地址 */
                if(strstr($_REQUEST['url'], 'http') && !strstr($_REQUEST['url'], $_SERVER['SERVER_NAME']))
                {
                    /* 取互联网图片至本地 */
                    $topic_img = get_url_image($_REQUEST['url']);
                }
                else{
                    sys_msg($_LANG['web_url_no']);
                }
            }
            unset($name, $target);
            $topic_img = empty($topic_img) ? $_POST['img_url'] : $topic_img;
            $htmls = '';
        break;
        case '2':
            $htmls     = $_POST['htmls'];
            $topic_img = '';
        break;
    }
    // cập nhật thumb
    $delete_thumb = false;
    //if ($_FILES['title_pic']['name'] && $_FILES['title_pic']['size'] > 0)
    if ((isset($_FILES['title_pic']['error']) && $_FILES['title_pic']['error'] == 0) || (!isset($_FILES['title_pic']['error']) && isset($_FILES['title_pic']['tmp_name']) && $_FILES['title_pic']['tmp_name'] != 'none'))
    {
        /* 检查文件合法性 */
        if(!get_file_suffix($_FILES['title_pic']['name'], $allow_suffix))
        {
            sys_msg($_LANG['invalid_type']);
        }

        $img_new_name = $_POST['slug'].'-topicthumb-'. gmtime();
        $title_pic = basename($image->upload_image($_FILES['title_pic'], CDN_PATH.'/afficheimg',  $img_new_name));
        $title_pic = 'afficheimg/'.$title_pic;
        $delete_thumb = true;
    }
    else if (!empty($_REQUEST['title_url']))
    {
        if(strstr($_REQUEST['title_url'], 'http') && !strstr($_REQUEST['title_url'], $_SERVER['SERVER_NAME']))
        {
            $title_pic = get_url_image($_REQUEST['title_url']);
            $delete_thumb = true;
        }
        else{
            sys_msg($_LANG['web_url_no']);
        }

    }else{
        $title_pic = '';
    }
    unset($name, $target);
    $title_pic = empty($title_pic) ? $_POST['title_img_url'] : $title_pic;
    require(ROOT_PATH . 'includes/cls_json.php');
    $start_time = local_strtotime($_POST['start_time']);
    $end_time   = local_strtotime($_POST['end_time']);
    /** edit by nobj */
    if(!empty($_POST['topic_data'])){
        $json       = new JSON;
        $tmp_data   = $json->decode($_POST['topic_data']);
        $data       = serialize($tmp_data);
    }else{
        $data = '';
    }

    $base_style = $_POST['base_style'];
    $keywords   = $_POST['keywords'];
    $description= $_POST['description'];
    $intro = $_POST['topic_intro'];

    if ($is_insert)
    {
        $sql = "INSERT INTO " . $ecs->table('topic') . " (title, remote_url, start_time,end_time,data,intro,template,css,topic_img,title_pic,base_style, htmls,keywords,description)" .
                "VALUES ('$_POST[topic_name]','$remote_url', '$start_time','$end_time','$data','$intro','$_POST[topic_template_file]','$_POST[topic_css]', '$topic_img', '$title_pic', '$base_style', '$htmls','$keywords','$description')";
        $db->query($sql);
        $topic_id = $db->insert_id();
        create_slug($topic_id, 'topic');
    }
    else
    {
        $ext = $delete_thumb == true ? " title_pic='$title_pic', " : '';

        $sql = "UPDATE " . $ecs->table('topic') .
                "SET title='$_POST[topic_name]',remote_url = '$remote_url',start_time='$start_time',end_time='$end_time',data='$data',intro='$_POST[topic_intro]',template='$_POST[topic_template_file]',css='$_POST[topic_css]', topic_img='$topic_img', base_style='$base_style', htmls='$htmls', keywords='$keywords', ".$ext." description='$description'" .
               " WHERE topic_id='$topic_id' LIMIT 1";

        $db->query($sql);
        update_slug($topic_id, 'topic');
        if($delete_thumb == true) @unlink(ROOT_PATH. CDN_PATH .'/'.$_POST['title_img_url']);
    }

    clear_cache_files();
    $links[] = array('href' => 'topic.php', 'text' =>  $_LANG['back_list']);
    sys_msg($_LANG['succed'], 0, $links);
}
elseif ($_REQUEST['act'] == 'get_goods_list')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    $filters = $json->decode($_GET['JSON']);
    $arr = get_goods_list($filters);
    $opt = array();
    foreach ($arr AS $key => $val)
    {
        $opt[] = array('value' => $val['goods_id'],
                       'text'  => $val['goods_name']);
    }
    make_json_result($opt);
}
elseif ($_REQUEST["act"] == "delete")
{
    admin_priv('topic_manage');
    $sql = "DELETE FROM " . $ecs->table('topic') . " WHERE ";
    $thumb = "SELECT title_pic FROM " . $GLOBALS['ecs']->table('topic') . " WHERE ";
    if (!empty($_POST['checkboxes']))
    {
        $sql    .= db_create_in($_POST['checkboxes'], 'topic_id');
        $thumb  .= db_create_in($_POST['checkboxes'], 'topic_id');
        //del multi slug
        $sql_slug =  db_create_in($_POST['checkboxes'], 'id');
        batch_slug('topic', $sql_slug);
    }
    elseif (!empty($_GET['id']))
    {
        $_GET['id'] = intval($_GET['id']);
        $sql .= "topic_id = '$_GET[id]'";
        $thumb  .= "topic_id = '$_GET[id]'";
        del_slug($_GET[id], 'topic');

    }
    else
    {
        exit;
    }
    $db->query($sql);
    /** Lấy link thumb và xóa chúng */
    $links_thumb = $db->query($sql);
    if(is_array($links_thumb)){
        foreach($links_thumb as $link){
            @unlink(ROOT_PATH. CDN_PATH .'/'.$link['title_pic']);
        }
    }else{
        @unlink(ROOT_PATH. CDN_PATH .'/'.$links_thumb);
    }

    clear_cache_files();
    if (!empty($_REQUEST['is_ajax']))
    {
        $url = 'topic.php?act=query&' . str_replace('act=delete', '', $_SERVER['QUERY_STRING']);
        ecs_header("Location: $url\n");
        exit;
    }
    $links[] = array('href' => 'topic.php', 'text' =>  $_LANG['back_list']);
    sys_msg($_LANG['succed'], 0, $links);
}

elseif ($_REQUEST["act"] == "query")
{
    $topic_list = get_topic_list();
    $smarty->assign('topic_list',   $topic_list['item']);
    $smarty->assign('filter',       $topic_list['filter']);
    $smarty->assign('record_count', $topic_list['record_count']);
    $smarty->assign('page_count',   $topic_list['page_count']);
    $smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);
    /* 排序标记 */
    $sort_flag  = sort_flag($topic_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
    $tpl = 'topic_list.htm';
    make_json_result($smarty->fetch($tpl), '',array('filter' => $topic_list['filter'], 'page_count' => $topic_list['page_count']));
}

/**
 * 获取专题列表
 * @access  public
 * @return void
 */
function get_topic_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 查询条件 */
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'topic_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('topic');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);
        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('topic'). " ORDER BY $filter[sort_by] $filter[sort_order]";
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $query = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);
    $res = array();
    while($topic = $GLOBALS['db']->fetch_array($query)){
        $topic['start_time'] = local_date('Y-m-d',$topic['start_time']);
        $topic['end_time']   = local_date('Y-m-d',$topic['end_time']);
        //$topic['url']        = $GLOBALS['ecs']->url() . 'topic.php?topic_id=' . $topic['topic_id'];
        $slug = get_slug($topic['topic_id'], 'topic');
        $topic['url'] = '../khuyen-mai/'.$slug.'.html';

        $res[] = $topic;
    }
    $arr = array('item' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
}
/**
 * 列表链接
 * @param   bool    $is_add     是否添加（插入）
 * @param   string  $text       文字
 * @return  array('href' => $href, 'text' => $text)
 */
function list_link($is_add = true, $text = '')
{
    $href = 'topic.php?act=list';
    if (!$is_add)
    {
        $href .= '&' . list_link_postfix();
    }
    if ($text == '')
    {
        $text = $GLOBALS['_LANG']['topic_list'];
    }
    return array('href' => $href, 'text' => $text);
}
function get_toppic_width_height()
{
    $width_height = array();
    $file_path = ROOT_PATH . 'themes/' . $GLOBALS['_CFG']['template'] . '/topic.dwt';
    if (!file_exists($file_path) || !is_readable($file_path))
    {
        return $width_height;
    }
    $string = file_get_contents($file_path);
    $pattern_width = '/var\s*topic_width\s*=\s*"(\d+)";/';
    $pattern_height = '/var\s*topic_height\s*=\s*"(\d+)";/';
    preg_match($pattern_width, $string, $width);
    preg_match($pattern_height, $string, $height);
    if(isset($width[1]))
    {
        $width_height['pic']['width'] = $width[1];
    }
    if(isset($height[1]))
    {
        $width_height['pic']['height'] = $height[1];
    }
    unset($width, $height);
    $pattern_width = '/TitlePicWidth:\s{1}(\d+)/';
    $pattern_height = '/TitlePicHeight:\s{1}(\d+)/';
    preg_match($pattern_width, $string, $width);
    preg_match($pattern_height, $string, $height);
    if(isset($width[1]))
    {
        $width_height['title_pic']['width'] = $width[1];
    }
    if(isset($height[1]))
    {
        $width_height['title_pic']['height'] = $height[1];
    }
    return $width_height;
}
function get_url_image($url)
{
    $ext = strtolower(end(explode('.', $url)));
    if($ext != "gif" && $ext != "jpg" && $ext != "png" && $ext != "bmp" && $ext != "jpeg")
    {
        return $url;
    }
    $name = date('Ymd');
    for ($i = 0; $i < 6; $i++)
    {
        $name .= chr(mt_rand(97, 122));
    }
    $name .= '.' . $ext;
    $target = ROOT_PATH . CDN_PATH . '/afficheimg/' . $name;
    $tmp_file = CDN_PATH . '/afficheimg/' . $name;
    $filename = ROOT_PATH . $tmp_file;
    $img = file_get_contents($url);
    $fp = @fopen($filename, "a");
    fwrite($fp, $img);
    fclose($fp);
    return $tmp_file;
}

?>
