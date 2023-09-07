
<div class="product_details clearfix">
    <div class="rowtop clearfix">
        <span class="very">Chính hãng</span><h1><?php echo $this->_var['goods']['goods_name']; ?></h1>
         <div class="rating">
            <span class="rank rank_<?php echo $this->_var['goods']['comment_rank']; ?>"></span> <a href="<?php echo $this->_var['goods']['url']; ?>#comment"><?php 
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
?> đánh giá</a>
        </div>
    </div>
    <div class="extra_details">
       <?php echo $this->fetch('library/goods_gallery_magiczoomplus.lbi'); ?>
    </div>
    <div class="details">
         <?php if ($this->_var['goods']['is_on_sale'] == 1): ?>
        <div class="price_and_no">
            <?php if ($this->_var['goods']['shop_price'] == 0): ?>
                <p class="price"><span>Giá:</span> <strong>Liên hệ</strong></p>
            <?php else: ?>
                <?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?>
                    
                    <div class="price newpr">
                    <?php if ($this->_var['goods']['is_vat'] == 0): ?> 
                    <span>Giá Khuyến Mại :</span> <strong  id="_amount"><?php echo $this->_var['goods']['promote_price']; ?></strong><em><?php echo $this->_var['goods']['discount']; ?> GIẢM</em> <del><?php echo $this->_var['goods']['market_price']; ?></del> 
                        <?php elseif ($this->_var['goods']['is_vat'] == 1): ?> 
                          <del><?php echo $this->_var['goods']['market_price']; ?></del>   <strong  id="_amount"><?php echo $this->_var['goods']['promote_price']; ?></strong><em><?php echo $this->_var['goods']['discount']; ?> GIẢM</em> <span> (Đã gồm VAT)</span>  
                        <?php elseif ($this->_var['goods']['is_vat'] == 2): ?>
                        <div class="price_none_vat">
                        <span style=" width: 146px; display: block; float: left; ">Giá chưa VAT:</span> <strong><?php echo $this->_var['goods']['promote_price_none_vat']; ?></strong> 
                        </div> 
                        <span>Giá Khuyến Mại (VAT):</span> <strong  id="_amount"><?php echo $this->_var['goods']['promote_price']; ?></strong><em><?php echo $this->_var['goods']['discount']; ?> GIẢM</em> <del ><?php echo $this->_var['goods']['shop_price_formated']; ?></del>
                           
                        <?php endif; ?>
                    </div>
                    
                <?php else: ?>
                    <div class="price newpr"> 
                        <?php if ($this->_var['goods']['is_vat'] == 0): ?> 
                   <span>Giá:</span><strong  id="_amount"><?php echo $this->_var['goods']['shop_price_formated']; ?></strong> <em><?php echo $this->_var['goods']['discount']; ?> GIẢM</em>  <del><?php echo $this->_var['goods']['market_price']; ?></del> 
                        <?php elseif ($this->_var['goods']['is_vat'] == 1): ?> 
                        <span>Giá:</span>  <strong  id="_amount"><?php echo $this->_var['goods']['shop_price_formated']; ?></strong> <del><?php echo $this->_var['goods']['market_price']; ?></del>  <em><?php echo $this->_var['goods']['discount']; ?> GIẢM</em>
                            <span style="margin-left: 3px;"> (Đã gồm VAT)</span>
                        <?php elseif ($this->_var['goods']['is_vat'] == 2): ?>
                        <div class="price_none_vat">
                        <span>Giá:</span>    <strong ><?php echo $this->_var['goods']['shop_price_none_vat']; ?></strong><span style="margin-left: 3px;">(Chưa VAT)</span>
                        </div>
                        <span>Giá: </span>   <strong  id="_amount"><?php echo $this->_var['goods']['shop_price_formated']; ?></strong> <em><?php echo $this->_var['goods']['discount']; ?> GIẢM</em> <del><?php echo $this->_var['goods']['market_price']; ?></del> <span style="margin-left: 3px;"> (Đã gồm VAT)</span>
                            
                         
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

               
                 <?php if ($this->_var['goods']['shop_price_hcm_formated'] > 0): ?>
                    <div class="local_price">
                            <label><input type="radio" name="price_option" value="hanoi" checked>Giá Hà Nội</label>
                            <label><input type="radio" name="price_option" value="hcm">Giá Hồ Chí Minh</label>
                     </div>
                <?php endif; ?>

                <?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?>
                <p class="end_time_wrapper">Thời gian KM còn lại: <span class="end_time" title="<?php 
