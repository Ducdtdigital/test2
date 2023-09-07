<?php

/*define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');*/

if ((DEBUG_MODE & 2) != 2)

{

    $smarty->caching = true;

}

/*------------------------------------------------------ */

//-- INPUT

/*------------------------------------------------------ */



$id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='category' AND slug = '".$slug."'");

$cat_id = $id > 0 ? intval($id) : 0;

$category_id = $cat_id;

if($cat_id == 0)

{

    withRedirect($getRequest['getBaseUrl']);

    exit;

}

/* 初始化分页信息 */

$page = isset($_REQUEST['page'])   && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;

$size = isset($_CFG['page_size'])  && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;

//$brand = isset($_REQUEST['brand']) && intval($_REQUEST['brand']) > 0 ? intval($_REQUEST['brand']) : 0;



if(isset($slug_brand))

{

    $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE slug = '".$slug_brand."' AND module='brand'");

    $brand = $id > 0 ? intval($id) : 0;

}else{

    $brand = 0;

}



$price_max = isset($_REQUEST['duoi']) && intval($_REQUEST['duoi']) > 0 ? intval($_REQUEST['duoi']) : 0;

$price_min = isset($_REQUEST['tu']) && intval($_REQUEST['tu']) > 0 ? intval($_REQUEST['tu']) : 0;



if (isset($_REQUEST['tren']) && intval($_REQUEST['tren']) > 0) {

    $price_min = intval($_REQUEST['tren']);

    $price_max = 0;

}





$recommend = isset($_REQUEST['rc']) && !empty($_REQUEST['rc']) ? $_REQUEST['rc'] : '';



$filter_attr_str = isset($_REQUEST['filter_attr']) ? htmlspecialchars(trim($_REQUEST['filter_attr'])) : '0';

$filter_attr_str = trim(urldecode($filter_attr_str));

$filter_attr_str = preg_match('/^[\d\.]+$/',$filter_attr_str) ? $filter_attr_str : '';

$filter_attr = empty($filter_attr_str) ? '' : explode('.', $filter_attr_str);



/* 排序、显示方式以及类型 */

$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');

$default_sort_order_method1 = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';

$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';



$default_sort_order_type1 = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'sort_order' : 'last_update');

$default_sort_order_type = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');



$sort = 'is_best';

if (isset($_REQUEST['sort'])) {

    if ($_REQUEST['sort'] == 'shop_price' || $_REQUEST['sort'] == 'is_best' || $_REQUEST['sort'] == 'is_new') {

        $sort = in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update', 'is_best', 'is_new')) ? trim($_REQUEST['sort']) : $default_sort_order_type;

    } else {

        $sort = in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'sort_order', 'last_update', 'is_best', 'is_new')) ? trim($_REQUEST['sort']) : $default_sort_order_type1;

    }

}



$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC'))) ? trim($_REQUEST['order']) : $default_sort_order_method;



$display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);





$display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';



setcookie('ECS[display]', $display, time() + 86400 * 0, $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);



/* nofflow SEO params bellow */

if(isset($_REQUEST['duoi']) || isset($_REQUEST['tu']) || isset($_REQUEST['filter_attr']) || isset($_REQUEST['display']) ||

    isset($_REQUEST['sort']) || isset($_REQUEST['order'])){

    $nofflow = 1;

}else{

    $nofflow = 0;

}





/*------------------------------------------------------ */

//-- PROCESSOR

/*------------------------------------------------------ */



$cache_id = sprintf('%X', crc32($cat_id . '-' . $display . '-' . $sort  .'-' . $order  .'-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' .

    $_CFG['lang'] .'-'. $brand. '-' . $price_max . '-' .$price_min . '-' . $filter_attr_str.'-'.$_device));

// Truy vấn lấy thông tin parent_id của danh mục hiện tại

$parent_id = $db->getOne("SELECT parent_id FROM " . $ecs->table('category') . " WHERE cat_id = " . $cat_id);



// Kiểm tra điều kiện để xác định template tương ứng

if ($parent_id == 0) {

    $_template = 'category' . $_device . '.dwt';

} else {

    $grandparent_id = $db->getOne("SELECT parent_id FROM " . $ecs->table('category') . " WHERE cat_id = " . $parent_id);

    if ($grandparent_id == 0) {

        $_template = 'category2' . $_device . '.dwt';

    } else {

        $greatgrandparent_id = $db->getOne("SELECT parent_id FROM " . $ecs->table('category') . " WHERE cat_id = " . $grandparent_id);

        if ($greatgrandparent_id == 0) {

            $_template = 'category3' . $_device . '.dwt';

        } else {

            // Nếu bạn muốn thêm cấp độ thứ 4, bạn có thể thêm một truy vấn và điều kiện ở đây

            $greatgreatgrandparent_id = $db->getOne("SELECT parent_id FROM " . $ecs->table('category') . " WHERE cat_id = " . $greatgrandparent_id);

            if ($greatgreatgrandparent_id == 0) {

                $_template = 'category4' . $_device . '.dwt';

            } else {

                // Nếu cần thiết, bạn có thể thêm các điều kiện khác ở đây

                $_template = 'category' . $_device . '.dwt';

            }

        }

    }

}

