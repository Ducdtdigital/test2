<?php echo $this->fetch('library/html_header.lbi'); ?>
 <link rel="canonical" href="<?php echo $this->_var['canonical']; ?>" />
<?php echo $this->_var['cat_faq']; ?>
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "image": "<?php echo $this->_var['cat_icon']; ?>",
    "description": "<?php echo $this->_var['description']; ?>",
    <?php if ($this->_var['category'] == $this->_var['sub']['id'] && $this->_var['sub']['cat_id']): ?>
    "isSimilarTo": [
        <?php $_from = $this->_var['catsubs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');$this->_foreach['catsub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['catsub']['total'] > 0):
    foreach ($_from AS $this->_var['sub']):
        $this->_foreach['catsub']['iteration']++;
?>
        
       <?php $_from = $this->_var['sub']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');$this->_foreach['child_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child_cat']['total'] > 0):
    foreach ($_from AS $this->_var['sub']):
        $this->_foreach['child_cat']['iteration']++;
?>
        {
          "@type": "Thing",
          "name": "<?php echo htmlspecialchars($this->_var['sub']['name']); ?>",
          "url": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['sub']['url']; ?>",
          "@id": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['sub']['url']; ?>#category",
          "image": "<?php echo $this->_var['sub']['icon']; ?>",
          "mainEntityOfPage": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['sub']['url']; ?>",
          "description": "<?php echo $this->_var['sub']['desc']; ?>"
        }
        <?php if (! ($this->_foreach['child_cat']['iteration'] == $this->_foreach['child_cat']['total'])): ?>,<?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      ],
    <?php endif; ?>
    "name": "<?php echo $this->_var['cat_name']; ?>",
    "url": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['active_url']; ?>",
    "@id": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['active_url']; ?>#category",
    "model": "<?php echo $this->_var['cat_name']; ?>",
    "sku": "<?php echo $this->_var['cat_name']; ?>",
    "mpn": "<?php echo $this->_var['cat_name']; ?>",
    "category": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['active_url']; ?>",
    "mainEntityOfPage": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['active_url']; ?>",
    "brand": {
      "@id": "<?php echo $this->_var['option']['static_path']; ?>#organization"
    },
    "logo": {
      "@context": "https://schema.org",
      "@type": "ImageObject",
      "url": "<?php echo $this->_var['cat_icon']; ?>",
      "image": "<?php echo $this->_var['cat_icon']; ?>",
      "name": "<?php echo $this->_var['cat_name']; ?>",
      "description": "<?php echo $this->_var['description']; ?>",
      "representativeOfPage": "true",
      "uploadDate": "10098223232323-060606-2121 0303:0606:3232.32"
    },
    "offers": {
      "@type": "AggregateOffer",
      "availability": "https://schema.org/InStock",
      "priceCurrency": "VND",
      "itemCondition": "https://schema.org/NewCondition",
      "priceValidUntil": "February 15, 2025",
      "highPrice": "17500000",
      "lowPrice": "103000",
      "offerCount": "675",
      "url": "<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['active_url']; ?>"
    },
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingCount": 30,
                "ratingValue": 4.7
            },
            "audience": [
                {
                    "@context": "https://schema.org",
                    "@type": "Audience",
                    "audienceType": "https://vi.wikipedia.org/wiki/Th%E1%BB%A3_m%E1%BB%99c",
                    "url": "https://vi.wikipedia.org/wiki/Th%E1%BB%A3_m%E1%BB%99c",
                    "name": "Thợ Mộc",
                    "geographicArea": {
                    "@type": "AdministrativeArea",
                    "url": "https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam",
                    "@id": "kg:/m/01crd5",
                    "name": "Việt Nam"
                }
                },
                    {
                    "@context": "https://schema.org",
                    "@type": "Audience",
                    "audienceType": ["https://vi.wikipedia.org/wiki/H%E1%BB%99_gia_%C4%91%C3%ACnh"],
                    "url": "https://vi.wikipedia.org/wiki/H%E1%BB%99_gia_%C4%91%C3%ACnh",
                    "name": "Hộ Gia Đình",
                    "geographicArea": {
                    "@type": "AdministrativeArea",
                    "url": "https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam",
                    "@id": "kg:/m/01crd5",
                    "name": "Việt Nam"
                    }
                }
            ],
            "potentialAction": 
                {
                    "instrument": ["phone", "laptop", "pc", "tablet"],
                    "@type": "BuyAction",
                    "mainEntityOfPage": "<?php echo $this->_var['option']['static_path']; ?>gio-hang",
                    "name": "giỏ hàng",
                    "url": "<?php echo $this->_var['option']['static_path']; ?>gio-hang",
                    "target": "<?php echo $this->_var['option']['static_path']; ?>gio-hang",
                    "description": "Chọn mua hàng",
                    "location": {
                        "@type": "City",
                        "sameAs": ["https://en.wikipedia.org/wiki/Vietnam", "https://www.wikidata.org/wiki/Q881"],
                        "name": "Việt Nam",
                        "url": "https://vi.wikipedia.org/wiki/Vi%E1%BB%87t_Nam",
                        "@id": "kg:/m/01crd5",
                        "mainEntityOfPage":"https://www.google.com/search?q=việt+nam&amp;kponly=&amp;kgmid=/m/01crd5","hasMap": "https://goo.gl/maps/5HUQFbzHSxRuAWVBA"
                    }
  }
}
</script>
<?php echo $this->smarty_insert_css(array('files'=>'owl-carousel/owl.carousel.2.3.4.min.css,css/style.css,css/category2.css')); ?>

