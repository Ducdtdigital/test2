<?php
/*define('IN_ECS', true);
require(ROOT_PATH . '/includes/init.php');*/
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}
$cache_id = sprintf('%X', crc32($_SESSION['user_rank'] . '-' . $_CFG['lang'].'-'.$_device));
$template = 'index'.$_device.'.dwt';
if (!$smarty->is_cached($template, $cache_id))
{
    assign_template();
    $position = assign_ur_here(0,'','index');
    $smarty->assign('page_title',      $_CFG['shop_title']);
    $smarty->assign('ur_here',         $position['ur_here']);
    /* meta information */
    $smarty->assign('keywords',        htmlspecialchars($_CFG['shop_keywords']));
    $smarty->assign('description',     htmlspecialchars($_CFG['shop_desc']));
    $smarty->assign('categories',      get_categories_tree());
    //$smarty->assign('helps',           get_shop_help());
    $smarty->assign('best_goods',      get_recommend_goods('best'));
    $smarty->assign('new_goods',       get_recommend_goods('new'));
    $smarty->assign('promotion_goods', get_promote_goods());
    $smarty->assign('brand_list_thb', get_brands());
    //$smarty->assign('group_buy_goods', index_get_group_buy());
    $smarty->assign('shop_notice',     $_CFG['shop_notice']);
    $smarty->assign('data_dir',        DATA_DIR);
    /** Dành riêng cho bản Desktop */
    if($client == 'pc'){
        $smarty->assign('total_cat',    count_goods_category([7,16,21,29]));
        $smarty->assign('hot_goods',       get_recommend_goods('hot'));
        //$smarty->assign('categories_article',     article_categories_tree(18));
    }

    $num_news = $_device == '_mobile' ? 5 : 4;
    $smarty->assign('new_articles',    index_get_new_articles($num_news));
    /* links */
    $links = index_get_links();
    //$smarty->assign('img_links',       $links['img']);
    $smarty->assign('txt_links',       $links['txt']);

    /* Recommend goods ajax tab */
    $cat_recommend_res = $db->getAll("SELECT c.cat_id, c.cat_name, cr.recommend_type FROM " . $ecs->table("cat_recommend") . " AS cr INNER JOIN " . $ecs->table("category") . " AS c ON cr.cat_id=c.cat_id");
    if (!empty($cat_recommend_res))
    {
        $cat_rec_array = array();
        foreach($cat_recommend_res as $cat_recommend_data)
        {
            $cat_rec[$cat_recommend_data['recommend_type']][] = array('cat_id' => $cat_recommend_data['cat_id'], 'cat_name' => $cat_recommend_data['cat_name']);
        }
        $smarty->assign('cat_rec', $cat_rec);
    }

    assign_dynamic('index');
}
$smarty->display($template, $cache_id);
/*------------------------------------------------------ */
//-- PRIVATE FUNCTIONS
/*------------------------------------------------------ */


/**
 * 获得所有的友情链接
 *
 * @access  private
 * @return  array
 */
function index_get_links()
{
    $sql = 'SELECT link_logo, link_name, link_url FROM ' . $GLOBALS['ecs']->table('friend_link') . ' ORDER BY show_order';
    $res = $GLOBALS['db']->getAll($sql);
    $links['img'] = $links['txt'] = array();
    foreach ($res AS $row)
    {
        if (!empty($row['link_logo']))
        {
            $links['img'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url'],
                                    'logo' => $row['link_logo']);
        }
        else
        {
            $links['txt'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url']);
        }
    }
    return $links;
}
/**
 * Lấy total sp thuộc danh mục muốn show home
 * @param  array  $cat_id Mảng id các danh mục cần show total
 * @param  string $ext
 */
function count_goods_category($cat_id = [],$ext='')
{
    foreach($cat_id as $k => $id){
      $res[$k] = $GLOBALS['db']->getRow('SELECT cat_name, cat_id FROM ' . $GLOBALS['ecs']->table('category') . " WHERE cat_id = $id LIMIT 1");
      $res[$k]['url'] = build_uri('category', array('cid' => $res[$k]['cat_id']), $res[$k]['cat_name']);
      $children = get_children($id);
      $where  = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
      $res[$k]['total'] = $GLOBALS['db']->getOne('SELECT COUNT("cat_id") FROM ' . $GLOBALS['ecs']->table('goods') . " AS g WHERE $where $ext");
    }
    return $res;
}

?>