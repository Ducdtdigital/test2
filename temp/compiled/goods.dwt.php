<?php echo $this->fetch('library/html_header.lbi'); ?>
<?php echo $this->smarty_insert_css(array('files'=>'owl-carousel/owl.carousel.2.3.4.min.css,css/magiczoomplus.css,css/style.css,css/goods.css')); ?>
<style type="text/css">
    .star-rating-on a, .star-rating-hover a{background-position: 0px 0px !important}
</style>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Product",
    "brand": "<?php echo $this->_var['goods']['goods_brand']; ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $this->_var['goods']['comment_rank']; ?>",
    "reviewCount": "<?php 
$k = array (
  'name' => 'comment_count',
  'id' => $this->_var['id'],
  'type' => $this->_var['type'],
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>"
  },
  "description": "<?php echo $this->_var['description']; ?>",
  "sku": "<?php echo $this->_var['goods']['goods_id']; ?>",
    "mpn": "<?php echo $this->_var['goods']['goods_id']; ?>",
    "review": {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "<?php echo $this->_var['goods']['comment_rank']; ?>",
      "bestRating": "<?php echo $this->_var['goods']['comment_rank']; ?>"
    },
     "author": {
      "@type": "Person",
      "name": "<?php echo $this->_var['shop_name']; ?>"
    }
  },
  "name": "<?php echo $this->_var['goods']['goods_name']; ?>",
  "image": "<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_thumb']; ?>",
  "offers": {
    "@type": "Offer",
    "url":"<?php echo $this->_var['canonical']; ?>",
    "availability": "http://schema.org/InStock",
    "price": "<?php echo $this->_var['goods']['shop_price']; ?>",
    "priceCurrency": "VND",
    "priceValidUntil": "2020-11-05"
  }
}
</script>
</head>
<body id="page_<?php echo $this->_var['pname']; ?>" class="animated fadeIn">
<?php echo $this->fetch('library/page_header.lbi'); ?>
 
<section>
    <?php echo $this->fetch('library/ur_here.lbi'); ?>

    <?php if ($this->_var['goods']['goods_status'] == 4): ?>
    <?php echo $this->fetch('library/goods_detail4.lbi'); ?>
    <?php elseif ($this->_var['goods']['goods_status'] == 3): ?>
    <?php echo $this->fetch('library/goods_detail3.lbi'); ?>
    <?php elseif ($this->_var['goods']['goods_status'] == 2): ?>
    <?php echo $this->fetch('library/goods_detail2.lbi'); ?>
     <?php else: ?>
    <?php echo $this->fetch('library/goods_detail.lbi'); ?>
    <?php endif; ?>
    <?php echo $this->_var['render']['before_goods_info']; ?>
    <div class="col_main_lg">
        <div class="clearfix"></div>
         <?php if ($this->_var['goods']['goods_thumb_url']): ?>
        <div class="imgnote">
            <img src="<?php echo $this->_var['goods']['goods_thumb_url']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
        </div>
        <?php endif; ?>
        <?php if ($this->_var['goods']['goods_desc'] || $this->_var['goods']['goods_brief']): ?>
        <article id="description">
            <div class="article_content">
            <?php if ($this->_var['goods']['goods_brief']): ?><p class="intro"><?php echo $this->_var['goods']['goods_brief']; ?></p><?php endif; ?>
            <?php echo $this->_var['goods']['goods_desc']; ?>
        </div>
            <div class="view-more hidden">
            <p id="btn-viewmore"><span id="view1">Đọc thêm </span><!-- <span id="view2" class="hidden">Thu gọn </span> --></p>
            </div>
        </article>
        <?php endif; ?>
        <?php echo $this->fetch('library/goods_fittings.lbi'); ?>
        <?php echo $this->fetch('library/goods_combo.lbi'); ?>
        <?php echo $this->fetch('library/goods_related.lbi'); ?>

        <div class="clr"></div>
         
         <?php echo $this->fetch('library/goods_price.lbi'); ?>

        <?php if ($this->_var['option']['comment_enabled']): ?><div class="clr"></div><?php echo $this->fetch('library/comments.lbi'); ?><?php endif; ?>
    </div>
    <div class="col_sub_lg">

       <?php if ($this->_var['properties']): ?>
        <div class="tableparameter" id="tableparameter">
            <h2>Thông số sản phẩm</h2>
            <ul class="parameter">
                <?php $_from = $this->_var['properties']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'property_group');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['property_group']):