if (!$smarty->is_cached($_template, $cache_id))

{

    /** Active menu */

    if(isset($active_url)){

        $smarty->assign('active_url', $active_url);

    }

    $children = get_children($cat_id);

    $cat = get_cat_info($cat_id);

    if (!empty($cat))

    {

        $smarty->assign('keywords',    htmlspecialchars($cat['keywords']));

        $smarty->assign('cat_faq',     ($cat['cat_faq']));
        $smarty->assign('nofflow_cat',     ($cat['is_show']));
        $smarty->assign('cat_icon',     ($cat['icon']));
        $smarty->assign('cat_name',    htmlspecialchars($cat['cat_name']));

        $smarty->assign('description', htmlspecialchars($cat['cat_desc']));

        $smarty->assign('cat_style',   htmlspecialchars($cat['style']));

    }

    else

    {

        ecs_header("Location: ./\n");

        exit;

    }

    /* 赋值固定内容 */

    if ($brand > 0)

    {

        $sql = "SELECT brand_name FROM " .$GLOBALS['ecs']->table('brand'). " WHERE brand_id = '$brand'";

        $brand_name = $db->getOne($sql);

        $brand_name = ' '.$brand_name;

    }

    else

    {

        $brand_name = '';

    }

    /**

     * Lọc theo bước giá

     * $price_start = Giá SP nhỏ nhất làm tròn + bước giá ==> Dưới xxx

     * $price_end = Giá SP lớn nhất làm tròn + bước giá

     */

    /* 获取价格分级 */

    if ($cat['grade'] == 0  && $cat['parent_id'] != 0)

    {

        $cat['grade'] = get_parent_grade($cat_id); //如果当前分类级别为空，取最近的上级分类

    }



if (!empty($cat['grade_value'])) {

    // Chuyển đổi chuỗi grade_value thành mảng các giá trị số

    $grade_values = explode(',', $cat['grade_value']);



    // Tạo các khoảng giá dựa trên các giá trị trong mảng $grade_values

    $price_ranges = [];

    for ($i = 0; $i <= count($grade_values); $i++) {

        if ($i == 0) {

            $price_ranges[] = ['start' => 0, 'end' => $grade_values[$i]];

        } elseif ($i == count($grade_values)) {

            $price_ranges[] = ['start' => $grade_values[$i - 1], 'end' => PHP_INT_MAX];

        } else {

            $price_ranges[] = ['start' => $grade_values[$i - 1], 'end' => $grade_values[$i]];

        }

    }



    foreach ($price_ranges as $key => $range) {

        // Tính toán số lượng sản phẩm trong khoảng giá này

        $sql = "SELECT COUNT(*) as goods_num FROM " . $ecs->table('goods') . " AS g WHERE ($children OR " . get_extension_goods($children) . ") AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.shop_price >= {$range['start']} AND g.shop_price < {$range['end']}";



        $goods_num = $db->getOne($sql);



        $price_grade[$key]['goods_num'] = $goods_num;

        $price_grade[$key]['start'] = $range['start'];

        $price_grade[$key]['end'] = $range['end'] == PHP_INT_MAX ? '' : $range['end'];

        if ($range['end'] == PHP_INT_MAX) {

            $price_grade[$key]['price_range'] = 'Trên ' . price_format_vn($range['start']);

        } elseif ($range['start'] == 0) {

            $price_grade[$key]['price_range'] = 'Dưới ' . price_format_vn($range['end']);

        } else {

            $price_grade[$key]['price_range'] = price_format_vn($range['start']) . ' - ' . price_format_vn($range['end']);

        }



        

        

        $price_grade[$key]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => $brand, 'price_min' => $range['start'], 'price_max' => $range['end'], 'filter_attr' => $filter_attr_str), $cat['cat_name']);

        if ($range['end'] == PHP_INT_MAX) {

                $price_grade[$key]['selected'] = ($_REQUEST['tu'] == $range['start']) ? 1 : 0;

            } elseif ($range['start'] == 0) {

                $price_grade[$key]['selected'] = ($_REQUEST['duoi'] == $range['end']) ? 1 : 0;

            } else {

                $price_grade[$key]['selected'] = ($_REQUEST['tu'] == $range['start'] && $_REQUEST['duoi'] == $range['end']) ? 1 : 0;

            }



    

    }



    $smarty->assign('price_grade', $price_grade);

}





    /* Lọc hãng Sx */

    $sql = "SELECT b.brand_id, b.brand_name, b.brand_logo, COUNT(*) AS goods_num ".

            "FROM " . $GLOBALS['ecs']->table('brand') . "AS b, ".

                $GLOBALS['ecs']->table('goods') . " AS g LEFT JOIN ". $GLOBALS['ecs']->table('goods_cat') . " AS gc ON g.goods_id = gc.goods_id " .

            "WHERE g.brand_id = b.brand_id AND ($children OR " . 'gc.cat_id ' . db_create_in(array_unique(array_merge(array($cat_id), array_keys(cat_list($cat_id, 0, false))))) . ") AND b.is_show = 1 " .

            " AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".

            "GROUP BY b.brand_id HAVING goods_num > 0 ORDER BY b.sort_order, b.brand_id ASC";

    $brands = $GLOBALS['db']->getAll($sql);

    foreach ($brands AS $key => $val)

    {

        $temp_key = $key + 1;

        $brands[$temp_key]['brand_name'] = $val['brand_name'];

        $brands[$temp_key]['brand_logo'] = $val['brand_logo'];

        $brands[$temp_key]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => $val['brand_id'], 'price_min'=>$price_min, 'price_max'=> $price_max, 'filter_attr'=>$filter_attr_str, 'rc'=>$recommend), $cat['cat_name']);



        if ($brand == $brands[$key]['brand_id'])

            $brands[$temp_key]['selected'] = 1;

        else

            $brands[$temp_key]['selected'] = 0;

    }

    $brands[0]['brand_name'] = $_LANG['all_attribute'];

    $brands[0]['brand_logo'] = '';

    $brands[0]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => 0, 'price_min'=>$price_min, 'price_max'=> $price_max, 'filter_attr'=>$filter_attr_str, 'rc'=>$recommend), $cat['cat_name']);

    $brands[0]['selected'] = empty($brand) ? 1 : 0;

    $smarty->assign('brands', $brands);

    /* Lọc thuộc tính */

    $ext = '';

    $ext_title = '';

    if ($cat['filter_attr'] > 0)

    {

        $cat_filter_attr = explode(',', $cat['filter_attr']);       //提取出此分类的筛选属性

        $all_attr_list = array();



        foreach ($cat_filter_attr AS $key => $value)

        {

            $sql = "SELECT a.attr_name FROM " . $ecs->table('attribute') . " AS a, " . $ecs->table('goods_attr') . " AS ga, " . $ecs->table('goods') . " AS g WHERE ($children OR " . get_extension_goods($children) . ") AND a.attr_id = ga.attr_id AND g.goods_id = ga.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND a.attr_id='$value'";



            if($temp_name = $db->getOne($sql))

            {

                $all_attr_list[$key]['filter_attr_name'] = $temp_name;

                $sql = "SELECT a.attr_id, MIN(a.goods_attr_id ) AS goods_id, a.attr_value AS attr_value FROM " . $ecs->table('goods_attr') . " AS a, " . $ecs->table('goods') .

                       " AS g" .

                       " WHERE ($children OR " . get_extension_goods($children) . ') AND g.goods_id = a.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 '.

                       " AND a.attr_id='$value' ".

                       " GROUP BY a.attr_value";

                $attr_list = $db->getAll($sql);

                $temp_arrt_url_arr = array();

                for ($i = 0; $i < count($cat_filter_attr); $i++)        //获取当前url中已选择属性的值，并保留在数组中

                {

                    $temp_arrt_url_arr[$i] = !empty($filter_attr[$i]) ? $filter_attr[$i] : 0;

                }

                $temp_arrt_url_arr[$key] = 0;                           //“全部”的信息生成

                $temp_arrt_url = implode('.', $temp_arrt_url_arr);

                $all_attr_list[$key]['attr_list'][0]['attr_value'] = $_LANG['all_attribute'];

                $all_attr_list[$key]['attr_list'][0]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_min, 'price_max'=>$price_max, 'filter_attr'=>$temp_arrt_url, 'rc'=>$recommend), $cat['cat_name']);

                $all_attr_list[$key]['attr_list'][0]['selected'] = empty($filter_attr[$key]) ? 1 : 0;

                foreach ($attr_list as $k => $v)

                {

                    $temp_key = $k + 1;

                    $temp_arrt_url_arr[$key] = $v['goods_id'];       //为url中代表当前筛选属性的位置变量赋值,并生成以‘.’分隔的筛选属性字符串

                    $temp_arrt_url = implode('.', $temp_arrt_url_arr);

                    $all_attr_list[$key]['attr_list'][$temp_key]['attr_value'] = $v['attr_value'];

                    $all_attr_list[$key]['attr_list'][$temp_key]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_min, 'price_max'=>$price_max, 'filter_attr'=>$temp_arrt_url, 'rc'=>$recommend), $cat['cat_name']);

                    if (!empty($filter_attr[$key]) AND $filter_attr[$key] == $v['goods_id'])

                    {

                        $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;

                        $ext_title .= ', '.$temp_name.' '.$v['attr_value'];

                    }

                    else

                    {

                        $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 0;

                    }

                }

            }

        }

        $ext_title = ' '.ltrim($ext_title, ', ');



        //var_dump($all_attr_list);

        $smarty->assign('filter_attr_list',  $all_attr_list);

        /* 扩展商品查询条件 */

        if (!empty($filter_attr))

        {

            $ext_sql = "SELECT DISTINCT(b.goods_id) FROM " . $ecs->table('goods_attr') . " AS a, " . $ecs->table('goods_attr') . " AS b " .  "WHERE ";

            $ext_group_goods = array();

            foreach ($filter_attr AS $k => $v)                      // 查出符合所有筛选属性条件的商品id */

            {

                if (is_numeric($v) && $v !=0 &&isset($cat_filter_attr[$k]))

                {

                    $sql = $ext_sql . "b.attr_value = a.attr_value AND b.attr_id = " . $cat_filter_attr[$k] ." AND a.goods_attr_id = " . $v;

                    $ext_group_goods = $db->getColCached($sql);

                    $ext .= ' AND ' . db_create_in($ext_group_goods, 'g.goods_id');

                }

            }

        }

    }

    /**

     * Tính năng mới thêm để tránh trùng lặp meta title

     * Lọc sp recommend 1:new|2:best|3:hot tại danh mục hiện hành

     */

     $ext_rc_title = '';

    if($recommend == 'moi'){

        $ext .= ' AND g.is_new=1 ';

        $ext_rc_title .= ' mới';

    }elseif ($recommend == 'ban-chay') {

        $ext .= ' AND g.is_best=1 ';

        $ext_rc_title .= ' bán chạy';

    }elseif ($recommend == 'hot') {

        $ext .= ' AND g.is_hot=1 ';

        $ext_rc_title .= ' đang Hot';

    }



    $ext_title_price = '';

    if($price_min && $price_max){

        $fprice_min = price_format($price_min);

        $fprice_max = price_format($price_max);

        $ext_title_price = ' giá từ '.$fprice_min.' đến '.$fprice_max;

    }

    elseif($price_min){

        $fprice_min = price_format($price_min);

        $ext_title_price = ' giá trên '.$fprice_min;

    }elseif($price_max){

        $fprice_max = price_format($price_max);

        $ext_title_price = ' giá dưới '.$fprice_max;

    }



    if (!empty($filter_attr)){

        $cat_title =  $cat['cat_name'] != '' ? htmlspecialchars($cat['cat_name']) : htmlspecialchars($cat['meta_title']);

    }elseif($brand_name) {

        $cat_title = $cat['cat_name'].$brand_name;

    }else{

       $cat_title =  $cat['cat_name'] != '' ? htmlspecialchars($cat['cat_name']) : htmlspecialchars($cat['meta_title']);

    }



    $sort_title = '';

    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] == 'sort_order' && $_REQUEST['order'] == 'ASC'){

        $sort_title = ', Sản phẩm mới nhất';

    }

    elseif(isset($_REQUEST['sort']) && $_REQUEST['sort'] && $_REQUEST['order'] == 'DESC'){

        $sort_title = ', giá giảm dần';

    }

    elseif(isset($_REQUEST['sort']) && $_REQUEST['sort'] && $_REQUEST['order'] == 'ASC'){

        $sort_title =  ', giá tăng dần';

    }




    $paginator_title = $page > 1 ? ' #'.$page : '';

    $page_title = htmlspecialchars($cat['meta_title']).$ext_title_price.$ext_rc_title.$ext_title.$sort_title.$paginator_title;

    $cat_faq = $cat['cat_faq'];
    $cat_icon = $cat['icon'];
    $nofflow_cat = $cat['is_show'];
    // Lấy thông tin từ bảng brand nếu $brand > 0

    if ($brand > 0) {
        $sql = "SELECT brand_desc, brand_title, brand_desc_sort, brand_keyword FROM " . $GLOBALS['ecs']->table('catbrand') . " WHERE brand_id = '$brand' AND cat_id = '$cat_id'";
        $brand_info = $GLOBALS['db']->getRow($sql);  // Lấy toàn bộ hàng dữ liệu thay vì chỉ lấy một trường

        $brand_desc = $brand_info['brand_desc'];
        $brand_title = $brand_info['brand_title'];
        $brand_desc_sort = $brand_info['brand_desc_sort'];
        $brand_keyword = $brand_info['brand_keyword'];
    }

    // Kiểm tra xem truy cập URL là brand hay không
    if ($brand > 0) {
        // Nếu truy cập URL là brand, gán giá trị của các trường brand cho các biến tương ứng dù rỗng hay không
        $description1 = $brand_desc;
        $title1 = htmlspecialchars($brand_title).$ext_title_price.$ext_rc_title.$ext_title.$sort_title.$paginator_title;
        $desc_sort1 = htmlspecialchars($brand_desc_sort);
        $keyword1 = $brand_keyword;
    } else {
        // Nếu không, gán giá trị của các trường tương ứng từ $cat cho các biến
        $description1 = $cat['long_desc'];
        $title1 = $page_title;  // Giả sử $cat có trường title
        $desc_sort1 = $description;  // Giả sử $cat có trường desc_sort
        $keyword1 = $cat['keyword'];  // Giả sử $cat có trường keyword
    }

    

    // Gán giá trị của $description1 cho biến smarty

    $smarty->assign('description1', $description1);
    $smarty->assign('title1', $title1);
    $smarty->assign('desc_sort1', $desc_sort1);
    $smarty->assign('keyword1', $keyword1);
    /**  End  */

    assign_template('c', array($cat_id));

    $position = assign_ur_here($cat_id, '', 'category');

    $smarty->assign('page_title', $page_title);

    $smarty->assign('ur_here',          $position['ur_here']);

   //$smarty->assign('categories',       get_categories_tree($cat_id));

    //$smarty->assign('helps',            get_shop_help());

    //$smarty->assign('top_goods',        get_top10());

    $smarty->assign('show_marketprice', $_CFG['show_marketprice']);

    $smarty->assign('category',         $cat_id);

    $smarty->assign('brand_id',         $brand);

    $smarty->assign('brand_name',       $brand_name);

    $smarty->assign('price_max',        $price_max);

    $smarty->assign('price_min',        $price_min);

    $smarty->assign('filter_attr',      $filter_attr_str);

    $smarty->assign('recommend',        $recommend);

    $smarty->assign('long_desc', $cat['long_desc']);
    $smarty->assign('boloc', $_REQUEST['filter_attr']);
    //var_dump($boloc);die;

    // if ($brand > 0)

    // {

    //     $arr['all'] = array('brand_id'  => 0,

    //                     'brand_name'    => $GLOBALS['_LANG']['all_goods'],

    //                     'brand_logo'    => '',

    //                     'goods_num'     => '',

    //                     'url'           => build_uri('category', array('cid'=>$cat_id), $cat['cat_name'])

    //                 );

    // }

    // else

    // {

    //     $arr = array();

    // }

    // $brand_list = array_merge($arr, get_brands($cat_id, 'category'));

    // $smarty->assign('brand_list',      $brand_list);



    $smarty->assign('data_dir',    DATA_DIR);



    //$smarty->assign('promotion_info', get_promotion_info());

    /* 调查 */

    // $vote = get_vote();

    // if (!empty($vote))

    // {

    //     $smarty->assign('vote_id',     $vote['id']);

    //     $smarty->assign('vote',        $vote['content']);

    // }



    if($client == 'pc'){

        $smarty->assign('hot_goods',  get_category_recommend_goods('hot', $children, $brand, $price_min, $price_max, $ext));

        $smarty->assign('best_goods',      get_category_recommend_goods('best', $children, $brand, $price_min, $price_max, $ext));

        $smarty->assign('promotion_goods', get_category_recommend_goods('promote', $children, $brand, $price_min, $price_max, $ext));

    }



    $count = get_cagtegory_goods_count($children, $brand, $price_min, $price_max, $ext);

    $max_page = ($count> 0) ? ceil($count / $size) : 1;



    if ($page > $max_page)

    {

        $page = $max_page;

    }

     $parent_catid  = $cat_id == 7 ? 7 : $cat['parent_id'];

     $goodslist = category_get_goods1($children, $brand, $price_min, $price_max, $ext, $size, $page, $sort, $order,$parent_catid);

     $goodslist2 = category_get_goods($children, $brand, $price_min, $price_max, $ext, $size, $page, $sort, $order,$parent_catid);

    if($display == 'grid')

    {

        if(count($goodslist2) % 2 != 0)

        {

            $goodslist2[] = array();

        }

    }

    



