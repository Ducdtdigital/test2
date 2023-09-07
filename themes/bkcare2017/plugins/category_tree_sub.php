<?php

function siy_category_tree_sub($atts) {
	$id = !empty($atts['id']) ? intval($atts['id']) : 0;

    $cat = siy_get_categories_tree_sub($id);
    echo '<pre>';
    var_dump($cat);
	$GLOBALS['smarty']->assign('cat_tree_sub', $cat);
	$form = (!empty($atts['form'])) ? $atts['form'] : 'library/siy_category_tree_sub.lbi';
	$val= $GLOBALS['smarty']->fetch($form);

	return $val;
}

function siy_get_categories_tree_sub($cat_id = 0) {
	$cat_arr = array();
	$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('category') . " WHERE parent_id = '$cat_id' AND is_show = 1 ";
	if ($GLOBALS['db']->getOne($sql) || $parent_id == 0) {
		$sql = 'SELECT cat_id, cat_name, parent_id ' .
			'FROM ' . $GLOBALS['ecs']->table('category') .
			"WHERE parent_id = '$parent_id' AND is_show = 1 ORDER BY sort_order ASC, cat_id ASC";
		$res = $GLOBALS['db']->getAll($sql);
		foreach ($res AS $row) {
			$cat_arr[$row['cat_id']]['id']   = $row['cat_id'];
			$cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
			$cat_arr[$row['cat_id']]['url']  = build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']);
			if (isset($row['cat_id']) != NULL) {
				$cat_arr[$row['cat_id']]['cat_id'] = siy_get_child_tree_sub($row['cat_id'], $cat_id);
			}
			$cat_arr[$row['cat_id']]['current'] = ($row['cat_id'] == $cat_id) ? 1 : 0;
			$cat_arr[$row['cat_id']]['parent'] = ($row['cat_id'] == $parent_id) ? 1 : 0;
		}
	}
	return $cat_arr;
}

function siy_get_child_tree_sub($tree_id = 0, $cat_id = 0) {
	$three_arr = array();
	if ($cat_id > 0) {
		$sql = 'SELECT parent_id FROM ' . $GLOBALS['ecs']->table('category') . " WHERE cat_id = '$cat_id'";
		$parent_id = $GLOBALS['db']->getOne($sql);
	} else {
		$parent_id = 0;
	}
	$sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('category') . " WHERE parent_id = '$tree_id' AND is_show = 1 ";
	if ($GLOBALS['db']->getOne($sql) || $tree_id == 0) {
		$child_sql = 'SELECT cat_id, cat_name, parent_id, is_show ' .
			'FROM ' . $GLOBALS['ecs']->table('category') .
			"WHERE parent_id = '$tree_id' AND is_show = 1 ORDER BY sort_order ASC, cat_id ASC";
		$res = $GLOBALS['db']->getAll($child_sql);
		foreach ($res AS $row) {
			$three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			$three_arr[$row['cat_id']]['name'] = $row['cat_name'];
			$three_arr[$row['cat_id']]['url']  = build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']);
			if (isset($row['cat_id']) != NULL) {
				$three_arr[$row['cat_id']]['cat_id'] = siy_get_child_tree_sub($row['cat_id'], $cat_id);
			}
			$three_arr[$row['cat_id']]['current'] = ($row['cat_id'] == $cat_id) ? 1 : 0;
			$three_arr[$row['cat_id']]['parent'] = ($row['cat_id'] == $parent_id) ? 1 : 0;
		}
	}
	return $three_arr;
}

?>
