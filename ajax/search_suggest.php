<?php
define('IN_ECS', true);
define('IS_AJAX', true);
require __DIR__. '/../includes/init.php';
include_once(ROOT_PATH.'includes/cls_json.php');
$json = new JSON;
/* 3 tham so cho tim kiem so sanh */
$act    = isset($_GET['act']) ? 'compare' : '';
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$gid    = isset($_GET['gid']) ? intval($_GET['gid']) : 0;
//Xác định xem có một yêu cầu ajax
$keyword    = !empty($_POST['keywords'])   ? trim($_POST['keywords'])     : '';
$keyword    = filter_var($keyword, FILTER_SANITIZE_STRING);

/* xử lí key giong ben search */
$keywords  = '';
if (!empty($keyword))
{
    $arr = array();
    if (stristr($keyword, ' AND ') !== false)
    {
        $arr        = explode('AND', $keyword);
        $operator   = " AND ";
    }
    elseif (stristr($keyword, ' OR ') !== false)
    {
        $arr        = explode('OR', $keyword);
        $operator   = " OR ";
    }
    elseif (stristr($keyword, ' + ') !== false)
    {
        $arr        = explode('+', $keyword);
        $operator   = " OR ";
    }
    else
    {
        $arr        = explode(' ', $keyword);
        $operator   = " AND ";
    }

    $keywords = 'AND (';
        foreach ($arr AS $key => $val)
        {
            if ($key > 0 && $key < count($arr) && count($arr) > 1)
            {
                $keywords .= $operator;
            }
            $val        = mysql_like_quote(trim($val));
            $keywords  .= "(goods_name LIKE '%$val%')";
        }
    $keywords .= ')';
}
$where_compare = ($act == 'compare' && $cat_id > 0 && $gid > 0) ? 'AND '. get_cat_children($cat_id). " AND goods_id <> $gid " : '';
if($keyword!="")
{
    $sql_count = "SELECT COUNT('goods_id') ".
            " FROM " .$ecs->table('goods'). " WHERE is_delete = 0 AND is_on_sale = 1 $keywords";

   $sql = "SELECT goods_id, goods_name, shop_price, promote_price, promote_start_date, promote_end_date, goods_thumb ".
            " FROM " .$ecs->table('goods'). " WHERE shop_price >= 0 $where_compare AND is_delete = 0 AND is_on_sale = 1  $keywords LIMIT 5";

  $res = $db->getAll($sql);
  $total = $db->getOne($sql_count);
}
if(!empty($res)){
  $content = '<ul>';
  foreach ($res as $idx => $row) {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $goods[$idx]['promote_price'] = $promote_price > 0 ? $promote_price : '';
        }
        else
        {
            $goods[$idx]['promote_price'] = '';
        }
        $row['goods_thumb'] = $base_cdn1.get_image_path($row['goods_id'], $row['goods_thumb'], true);

        $price = !empty($goods[$idx]['promote_price']) ? $goods[$idx]['promote_price'] : $row['shop_price'];

        if($act =='compare'){
            $row['url'] = build_uri('goods', array('gid' => $gid, 'cpid'=>$row['goods_id'], 'compare'=>1));
        }else{
            $row['url'] = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
        }

        $content .= '<li><a href="'.$row['url'].'"><img src="'.$row['goods_thumb'].'" alt="'.$row['goods_name'].'"><h3>'.$row['goods_name'].'</h3>';
        if($price > 0){
            $content .='<span class="price">'.price_format($price).'</span>';
         }
        $content .='</a></li>';
    }
    $content .= '</ul>';
   if($act ==''){
        $content .= '<p class="viewall"><a href="tim-kiem?keywords='.urlencode($keyword).'">Xem Tất cả '.$total.' kết quả với từ khóa <strong>'.$keyword.'</strong></a></p>';
   }
  $error = 0;
}else{
  $content = '<p class="nosearch">Không tìm thấy kết quả nào phù hợp với từ khoá <strong>'.$keyword.'</strong></p>';
  $error = 1;
}
$result   = array('error' => $error, 'content' => $content, 'sql'=> $sql, 'key'=> $keyword);
die($json->encode($result));

?>