$subcategories = get_subcategories($category_id);



foreach ($subcategories as &$subcategory) {

    $subcategory['goods'] = get_subcategory_goods($subcategory['cat_id']);

}

//test

$current_cat_id = $cat_id;

$smarty->assign('sibling_cats', get_sibling_categories($current_cat_id));





// Gán $subcategories cho Smarty

    $smarty->assign('subcategories', $subcategories);

    $smarty->assign('catsubs', get_cat_child($cat_id, $cat['parent_id']));

    $smarty->assign('goods_list',       $goodslist);

    $smarty->assign('goods_list2',       $goodslist2);

    $smarty->assign('parent_id',        $cat['parent_id']);

    $smarty->assign('ads_id',        $cat['ads_id']);

    $smarty->assign('script_name', 'category');

    $smarty->assign('nofflow', $nofflow);



    $viewmore_number = intval($count)-($page*$size);

    $smarty->assign('viewmore_number', $viewmore_number);

    assign_pager('category', $cat_id, $count, $size, $sort, $order, $page, '', $brand, $price_min, $price_max, $display, $filter_attr_str); // 分页

    assign_dynamic('category');



}

$smarty->display($_template, $cache_id);





/**

 *

 * @param   integer $cat_id

 *

 * @return  void

 */

function get_subcategory_goods($cat_id)

