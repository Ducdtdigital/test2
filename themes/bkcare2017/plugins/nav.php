<?php
function siy_nav($atts) {
	$ctype = '';
	$catlist = array();
	$type = (!empty($atts['type']) && in_array($atts['type'], array('top', 'middle', 'bottom', 'mobile', 'menucat'))) ? $atts['type'] : 'middle';
	$sql = 'SELECT * FROM '. $GLOBALS['ecs']->table('nav') . '
		WHERE ifshow = "1" AND type = "'.$type.'" ORDER BY vieworder';
	$res = $GLOBALS['db']->query($sql);

	$active = 0;
	$navlist = array();
	while ($row = $GLOBALS['db']->fetchRow($res)) {
		$navlist[] = array(
			'name'      =>  $row['name'],
			'opennew'   =>  $row['opennew'],
			'url'       =>  $row['url'],
			'ctype'     =>  $row['ctype'],
            'id'        =>  $row['id'],
			'cid'       =>  $row['cid'],
			'order'     =>  $row['vieworder'],
			'cat_id'     =>  $row['cat_id'],
            'class'     =>  $row['class'],
            'title'     =>  $row['title'],
            'active'    =>  $row['url'] == $atts['active'] ? 1 : 0
		);
	}


	$nav = array();
	foreach($navlist as $k=>$v) {
		if(strlen(strtr($v['order'], '-', '')) == 1) {
			$nav[] = array(
				'name'      =>  $v['name'],
				'opennew'   =>  $v['opennew'],
				'url'       =>  $v['url'],
				'ctype'     =>  $v['ctype'],
                'id'        =>  $v['id'],
				'cid'       =>  $v['cid'],
				'order'     =>  $v['order'],
				'cat_id'     =>  $v['cat_id'],
                'class'     =>  $v['class'],
                'title'     =>  $v['title'],
				'active'    =>  $v['active'],
				'children'  =>  _siy_nav_children($navlist, $v['order']),
			);
		}
	}
	$GLOBALS['smarty']->assign('nav', $nav);
	    switch ($type) {
        case 'top':
            $form = 'library/siy_nav_top.lbi';
            break;
        case 'middle':
            $form = 'library/siy_nav.lbi';
            break;
        case 'bottom':
            $form = 'library/siy_nav_bottom.lbi';
            break;
        case 'mobile':
            $form = 'library/siy_nav_mobile.lbi';
            break;
        case 'menucat':
            $form = 'library/siy_nav_menucat.lbi';
            break;
        default:
            $form = (!empty($atts['form'])) ? $atts['form'] : 'library/siy_nav.lbi';
            break;
    }

    $val= $GLOBALS['smarty']->fetch($form);
    return $val;
	$val= $GLOBALS['smarty']->fetch($form);
	return $val;
}

function _siy_nav_children($navlist, $order) {
	foreach($navlist as $k=>$v) {
		if(strlen(strtr($v['order'], '-', '')) == 2 and (substr($v['order'], 0, 1) == $order or substr($v['order'], 0, 2) == $order)) {
			$children[] = array(
				'name'      =>  $v['name'],
				'opennew'   =>  $v['opennew'],
				'url'       =>  $v['url'],
				'ctype'     =>  $v['ctype'],
                'id'        =>  $v['id'],
				'cid'       =>  $v['cid'],
				'order'     =>  $v['order'],
				'active'    =>  $v['active'],
                'class'     =>  $v['class'],
                'title'     =>  $v['title']
			);
		}
	}
	return $children;
}
