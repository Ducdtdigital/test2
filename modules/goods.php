<?php
/*define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');*/
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

//$goods_id = isset($_REQUEST['id'])  ? intval($_REQUEST['id']) : 0;

$id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='goods' AND slug = '".$slug."'");
$goods_id = $id > 0 ? intval($id) : 0;

/* Validate URL */
$goods_url_right =  build_uri('goods', array('gid' => $goods_id));
if($goods_url_right != $url && $tragop == 0){
    withRedirect($getRequest['getBaseUrl'].$goods_url_right);
}
/* End Validate URL */

if($goods_id == 0){
    withRedirect($getRequest['getBaseUrl']);
}

$is_real  = $db->getOne("SELECT goods_status FROM " . $ecs->table('goods') ." WHERE goods_id=".$goods_id);

$cache_id = $goods_id.$is_real.$tragop . '-' . $_SESSION['user_rank'].'-'.$_CFG['lang'].'-'.$_device;
$cache_id = sprintf('%X', crc32($cache_id));
/**
 * Layout thay đổi theo biến is_real
 * goods:mặc định | goods2:Hàng cũ | goods3:Preorder | goods4:Mới ra mắt
 */
if($tragop == 0){
    $_template =  'goods'.$_device.'.dwt';
}else{
    $_template = 'goods_tragop'.$_device.'.dwt' ;
}