{

    if ($cat_id <= 0) {

        return array();

    }



    $children = get_children($cat_id);

    $goods_list = category_get_goods1($children, 0, 0, 0, '', 20, 1, 'sort_order', 'ASC', 0, $cat_id, 20, true);





    return $goods_list;

}



function get_subcategories($parent_id, $page = 1, $limit = 99)

{

    $offset = ($page - 1) * $limit;



    // Truy vấn để lấy danh sách danh mục con dựa trên parent_id và giới hạn số lượng là 4

    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('category') . " WHERE parent_id = '$parent_id' ORDER BY sort_order ASC LIMIT $offset, $limit";

    $result = $GLOBALS['db']->getAll($sql);



    // Thêm url cho từng danh mục con

    foreach ($result as &$subcategory) {

        $subcategory['url'] = build_uri('category', array('cid' => $subcategory['cat_id']), $subcategory['cat_name']);

    }



    return $result;

}







function category_get_goods1($children, $brand, $min, $max, $ext, $size, $page, $sort, $order, $parent_catid = 0, $cat_id = 0, $limit = 20, $random = false)
{
    $display = $GLOBALS['display'];
    $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND ".
            "g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
    if ($brand > 0)
    {
        $where .=  "AND g.brand_id=$brand ";
    }
    if ($min > 0)
    {
        $where .= " AND g.shop_price >= $min ";
    }
    if ($max > 0)
    {
        $where .= " AND g.shop_price <= $max ";
    }
    /* get goods list */
    $order_by = $random ? "RAND()" : "$sort $order";
    $sql = "SELECT g.goods_id, g.goods_name, g.click_count, g.is_feature, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, " .
           "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.online_price, g.goods_type, " .
           "IFNULL(AVG(r.comment_rank),0) AS comment_rank, IF(r.comment_rank,count(*),0) AS  comment_count, " .
           "g.promote_start_date, g.promote_end_date, g.desc_short, g.seller_note, g.goods_thumb_url, g.goods_thumb , g.goods_img, b.brand_logo " .
           "FROM " . $GLOBALS['ecs']->table('goods') . " AS g " .
           "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp " .
           "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
           "LEFT JOIN " . $GLOBALS['ecs']->table('brand') . " AS b ON g.brand_id = b.brand_id " .
           "LEFT JOIN " . $GLOBALS['ecs']->table('comment') . " AS r ".
           "ON r.id_value = g.goods_id AND comment_type = 0 AND r.parent_id = 0 AND r.status = 1 " .
           "WHERE $where $ext group by g.goods_id ORDER BY $order_by";

    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    $arr = array();

    $count = 0; // Thêm biến đếm



    while ($row = $GLOBALS['db']->fetchRow($res))

    {

        if ($count >= $limit) { // Nếu đã đạt đến giới hạn, dừng vòng lặp

            break;

        }

        if ($row['promote_price'] > 0)

        {

            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);

        }

        else

        {

            $promote_price = 0;

        }



        $arr[$row['goods_id']]['discount'] =$promote_price > 0 ? get_discount($row['shop_price'], $promote_price): '';



        $arr[$row['goods_id']]['goods_id']         = $row['goods_id'];



        if($display == 'grid')

        {

            $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];

        }

        else

        {

            $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];

        }



        /** End Tinh Diem */

        $arr[$row['goods_id']]['comment_rank']= ceil($row['comment_rank']);

        $arr[$row['goods_id']]['comment_count']=$row['comment_count'];



        $arr[$row['goods_id']]['istragop'] = ($parent_catid == 7 && $row['shop_price'] >= 3000000) ? 1 : 0;

        $arr[$row['goods_id']]['click_count']=$row['click_count'];

        $arr[$row['goods_id']]['is_best']      = $row['is_best'];

        $arr[$row['goods_id']]['is_hot']       = $row['is_hot'];

        $arr[$row['goods_id']]['is_new']       = $row['is_new'];

        $arr[$row['goods_id']]['is_feature']       = $row['is_feature'];

        $arr[$row['goods_id']]['name']             = $row['goods_name'];

        $arr[$row['goods_id']]['desc_short']   = nl2p(strip_tags($row['desc_short']));

        $arr[$row['goods_id']]['seller_note']  = nl2p(strip_tags($row['seller_note']));

        $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'],$row['goods_name_style']);

        $arr[$row['goods_id']]['market_price']     = price_format($row['market_price']);

        $arr[$row['goods_id']]['shop_price']       = price_format($row['shop_price']);

        $arr[$row['goods_id']]['price']            = $row['shop_price'];

        $arr[$row['goods_id']]['type']             = $row['goods_type'];

        $arr[$row['goods_id']]['promote_price']    = ($promote_price > 0) ? price_format($promote_price) : '';

        $arr[$row['goods_id']]['goods_thumb']      = get_image_path($row['goods_id'], $row['goods_thumb'], true);

        $arr[$row['goods_id']]['goods_img']        = get_image_path($row['goods_id'], $row['goods_img']);

        $arr[$row['goods_id']]['thumb_url']        = $row['goods_thumb_url'];
        $arr[$row['goods_id']]['brand_logo']        = $row['brand_logo'];

        $arr[$row['goods_id']]['url']              = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);

        $count++;

    }

    return $arr;

}