$k = array (
  'name' => 'date_format',
  'date' => $this->_var['goods']['gmt_end_time'],
  'format' => 'Y-m-d-H-i-s',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>"><?php 
$k = array (
  'name' => 'date_format',
  'date' => $this->_var['goods']['gmt_end_time'],
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?></span></p>
                <?php endif; ?>
            <?php endif; ?>
  
            <p><span>Thương hiệu: <a href="<?php echo $this->_var['goods']['brand_goods_url']; ?>"><?php echo $this->_var['goods']['goods_brand']; ?></a></span> </p>
            <p><span>Mã sản phẩm: <?php echo $this->_var['goods']['goods_sn']; ?></span> </p>
            <p><span>Bảo hành: <?php echo $this->_var['goods']['warranty']; ?></span> </p>
            <?php if ($this->_var['goods']['goods_sx'] != "null"): ?>
            <p>Xuất xứ: <?php echo $this->_var['goods']['goods_sx']; ?></p>
            <?php endif; ?>
            <?php if (( $this->_var['goods']['goods_number'] == '0' ) && ( $this->_var['option']['use_storage'] == '1' )): ?>
            <p class="price" style="color: #f60"><span>Trạng thái:</span>Tạm hết hàng</p>
            <?php else: ?>
            <p >Tình trạng:<span style="color:#528bc0"> Sẵn hàng </span></p> 
            <?php endif; ?>

           
            
        </div>

        <?php if ($this->_var['goods']['goods_gift']): ?>
        <div class="area_promotion" id="gdsgift">
            <div class="protit">Khuyến mãi, Ưu đãi</div>
            <div class="prob">
                <?php echo $this->_var['goods']['goods_gift']; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <ul class="basic clearfix">
            <?php if ($this->_var['promotion']['0']['url']): ?>
            <?php $_from = $this->_var['promotion']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
                <?php if ($this->_var['item']['type'] == "favourable"): ?>
                <li><?php echo $this->_var['lang']['favourable']; ?> <strong><?php echo $this->_var['item']['act_name']; ?></strong></li>
                <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
        </ul>
        <?php if ($this->_var['goods']['goods_number'] != '0' && $this->_var['option']['use_storage'] == '1'): ?>
        <form action="javascript:buy(<?php echo $this->_var['goods']['goods_id']; ?>)" method="post" id="purchase_form">
            <?php if ($this->_var['specification']): ?>
                <div class="properties clearfix">
                    <?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key', 'spec');$this->_foreach['specification'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['specification']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key'] => $this->_var['spec']):
        $this->_foreach['specification']['iteration']++;
