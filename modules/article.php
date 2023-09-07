<?php
/*define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');*/
if ((DEBUG_MODE & 2) != 2) $smarty->caching = true;
 function getRank2($goods_id){
    $sql = 'SELECT AVG(comment_rank) AS comment_rank, COUNT(comment_rank) AS comment_count FROM '.$GLOBALS['ecs']->table('comment').
         " WHERE id_value = $goods_id AND comment_type = 0 AND parent_id = 0 AND status = 1 ";
    $res = $GLOBALS['db']->getRow($sql);
    return   $res;
}
/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */
$article_id  = intval($id);

/* Validate URL */
$article_url_right =  build_uri('article', array('aid' => $article_id));
if($article_url_right != $url){
    withRedirect($getRequest['getBaseUrl'].$article_url_right);
}
/* End Validate URL */

$cache_id = sprintf('%X', crc32($article_id . '-' . $_CFG['lang'].'-'.$_device));
$_teamplate = isset($amp) && $amp == true ? 'article_amp.dwt' : 'article'.$_device.'.dwt';
//$_teamplate = 'article'.$_device.'.dwt';
if (!$smarty->is_cached($_teamplate, $cache_id))
{
    /** Active menu */
    if(isset($active_url)){
        $smarty->assign('active_url', $active_url);
    }
    $article = get_article_info($article_id);

    if (empty($article['cat_id']))
    {
        withRedirect($getRequest['getBaseUrl']);
        exit;
    }

    $smarty->assign('article_categories',           article_categories_tree(18));

    if($client == 'pc'){
        //$smarty->assign('top_goods',        get_top10());
        //$smarty->assign('new_goods',        get_recommend_goods('new'));
        //$smarty->assign('promotion_goods',  get_promote_goods());
        //$smarty->assign('best_goods',           get_recommend_goods('best'));
        //$smarty->assign('categories',       get_categories_tree());
        $smarty->assign('helps',            get_shop_help());
        //$smarty->assign('new_articles',    index_get_new_articles(5));
        //$smarty->assign('promotion_info', get_promotion_info());
    }
    $article_rela = get_related_articles($article_id);
    $smarty->assign('article_rela',    $article_rela);
    $smarty->assign('related_goods',    article_related_goods($article_id));
    $smarty->assign('id',               $article_id);
    $smarty->assign('username',         $_SESSION['user_name']);
    $smarty->assign('email',            $_SESSION['email']);
    $smarty->assign('type',            '1');

    /* Caphe cho binh luan */
    if ((intval($_CFG['captcha']) & CAPTCHA_COMMENT) && gd_version() > 0)
    {
        $smarty->assign('enabled_captcha', 1);
        $smarty->assign('rand',            mt_rand());
    }
    /* Biến đối img to amp-img cho AMP */
    if(isset($amp) && $amp == true && $_device == '_mobile'){
        $youtube_embed = strpos($article['content'], 'https://www.youtube.com/embed')? 1 : 0;
        $smarty->assign('youtube_embed',  $youtube_embed);
        require(ROOT_PATH . 'includes/AmpHelper.php');
        $article['content'] = AmpHelper::replaceHtmlTagToCorresAmpTags($article['content']);
        $article['content']  = preg_replace('/(<[^>]+) textvalue=".*?"/i', '$1',$article['content'] );
    }
    $smarty->assign('article',      $article);
    $smarty->assign('cat_id',      $article['cat_id']);
    $smarty->assign('keywords',     htmlspecialchars($article['keywords']));
    $smarty->assign('faq',     ($article['faq']));
    $smarty->assign('description', htmlspecialchars($article['description']));
	$smarty->assign('ar_canonical',     ($article['ar_canonical']));
    $catlist = array();
    foreach(get_article_parent_cats($article['cat_id']) as $k=>$v)
    {
        $catlist[] = $v['cat_id'];
    }
    assign_template('a', $catlist);

    if($article['cat_id'] > 2)
        $position = assign_ur_here($article['cat_id'], '', 'article');
    else
        $position = assign_ur_here(0, htmlspecialchars($article['title']), 'article');

    $smarty->assign('page_title',   htmlspecialchars($article['title']));
    $smarty->assign('ur_here',      $position['ur_here']);
    $smarty->assign('comment_type', 1);
    /* 相关商品 */
    $sql = "SELECT a.goods_id, g.goods_name " .
            "FROM " . $ecs->table('goods_article') . " AS a, " . $ecs->table('goods') . " AS g " .
            "WHERE a.goods_id = g.goods_id " .
            "AND a.article_id = $article_id";
    $smarty->assign('goods_list', $db->getAll($sql));
    /* 上一篇下一篇文章 */
    $next_article = $db->getRow("SELECT article_id, title FROM " .$ecs->table('article'). " WHERE article_id = ($article_id+1)  AND is_open=1 LIMIT 1");
    if (!empty($next_article))
    {
        $next_article['url'] = build_uri('article', array('aid'=>$next_article['article_id']), $next_article['title']);
        $smarty->assign('next_article', $next_article);
    }
    $prev_aid = $db->getOne("SELECT max(article_id) FROM " . $ecs->table('article') . " WHERE article_id = ($article_id-1) AND is_open=1");
    if (!empty($prev_aid))
    {
        $prev_article = $db->getRow("SELECT article_id, title FROM " .$ecs->table('article'). " WHERE article_id = $prev_aid");
        $prev_article['url'] = build_uri('article', array('aid'=>$prev_article['article_id']), $prev_article['title']);
        $smarty->assign('prev_article', $prev_article);
    }
    $db->query('UPDATE ' . $ecs->table('article') . " SET viewed = $article[viewed] + 1 WHERE article_id = '$article_id'");

    assign_dynamic('article');
}
if(isset($article) && $article['cat_id'] > 3)
{
    $smarty->assign('nofflow', 0);
    $smarty->display($_teamplate, $cache_id);
}
else
{
    $smarty->assign('nofflow', 1);
    $smarty->display('article_pro'.$_device.'.dwt', $cache_id);
}
/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */
/**
 * 获得指定的文章的详细信息
 *
 * @access  private
 * @param   integer     $article_id
 * @return  array
 */