?>
                 <?php $_from = $this->_var['property_group']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'property');$this->_foreach['property_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['property_list']['total'] > 0):
    foreach ($_from AS $this->_var['property']):
        $this->_foreach['property_list']['iteration']++;
?>
                <?php if (($this->_foreach['property_list']['iteration'] - 1) < 8): ?>
                <li><strong><?php echo htmlspecialchars($this->_var['property']['name']); ?>:</strong> <?php echo $this->_var['property']['value']; ?></li>
                <?php endif; ?>
                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        <div class="viewparameterfull">Xem cấu hình chi tiết</div>
        </div>
        <?php endif; ?>
 
        
<?php echo $this->fetch('library/goods_article.lbi'); ?>

    </div>
    <?php echo $this->_var['render']['after_goods_info']; ?>
    

    <?php echo $this->_var['render']['after_main']; ?>
</section>
<?php echo $this->fetch('library/page_footer.lbi'); ?>


<?php if ($this->_var['properties']): ?>
<div class="closebtn"><span>✖</span>Đóng</div>
<div class="fullparameter">
    <div class="scroll"><h3>Thông số kỹ thuật <?php echo $this->_var['goods']['goods_name']; ?></h3>
        <ul class="parameter">
            <?php $_from = $this->_var['properties']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'property_group');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['property_group']):
?>
             <?php $_from = $this->_var['property_group']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'property');if (count($_from)):
    foreach ($_from AS $this->_var['property']):
?>
            <li><strong><?php echo htmlspecialchars($this->_var['property']['name']); ?></strong>: <?php echo $this->_var['property']['value']; ?></li>
             <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
</div></div>
<?php endif; ?>

<?php echo $this->smarty_insert_js(array('name'=>'goods.Desktop.min.js','no_minify'=>'js/jquery.min.1.8.3.js,js/plugins.js,js/lang.vi_vn.js,js/magiczoomplus.js,owl-carousel/owl.carousel.2.3.4.min.js','minify'=>'js/global.js,js/init.js')); ?>
<script type="text/javascript">
    $(".cart-qty-plus").click(function() {
        var n = $(this).parent(".button-container").find(".qty");
        n.val(Number(n.val())+1);
        $('#purchase_form').ChangePriceSiy();
    });
    $(".cart-qty-minus").click(function() {
        var n = $(this).parent(".button-container").find(".qty");
        var amount = Number(n.val());
        if (amount > 1) {
            n.val(amount-1);
        }
        $('#purchase_form').ChangePriceSiy();

    });
</script>
<script type="text/javascript">
  
    var owl = $("#goods_price .cate");
    if(owl.length > 0){
        owl.find('li').css('width','100%');
        owl.owlCarousel({items: 5,slideBy: 3,lazyLoad:!0,slideSpeed : 2000,nav:!0,dots:!1,loop:!1,responsiveRefreshRate : 200,navText: ['‹','›'],});
    }

    $('.end_time[title!=""]').each(function(){
        var countdown = $(this);
        countdown.parent().find('.label').text(lang.remaining_time + lang.colon);
        var end_date = countdown.attr('title').split('-');
        countdown.removeAttr('title');
        var date = new Date(end_date[0],end_date[1]-1,end_date[2],end_date[3],end_date[4],end_date[5]);
        countdown.countdown({
            until: date,
            layout: '<em>{dn}</em>{dl}<em>{hn}</em>{hl}<em>{mn}</em>{ml}<em>{sn}</em>{sl}'
        });
    });

    $(".viewparameterfull, .tskt").click(function() {
        if($(".fullparameter").css("display") == "block"){
            $(".closebtn").hide();
            $(".fullparameter").hide();
            $("body").removeClass("fixbody");
            $(".fixparameter").remove();
        }else{
            $(".fullparameter").show();
            $(".closebtn").show();
            $("body").addClass("fixbody").prepend('<div class="fixparameter">content<\/div>');
        }
    });
    $(".closebtn").click(function() {
        $(".viewparameterfull").click();
        $("body").removeClass("fixbody");
        $(".fixparameter").remove();
        $(".closebtn").hide();
    });
    $(document.body).on("click", ".fixparameter", function() {
        $("body").toggleClass("fixbody");
        $(".fixparameter").remove();
        $(".viewparameterfull").click();
    });