if (!$smarty->is_cached($_template, $cache_id))
{
    $smarty->assign('image_width',  $_CFG['image_width']);
    $smarty->assign('image_height', $_CFG['image_height']);
    //$smarty->assign('helps',        get_shop_help()); //
    $smarty->assign('id',           $goods_id);
    $smarty->assign('type',         0);
    $smarty->assign('cfg',          $_CFG);
    $smarty->assign('promotion',       get_promotion_info($goods_id));
    $smarty->assign('promotion_info', get_promotion_info());
    /* Get danh info san pham */
    $goods = get_goods_info($goods_id);
    $smarty->assign('promotion',       get_promotion_info($goods_id));
    $phone_data = get_goods_info_phone($goods_id);
    $smarty->assign('goods_phone_hn',          $phone_data['goods_phone_hn']);
    $smarty->assign('goods_phone_hcm',          $phone_data['goods_phone_hcm']);
 
  
        if ($goods['brand_id'] > 0)
        {
            $goods['goods_brand_url'] = build_uri('brand', array('bid'=>$goods['brand_id']), $goods['goods_brand']);
        }
        $shop_price   = $goods['shop_price'];
        $linked_goods = get_linked_goods($goods_id);
        $goods['goods_style_name'] = add_style($goods['goods_name'], $goods['goods_name_style']);
        /* 购买该商品可以得到多少钱的红包 */
        // if ($goods['bonus_type_id'] > 0)
        // {
        //     $time = gmtime();
        //     $sql = "SELECT type_money FROM " . $ecs->table('bonus_type') .
        //             " WHERE type_id = '$goods[bonus_type_id]' " .
        //             " AND send_type = '" . SEND_BY_GOODS . "' " .
        //             " AND send_start_date <= '$time'" .
        //             " AND send_end_date >= '$time'";
        //     $goods['bonus_money'] = floatval($db->getOne($sql));
        //     if ($goods['bonus_money'] > 0)
        //     {
        //         $goods['bonus_money'] = price_format($goods['bonus_money']);
        //     }
        // }
        /* Lấy danh sách đặt trước nếu có đặt trước */
        if($is_real == 3){
            $smarty->assign('preorder_list', get_booking($goods['goods_id']));
        }
        $smarty->assign('goods',              $goods);
        $smarty->assign('goods_id',           $goods['goods_id']);
		$smarty->assign('goods_cano',           $goods['goods_cano']);
		$smarty->assign('goods_schema',           $goods['goods_schema']);
        $smarty->assign('promote_end_time',   $goods['gmt_end_time']);
        $smarty->assign('categories',         get_categories_tree($goods['cat_id']));  // 分类树
        /* meta */
        $smarty->assign('keywords',           htmlspecialchars($goods['keywords']));
        $smarty->assign('description',        htmlspecialchars($goods['description']));

        $catlist = get_parent_cats($goods['cat_id']);
        $parent_catid = isset($catlist[1]['cat_id']) ?  $catlist[1]['cat_id'] : $catlist[0]['cat_id'];
        $smarty->assign('parent_catid',     $parent_catid);
        //$smarty->assign('parent_cat_name',   $catlist[1]['cat_name']);

        assign_template('c', $catlist);
        $position = assign_ur_here($goods['cat_id'], '', 'goods');

        /* current position */
        $titletg = $tragop == 1 ? 'Trả góp ' : '';
        $smarty->assign('page_title',          $titletg.htmlspecialchars($goods['title']));                    // 页面标题
        $smarty->assign('ur_here',             $position['ur_here']);
        $properties = get_goods_properties($goods_id);
        $smarty->assign('properties',          $properties['pro']);
        $smarty->assign('specification',       $properties['spe']);
        $smarty->assign('attribute_linked',    get_same_attribute_goods($properties));
        $smarty->assign('related_goods',       $linked_goods);
        $smarty->assign('goods_article_list',  get_linked_articles($goods_id));
        $smarty->assign('fittings',            get_goods_fittings(array($goods_id)));
        $smarty->assign('rank_prices',         get_user_rank_prices($goods_id, $shop_price));
        $smarty->assign('pictures',            get_goods_gallery($goods_id));
        //$smarty->assign('bought_goods',        get_also_bought($goods_id));
        $smarty->assign('goods_rank',          get_goods_rank($goods_id));

        /* Tragop Maygame */
        // $cate_allowed_tragop = [8,9,10];
        // if(in_array($parent_catid, $cate_allowed_tragop) && $goods['price'] >= 3000000){
        //     $smarty->assign('istragop', 1);
        // }
        if($goods['final_price'] >= 3000000000000000000000 && $goods['final_price'] <= 6000000000000000000000000){
            $smarty->assign('istragop', 1);
        }
        /* End Tragop Maygame */

        // $tag_array = get_tags($goods_id);
        // $smarty->assign('tags',                $tag_array);

        $package_goods_list = get_package_goods_list($goods['goods_id']);
        $smarty->assign('package_goods_list',$package_goods_list);
        $smarty->assign('goods_price_list', get_same_price_goods($parent_catid, $goods['price'], 10, $goods['goods_id']));
        assign_dynamic('goods');
        // $volume_price_list = get_volume_price_list($goods['goods_id'], '1');
        // $smarty->assign('volume_price_list',$volume_price_list);    // 商品优惠价格区间
    
}
/* 记录浏览历史 */
if (!empty($_COOKIE['ECS']['history']))
{
    $history = explode(',', $_COOKIE['ECS']['history']);
    array_unshift($history, $goods_id);
    $history = array_unique($history);
    while (count($history) > $_CFG['history_number'])
    {
        array_pop($history);
    }
    setcookie('ECS[history]', implode(',', $history), gmtime() + 3600 * 24 * 30, $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
}
else
{
    setcookie('ECS[history]', $goods_id, gmtime() + 3600 * 24 * 30, $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
}
/* 更新点击次数 */
$db->query('UPDATE ' . $ecs->table('goods') . " SET click_count = click_count + 1 WHERE goods_id = '$goods_id'");
$smarty->assign('now_time',  gmtime());           // 当前系统时间
$smarty->display($_template , $cache_id);
/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * Get goods same price, same parent cat id
 * @param  [type] $goods_id [description]
 * @return [type]           [description]
 */
function get_same_price_goods($parent_catid, $price, $limit, $gid)
{
    $children = get_children($parent_catid);
    $best_price = round($price*0.3); // lệch 30% gia gốc
    $min_price = $price-$best_price;
    $max_price = $price+$best_price;
    $sql = 'SELECT g.goods_id, g.goods_name, RAND() AS rnd, g.goods_thumb, g.goods_img, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                'g.market_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' g ' .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            "WHERE g.goods_id <> $gid AND shop_price >  $min_price AND shop_price < $max_price AND $children AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".
            " ORDER BY rnd LIMIT " . $limit;
    $res = $GLOBALS['db']->query($sql);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$row['goods_id']]['goods_id']     = $row['goods_id'];
        $arr[$row['goods_id']]['goods_name']   = $row['goods_name'];
        $arr[$row['goods_id']]['goods_thumb']  = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['price']        = $row['shop_price'];
        $arr[$row['goods_id']]['shop_price']   = price_format($row['shop_price']);
        $arr[$row['goods_id']]['url']          = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
        if ($row['promote_price'] > 0)
        {
            $arr[$row['goods_id']]['promote_price'] = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $arr[$row['goods_id']]['formated_promote_price'] = price_format($arr[$row['goods_id']]['promote_price']);
        }
        else
        {
            $arr[$row['goods_id']]['promote_price'] = 0;
        }
        $arr[$row['goods_id']]['discount'] = $arr[$row['goods_id']]['promote_price'] > 0 ? get_discount($row['shop_price'], $arr[$row['goods_id']]['promote_price']): '';
    }
    return $arr;
}

