<?php echo $this->fetch('library/html_header.lbi'); ?>
<?php echo $this->smarty_insert_css(array('files'=>'owl-carousel/owl.carousel.2.3.4.min.css,css/style.css,css/article.css')); ?>
  <script type="application/ld+json">
    {
      "@context" : "http://schema.org",
      "@type" : "Article",
      "name" : "<?php echo htmlspecialchars($this->_var['article']['title_display']); ?>",
      "author" : {
        "@type" : "Person",
        "name" : "<?php echo $this->_var['article']['author']; ?>"
      },
      "datePublished" : "<?php echo $this->_var['article']['date']; ?>"
    }
    </script>
<style type="text/css">
    .star-rating-on a, .star-rating-hover a{background-position: 0px 0px !important}
</style>
</head>
<body id="page_<?php echo $this->_var['pname']; ?>" class="animated fadeIn">

<section>
    <div class="article_category clearfix">
    	<?php echo $this->fetch('library/article_category_tree.lbi'); ?>
    </div>
	<?php echo $this->_var['render']['before_main']; ?>
	

   <article>
        <h1 class="titledetail"><?php echo htmlspecialchars($this->_var['article']['title_display']); ?></h1>
        <div class="userdetail">
            <a><?php echo $this->_var['article']['author']; ?></a>
            <span><?php echo $this->_var['article']['add_time']; ?></span>
            <span><?php echo $this->_var['article']['viewed']; ?> lượt xem</span>
        </div>
        <div id="share-buttons">
                
                <a rel="nofollow" href="http://www.facebook.com/sharer.php?u=<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['article']['url']; ?>" target="_blank">
                    <img src="https://simplesharebuttons.com/images/somacro/facebook.png" alt="Facebook" />
                </a>
                
                <a rel="nofollow" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['article']['url']; ?>" target="_blank">
                    <img src="https://simplesharebuttons.com/images/somacro/linkedin.png" alt="LinkedIn" />
                </a>
                
                <a rel="nofollow" href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
                    <img src="https://simplesharebuttons.com/images/somacro/pinterest.png" alt="Pinterest" />
                </a>
                
                <a rel="nofollow" href="https://twitter.com/share?url=<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['article']['url']; ?>" target="_blank">
                    <img src="https://simplesharebuttons.com/images/somacro/twitter.png" alt="Twitter" />
                </a>
            </div>
			<button class="titletoc" onclick="myFunction()">Nội dung chính</button>
			<i id="up" ></i>
			<div id="myDIV">
         <ul id="toc"></ul></div>
            <?php if ($this->_var['article']['content']): ?>
            <div class="article_content"><?php echo $this->_var['article']['content']; ?></div>
            
            <?php else: ?>
            <p class="empty"><?php echo $this->_var['lang']['content_empty']; ?></p>
            <?php endif; ?>
        
<?php echo $this->fetch('library/goods_related.lbi'); ?>

        <div class="likewied">
            <div class="fb-like" data-href="<?php echo $this->_var['option']['static_path']; ?><?php echo $this->_var['article']['url']; ?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
            <span class="total_view"><?php echo $this->_var['article']['viewed']; ?> lượt xem</span><span class="text-like">, Like và chia sẻ nếu thấy thích nhé !!</span>
        </div>
    </article>
    

    <?php if ($this->_var['article_rela']): ?>
     <div class="bottom">
        <h5 class="titlerelate">Bài viết liên quan</h5>
        <ul class="newsrelate">
            <?php $_from = $this->_var['article_rela']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('i', 'article_0_29361100_1694103184');$this->_foreach['article_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['article_list']['total'] > 0):
    foreach ($_from AS $this->_var['i'] => $this->_var['article_0_29361100_1694103184']):
        $this->_foreach['article_list']['iteration']++;
?>
            <li class="item_<?php echo $this->_var['i']; ?>">
                <a href="<?php echo $this->_var['article_0_29361100_1694103184']['url']; ?>" class="linkimg" title="<?php echo htmlspecialchars($this->_var['article_0_29361100_1694103184']['title']); ?>">
                    <div class="tempvideo">
                        <img alt="<?php echo htmlspecialchars($this->_var['article_0_29361100_1694103184']['title']); ?>" src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['article_0_29361100_1694103184']['article_thumb']; ?>" width="100">
                    </div>
                    <h3><?php echo $this->_var['article_0_29361100_1694103184']['title']; ?></h3>
                    <span class="timepost"><?php echo $this->_var['article_0_29361100_1694103184']['add_time']; ?></span>
                </a>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <?php endif; ?>
    

    <div class="bottom pluggin-comment-facebook">
        <?php if ($this->_var['option']['comment_enabled']): ?><?php echo $this->fetch('library/comments.lbi'); ?><?php endif; ?>
    </div>

    <?php echo $this->_var['render']['after_main']; ?>
</section>
<?php echo $this->fetch('library/page_header.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<?php echo $this->smarty_insert_js(array('name'=>'article.Desktop.min.js','no_minify'=>'js/jquery.min.1.8.3.js,js/plugins.js,js/lang.vi_vn.js','minify'=>'js/global.js,js/init.js')); ?>
<script type="text/javascript" src="<?php echo $this->_var['option']['static_path']; ?>static/js/jquery.toc.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_var['option']['static_path']; ?>static/owl-carousel/owl.carousel.2.3.4.min.js"></script>
<script>
    var base_path = '<?php echo $this->_var['option']['static_path']; ?>';
    var url_post = base_path+'<?php echo $this->_var['article']['url']; ?>';
    $("#toc").toc({content: "div.article_content", headings: "h2,h3,h4", url: url_post});
    $("#toc").wrap( "<div class='wrap_toc'></div>" );
    $(".wrap_toc").css('visibility', 'visible').prepend('<p></p>');

    $(document).ready(function() {
        $("#owl-cate").owlCarousel({items:2,lazyLoad:!0,navigation:!1,pagination:!0,itemsDesktop:2,itemsDesktopSmall:2,itemsTablet:2,autoPlay:5e3,stopOnHover:!0});
    });

   $("#toc a").on('click', function(event) {
    if (this.hash !== "") {
      event.preventDefault();

      var hash = this.hash;
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){
        window.location.hash = hash;
      });
    } // End if
  });
</script>
<script type="text/javascript">
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
  var e = document.getElementById("up");
  if (e.style.transform === "rotate(45deg)") {
    e.style.transform = "rotate(224deg)";
  } else {
    e.style.transform = "rotate(45deg)";
  }
}
</script>

<script type="text/javascript">
    $('.rank_star').rating({
        focus: function(value, link){
            var tip = $('#star_tip');
            tip[0].data = tip[0].data || tip.html();
            tip.html(link.title || 'value: '+value).show();
        },
        blur: function(value, link){
            $('#star_tip').html(link.title).show();
        },
        callback: function(value, link){
           $('#star_tip').html(link.title).show();
           $('div.action-comment').fadeIn('600');
        }
    });
</script>
<script>
    var owl = $("#goods_price .cate");
    if(owl.length > 0){
        owl.find('li').css('width','100%');
        owl.owlCarousel({items: 4,slideBy: 3,lazyLoad:!0,slideSpeed : 2000,nav:!0,dots:!1,loop:!1,responsiveRefreshRate : 200,navText: ['‹','›'],});
    }

    
    $(document).ready(function() {
        $("#owl-cate").owlCarousel({items:2,lazyLoad:!0,navigation:!1,pagination:!0,itemsDesktop:2,itemsDesktopSmall:2,itemsTablet:2,autoPlay:5e3,stopOnHover:!0});
    });

</script>
</body>
</html>