</head>
<body id="page_<?php echo $this->_var['pname']; ?>" class="animated fadeIn">
<?php echo $this->fetch('library/page_header.lbi'); ?>
<section>
    <?php echo $this->fetch('library/ur_here.lbi'); ?>
    <div class="col_sub">
         <?php echo $this->fetch('library/cat_filter_tgdd.lbi'); ?>
          
<?php echo $this->fetch('library/history.lbi'); ?>

    </div>
    <div class="col_main">
         <?php if ($this->_var['catsubs']): ?>
          <div class="cat-container">
            <div class="cat_cap2">
              <?php $_from = $this->_var['catsubs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');$this->_foreach['catsub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['catsub']['total'] > 0):
    foreach ($_from AS $this->_var['sub']):
        $this->_foreach['catsub']['iteration']++;
?>
                <?php if ($this->_var['category'] == $this->_var['sub']['id'] && $this->_var['sub']['cat_id']): ?>
                  <?php $_from = $this->_var['sub']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['child_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child_cat']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['child_cat']['iteration']++;
?>
                    <li>
                      <a href="<?php echo $this->_var['child']['url']; ?>">
                        <img src="<?php echo $this->_var['child']['icon']; ?>" alt="<?php echo htmlspecialchars($this->_var['child']['name']); ?>">
                      </a>
                      <a href="<?php echo $this->_var['child']['url']; ?>">
                        <span><?php echo $this->_var['child']['name']; ?></span>
                      </a>
                    </li>
                    <?php if ($this->_foreach['child_cat']['iteration'] == 9): ?>
                      <li class="toggle-more-li">
                        <span class="toggle-more">Xem thêm</span>
                      </li>
                    <?php endif; ?>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
              <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
          </div>
        <?php endif; ?>

         

<?php if ($this->_var['best_goods']): ?>
<?php if ($this->_var['cat_rec_sign'] != 1): ?>
<div class="xm-plain-box recommend_goods best-goods">
    <div class="box-hd">
        <h2 class="titlebest"><?php if ($this->_var['pname'] == 'category'): ?><?php echo $this->_var['cat_name']; ?> <?php endif; ?>nổi bật
            <span class="sub-title-after"></span>
        </h2>
        
    </div>
    <div class="box-bd goods_list">
             <ul class="cate owl-carousel owl-theme" id="show_best">
            <?php endif; ?>
                <?php $_from = $this->_var['best_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['best_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['best_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['best_goods']['iteration']++;
?>
                    <li>
                        <a href="<?php echo $this->_var['goods']['url']; ?>" >
                            <?php if ($this->_var['goods']['thumb_url']): ?>
                                <img src="<?php echo $this->_var['goods']['thumb_url']; ?>" width="388" height="180" alt="<?php echo htmlspecialchars($this->_var['goods']['name']); ?>">
                            <?php else: ?>
                                <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['thumb']; ?>" width="388" height="180" alt="<?php echo htmlspecialchars($this->_var['goods']['name']); ?>">
                            <?php endif; ?>
                            <?php if ($this->_var['goods']['is_new'] == 1 && $this->_var['goods']['is_hot'] == 0): ?><label class="new">Mới</label><?php endif; ?>
                    <?php if ($this->_var['goods']['is_new'] == 0 && $this->_var['goods']['is_hot'] == 1): ?><label class="shockprice">Hot</label><?php endif; ?>
                    <?php if ($this->_var['goods']['is_best'] == 1): ?><label class="shockprice">Bán chạy</label><?php endif; ?>
                            <h3><?php echo $this->_var['goods']['name']; ?></h3>
                            <?php if ($this->_var['goods']['price'] == 0): ?>
            <strong>Giá liên hệ</strong>
            <?php else: ?>
                            <strong><?php if ($this->_var['goods']['promote_price']): ?><?php echo $this->_var['goods']['promote_price']; ?><?php else: ?><?php echo $this->_var['goods']['shop_price']; ?><?php endif; ?></strong>
                            <?php endif; ?>
                            
                             <div class="promotion"><?php echo $this->_var['goods']['seller_note']; ?></div>
                        </a>
                    </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php if ($this->_var['cat_rec_sign'] != 1): ?>
             </ul>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
        <div class="category_name">
                <div class="sortby">
                    Sắp xếp theo:
                    <a href="<?php 
$k = array (
  'name' => 'build_uri',
  'app' => 'category',
  'cid' => $this->_var['category'],
  'bid' => $this->_var['brand_id'],
  'price_min' => $this->_var['price_min'],
  'price_max' => $this->_var['price_max'],
  'filter_attr' => $this->_var['filter_attr'],
  'page' => $this->_var['pager']['page'],
  'sort' => 'is_best',
  'order' => 'DESC',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>"><span><?php if ($this->_var['pager']['sort'] == 'is_best'): ?>✓<?php endif; ?></span> Sản phẩm bán chạy</a>
                
                    <a href="<?php 
$k = array (
  'name' => 'build_uri',
  'app' => 'category',
  'cid' => $this->_var['category'],
  'bid' => $this->_var['brand_id'],
  'price_min' => $this->_var['price_min'],
  'price_max' => $this->_var['price_max'],
  'filter_attr' => $this->_var['filter_attr'],
  'page' => $this->_var['pager']['page'],
  'sort' => 'is_new',
  'order' => 'DESC',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>"> <span>
                       <?php if ($this->_var['pager']['sort'] == 'is_new'): ?>✓<?php endif; ?></span> Mới nhất</a>
                    <a href="<?php 
$k = array (
  'name' => 'build_uri',
  'app' => 'category',
  'cid' => $this->_var['category'],
  'bid' => $this->_var['brand_id'],
  'price_min' => $this->_var['price_min'],
  'price_max' => $this->_var['price_max'],
  'filter_attr' => $this->_var['filter_attr'],
  'page' => $this->_var['pager']['page'],
  'sort' => 'shop_price',
  'order' => 'ASC',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>"> <span>
                        <?php if ($this->_var['pager']['sort'] == 'shop_price' && $this->_var['pager']['order'] == 'ASC'): ?>✓<?php endif; ?></span> Giá từ thấp đến cao</a>
                    <a href="<?php 
$k = array (
  'name' => 'build_uri',
  'app' => 'category',
  'cid' => $this->_var['category'],
  'bid' => $this->_var['brand_id'],
  'price_min' => $this->_var['price_min'],
  'price_max' => $this->_var['price_max'],
  'filter_attr' => $this->_var['filter_attr'],
  'page' => $this->_var['pager']['page'],
  'sort' => 'shop_price',
  'order' => 'DESC',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>"><span><?php if ($this->_var['pager']['sort'] == 'shop_price' && $this->_var['pager']['order'] == 'DESC'): ?>✓<?php endif; ?></span> Giá từ cao đến thấp</a>
                </div>


        </div>
        <?php echo $this->fetch('library/goods_list2.lbi'); ?>
        <?php if ($this->_var['description1']): ?>
         <div class="clr"></div>
        <article id="description">
                <div class="article_content"><?php echo $this->_var['description1']; ?></div>
                <div class="view-more hidden">
                <p id="btn-viewmore"><span id="view1">Đọc thêm </span><span id="view2" class="hidden">Thu gọn </span> </p>
                </div>
        </article>
        <?php endif; ?>
         

    </div>

    <div class="clr"></div>
     


</section>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<?php echo $this->smarty_insert_js(array('name'=>'category.Desktop.min.js','no_minify'=>'js/jquery.min.1.8.3.js,js/plugins.js,js/lang.vi_vn.js,owl-carousel/owl.carousel.2.3.4.min.js','minify'=>'js/global.js,js/init.js')); ?>
<script>
    var base_path = '<?php echo $this->_var['option']['static_path']; ?>';
    $(document).ready(function() {

        if($("#owl-cate").length > 0){
            $("#owl-cate").owlCarousel({items: 3,slideBy: 1,margin:10,lazyLoad:!0,slideSpeed : 2000,nav:!0,dots:!1,loop:!0,responsiveRefreshRate : 200,navText: ['‹','›'],});
        }
    });

    /** view more */
    var el = $('#description .article_content');
    var h = el.height();

    if(h>300){
        el.addClass('short_view');
        $('#description .view-more').removeClass('hidden');
    }
    $('#btn-viewmore').click(function(e){
        el.toggleClass('short_view');
        $('#view1').toggleClass('hidden');
        $('#view2').toggleClass('hidden');
        e.preventDefault();
    });
    /** end view more */

    
$(document).ready(function() {
  // Hide the remaining image rows
  $(".cat_cap2 li:not(.toggle-more-li):nth-child(n+11)").hide();

  // Handle event when clicking on the span element
  $(".toggle-more").on("click", function() {
    $(".cat_cap2 li:not(.toggle-more-li):nth-child(n+11)").slideToggle();
    $(this).text(function(i, text){
      return text === "Xem thêm" ? "Thu gọn" : "Xem thêm";
    });
  });
});

$(document).ready(function() {
    $('.dropdown-icon').click(function(e) {
        e.stopPropagation();
        $(this).parent().siblings('ul').slideToggle();
        $(this).text($(this).text() === '▼' ? '▲' : '▼');
    });

    $('.menucat-list a').click(function(e) {
        e.stopPropagation();
    });

    // Automatically open submenu if the submenu item is active
    if ($('.fsub .active_sub').length) {
        $('.fsub .active_sub').parents('li').addClass('active_cat');
        $('.fsub .active_sub').parents('ul').show();
    }

    // Automatically open submenu if the menu item is active
    if ($('.fsub .active_cat').length) {
        $('.fsub .active_cat').find('ul').show();
        $('.fsub .active_cat').find('.dropdown-icon').text('▲');
    }
});






</script>
<?php echo $this->fetch('library/html_desktopmobile.lbi'); ?>
</body>
</html>