/**
 * 获得指定商品的关联商品
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  array
 */
function get_linked_goods($goods_id)
{

    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_thumb, g.goods_img, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                'g.market_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('link_goods') . ' lg ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = lg.link_goods_id ' .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            "WHERE lg.goods_id = '$goods_id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".
            "LIMIT " . $GLOBALS['_CFG']['related_goods_number'];
    $res = $GLOBALS['db']->query($sql);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$row['goods_id']]['goods_id']     = $row['goods_id'];
        $arr[$row['goods_id']]['goods_name']   = $row['goods_name'];
        $arr[$row['goods_id']]['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
            sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $arr[$row['goods_id']]['goods_thumb']  = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['market_price'] = price_format($row['market_price']);
        $arr[$row['goods_id']]['price']        = $row['shop_price'];
        $arr[$row['goods_id']]['shop_price']   = price_format($row['shop_price']);
        $arr[$row['goods_id']]['url']          = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
        if ($row['promote_price'] > 0)
        {
            $promote_price  = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $arr[$row['goods_id']]['promote_price'] = $promote_price;
            $arr[$row['goods_id']]['formated_promote_price'] = price_format($promote_price);
            $arr[$row['goods_id']]['discount'] =  get_discount($arr[$row['goods_id']]['price'], $promote_price);
        }
        else
        {
            $arr[$row['goods_id']]['discount'] = 0;
            $arr[$row['goods_id']]['promote_price'] = 0;
        }
    }
    return $arr;
}

/**
 * Đã thêm Mod = 0 để chỉ lấy liên quan đến goods
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  void
 */
function get_linked_articles($goods_id)
{
    $sql = 'SELECT a.article_id, a.title, a.article_thumb, a.article_sthumb, a.file_url, a.open_type, a.add_time, a.viewed ' .
            'FROM ' . $GLOBALS['ecs']->table('goods_article2') . ' AS g, ' .
                $GLOBALS['ecs']->table('article') . ' AS a ' .
            "WHERE g.article_id = a.article_id AND g.mod_type = 0 AND g.goods_id = '$goods_id' AND a.is_open = 1 " .
            'ORDER BY a.add_time DESC';
    $res = $GLOBALS['db']->query($sql);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['url']         = $row['open_type'] != 1 ?
            build_uri('article', array('aid'=>$row['article_id']), $row['title']) : trim($row['file_url']);
        $row['add_time']    = timeAgo(local_date($GLOBALS['_CFG']['time_format'], $row['add_time']));
        $row['article_thumb']    = $row['article_thumb'];
        $row['viewed']    = $row['viewed'];
        $row['article_sthumb']   = $row['article_sthumb'];
        $arr[] = $row;
    }
    return $arr;
}
/**
 * 获得指定商品的各会员等级对应的价格
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  array
 */
function get_user_rank_prices($goods_id, $shop_price)
{
    $sql = "SELECT rank_id, IFNULL(mp.user_price, r.discount * $shop_price / 100) AS price, r.rank_name, r.discount " .
            'FROM ' . $GLOBALS['ecs']->table('user_rank') . ' AS r ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                "ON mp.goods_id = '$goods_id' AND mp.user_rank = r.rank_id " .
            "WHERE r.show_price = 1 OR r.rank_id = '$_SESSION[user_rank]'";
    $res = $GLOBALS['db']->query($sql);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$row['rank_id']] = array(
                        'rank_name' => htmlspecialchars($row['rank_name']),
                        'price'     => price_format($row['price']));
    }
    return $arr;
}
/**
 * 获得购买过该商品的人还买过的商品
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  array
 */
function get_also_bought($goods_id)
{
    $sql = 'SELECT COUNT(b.goods_id ) AS num, g.goods_id, g.goods_name, g.goods_thumb, g.goods_img, g.shop_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS a ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' AS b ON b.order_id = a.order_id ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = b.goods_id ' .
            "WHERE a.goods_id = '$goods_id' AND b.goods_id <> '$goods_id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 " .
            'GROUP BY b.goods_id ' .
            'ORDER BY num DESC ' .
            'LIMIT ' . $GLOBALS['_CFG']['bought_goods'];
    $res = $GLOBALS['db']->query($sql);
    $key = 0;
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$key]['goods_id']    = $row['goods_id'];
        $arr[$key]['goods_name']  = $row['goods_name'];
        $arr[$key]['short_name']  = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
            sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $arr[$key]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$key]['goods_img']   = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$key]['shop_price']  = price_format($row['shop_price']);
        $arr[$key]['url']         = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
        if ($row['promote_price'] > 0)
        {
            $arr[$key]['promote_price'] = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $arr[$key]['formated_promote_price'] = price_format($arr[$key]['promote_price']);
        }
        else
        {
            $arr[$key]['promote_price'] = 0;
        }
        $key++;
    }
    return $arr;
}
/**
 * 获得指定商品的销售排名
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  integer
 */