?>
                    <dl<?php if ($this->_var['spec']['attr_type'] == 2): ?> title="<?php echo $this->_var['lang']['multi_choice']; ?>"<?php endif; ?>>
                        <dt><?php echo $this->_var['spec']['name']; ?><?php echo $this->_var['lang']['colon']; ?></dt>
                        <?php if ($this->_var['spec']['attr_type'] == 1): ?>
                        <dd class="radio">
                            <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
                            <label for="spec_value_<?php echo $this->_var['value']['id']; ?>" title="<?php if ($this->_var['value']['price'] > 0): ?><?php echo $this->_var['value']['format_price']; ?><?php elseif ($this->_var['value']['price'] < 0): ?><?php echo $this->_var['lang']['minus']; ?><?php echo $this->_var['value']['format_price']; ?><?php endif; ?>">
                                <input type="radio" name="spec_<?php echo $this->_var['spec_key']; ?>" value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>"<?php if ($this->_var['key'] == 0): ?> checked="checked"<?php endif; ?> />
                            <?php echo $this->_var['value']['label']; ?></label>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </dd>
                        <?php else: ?>
                        <dd class="checkbox">
                            <?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
                            <label for="spec_value_<?php echo $this->_var['value']['id']; ?>" title="<?php if ($this->_var['value']['price'] > 0): ?><?php echo $this->_var['value']['format_price']; ?><?php elseif ($this->_var['value']['price'] < 0): ?><?php echo $this->_var['lang']['minus']; ?><?php echo $this->_var['value']['format_price']; ?><?php endif; ?>">
                                <input type="checkbox" name="spec_<?php echo $this->_var['spec_key']; ?>" value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>" />
                            <?php echo $this->_var['value']['label']; ?></label>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </dd>
                        <?php endif; ?>
                    </dl>
                    <input type="hidden" name="spec_list" value="<?php echo $this->_var['key']; ?>">
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            <?php endif; ?>

                                        
        <div class="actions clearfix">
                <div class="sfamout">Số lượng:
                    <div class="button-container">
                        <button class="cart-qty-minus" type="button" value="-">-</button>
                        <input type="number" name="number" class="qty" value="1" min="1" max="<?php echo $this->_var['goods']['goods_number']; ?>">
                        <button class="cart-qty-plus" type="button" value="+">+</button>
                    </div>
                     <?php echo $this->_var['lang']['amount']; ?><?php echo $this->_var['lang']['colon']; ?> <span class="price amount"><?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?><?php echo $this->_var['goods']['promote_price']; ?><?php else: ?><?php echo $this->_var['goods']['shop_price_formated']; ?><?php endif; ?></span>
                 </div>
        
        <a href="javascript:;" id="showForm"><strong>Nhận tư vấn</strong>
                    <span>Hãy để lại số điện thoại</span></a>
        
        


            <div class="submit">
                    <a href="javascript:buy(<?php echo $this->_var['goods']['goods_id']; ?>)" class="button btn-primary <?php if ($this->_var['istragop']): ?>btn-inline<?php endif; ?>">
                        <span><?php echo $this->_var['lang']['btn_buy']; ?></span> Mua online hoặc tại cửa hàng</a>
                </div>
            </div> 
            
        </form>
        <div class="requestcall" style="display: none;">
                <div class="request-msg" style="display: none;">Chúng đã nhận được thông tin của bạn. Chúng tôi sẽ tư vấn theo thông tin bạn đã cung cấp.</div>
                <form id="contactForm">
                    <input type="hidden" name="url" value="">
                    <input type="tel" name="tel" placeholder="Nhập số điện thoại của anh/chị">
                    <a href="javascript:;" id="_call"><i class="icon-poltel"></i> <strong>Hãy tư vấn cho tôi</strong></a>
                    <a href="javascript:;" id="closeForm">Đóng</a>
                </form>
            </div>
        <?php endif; ?>
        
        <?php else: ?>
            <p class="price" style="color: #d0021b"><span>Ngừng Kinh Doanh</span></p>
        <?php endif; ?>
        
    </div>
    <div class="input_action">
        <div class="infor"><span class="sub-title-after"></span>Lợi ích khi mua hàng</div>
        <div class="talbegoods">
        <img class="imgic" src="/static/img/icon/1.png"></img>
        <span>Sản phẩm chính hãng 100% </span>
        </div>
        <div class="talbegoods">
        <img class="imgic" src="/static/img/icon/2.png"></img>
        <span>Giá luôn tốt nhất</span>
        </div>
        <div class="talbegoods">
        <img class="imgic" src="/static/img/icon/3.png"></img>
        <span>Tư vấn chuyên nghiệp</span>
        </div>
        <div class="talbegoods">
        <img class="imgic" src="/static/img/icon/4.png"></img>
        <span>Giao hàng toàn quốc</span>
        </div>
        <div class="talbegoods">
        <img class="imgic" src="/static/img/icon/5.png"></img>
        <span>Dưới 5km & đơn hàng trên 1 triệu</span>
        </div>
        <div class="talbegoods">
        <img class="imgic" src="/static/img/icon/6.png"></img>
        <span>Bảo hành & sửa chữa tận tâm</span>
        </div>
        <?php if ($this->_var['goods_phone_hn'] || $this->_var['goods_phone_hcm']): ?>
            <div class="phone_cat">
                <?php if ($this->_var['goods_phone_hn']): ?>
                    <div class="local hanoi">
                        <span>Hotline Hà Nội</span>
                        <?php $_from = $this->_var['goods_phone_hn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'phone_hn');if (count($_from)):
    foreach ($_from AS $this->_var['phone_hn']):
?>
                            <p><?php echo htmlspecialchars($this->_var['phone_hn']['name']); ?> - <a class="localphone" href="tel:<?php echo $this->_var['phone_hn']['phone']; ?>"><?php echo $this->_var['phone_hn']['phone']; ?></a></p>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->_var['goods_phone_hcm']): ?>
                    <div class="local hcm">
                        <span>Hotline Sài Gòn</span>
                        <?php $_from = $this->_var['goods_phone_hcm']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'phone_hcm');if (count($_from)):
    foreach ($_from AS $this->_var['phone_hcm']):
?>
                            <p><?php echo htmlspecialchars($this->_var['phone_hcm']['name']); ?> - <a class="localphone" href="tel:<?php echo $this->_var['phone_hcm']['phone']; ?>"><?php echo $this->_var['phone_hcm']['phone']; ?></a></p>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
