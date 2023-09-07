<?php

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 获得文章分类下的文章列表
 *
 * @access  public
 * @param   integer     $cat_id
 * @param   integer     $page
 * @param   integer     $size
 *
 * @return  array
 */
function get_cat_articles($cat_id, $page = 1, $size = 20 ,$requirement='')
{
     $time = gmtime();
    //取出所有非0的文章
    if ($cat_id == '-1')
    {
        $cat_str = 'cat_id > 0';
    }
    else
    {
        $cat_str = get_article_children($cat_id);
    }
    //增加搜索条件，如果有搜索内容就进行搜索
    if ($requirement != '')
    {
        $sql = 'SELECT article_id, title, title_display, article_thumb, article_sthumb, description, author, add_time, post_time, is_hot, viewed, file_url, open_type' .
               ' FROM ' .$GLOBALS['ecs']->table('article') .
               ' WHERE is_open = 1 AND post_time < '.$time.' AND title like \'%' . $requirement . '%\' ' .
               ' ORDER BY article_id DESC';
    }
    else
    {

        $sql = 'SELECT article_id, title, title_display, article_thumb, article_sthumb, description, author, add_time, post_time, is_hot, viewed, file_url, open_type, sort_article' .
               ' FROM ' .$GLOBALS['ecs']->table('article') .
               ' WHERE is_open = 1 AND post_time < '.$time.' AND ' . $cat_str .
               ' ORDER BY is_hot DESC, sort_article ASC, article_id DESC';

    }

    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page-1) * $size);

    $arr = array();
    if ($res)
    {
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $article_id = $row['article_id'];

            $arr[$article_id]['id']          = $article_id;
            $arr[$article_id]['title']       = $row['title_display'] != '' ? $row['title_display'] : $row['title'];
            $arr[$article_id]['viewed']      = $row['viewed'];
            $arr[$article_id]['is_hot']      = $row['is_hot'];
            $arr[$article_id]['desc']        = $row['description'];
            $arr[$article_id]['lthumb']      = (empty($row['article_thumb'])) ? $GLOBALS['_CFG']['no_picture'] : $row['article_thumb'];
            $arr[$article_id]['thumb']       = (empty($row['article_sthumb'])) ? $GLOBALS['_CFG']['no_picture'] : $row['article_sthumb'];
            $arr[$article_id]['author']      = empty($row['author']) || $row['author'] == '_SHOPHELP' ? $GLOBALS['_CFG']['shop_name'] : $row['author'];
            $arr[$article_id]['url']         = $row['open_type'] != 1 ? build_uri('article', array('aid'=>$article_id), $row['title']) : trim($row['file_url']);
            $arr[$article_id]['add_time']    = timeAgo(local_date($GLOBALS['_CFG']['time_format'], $row['add_time']));
        }
    }

    return $arr;
}

/**
 * 获得指定分类下的文章总数
 *
 * @param   integer     $cat_id
 *
 * @return  integer
 */
function get_article_count($cat_id ,$requirement='')
{
    global $db, $ecs;
    if ($requirement != '')
    {
        $count = $db->getOne('SELECT COUNT(*) FROM ' . $ecs->table('article') . ' WHERE ' . get_article_children($cat_id) . ' AND  title like \'%' . $requirement . '%\'  AND is_open = 1');
    }
    else
    {
        $count = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table('article') . " WHERE " . get_article_children($cat_id) . " AND is_open = 1");
    }
    return $count;
}

/**
 * 5 tin duoc danh dau la HOT
 *
 * @access  public
 * @param   integer     $cat_id
 *
 * @return  array
 */
function get_top5_articles($cat_id)
{
     $time = gmtime();
    //取出所有非0的文章
    if ($cat_id == '-1')
    {
        $cat_str = 'cat_id > 0';
    }
    else
    {
        $cat_str = get_article_children($cat_id);
    }

    $sql = 'SELECT * FROM (SELECT article_id, title, title_display, description, article_thumb,  article_sthumb, open_type, author, add_time, viewed' .
               ' FROM ' .$GLOBALS['ecs']->table('article') .
               ' WHERE is_open = 1 AND article_type = 1 AND ' . $cat_str .
               ' ORDER BY add_time DESC LIMIT 5) TB ORDER BY RAND()';
    $res = $GLOBALS['db']->getAll($sql);
    $total  = count($res);
    $arr = array();
    if ($res)
    {
        foreach ($res AS $row)
        {
            $article_id = $row['article_id'];

            $arr[$article_id]['id']          = $article_id;
            $arr[$article_id]['title']       = $row['title'];
            $arr[$article_id]['desc']        = $row['description'];
            $arr[$article_id]['viewed']      = $row['viewed'];

            $arr[$article_id]['thumb']       = !empty($row['article_thumb'])? $row['article_thumb'] :'images/no_picture.gif';
            $arr[$article_id]['sthumb']      = !empty($row['article_sthumb'])? $row['article_sthumb'] :'images/no_picture.gif';

            $arr[$article_id]['short_title']  = $GLOBALS['_CFG']['article_title_length'] > 0 ? sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];
            $arr[$article_id]['author']      = empty($row['author']) || $row['author'] == '_SHOPHELP' ? $GLOBALS['_CFG']['shop_name'] : $row['author'];
            $arr[$article_id]['url']         = $row['open_type'] != 1 ? build_uri('article', array('aid'=>$article_id), $row['title']) : trim($row['file_url']);
            $arr[$article_id]['add_time']    = timeAgo(local_date($GLOBALS['_CFG']['time_format'], $row['add_time']));
        }
    }
    return $arr;
}

