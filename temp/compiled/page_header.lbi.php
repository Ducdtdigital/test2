
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMHG5TK8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<header>
    <div class="htop">
        <div class="wrap-main">
            <a class="logo" title="THB Việt Nam - Cung Cấp Dụng Cụ Điện, Thiết Bị Đo Lường Chất Lượng Cao" href="./"><?php if ($this->_var['pname'] == 'index'): ?><h1><img src="<?php echo $this->_var['option']['static_path']; ?>static/img/logo.png" alt="<?php echo $this->_var['shop_name']; ?>"></h1><?php else: ?><img src="<?php echo $this->_var['option']['static_path']; ?>static/img/logo.png" alt="<?php echo $this->_var['shop_name']; ?>"><?php endif; ?></a>
            <div class="search-thb">
            <?php if ($this->_var['pname'] == 'article' || $this->_var['pname'] == 'article_cat' || $this->_var['pname'] == 'article_cat_index'): ?>
            <form id="search-site" action="<?php echo $this->_var['option']['static_path']; ?>tin-tuc" method="get" autocomplete="off">
                <input class="topinput" id="search-keyword" name="keywords" type="text" tabindex="1" value="<?php echo htmlspecialchars($this->_var['search_keywords']); ?>" placeholder="Tìm kiếm tin tức..." autocomplete="off"  maxlength="50">
                <button class="btntop" type="submit"><i class="icontgdd-topsearch"></i></button>
                <div class="search-suggest"></div>
                
            </form>
            <div class="search-sug"><?php 
$k = array (
  'name' => 'nav',
  'type' => 'top',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?></div>
            </div>
            <?php else: ?>
            <form id="search-site" action="<?php echo $this->_var['option']['static_path']; ?>tim-kiem" method="get" autocomplete="off">
                <input class="topinput" id="search-keyword" name="keywords" type="text" tabindex="1" value="<?php echo htmlspecialchars($this->_var['search_keywords']); ?>" placeholder="Tìm kiếm sản phẩm bạn cần..." autocomplete="off"  maxlength="50">
                <button class="btntop" type="submit"><i class="icontgdd-topsearch"></i></button>
                <div class="search-suggest"></div>
                
            </form>
            <div class="search-sug"><?php 
$k = array (
  'name' => 'nav',
  'type' => 'top',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?></div>
            </div>
            <?php endif; ?>
            <span class="htop-r">
                <span><strong>0904 810 817</strong><p>Hà Nội</p></span>
                <span><strong>0979 244 335</strong><p>Hồ Chí Minh</p></span>
            </span>
             <div id="cart" class="cart_space">
                <p class="label">
                <a href="gio-hang"> Giỏ hàng <i class="icontgdd-cartstick"></i><?php 
$k = array (
  'name' => 'cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?></a>
                </p>
            </div>
        </div>
    </div>
    <div class="hnav">
         <div class="wrap-main">
           <div class="all_cat_wrapper">
                <div class="all_cat">
                    <div class="line"><i></i><i></i><i></i></div>
                    <span> Danh mục sản phẩm</span>
                </div>
                    <div class="all_category">
                    <?php echo $this->fetch('library/category_tree.lbi'); ?>
                    </div>
            </div>
            <?php 
$k = array (
  'name' => 'nav',
  'type' => 'middle',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>
        </div>
    </div>
    <div class="clr"></div>
</header>