function get_article_info($article_id)
{
    /* 获得文章的信息 */
    $sql = "SELECT a.*, IFNULL(AVG(r.comment_rank), 0) AS comment_rank ".
            "FROM " .$GLOBALS['ecs']->table('article'). " AS a ".
            "LEFT JOIN " .$GLOBALS['ecs']->table('comment'). " AS r ON r.id_value = a.article_id AND comment_type = 1 ".
            "WHERE a.is_open = 1 AND a.article_id = '$article_id' GROUP BY a.article_id";
    $row = $GLOBALS['db']->getRow($sql);
    if ($row !== false)
    {
        $row['comment_rank'] = ceil($row['comment_rank']);
        $row['utc_time_public']     = date("c", $row['add_time']);
        $row['utc_time_modify']     = date("c", $row['modify_time']);
        $row['add_time']     = timeAgo(local_date($GLOBALS['_CFG']['time_format'], $row['add_time'])); // 修正添加时间显示
        $row['url'] = build_uri('article', array('aid'=>$row['article_id']), $row['title']);

       if($row['cat_id'] > 3){
            $row['url_amp'] = build_uri('article', array('aid'=>$row['article_id'], 'amp'=>true), $row['title']);
       }else{
            $row['url_amp'] = '';
       }

        /* 作者信息如果为空，则用网站名称替换 */
        if (empty($row['author']) || $row['author'] == '_SHOPHELP')
        {
            $row['author'] = $GLOBALS['_CFG']['shop_name'];
        }
        $row['viewed'] = $row['viewed'];

        // Check if post_time is greater than current time
        if ($row['post_time'] > time()) {
            $row['nofollow'] = true;
        } else {
            $row['nofollow'] = false;
        }
    }
    return $row;
}

/**
 * 获得文章关联的商品
 *
 * @access  public
 * @param   integer $id
 * @return  array
 */
function article_related_goods($id)
{
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_thumb, g.desc_short, g.seller_note, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                'g.market_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('goods_article4') . ' ga ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = ga.goods_id ' .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            "WHERE ga.article_id = '$id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0";
    $res = $GLOBALS['db']->query($sql);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
                    if ($row['promote_price'] > 0 and $row['promote_end_date'] > $time )
            {
                $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
            }
            else
            {
                $goods[$idx]['promote_price'] = '';
            }
        $comments = getRank2($row['goods_id']);
        $arr[$row['goods_id']]['comment_rank']= round($comments['comment_rank'],1);
        $arr[$row['goods_id']]['comment_count'] = $comments['comment_count'];
        $arr[$row['goods_id']]['comment_rank_css'] = round($comments['comment_rank']);


        $arr[$row['goods_id']]['discount'] = $promote_price > 0 ? get_discount($row['shop_price'], $promote_price): '';           
        $arr[$row['goods_id']]['goods_id']      = $row['goods_id'];
        $arr[$row['goods_id']]['goods_name']    = $row['goods_name'];
        $arr[$row['goods_id']]['desc_short']   = nl2p(strip_tags($row['desc_short']));
        $arr[$row['goods_id']]['seller_note']  = nl2p(strip_tags($row['seller_note']));
        $arr[$row['goods_id']]['goods_thumb']   = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['price']         = $row['shop_price'];
        $arr[$row['goods_id']]['market_price']  = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']    = price_format($row['shop_price']);
        $arr[$row['goods_id']]['url']           = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);

    }
    return $arr;
}
/** Tin tức liên quan */
function get_related_articles($id)
{
    $sql = 'SELECT ga.article_id, a.title, a.title_display, a.article_thumb, a.article_sthumb, a.open_type, a.add_time, a.viewed ' .
            'FROM ' . $GLOBALS['ecs']->table('goods_article3') . ' AS ga ' .
            " LEFT JOIN ".$GLOBALS['ecs']->table('article') . ' AS a ' .
            " ON a.article_id = ga.article_id" .
            " WHERE ga.goods_id = '$id' AND a.is_open = 1 AND ga.mod_type = 1 " .
            ' ORDER BY a.add_time DESC';

    $res = $GLOBALS['db']->query($sql);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['url']         = build_uri('article', array('aid'=>$row['article_id']), $row['title']);
        $row['add_time']    = timeAgo(date('d-m-Y H:i:s', $row['add_time']));
        $row['article_thumb']    = $row['article_thumb'];
        $row['title']       = $row['title_display'] != '' ? $row['title_display'] : $row['title'];
        $row['viewed'] = $row['viewed'];
        $arr[] = $row;
    }

    return $arr;
}

?>