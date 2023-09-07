<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* 检查权限 */
admin_priv('sitemap');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    /*------------------------------------------------------ */
    //-- 设置更新频率
    /*------------------------------------------------------ */
    assign_query_info();
    $config = unserialize($_CFG['sitemap']);
    $smarty->assign('config',           $config);
    $smarty->assign('ur_here',          $_LANG['sitemap']);
    $smarty->assign('arr_changefreq',   array(1,0.9,0.8,0.7,0.6,0.5,0.4,0.3,0.2,0.1));
    $smarty->display('sitemap.htm');
}
else
{
    /*------------------------------------------------------ */
    //-- 生成站点地图
    /*------------------------------------------------------ */
    include_once('includes/cls_phpzip.php');
    include_once('includes/cls_google_sitemap.php');

    $domain = $ecs->url();
    $today  = local_date('Y-m-d');
    global $sitemap_files;
    $sitemap_files = [];

    $config = array(
        'homepage_changefreq' => $_POST['homepage_changefreq'],
        'homepage_priority' => $_POST['homepage_priority'],
        'product_category_changefreq' => $_POST['product_category_changefreq'],
        'product_category_priority' => $_POST['product_category_priority'],
        'article_category_changefreq' => $_POST['article_category_changefreq'],
        'article_category_priority' => $_POST['article_category_priority'],
        'brand_changefreq' => $_POST['brand_changefreq'],
        'brand_priority' => $_POST['brand_priority'],
        'product_changefreq' => $_POST['product_changefreq'],
        'product_priority' => $_POST['product_priority'],
        'article_changefreq' => $_POST['article_changefreq'],
        'article_priority' => $_POST['article_priority'],
    );

    $config = serialize($config);

    $db->query("UPDATE " .$ecs->table('shop_config'). " SET VALUE='$config' WHERE code='sitemap'");

    /* 商品分类 */
    $product_category_sitemap_items = [];
    $sql = "SELECT cat_id,cat_name FROM " .$ecs->table('category'). " WHERE is_show=1 ORDER BY parent_id";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $product_category_sitemap_items[] = [
            'loc' => $domain . build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']),
            'lastmod' => $today
        ];
    }
    /* Thương hiệu trong từng danh mục */
    $brand_in_category_sitemap_items = [];
    $sql = "SELECT b.brand_id, b.brand_name, c.cat_id, c.cat_name FROM " .$ecs->table('brand'). " AS b JOIN " .$ecs->table('goods'). " AS g ON b.brand_id = g.brand_id JOIN " .$ecs->table('category'). " AS c ON g.cat_id = c.cat_id WHERE b.is_show = 1 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 GROUP BY b.brand_id, c.cat_id HAVING COUNT(g.goods_id) > 0";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $brand_in_category_sitemap_items[] = [
            'loc' => $domain . build_uri('category', array('cid' => $row['cat_id'], 'bid' => $row['brand_id']), $row['cat_name']),
            'lastmod' => $today
        ];
    }
        /* 品牌 */
    $brand_sitemap_items = [];
    $sql = "SELECT brand_id, brand_name FROM " . $ecs->table('brand');
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $brand_sitemap_items[] = [
            'loc' => $domain . build_uri('brand', array('bid' => $row['brand_id']), $row['brand_name']),
            'lastmod' => $today
        ];
    }

    /* 文章分类 */
    $article_category_sitemap_items = [];
    $sql = "SELECT cat_id,cat_name FROM " .$ecs->table('article_cat'). " WHERE cat_type=1";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $article_category_sitemap_items[] = [
            'loc' => $domain . build_uri('article_cat', array('acid' => $row['cat_id']), $row['cat_name']),
            'lastmod' => $today
        ];
    }

    /* 商品 */
    $goods_sitemap_items = [];
    $sql = "SELECT goods_id, goods_name FROM " .$ecs->table('goods'). " WHERE is_on_sale = 1";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $goods_sitemap_items[] = [
            'loc' => $domain . build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']),
            'lastmod' => $today
        ];
    }

    /* 文章 */
    $article_sitemap_items = [];
    $sql = "SELECT article_id,title,file_url,open_type FROM " .$ecs->table('article'). " WHERE is_open=1 AND cat_id > 3";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $article_url = $row['open_type'] != 1 ? build_uri('article', array('aid' => $row['article_id']), $row['title']) : trim($row['file_url']);
        $article_sitemap_items[] = [
            'loc' => $domain . $article_url,
            'lastmod' => $today
        ];
    }

    clear_cache_files();    // 清除缓存

    create_sitemap($product_category_sitemap_items, 'sitemap-catgoods', $_POST['product_category_changefreq'], $_POST['product_category_priority']);
    create_sitemap($brand_sitemap_items, 'sitemap-brand', $_POST['brand_changefreq'], $_POST['brand_priority']);
    create_sitemap($article_category_sitemap_items, 'sitemap-catarticle', $_POST['article_category_changefreq'], $_POST['article_category_priority']);
    create_sitemap($goods_sitemap_items, 'sitemap-goods', $_POST['product_changefreq'], $_POST['product_priority']);
    create_sitemap($article_sitemap_items, 'sitemap-article', $_POST['article_changefreq'], $_POST['article_priority']);
    create_sitemap($brand_in_category_sitemap_items, 'sitemap-cat-brand', $_POST['brand_changefreq'], $_POST['brand_priority']);
    create_sitemap_index($sitemap_files);
    // Thông báo thành công khi tất cả các tệp sitemap được tạo
        sys_msg(sprintf($_LANG['generate_success'], $ecs->url() . 'sitemap.xml'));
}

// Hàm tạo sitemap riêng biệt cho từng loại với giới hạn phần tử
function create_sitemap($items, $filename, $changefreq, $priority, $limit = 1000) {
    $counter = 1;
    $domain = $GLOBALS['ecs']->url();
    $today  = local_date('Y-m-d');
    $sitemap_file = "../{$filename}.xml";

    global $sitemap_files;
    $num_sitemaps = ceil(count($items) / $limit);
    for ($i = 1; $i <= $num_sitemaps; $i++) {
        $suffix = ($i > 1) ? "-{$i}" : '';
        $sitemap_files[$filename . $suffix . '.xml'] = $today;
    }

    while (count($items) > 0) {
        $sitemap = new google_sitemap();
        for ($i = 0; $i < $limit && !empty($items); ++$i) {
            $item = array_shift($items);
            $smi = new google_sitemap_item($item['loc'], $item['lastmod'], $changefreq, $priority);
            $sitemap->add_item($smi);
        }
        if ($counter > 1) {
            $sitemap_file = "../{$filename}-{$counter}.xml";
        }
        $sitemap->build($sitemap_file);
        $counter++;
    }
}

function create_sitemap_index($sitemap_files) {
    $domain = $GLOBALS['ecs']->url();
    $sitemap_index_file = '../sitemap.xml';

    $xml = new SimpleXMLElement('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');

    foreach ($sitemap_files as $filename => $lastmod) {
        $sitemap = $xml->addChild('sitemap');
        $sitemap->addChild('loc', $domain . $filename);
        $sitemap->addChild('lastmod', $lastmod);
    }

    $xml->asXML($sitemap_index_file);
}

?>