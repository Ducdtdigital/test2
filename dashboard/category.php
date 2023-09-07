<?php

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
$exc = new exchange($ecs->table("category"), $db, 'cat_id', 'cat_name');
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
//-- 商品分类列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 获取分类列表 */
    $cat_list = cat_list(0, 0, false);
    /* 模板赋值 */
    $smarty->assign('ur_here',      $_LANG['03_category_list']);
    $smarty->assign('action_link',  array('href' => 'category.php?act=add', 'text' => $_LANG['04_category_add']));
    $smarty->assign('full_page',    1);
    $smarty->assign('cat_info',     $cat_list);
    /* 列表页面 */
    assign_query_info();
    $smarty->display('category_list.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $cat_list = cat_list(0, 0, false);
    $smarty->assign('cat_info',     $cat_list);
    make_json_result($smarty->fetch('category_list.htm'));
}
/*------------------------------------------------------ */
//-- 添加商品分类
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限检查 */
    admin_priv('cat_manage');
     CKeditor('long_desc', '');
    /* 模板赋值 */
    $smarty->assign('ur_here',      $_LANG['04_category_add']);
    $smarty->assign('action_link',  array('href' => 'category.php?act=list', 'text' => $_LANG['03_category_list']));
   // $smarty->assign('goods_type_list',  goods_type_list(0)); // 取得商品类型
    $smarty->assign('attr_list',        get_attr_list()); // 取得商品属性
    $smarty->assign('cat_select',   cat_list(0, 0, true));
    $smarty->assign('form_act',     'insert');
    $smarty->assign('cat_info',     array('is_show' => 1));
    /* 显示页面 */
    assign_query_info();
    $smarty->display('category_info.htm');
}
/*------------------------------------------------------ */
//-- 商品分类添加时的处理
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限检查 */
    admin_priv('cat_manage');
    /* 初始化变量 */
    $cat['cat_id']       = !empty($_POST['cat_id'])       ? intval($_POST['cat_id'])     : 0;
    $cat['parent_id']    = !empty($_POST['parent_id'])    ? intval($_POST['parent_id'])  : 0;
    $cat['sort_order']   = !empty($_POST['sort_order'])   ? intval($_POST['sort_order']) : 0;
    $cat['meta_title']     = !empty($_POST['meta_title'])   ? trim($_POST['meta_title'])     : '';
    $cat['meta_title'] = filter_var($cat['meta_title'], FILTER_SANITIZE_STRING);
    $cat['keywords']     = !empty($_POST['keywords'])     ? trim($_POST['keywords'])     : '';
    $cat['cat_faq']     = !empty($_POST['cat_faq'])     ? trim($_POST['cat_faq'])     : '';
    $cat['cat_desc']     = !empty($_POST['cat_desc'])     ? $_POST['cat_desc']           : '';
    $cat['measure_unit'] = !empty($_POST['measure_unit']) ? trim($_POST['measure_unit']) : '';
    $cat['cat_name']     = !empty($_POST['cat_name'])     ? trim($_POST['cat_name'])     : '';
    $cat['cat_name'] = filter_var($cat['cat_name'], FILTER_SANITIZE_STRING);
    $cat['custom_name'] = !empty($_POST['custom_name']) ? trim($_POST['custom_name']) : '';
    $cat['show_in_nav']  = !empty($_POST['show_in_nav'])  ? intval($_POST['show_in_nav']): 0;
    $cat['style']        = !empty($_POST['style'])        ? trim($_POST['style'])        : '';
    $cat['class']        = !empty($_POST['class'])        ? trim($_POST['class'])        : '';
    $cat['ads_id']        = !empty($_POST['ads_id'])      ? trim($_POST['ads_id'])        : '';
    $cat['is_show']      = !empty($_POST['is_show'])      ? intval($_POST['is_show'])    : 0;
    $cat['grade']        = !empty($_POST['grade'])        ? intval($_POST['grade'])      : 0;
    $cat['grade_value']  = !empty($_POST['grade_value'])  ? intval($_POST['grade_value'])  : 100000;
    $cat['filter_attr']  = !empty($_POST['filter_attr'])  ? implode(',', array_unique(array_diff($_POST['filter_attr'],array(0)))) : 0;
    $cat['cat_recommend']  = !empty($_POST['cat_recommend'])  ? $_POST['cat_recommend'] : array();

    $cat['show_cat_home']  = !empty($_POST['show_cat_home'])  ? intval($_POST['show_cat_home']): 0;
    $cat['long_desc'] = !empty($_POST['long_desc'])  ? $_POST['long_desc'] : '';

    $cat['icon']        = !empty($_POST['icon'])      ? trim($_POST['icon'])    : '';

    if (cat_exists($cat['cat_name'], $cat['parent_id']))
    {
       $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
       sys_msg($_LANG['catname_exist'], 0, $link);
    }

    if($cat['grade'] > 10 || $cat['grade'] < 0)
    {
        /* 价格区间数超过范围 */
       $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
       sys_msg($_LANG['grade_error'], 0, $link);
    }

    /* 入库的操作 */
    if ($db->autoExecute($ecs->table('category'), $cat) !== false)
    {
        $cat_id = $db->insert_id();

        //insert slug trước khi chèn những cái khác
        create_slug($cat_id, 'category');

        insert_cat_recommend($cat['cat_recommend'], $cat_id);
        admin_log($_POST['cat_name'], 'add', 'category');   // 记录管理员操作
        clear_cache_files();    // 清除缓存
        /*添加链接*/
        $link[0]['text'] = $_LANG['continue_add'];
        $link[0]['href'] = 'category.php?act=add';
        $link[1]['text'] = $_LANG['back_list'];
        $link[1]['href'] = 'category.php?act=list';
        sys_msg($_LANG['catadd_succed'], 0, $link);
    }
 }