/**
 * 10 tin xem nhieu nhat
 *
 * @access  public
 * @param   integer     $cat_id
 *
 * @return  array
 */
function get_top10viewed_articles($cat_id)
{
    $time = gmtime();
    //取出所有非0的文章
    if ($cat_id == '-1')
    {
        $cat_str = 'cat_id > 0';
    }
    else
    {
        $cat_str = get_article_children($cat_id);
    }
    $sql = 'SELECT article_id, title, title_display, description, open_type, article_thumb, article_sthumb, author, add_time, post_time, viewed' .
               ' FROM ' .$GLOBALS['ecs']->table('article') .
               ' WHERE is_open = 1 AND ' . $cat_str .
               ' ORDER BY viewed DESC, article_id DESC LIMIT 10';

    $res = $GLOBALS['db']->getAll($sql);

    $arr = array();
    if ($res)
    {
        foreach ($res AS $row)
        {
            $article_id = $row['article_id'];

            $arr[$article_id]['id']          = $article_id;
            $arr[$article_id]['title']       = $row['title'];
            $arr[$article_id]['desc']        = $row['description'];
            $arr[$article_id]['viewed']      = $row['viewed'];

            $arr[$article_id]['thumb']       = !empty($row['article_thumb'])? $row['article_thumb'] :'images/no_picture.gif';
            $arr[$article_id]['sthumb'] = !empty($row['article_sthumb'])? $row['article_sthumb'] :'images/no_picture.gif';

            $arr[$article_id]['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ? sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];
            $arr[$article_id]['author']      = empty($row['author']) || $row['author'] == '_SHOPHELP' ? $GLOBALS['_CFG']['shop_name'] : $row['author'];
            $arr[$article_id]['url']         = $row['open_type'] != 1 ? build_uri('article', array('aid'=>$article_id), $row['title']) : trim($row['file_url']);
            $arr[$article_id]['add_time']    = timeAgo(local_date($GLOBALS['_CFG']['time_format'], $row['add_time']));
        }
    }

    return $arr;
}
/**
 * 获得最新的文章列表。
 *
 * @access  private
 * @return  array
 */
function index_get_new_articles($limit)
{
    $time = gmtime();
    $sql = 'SELECT a.article_id, a.title, title_display, ac.cat_name, a.viewed, a.add_time, a.post_time, a.article_thumb, a.article_sthumb, a.open_type, ac.cat_id, ac.cat_name ' .
            ' FROM ' . $GLOBALS['ecs']->table('article') . ' AS a, ' .
                $GLOBALS['ecs']->table('article_cat') . ' AS ac' .
            " WHERE a.is_open = 1 AND ac.cat_id > 3 AND a.cat_id = ac.cat_id AND ac.cat_type = 1 ".
            ' ORDER BY a.article_type DESC, a.add_time DESC LIMIT ' . $limit;
    $res = $GLOBALS['db']->getAll($sql);
    $arr = array();
    foreach ($res AS $idx => $row)
    {
        $arr[$idx]['id']          = $row['article_id'];
        $arr[$idx]['title']       = $row['title_display'] != '' ? $row['title_display'] : $row['title'];
        $arr[$idx]['cat_name']    = $row['cat_name'];
        $arr[$idx]['viewed']    = $row['viewed'];
        $arr[$idx]['thumb']       = !empty($row['article_thumb'])? $row['article_thumb'] :'images/no_picture.gif';
        $arr[$idx]['sthumb']      = !empty($row['article_sthumb'])? $row['article_sthumb'] :'images/no_picture.gif';
        $arr[$idx]['add_time']    = timeAgo(local_date($GLOBALS['_CFG']['time_format'], $row['add_time']));
        $arr[$idx]['url']         = $row['open_type'] != 1 ?
                                        build_uri('article', array('aid' => $row['article_id']), $row['title']) : trim($row['file_url']);
        $arr[$idx]['cat_url']     = build_uri('article_cat', array('acid' => $row['cat_id']), $row['cat_name']);
    }
    return $arr;
}
?>