function get_goods_rank($goods_id)
{
    /* 统计时间段 */
    $period = intval($GLOBALS['_CFG']['top10_time']);
    if ($period == 1) // 一年
    {
        $ext = " AND o.add_time > '" . local_strtotime('-1 years') . "'";
    }
    elseif ($period == 2) // 半年
    {
        $ext = " AND o.add_time > '" . local_strtotime('-6 months') . "'";
    }
    elseif ($period == 3) // 三个月
    {
        $ext = " AND o.add_time > '" . local_strtotime('-3 months') . "'";
    }
    elseif ($period == 4) // 一个月
    {
        $ext = " AND o.add_time > '" . local_strtotime('-1 months') . "'";
    }
    else
    {
        $ext = '';
    }
    /* 查询该商品销量 */
    $sql = 'SELECT IFNULL(SUM(g.goods_number), 0) ' .
        'FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o, ' .
            $GLOBALS['ecs']->table('order_goods') . ' AS g ' .
        "WHERE o.order_id = g.order_id " .
        "AND o.order_status = '" . OS_CONFIRMED . "' " .
        "AND o.shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
        " AND o.pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) .
        " AND g.goods_id = '$goods_id'" . $ext;
    $sales_count = $GLOBALS['db']->getOne($sql);
    if ($sales_count > 0)
    {
        /* 只有在商品销售量大于0时才去计算该商品的排行 */
        $sql = 'SELECT DISTINCT SUM(goods_number) AS num ' .
                'FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o, ' .
                    $GLOBALS['ecs']->table('order_goods') . ' AS g ' .
                "WHERE o.order_id = g.order_id " .
                "AND o.order_status = '" . OS_CONFIRMED . "' " .
                "AND o.shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
                " AND o.pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . $ext .
                " GROUP BY g.goods_id HAVING num > $sales_count";
        $res = $GLOBALS['db']->query($sql);
        $rank = $GLOBALS['db']->num_rows($res) + 1;
        if ($rank > 10)
        {
            $rank = 0;
        }
    }
    else
    {
        $rank = 0;
    }
    return $rank;
}
/**
 * 获得商品选定的属性的附加总价格
 *
 * @param   integer     $goods_id
 * @param   array       $attr
 *
 * @return  void
 */
function get_attr_amount($goods_id, $attr)
{
    $sql = "SELECT SUM(attr_price) FROM " . $GLOBALS['ecs']->table('goods_attr') .
        " WHERE goods_id='$goods_id' AND " . db_create_in($attr, 'goods_attr_id');
    return $GLOBALS['db']->getOne($sql);
}
/**
 * 取得跟商品关联的礼包列表
 *
 * @param   string  $goods_id    商品编号
 *
 * @return  礼包列表
 */