/*------------------------------------------------------ */
//-- 编辑商品分类信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    admin_priv('cat_manage'); // Quyền kiểm tra
    $cat_id = intval($_REQUEST['cat_id']);
    $cat_info = get_cat_info($cat_id); // Truy vấn thông tin danh mục
    $parent_cat_id = $db->getOne("SELECT parent_id FROM " . $ecs->table('category') . " WHERE cat_id = '$cat_id'");
    $attr_list = get_attr_list();
    $filter_attr_list = array();
    if ($cat_info['filter_attr'])
    {
        $filter_attr = explode(",", $cat_info['filter_attr']);  //把多个筛选属性放到数组中
        foreach ($filter_attr AS $k => $v)
        {
            $attr_cat_id = $db->getOne("SELECT cat_id FROM " . $ecs->table('attribute') . " WHERE attr_id = '" . intval($v) . "'");
        $filter_attr_list[$k]['goods_type_list'] = goods_type_list1($attr_cat_id, $parent_cat_id);  //取得每个属性的商品类型
            $filter_attr_list[$k]['filter_attr'] = $v;
            $attr_option = array();
            foreach ($attr_list[$attr_cat_id] as $val)
            {
                $attr_option[key($val)] = current ($val);
            }
            $filter_attr_list[$k]['option'] = $attr_option;
        }
        $smarty->assign('filter_attr_list', $filter_attr_list);
    }
    else
    {
        $attr_cat_id = 0;
    }


    /* 模板赋值 */
    $smarty->assign('attr_list',        $attr_list); // 取得商品属性
    $smarty->assign('attr_cat_id',      $attr_cat_id);
    $smarty->assign('brand_list', get_brand_list1($cat_id));

    $smarty->assign('phone_sale_list', get_phone_sale_data($cat_id));
    $smarty->assign('ur_here',     $_LANG['category_edit']);
    $smarty->assign('action_link', array('text' => $_LANG['03_category_list'], 'href' => 'category.php?act=list'));
    //分类是否存在首页推荐
    $res = $db->getAll("SELECT recommend_type FROM " . $ecs->table("cat_recommend") . " WHERE cat_id=" . $cat_id);
    if (!empty($res))
    {
        $cat_recommend = array();
        foreach($res as $data)
        {
            $cat_recommend[$data['recommend_type']] = 1;
        }
        $smarty->assign('cat_recommend', $cat_recommend);
    }
    // Lấy danh sách các brand đã được thêm vào bảng catbrand
    $sql = "SELECT b.brand_id, b.brand_name, cb.brand_desc, cb.is_show, cb.brand_title, cb.brand_desc_sort, cb.brand_keyword FROM " . $ecs->table('catbrand') . " AS cb JOIN " . $ecs->table('brand') . " AS b ON cb.brand_id = b.brand_id WHERE cb.cat_id = '$cat_id'";
    $added_brands = $db->getAll($sql);
    $brand_desc_ckeditor = array();
        foreach ($added_brands as $key => $added_brand) {
            $brand_desc_ckeditor[$key] = CKeditorForBrands("brand_desc[$key]", $added_brand['brand_desc']);
        }
    $smarty->assign('brand_desc_ckeditor', $brand_desc_ckeditor);


    $smarty->assign('added_brands', $added_brands);
    
    $sql = "SELECT b.id, b.phone, b.name, b.khu_vuc FROM " . $ecs->table('catphone') . " AS cb JOIN " . $ecs->table('phone_sale') . " AS b ON cb.phone = b.phone WHERE cb.cat_id = '$cat_id'";
    $added_phones = $db->getAll($sql);
    
    $smarty->assign('added_phones', $added_phones);
    $smarty->assign('phone_list', get_phone_list());


    //get SLug
    $slug = get_slug($cat_id, 'category');
    $smarty->assign('slug',  $slug);
    
    CKeditor('long_desc', html_entity_decode($cat_info['long_desc']));
     
    $smarty->assign('cat_info',    $cat_info);
    $smarty->assign('form_act',    'update');
    $smarty->assign('cat_select',  cat_list(0, $cat_info['parent_id'], true));
    $smarty->assign('goods_type_list', goods_type_list1(0, $parent_cat_id)); // 取得商品类型
    /* 显示页面 */
    //var_dump(cat_list(0, $cat_info['parent_id'], true));die;
    assign_query_info();
    $smarty->display('category_info.htm');
}
elseif($_REQUEST['act'] == 'add_category')
{
    $parent_id = empty($_REQUEST['parent_id']) ? 0 : intval($_REQUEST['parent_id']);
    $category = empty($_REQUEST['cat']) ? '' : json_str_iconv(trim($_REQUEST['cat']));


    if(cat_exists($category, $parent_id))
    {
        make_json_error($_LANG['catname_exist']);
    }
    else
    {
        $sql = "INSERT INTO " . $ecs->table('category') . "(cat_name, parent_id, is_show)" .
               "VALUES ( '$category', '$parent_id', 1)";
        $db->query($sql);
        $category_id = $db->insert_id();
        $slug = build_slug($category);
        create_slug_direct($category_id, $slug, 'category');
        $arr = array("parent_id"=>$parent_id, "id"=>$category_id, "cat"=>$category);
        clear_cache_files();    // 清除缓存
        make_json_result($arr);
    }
}
/*------------------------------------------------------ */
//-- 编辑商品分类信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'update')
{
    /* 权限检查 */
    admin_priv('cat_manage');
    /* 初始化变量 */
    $cat_id              = !empty($_POST['cat_id'])       ? intval($_POST['cat_id'])     : 0;
    $old_cat_name        = $_POST['old_cat_name'];
    $cat['parent_id']    = !empty($_POST['parent_id'])    ? intval($_POST['parent_id'])  : 0;
    $cat['sort_order']   = !empty($_POST['sort_order'])   ? intval($_POST['sort_order']) : 0;
    $cat['meta_title']    = !empty($_POST['meta_title'])   ? trim($_POST['meta_title'])     : '';    
    $cat['keywords']     = !empty($_POST['keywords'])     ? trim($_POST['keywords'])     : '';
    $cat['cat_faq']     = !empty($_POST['cat_faq'])     ? trim($_POST['cat_faq'])     : '';
    $cat['meta_title']   = filter_var($cat['meta_title'], FILTER_SANITIZE_STRING);
    $cat['cat_desc']     = !empty($_POST['cat_desc'])     ? $_POST['cat_desc']           : '';
    $cat['measure_unit'] = !empty($_POST['measure_unit']) ? trim($_POST['measure_unit']) : '';
    $cat['cat_name']     = !empty($_POST['cat_name'])     ? trim($_POST['cat_name'])     : '';
    $cat['cat_name'] = filter_var($cat['cat_name'], FILTER_SANITIZE_STRING);
    $cat['custom_name'] = !empty($_POST['custom_name']) ? trim($_POST['custom_name']) : '';
    $cat['is_show']      = !empty($_POST['is_show'])      ? intval($_POST['is_show'])    : 0;
    $cat['show_in_nav']  = !empty($_POST['show_in_nav'])  ? intval($_POST['show_in_nav']): 0;
    $cat['style']        = !empty($_POST['style'])        ? trim($_POST['style'])        : '';
    $cat['class']        = !empty($_POST['class'])        ? trim($_POST['class'])        : '';
    $cat['ads_id']        = !empty($_POST['ads_id'])      ? trim($_POST['ads_id'])        : '';
    $cat['grade']        = !empty($_POST['grade'])        ? intval($_POST['grade'])      : 0;
    $cat['grade_value'] = !empty($_POST['grade_value']) ? $_POST['grade_value'] : '';
    $cat['sdt_dm'] = !empty($_POST['sdt_dm']) ? $_POST['sdt_dm'] : '';
    $cat['filter_attr']  = !empty($_POST['filter_attr'])  ? implode(',', array_unique(array_diff($_POST['filter_attr'],array(0)))) : 0;
    $cat['cat_recommend']  = !empty($_POST['cat_recommend'])  ? $_POST['cat_recommend'] : array();
    $cat['long_desc'] = !empty($_POST['long_desc'])  ? $_POST['long_desc'] : '';
    $cat['icon']        = !empty($_POST['icon'])      ? trim($_POST['icon'])    : '';
   
    $cat['show_cat_home']  = !empty($_POST['show_cat_home'])  ? intval($_POST['show_cat_home']): 0;
// Lấy dữ liệu từ form
$filter_brands = $_POST['filter_brand'];
$brand_descs = $_POST['brand_desc'];
$is_shows = isset($_POST['is_show']) ? array_map('intval', $_POST['is_show']) : array();
$brand_titles = $_POST['brand_title'];
$brand_desc_sorts = $_POST['brand_desc_sort'];
$brand_keywords = $_POST['brand_keyword'];

// Lấy tất cả brand_id hiện tại trong bảng catbrand cho cat_id đang xét
$sql = "SELECT brand_id FROM " . $ecs->table('catbrand') . " WHERE cat_id = '$cat_id'";
$current_brands = $db->getAll($sql);

// Tạo một mảng để chứa tất cả brand_id từ form
$submitted_brands = array();

// Xử lý dữ liệu
foreach ($filter_brands as $key => $filter_brand) {
    $filter_brand = intval($filter_brand);
    if ($filter_brand != 0) {
        $brand_desc = trim($brand_descs[$key]);
        $is_show = in_array($key, $is_shows) ? 1 : 0;
        $brand_title = trim($brand_titles[$key]);
        $brand_desc_sort = trim($brand_desc_sorts[$key]);
        $brand_keyword = trim($brand_keywords[$key]);

        // Thêm brand_id hiện tại vào mảng submitted_brands
        $submitted_brands[] = $filter_brand;

        // Kiểm tra xem bản ghi đã tồn tại trong bảng catbrand chưa
        $sql = "SELECT COUNT(*) FROM " . $ecs->table('catbrand') . " WHERE cat_id = '$cat_id' AND brand_id = '$filter_brand'";
        $record_exists = $db->getOne($sql) > 0;

         if ($record_exists) {
        // Nếu bản ghi đã tồn tại, cập nhật tất cả các trường
        $sql = "UPDATE " . $ecs->table('catbrand') . " SET brand_desc = '$brand_desc', is_show = '$is_show', brand_title = '$brand_title', brand_desc_sort = '$brand_desc_sort', brand_keyword = '$brand_keyword' WHERE cat_id = '$cat_id' AND brand_id = '$filter_brand'";
        } else {
            // Nếu bản ghi chưa tồn tại, thêm mới
            $sql = "INSERT INTO " . $ecs->table('catbrand') . " (cat_id, brand_id, brand_desc, is_show, brand_title, brand_desc_sort, brand_keyword) VALUES ('$cat_id', '$filter_brand', '$brand_desc', '$is_show', '$brand_title', '$brand_desc_sort', '$brand_keyword')";
        }
        $db->query($sql);
    }
}

// Xóa những bản ghi không còn tồn tại trong form
foreach ($current_brands as $current_brand) {
    if (!in_array($current_brand['brand_id'], $submitted_brands)) {
        $sql = "DELETE FROM " . $ecs->table('catbrand') . " WHERE cat_id = '$cat_id' AND brand_id = '{$current_brand['brand_id']}'";
        $db->query($sql);
    }
}

// Lấy dữ liệu từ form
$filter_phones = $_POST['filter_phone'];

$sql = "SELECT id, phone, phone_id FROM " . $ecs->table('catphone') . " WHERE cat_id = '$cat_id'";
$current_phones = $db->getAll($sql);

$submitted_phones = array();

foreach ($filter_phones as $key => $filter_phone) {
    $filter_phone = intval($filter_phone);
    if ($filter_phone != 0) {
        $sql = "SELECT id, phone, name, khu_vuc FROM " . $ecs->table('phone_sale') . " WHERE id = '$filter_phone'";
        $phone_data = $db->getRow($sql);

        if ($phone_data) {
            $phone = $phone_data['phone'];
            $name = trim($phone_data['name']);
            $khu_vuc = $phone_data['khu_vuc'];
            $phone_id = $phone_data['id'];

            $submitted_phones[] = $phone;  // Sử dụng phone thay vì id

            $sql = "SELECT COUNT(*) FROM " . $ecs->table('catphone') . " WHERE cat_id = '$cat_id' AND phone = '$phone'";
            $record_exists = $db->getOne($sql) > 0;

            if ($record_exists) {
                $sql = "UPDATE " . $ecs->table('catphone') . " SET phone_id = '$phone_id', name = '$name', khu_vuc = '$khu_vuc' WHERE cat_id = '$cat_id' AND phone = '$phone'";
            } else {
                $sql = "INSERT INTO " . $ecs->table('catphone') . " (cat_id, phone_id, name, khu_vuc, phone) VALUES ('$cat_id', '$phone_id', '$name', '$khu_vuc', '$phone')";
            }
            $db->query($sql);
        }
    }
}

foreach ($current_phones as $current_phone) {
    if (!in_array($current_phone['phone'], $submitted_phones)) {
        $sql = "DELETE FROM " . $ecs->table('catphone') . " WHERE cat_id = '$cat_id' AND phone = '{$current_phone['phone']}'";
        $db->query($sql);
    }
}



    /* 判断分类名是否重复 */
    if ($cat['cat_name'] != $old_cat_name)
    {
        if (cat_exists($cat['cat_name'],$cat['parent_id'], $cat_id))
        {
           $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
           sys_msg($_LANG['catname_exist'], 0, $link);
        }
    }

    /* 判断上级目录是否合法 */
    $children = array_keys(cat_list($cat_id, 0, false));     // 获得当前分类的所有下级分类
    if (in_array($cat['parent_id'], $children))
    {
        /* 选定的父类是当前分类或当前分类的下级分类 */
       $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
       sys_msg($_LANG["is_leaf_error"], 0, $link);
    }
    if($cat['grade'] > 10 || $cat['grade'] < 0)
    {
        /* 价格区间数超过范围 */
       $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
       sys_msg($_LANG['grade_error'], 0, $link);
    }
    $dat = $db->getRow("SELECT cat_name, show_in_nav FROM ". $ecs->table('category') . " WHERE cat_id = '$cat_id'");
    if ($db->autoExecute($ecs->table('category'), $cat, 'UPDATE', "cat_id='$cat_id'"))
    {
        if($cat['cat_name'] != $dat['cat_name'])
        {
            //如果分类名称发生了改变
            $sql = "UPDATE " . $ecs->table('nav') . " SET name = '" . $cat['cat_name'] . "' WHERE ctype = 'c' AND cid = '" . $cat_id . "' AND type = 'middle'";
            $db->query($sql);
        }
        update_slug($cat_id, 'category');
        //更新首页推荐
        insert_cat_recommend($cat['cat_recommend'], $cat_id);
        /* 更新分类信息成功 */
        clear_cache_files(); // 清除缓存
        admin_log($_POST['cat_name'], 'edit', 'category'); // 记录管理员操作
        /* 提示信息 */
        $link[] = array('text' => $_LANG['back_list'], 'href' => 'category.php?act=list');
        sys_msg($_LANG['catedit_succed'], 0, $link);
    }
}
/*------------------------------------------------------ */
//-- 批量转移商品分类页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'move')
{
    /* 权限检查 */
    admin_priv('cat_drop');
    $cat_id = !empty($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0;
    /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['move_goods']);
    $smarty->assign('action_link', array('href' => 'category.php?act=list', 'text' => $_LANG['03_category_list']));
    $smarty->assign('cat_select', cat_list(0, $cat_id, true));
    $smarty->assign('form_act',   'move_cat');
    /* 显示页面 */
    assign_query_info();
    $smarty->display('category_move.htm');
}
/*------------------------------------------------------ */
//-- 处理批量转移商品分类的处理程序
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'move_cat')
{
    /* 权限检查 */
    admin_priv('cat_drop');
    $cat_id        = !empty($_POST['cat_id'])        ? intval($_POST['cat_id'])        : 0;
    $target_cat_id = !empty($_POST['target_cat_id']) ? intval($_POST['target_cat_id']) : 0;
    /* 商品分类不允许为空 */
    if ($cat_id == 0 || $target_cat_id == 0)
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'category.php?act=move');
        sys_msg($_LANG['cat_move_empty'], 0, $link);
    }
    /* 更新商品分类 */
    $sql = "UPDATE " .$ecs->table('goods'). " SET cat_id = '$target_cat_id' ".
           "WHERE cat_id = '$cat_id'";
    if ($db->query($sql))
    {
        /* 清除缓存 */
        clear_cache_files();
        /* 提示信息 */
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'category.php?act=list');
        sys_msg($_LANG['move_cat_success'], 0, $link);
    }
}
/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit_sort_order')
{
    check_authz_json('cat_manage');
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);
    if (cat_update($id, array('sort_order' => $val)))
    {
        clear_cache_files(); // 清除缓存
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 编辑数量单位
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit_measure_unit')
{
    check_authz_json('cat_manage');
    $id = intval($_POST['id']);
    $val = json_str_iconv($_POST['val']);
    if (cat_update($id, array('measure_unit' => $val)))
    {
        clear_cache_files(); // 清除缓存
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit_grade')
{
    check_authz_json('cat_manage');
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);
    if($val > 10 || $val < 0)
    {
        /* 价格区间数超过范围 */
        make_json_error($_LANG['grade_error']);
    }
    if (cat_update($id, array('grade' => $val)))
    {
        clear_cache_files(); // 清除缓存
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 切换是否显示在导航栏
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'toggle_show_in_nav')
{
    check_authz_json('cat_manage');
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);
    if (cat_update($id, array('show_in_nav' => $val)) != false)
    {
        if($val == 1)
        {
            //显示
            $vieworder = $db->getOne("SELECT max(vieworder) FROM ". $ecs->table('nav') . " WHERE type = 'middle'");
            $vieworder += 2;
            $catname = $db->getOne("SELECT cat_name FROM ". $ecs->table('category') . " WHERE cat_id = '$id'");
            //显示在自定义导航栏中
            $_CFG['rewrite'] = 0;
            $uri = build_uri('category', array('cid'=> $id), $catname);
            $nid = $db->getOne("SELECT id FROM ". $ecs->table('nav') . " WHERE ctype = 'c' AND cid = '" . $id . "' AND type = 'middle'");
            if(empty($nid))
            {
                //不存在
                $sql = "INSERT INTO " . $ecs->table('nav') . " (name,ctype,cid,ifshow,vieworder,opennew,url,type) VALUES('" . $catname . "', 'c', '$id','1','$vieworder','0', '" . $uri . "','middle')";
            }
            else
            {
                $sql = "UPDATE " . $ecs->table('nav') . " SET ifshow = 1 WHERE ctype = 'c' AND cid = '" . $id . "' AND type = 'middle'";
            }
            $db->query($sql);
        }
        else
        {
            //去除
            $db->query("UPDATE " . $ecs->table('nav') . "SET ifshow = 0 WHERE ctype = 'c' AND cid = '" . $id . "' AND type = 'middle'");
        }
        clear_cache_files();
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'toggle_is_show')
{
    check_authz_json('cat_manage');
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);
    if (cat_update($id, array('is_show' => $val)) != false)
    {
        clear_cache_files();
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}
/** Add by Nobj */
if ($_REQUEST['act'] == 'toggle_show_cat_home')
{
    check_authz_json('cat_manage');
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);
    if (cat_update($id, array('show_cat_home' => $val)) != false)
    {
        clear_cache_files();
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 删除商品分类
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'remove')
{
    check_authz_json('cat_manage');
    /* 初始化分类ID并取得分类名称 */
    $cat_id   = intval($_GET['id']);
    $cat_name = $db->getOne('SELECT cat_name FROM ' .$ecs->table('category'). " WHERE cat_id='$cat_id'");
    /* 当前分类下是否有子分类 */
    $cat_count = $db->getOne('SELECT COUNT(*) FROM ' .$ecs->table('category'). " WHERE parent_id='$cat_id'");
    /* 当前分类下是否存在商品 */
    $goods_count = $db->getOne('SELECT COUNT(*) FROM ' .$ecs->table('goods'). " WHERE cat_id='$cat_id'");
    /* 如果不存在下级子分类和商品，则删除之 */
    if ($cat_count == 0 && $goods_count == 0)
    {
        /* 删除分类 */
        $sql = 'DELETE FROM ' .$ecs->table('category'). " WHERE cat_id = '$cat_id'";
        if ($db->query($sql))
        {
            $db->query("DELETE FROM " . $ecs->table('nav') . "WHERE ctype = 'c' AND cid = '" . $cat_id . "' AND type = 'middle'");
            clear_cache_files();
            admin_log($cat_name, 'remove', 'category');
        }
        //del slug
        del_slug($cat_id, 'category');
    }
    else
    {
        make_json_error($cat_name .' '. $_LANG['cat_isleaf']);
    }
    $url = 'category.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
    ecs_header("Location: $url\n");
    exit;
}
/*------------------------------------------------------ */
//-- PRIVATE FUNCTIONS
/*------------------------------------------------------ */
//
///**
// * 检查分类是否已经存在
// *
// * @param   string      $cat_name       分类名称
// * @param   integer     $parent_cat     上级分类
// * @param   integer     $exclude        排除的分类ID
// *
// * @return  boolean
// */
//function cat_exists($cat_name, $parent_cat, $exclude = 0)
//{
//    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('category').
//           " WHERE parent_id = '$parent_cat' AND cat_name = '$cat_name' AND cat_id<>'$exclude'";
//    return ($GLOBALS['db']->getOne($sql) > 0) ? true : false;
//}
/**
 * 获得商品分类的所有信息
 *
 * @param   integer     $cat_id     指定的分类ID
 *
 * @return  mix
 */
function get_cat_info($cat_id)
{
    $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('category'). " WHERE cat_id='$cat_id' LIMIT 1";
    return $GLOBALS['db']->getRow($sql);
}
/**
 * 添加商品分类
 *
 * @param   integer $cat_id
 * @param   array   $args
 *
 * @return  mix
 */
function cat_update($cat_id, $args)
{
    if (empty($args) || empty($cat_id))
    {
        return false;
    }
    return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('category'), $args, 'update', "cat_id='$cat_id'");
}
/**
 * 获取属性列表
 *
 * @access  public
 * @param
 *
 * @return void
 */
function get_attr_list()
{
    $sql = "SELECT a.attr_id, a.cat_id, a.attr_name ".
           " FROM " . $GLOBALS['ecs']->table('attribute'). " AS a,  ".
           $GLOBALS['ecs']->table('goods_type') . " AS c ".
           " WHERE  a.cat_id = c.cat_id AND c.enabled = 1 ".
           " ORDER BY a.cat_id , a.sort_order";
    $arr = $GLOBALS['db']->getAll($sql);
    $list = array();
    foreach ($arr as $val)
    {
        $list[$val['cat_id']][] = array($val['attr_id']=>$val['attr_name']);
    }
    return $list;
}
/**
 * 插入首页推荐扩展分类
 *
 * @access  public
 * @param   array   $recommend_type 推荐类型
 * @param   integer $cat_id     分类ID
 *
 * @return void
 */

function insert_cat_recommend($recommend_type, $cat_id)
{
    //检查分类是否为首页推荐
    if (!empty($recommend_type))
    {
        //取得之前的分类
        $recommend_res = $GLOBALS['db']->getAll("SELECT recommend_type FROM " . $GLOBALS['ecs']->table("cat_recommend") . " WHERE cat_id=" . $cat_id);
        if (empty($recommend_res))
        {
            foreach($recommend_type as $data)
            {
                $data = intval($data);
                $GLOBALS['db']->query("INSERT INTO " . $GLOBALS['ecs']->table("cat_recommend") . "(cat_id, recommend_type) VALUES ('$cat_id', '$data')");
            }
        }
        else
        {
            $old_data = array();
            foreach($recommend_res as $data)
            {
                $old_data[] = $data['recommend_type'];
            }
            $delete_array = array_diff($old_data, $recommend_type);
            if (!empty($delete_array))
            {
                $GLOBALS['db']->query("DELETE FROM " . $GLOBALS['ecs']->table("cat_recommend") . " WHERE cat_id=$cat_id AND recommend_type " . db_create_in($delete_array));
            }
            $insert_array = array_diff($recommend_type, $old_data);
            if (!empty($insert_array))
            {
                foreach($insert_array as $data)
                {
                    $data = intval($data);
                    $GLOBALS['db']->query("INSERT INTO " . $GLOBALS['ecs']->table("cat_recommend") . "(cat_id, recommend_type) VALUES ('$cat_id', '$data')");
                }
            }
        }
    }
    else
    {
        $GLOBALS['db']->query("DELETE FROM ". $GLOBALS['ecs']->table("cat_recommend") . " WHERE cat_id=" . $cat_id);
    }
}
function get_brand_list1($cat_id) {
    // Lấy tất cả các danh mục con
    $sql = "SELECT cat_id FROM " . $GLOBALS['ecs']->table('category') . " WHERE parent_id = '$cat_id'";
    $sub_categories = $GLOBALS['db']->getCol($sql);

    // Thêm danh mục cha vào mảng danh mục
    $sub_categories[] = $cat_id;

    // Chuyển mảng thành chuỗi để sử dụng trong câu truy vấn
    $cat_ids_str = implode(',', $sub_categories);

    $sql = "SELECT DISTINCT b.brand_id, b.brand_name
            FROM " . $GLOBALS['ecs']->table('brand') . " AS b
            INNER JOIN " . $GLOBALS['ecs']->table('goods') . " AS g ON b.brand_id = g.brand_id
            WHERE g.cat_id IN ($cat_ids_str)
            ORDER BY b.sort_order";
    $res = $GLOBALS['db']->getAll($sql);

    $brand_list = array();
    foreach ($res as $row) {
        $brand_list[] = array(
            'brand_id' => $row['brand_id'],
            'brand_name' => addslashes($row['brand_name'])
        );
    }

    return $brand_list;
}
function get_phone_list() {
    // Lấy tất cả bản ghi từ bảng phone_sale
    $sql = "SELECT id, name, phone FROM " . $GLOBALS['ecs']->table('phone_sale');
    $res = $GLOBALS['db']->getAll($sql);

    $phone_list = array();
    foreach ($res as $row) {
        $phone_list[] = array(
            'id' => $row['id'],
            'phone' => addslashes($row['phone']),
            'name' => addslashes($row['name'])
        );
    }

    return $phone_list;
}

function get_phone_sale_data() {
    $sql = "SELECT id, name, khu_vuc FROM " . $GLOBALS['ecs']->table('phone_sale');
    $res = $GLOBALS['db']->getAll($sql);

    return $res;
}


?>