</script>
<script type="text/javascript">
    var goodsId = <?php echo $this->_var['goods_id']; ?>;

    /** view more */
    var el = $('#description .article_content');
    var h = el.height();

    if(h>500){
        el.addClass('short_view');
        $('#description .view-more').removeClass('hidden');
    }
    $('#btn-viewmore').click(function(e){
        el.toggleClass('short_view');
        $('#view1').toggleClass('hidden');
        e.preventDefault();
    });
    /** end view more */

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
    $("#_call").click(function(){
        $form=$(this).closest("form");
        var $tel=0;
        $tel=$form.find("input[name='tel']").val();

        if($tel.length<10 || $tel.length > 11 || $tel[0]!="0")
        {
            alert("Số điện thoại không hợp lệ");
        }
        else
        {
            $form.find("input[type='hidden']").val(window.location.href);
            $.ajax({
                    url:$("base").attr("href")+"ajax/requestcall.php",
                    data:$form.serialize(),
                    type:"POST",
                    success:function(data){
                        console.log(data);
                        $form.hide();
                        $form.find("input[name='tel']").val('');
                        $(".request-msg").fadeIn();
                    }
            });
        }
        return false;
    });
    $("#_booking").click(function(){
        $form=$(this).closest("form");
        var $tel=0;
        var $linkman = '';
        var $sex = '';
        $tel=$form.find("input[name='tel']").val();
        $sex=$form.find("input[name='sex']").val();
        $linkman=$form.find("input[name='linkman']").val();

        if($tel.length<10 || $tel.length > 11 || $tel[0]!="0")
        {
            alert("Số điện thoại không hợp lệ");
        }
        else if($linkman < 6 || $linkman == '')
        {
            alert("Tên có dộ dài ít nhất 6 ký tự");
        }
        else if($sex == '')
        { 
            alert("Chưa chọn giới tính");
        }
        else
        {
            $("#loading_box").show();
            $.ajax({
                    url:$("base").attr("href")+"ajax/goods.php?act=booking",
                    data:$form.serialize(),
                    type:"POST",
                    success:function(data){
                        var res = $.evalJSON(data);
                        $("#loading_box").hide();
                        if(res.err_no == 1){
                            $("#_bookingresult").html(res.err_msg).addClass('error_box').fadeIn();
                        }else{
                            $form.find("input[name='tel']").val('');
                            $form.find("input[name='linkman']").val('');
                            $form.find("input[name='email']").val('');
                            $("#_bookingresult").html(res.err_msg).addClass('success_box').fadeIn();
                        }
                    }
            });
        }
        return false;
    });
    $('input[name="price_option"]').change(function() {
    var option = $(this).val();
    $.get('ajax/goods.php', {act: 'price', id: goodsId, city: option}, function(response) {
        var res = $.evalJSON(response);
        // cập nhật giá trên trang
        $('.amount').html(res.result);
        $('#_amount').html(res.result);
    });
});

// jQuery document ready function
$(document).ready(function() {
    // Add a click event listener to the showForm link
    $('#showForm').on('click', function(e) {
        e.preventDefault();
        // Show the form
        $('.requestcall').show();
    });

    // Add a click event listener to the _call link
    $('#_call').on('click', function(e) {
        e.preventDefault();
        // Show the message
        $('.request-msg').show();

        // Assuming you want to submit the form
        // Add code here to submit your form. For example:
        // $('#contactForm').submit();
    });
});
$('#closeForm').on('click', function(e) {
    e.preventDefault();
    $('.requestcall').hide();
});



</script>
<?php echo $this->fetch('library/html_desktopmobile.lbi'); ?>

</body>
</html>