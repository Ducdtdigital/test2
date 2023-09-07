<footer>
    <div class="rowfoot1">
         <ul class="colfoot" style="width: 32%">
            <li style="padding-right: 30px">
                <p>Đơn vị chủ quản: Công ty CP công nghệ THB Việt Nam</p>
               <p>Giấy phép đăng ký kinh doanh số 0105848319 do sở KHĐT TP Hà Nội cấp ngày 09/04/2012</p>
               <p>Website đang trong quá trình chạy thử nghiệm</p>
               <p><iframe name="f6eb905b681fac" width="1000px" height="1000px" data-testid="fb:page Facebook Social Plugin" title="fb:page Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/v2.6/plugins/page.php?adapt_container_width=true&amp;app_id=&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fx%2Fconnect%2Fxd_arbiter%2F%3Fversion%3D46%23cb%3Df3a316387c457dc%26domain%3Dmaydopro.com%26is_canvas%3Dfalse%26origin%3Dhttps%253A%252F%252Fmaydopro.com%252Ff131018e7b4bdbc%26relation%3Dparent.parent&amp;container_width=270&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2Fmaydochuyendung&amp;locale=vi_VN&amp;sdk=joey&amp;show_facepile=true&amp;small_header=true" style="border: none; visibility: visible; width: 270px; height: 70px;" class=""></iframe></p>
               <p style=" float: left; "><a href="https://www.facebook.com/maydochuyendung" target="_blank"><img src="/images/ss-facebook.png"></a></p>
               <p><a href="https://www.youtube.com/channel/UCQ81XNZW6OZtJdBxGiHcrCA" target="_blank"><img src="/images/ss-youtube.png"></a></p>
        </ul>
        <ul class="colfoot" style="width:20%">
          <li><a href="chinh-sach-doi-tra.html" title="Chính sách đổi trả hàng" rel="nofollow">Chính sách đổi trả hàng</a></li>
          <li><a href="huong-dan-mua-hang-thanh-toan.html" title="Hướng dẫn mua hàng & Thanh toán" rel="nofollow">Hướng dẫn mua hàng & Thanh toán</a></li>
          <li><a href="chinh-sach-bao-hanh.html" title="Chính sách bản hành" rel="nofollow">Chính sách bảo hành</a></li>
          <li><a rel="nofollow" href="chinh-sach-bao-mat.html" title="Chính sách bảo mật">Chính sách bảo mật</a></li>
          <li><a rel="nofollow" href="chinh-sach-van-chuyen.html" title="Chính sách vận chuyển">Chính sách vận chuyển</a></li>

            
        </ul>
        <ul class="colfoot" style="width:15%">
          <li><a href="gioi-thieu.html" target="_blank" title="Giới thiệu công ty" rel="nofollow">Giới thiệu</a></li>
          <li><a href="quy-dinh-su-dung.html" target="_blank" title="Quy định sử dụng " rel="nofollow">Quy định sử dụng </a></li>
          <li><a href="lien-he" title="Liên hệ"rel="nofollow">Liên hệ</a></li>
          <li><a class="viewmb" rel="nofollow" href="javascript:window.location.href='?client=mobile'" title="Xem bản mobile">Xem bản Mobile</a></li>
        </ul>
        <ul class="colfoot" style="width:auto%;">
                <li style="font-weight: 700;">CHI NHÁNH HÀ NỘI</li>
                <li><?php echo $this->_var['shop_address']; ?></li>
                <li>Tel: (024) 3793 8604 - 3219 1220 </li>
                <li>Hotline/Zalo: 0902148147 | 0976606017 | 0981060817 | 0865 466 689 | 0904 810817  </li>
                <li style=" border-bottom: solid 1px; padding-bottom: 5px; ">Email: <?php echo $this->_var['service_email']; ?> </li>   
                <li style="font-weight: 700;">CHI NHÁNH TẠI HỒ CHÍ MINH</li>             
                <li><?php echo $this->_var['shop_address_sg']; ?></li>
                <li>Tel: <?php echo $this->_var['service_phone_sg']; ?> </li>
                <li>Hotline/Zalo: 0979244335 </li>
                <li>Email: <?php echo $this->_var['service_email_sg']; ?> </li>
        </ul>

    </div>
    <div class="rowfoot2">
        <p><?php echo $this->_var['copyright']; ?></p>
    </div>
</footer>
<a href="#top" rel="nofollow" title="Về Đầu Trang" id="back-top">↑</a>
<div id="quick_support">
    <div class="phone_all">
        <img class="icon_phone" src="https://thbvietnam.com/static/social/call.png" height="40">
        <div class="phone_all_khuvuc">
             <?php if ($this->_var['goods_phone_hn_all']): ?>
                    <div class="local_hanoi">
                        <span>Miền Bắc</span>
                        <?php $_from = $this->_var['goods_phone_hn_all']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'phone_hn_0_83633100_1694102530');if (count($_from)):
    foreach ($_from AS $this->_var['phone_hn_0_83633100_1694102530']):
?>
                            <a class="localphone_hn" href="tel:<?php echo $this->_var['phone_hn_0_83633100_1694102530']['phone']; ?>"><?php echo $this->_var['phone_hn_0_83633100_1694102530']['phone']; ?></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->_var['goods_phone_hcm_all']): ?>
                    <div class="local_hcm">
                        <span>Miền Nam</span>
                        <?php $_from = $this->_var['goods_phone_hcm_all']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'phone_hcm_0_83636000_1694102530');if (count($_from)):
    foreach ($_from AS $this->_var['phone_hcm_0_83636000_1694102530']):
?>
                           <a class="localphone_hcm" href="tel:<?php echo $this->_var['phone_hcm_0_83636000_1694102530']['phone']; ?>"><?php echo $this->_var['phone_hcm_0_83636000_1694102530']['phone']; ?></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                <?php endif; ?>
        </div>
    </div>
    <a rel="nofollow"  href="<?php echo $this->_var['messenger']; ?>" target="_blank">
        <img src="<?php echo $this->_var['option']['static_path']; ?>static/social/messager.png" height="40">
    </a>
    <a  rel="nofollow" href="<?php echo $this->_var['fanpage']; ?>" target="_blank">
        <img src="<?php echo $this->_var['option']['static_path']; ?>static/social/facebook.png" height="40">
    </a>
    <a  rel="nofollow" href="<?php echo $this->_var['zalo']; ?>" target="_blank">
        <img src="<?php echo $this->_var['option']['static_path']; ?>static/social/zalo.png" height="40">
    </a>
</div>
<script type="text/javascript">
    var base_path = "<?php echo $this->_var['option']['static_path']; ?>";
</script>