function get_package_goods_list($goods_id)
{
    $now = gmtime();
    $sql = "SELECT pg.goods_id, ga.act_id, ga.act_name, ga.act_desc, ga.goods_name, ga.start_time,
                   ga.end_time, ga.is_finished, ga.ext_info
            FROM " . $GLOBALS['ecs']->table('goods_activity') . " AS ga, " . $GLOBALS['ecs']->table('package_goods') . " AS pg
            WHERE pg.package_id = ga.act_id
            AND ga.start_time <= '" . $now . "'
            AND ga.end_time >= '" . $now . "'
            AND pg.goods_id = " . $goods_id . "
            GROUP BY ga.act_id
            ORDER BY ga.act_id ";
    $res = $GLOBALS['db']->getAll($sql);
    foreach ($res as $tempkey => $value)
    {
        $subtotal = 0;
        $row = unserialize($value['ext_info']);
        unset($value['ext_info']);
        if ($row)
        {
            foreach ($row as $key=>$val)
            {
                $res[$tempkey][$key] = $val;
            }
        }
        $sql = "SELECT pg.package_id, pg.goods_id, pg.goods_number, pg.admin_id, p.goods_attr, g.goods_sn, g.goods_name, g.market_price, g.goods_thumb, IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS rank_price
                FROM " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                    LEFT JOIN ". $GLOBALS['ecs']->table('goods') . " AS g
                        ON g.goods_id = pg.goods_id
                    LEFT JOIN ". $GLOBALS['ecs']->table('products') . " AS p
                        ON p.product_id = pg.product_id
                    LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp
                        ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]'
                WHERE pg.package_id = " . $value['act_id']. "
                ORDER BY pg.package_id, pg.goods_id";
        $goods_res = $GLOBALS['db']->getAll($sql);
        foreach($goods_res as $key => $val)
        {
            $goods_id_array[] = $val['goods_id'];
            $goods_res[$key]['goods_thumb']  = get_image_path($val['goods_id'], $val['goods_thumb'], true);
            $goods_res[$key]['market_price'] = price_format($val['market_price']);
            $goods_res[$key]['rank_price']   = price_format($val['rank_price']);
            $subtotal += $val['rank_price'] * $val['goods_number'];
        }
        /* 取商品属性 */
        $sql = "SELECT ga.goods_attr_id, ga.attr_value
                FROM " .$GLOBALS['ecs']->table('goods_attr'). " AS ga, " .$GLOBALS['ecs']->table('attribute'). " AS a
                WHERE a.attr_id = ga.attr_id
                AND a.attr_type = 1
                AND " . db_create_in($goods_id_array, 'goods_id');
        $result_goods_attr = $GLOBALS['db']->getAll($sql);
        $_goods_attr = array();
        foreach ($result_goods_attr as $value)
        {
            $_goods_attr[$value['goods_attr_id']] = $value['attr_value'];
        }
        /* 处理货品 */
        $format = '[%s]';
        foreach($goods_res as $key => $val)
        {
            if ($val['goods_attr'] != '')
            {
                $goods_attr_array = explode('|', $val['goods_attr']);
                $goods_attr = array();
                foreach ($goods_attr_array as $_attr)
                {
                    $goods_attr[] = $_goods_attr[$_attr];
                }
                $goods_res[$key]['goods_attr_str'] = sprintf($format, implode('，', $goods_attr));
            }
        }
        $res[$tempkey]['goods_list']    = $goods_res;
        $res[$tempkey]['subtotal']      = price_format($subtotal);
        $res[$tempkey]['saving']        = price_format(($subtotal - $res[$tempkey]['package_price']));
        $res[$tempkey]['package_price'] = price_format($res[$tempkey]['package_price']);
    }
    return $res;
}
function get_goods_info_phone($goods_id)
{
    // Fetch goods info
    $sql_goods = "SELECT g.cat_id, c.parent_id, c2.parent_id as grandparent_id " .
                 "FROM " . $GLOBALS['ecs']->table('goods') . " AS g " .
                 "JOIN " . $GLOBALS['ecs']->table('category') . " AS c ON g.cat_id = c.cat_id " .
                 "LEFT JOIN " . $GLOBALS['ecs']->table('category') . " AS c2 ON c.parent_id = c2.cat_id " .
                 "WHERE g.goods_id = '$goods_id' ";
    $goods = $GLOBALS['db']->getRow($sql_goods);

    if (!$goods) {
        return false;
    }

    // Determine potential parent_ids in order of preference
    $potential_parent_ids = [$goods['cat_id'], $goods['parent_id'], $goods['grandparent_id']];
    $result = [];
    $use_level_1_data = true;

    foreach ($potential_parent_ids as $parent_id) {
        if ($parent_id != $goods['grandparent_id']) { // if level 2 or 3
            foreach ([1 => 'hn', 2 => 'hcm'] as $region_code => $region_name) {
                $sql_phone = "SELECT cp.name, cp.phone, cp.khu_vuc " .
                             "FROM " . $GLOBALS['ecs']->table('catphone') . " AS cp " .
                             "WHERE cp.cat_id = '$parent_id' AND cp.khu_vuc = '$region_code' ";
                $phones = $GLOBALS['db']->getAll($sql_phone);
                if ($phones) {
                    shuffle($phones);
                    $result["goods_phone_$region_name"] = $phones;
                    $use_level_1_data = false; // level 2 or 3 data exists
                }
            }
        }
        if (!$use_level_1_data) { // if level 2 or 3 data exists, stop checking
            break;
        }
    }

    if ($use_level_1_data) { // if no level 2 or 3 data exists, use level 1 data
        foreach ([1 => 'hn', 2 => 'hcm'] as $region_code => $region_name) {
            $sql_phone = "SELECT cp.name, cp.phone, cp.khu_vuc " .
                         "FROM " . $GLOBALS['ecs']->table('catphone') . " AS cp " .
                         "WHERE cp.cat_id = '".$goods['grandparent_id']."' AND cp.khu_vuc = '$region_code' ";
            $phones = $GLOBALS['db']->getAll($sql_phone);
            if ($phones) {
                shuffle($phones);
                $result["goods_phone_$region_name"] = $phones;
            }
        }
    }
    return $result;
}




?>