function get_cat_info($cat_id)

{

    return $GLOBALS['db']->getRow('SELECT cat_name, ads_id, long_desc, meta_title, keywords, cat_faq, cat_desc, style, grade, grade_value, filter_attr, parent_id, icon,is_show FROM ' . $GLOBALS['ecs']->table('category') .

        " WHERE cat_id = '$cat_id'");

}







/**

 * @access  public

 * @param   string  $children

 * @return  array

 */

function category_get_goods($children, $brand, $min, $max, $ext, $size, $page, $sort, $order, $parent_catid = 0, $cat_id = 0)

{

    $display = $GLOBALS['display'];

    $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND ".

            "g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';

    if ($brand > 0)

    {

        $where .=  "AND g.brand_id=$brand ";

    }

    if ($min > 0)

    {

        $where .= " AND g.shop_price >= $min ";

    }

    if ($max > 0)

    {

        $where .= " AND g.shop_price <= $max ";

    }

    /* 获得商品列表 */

// Sắp xếp sản phẩm dựa trên is_best, is_new và sort_order

$additional_sort = '';

if ($sort == 'is_best') {

    $additional_sort = "g.is_best DESC, g.sort_order, ";

} elseif ($sort == 'is_new') {

    $additional_sort = "g.is_new DESC, g.sort_order, ";

}



$sql = 'SELECT g.goods_id, g.goods_name, g.click_count, g.is_feature, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .

                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.online_price, g.goods_type, " .

                " IFNULL(AVG(r.comment_rank),0) AS comment_rank,IF(r.comment_rank,count(*),0) AS  comment_count, ".

                'g.promote_start_date, g.promote_end_date, g.desc_short, g.seller_note, g.goods_thumb_url, g.goods_thumb , g.goods_img, b.brand_logo ' .

            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .

            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .

                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('brand') . " AS b ON g.brand_id = b.brand_id " .

             ' LEFT JOIN  '. $GLOBALS['ecs']->table('comment') .' AS r '.

                'ON r.id_value = g.goods_id AND comment_type = 0 AND r.parent_id = 0 AND r.status = 1 ' .

            "WHERE $where $ext group by g.goods_id ";



// Thêm điều kiện sắp xếp dựa trên is_best, is_new và sort_order

$sql .= "ORDER BY (g.shop_price = 0) ASC, (g.goods_number = 0) ASC, $additional_sort $sort $order";



    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    $arr = array();

    while ($row = $GLOBALS['db']->fetchRow($res))

    {

        if ($row['promote_price'] > 0)

        {

            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);

        }

        else

        {

            $promote_price = 0;

        }



        $arr[$row['goods_id']]['discount'] =$promote_price > 0 ? get_discount($row['shop_price'], $promote_price): '';



        $arr[$row['goods_id']]['goods_id']         = $row['goods_id'];



        if($display == 'grid')

        {

            $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];

        }

        else

        {

            $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];

        }



        /** End Tinh Diem */

        $arr[$row['goods_id']]['comment_rank']= ceil($row['comment_rank']);

        $arr[$row['goods_id']]['comment_count']=$row['comment_count'];



        $arr[$row['goods_id']]['istragop'] = ($parent_catid == 7 && $row['shop_price'] >= 3000000) ? 1 : 0;

        $arr[$row['goods_id']]['click_count']=$row['click_count'];

        $arr[$row['goods_id']]['is_best']      = $row['is_best'];

        $arr[$row['goods_id']]['is_hot']       = $row['is_hot'];

        $arr[$row['goods_id']]['is_new']       = $row['is_new'];

        $arr[$row['goods_id']]['is_feature']       = $row['is_feature'];

        $arr[$row['goods_id']]['name']             = $row['goods_name'];

        $arr[$row['goods_id']]['desc_short']   = nl2p(strip_tags($row['desc_short']));

        $arr[$row['goods_id']]['seller_note']  = nl2p(strip_tags($row['seller_note']));

        $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'],$row['goods_name_style']);

        $arr[$row['goods_id']]['market_price']     = price_format($row['market_price']);

        $arr[$row['goods_id']]['shop_price']       = price_format($row['shop_price']);

        $arr[$row['goods_id']]['price']            = $row['shop_price'];

        $arr[$row['goods_id']]['type']             = $row['goods_type'];

        $arr[$row['goods_id']]['promote_price']    = ($promote_price > 0) ? price_format($promote_price) : '';

        $arr[$row['goods_id']]['goods_thumb']      = get_image_path($row['goods_id'], $row['goods_thumb'], true);

        $arr[$row['goods_id']]['goods_img']        = get_image_path($row['goods_id'], $row['goods_img']);

        $arr[$row['goods_id']]['thumb_url']        = $row['goods_thumb_url'];

        $arr[$row['goods_id']]['brand_logo']        = $row['brand_logo'];
        $arr[$row['goods_id']]['url']              = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);

    }

    return $arr;

}



/**

 * 获得分类下的商品总数

 *

 * @access  public

 * @param   string     $cat_id

 * @return  integer

 */

function get_cagtegory_goods_count($children, $brand = 0, $min = 0, $max = 0, $ext='')

{

    $where  = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';

    if ($brand > 0)

    {

        $where .=  " AND g.brand_id = $brand ";

    }

    if ($min > 0)

    {

        $where .= " AND g.shop_price >= $min ";

    }

    if ($max > 0)

    {

        $where .= " AND g.shop_price <= $max ";

    }

    /* 返回商品总数 */

    return $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('goods') . " AS g WHERE $where $ext");

}

/**

 * 取得最近的上级分类的grade值

 *

 * @access  public

 * @param   int     $cat_id    //当前的cat_id

 *

 * @return int

 */

function get_parent_grade($cat_id)

{

    static $res = NULL;

    if ($res === NULL)

    {

        $data = read_static_cache('cat_parent_grade');

        if ($data === false)

        {

            $sql = "SELECT parent_id, cat_id, grade, grade_value  ".

                   " FROM " . $GLOBALS['ecs']->table('category');

            $res = $GLOBALS['db']->getAll($sql);

            write_static_cache('cat_parent_grade', $res);

        }

        else

        {

            $res = $data;

        }

    }

    if (!$res)

    {

        return 0;

    }

    $parent_arr = array();

    $grade_arr = array();

    foreach ($res as $val)

    {

        $parent_arr[$val['cat_id']] = $val['parent_id'];

        $grade_arr[$val['cat_id']] = $val['grade'];



    }

    while ($parent_arr[$cat_id] >0 && $grade_arr[$cat_id] == 0)

    {

        $cat_id = $parent_arr[$cat_id];

    }

    return $grade_arr[$cat_id];

}
function price_format_vn($price) {
    $price_in_million = $price / 1000000;

    if($price_in_million < 1) {
        // Nếu giá tiền nhỏ hơn 1 triệu, thì hiển thị là "trăm"
        $price_vn = number_format($price_in_million * 10, 0, ',', '.') . ' trăm';
    } else {
        // Nếu giá tiền lớn hơn hoặc bằng 1 triệu, thì hiển thị là "triệu"
        if (floor($price_in_million) == $price_in_million) {
            // Nếu là số nguyên, thì không hiển thị phần thập phân
            $price_vn = number_format($price_in_million, 0, ',', '.') . ' triệu';
        } else {
            // Nếu là số thập phân, thì hiển thị 1 chữ số sau dấu phẩy
            $price_vn = number_format($price_in_million, 1, ',', '.') . ' triệu';
        }
    }

    return $price_vn;
}






?>

