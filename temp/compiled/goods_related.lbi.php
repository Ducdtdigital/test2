<?php if ($this->_var['related_goods'] && $this->_var['pname'] == 'goods'): ?>
<div class="clearfix" id="related">
	<h2 class="title_heading">Sản phẩm liên quan</h2>
	<div class="overpromo _related">
    <ul class="cate listpromo">
		<?php $_from = $this->_var['related_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'releated_goods_data');$this->_foreach['related_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['related_goods']['total'] > 0):
    foreach ($_from AS $this->_var['releated_goods_data']):
        $this->_foreach['related_goods']['iteration']++;
?>
		<li>
            <?php if ($this->_var['releated_goods_data']['promote_price']): ?><span class="discount"><?php echo $this->_var['goods']['discount']; ?></span><?php endif; ?>
            <a href="<?php echo $this->_var['releated_goods_data']['url']; ?>">
                <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['releated_goods_data']['goods_thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['releated_goods_data']['goods_name']); ?>">
                <h3><?php echo $this->_var['releated_goods_data']['short_name']; ?></h3>
                <?php if ($this->_var['releated_goods_data']['price'] == 0): ?>
                    <span>Giá liên hệ</span>
                <?php else: ?>
                    <?php if ($this->_var['releated_goods_data']['promote_price']): ?>
                    <strong class="gs"><?php echo $this->_var['releated_goods_data']['formated_promote_price']; ?> <del><?php echo $this->_var['releated_goods_data']['shop_price']; ?></del></strong>
                    <?php else: ?>
                    <strong class="gs"><?php echo $this->_var['releated_goods_data']['shop_price']; ?></strong>
                    <?php endif; ?>
                <?php endif; ?>
            </a>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</ul>
    </div>
</div>
<?php elseif ($this->_var['related_goods'] && $this->_var['pname'] == 'article'): ?>
    <div class="xm-plain-box clearfix" id="goods_price">
    <div class="box-hd"><h4 class="title">Sản phẩm liên quan</h4></div>
    <ul class="box-bd cate owl-carousel owl-theme">
         <?php $_from = $this->_var['related_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_01088500_1694103632');$this->_foreach['related_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['related_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods_0_01088500_1694103632']):
        $this->_foreach['related_goods']['iteration']++;
?>
        <li>
        <?php if ($this->_var['goods_0_01088500_1694103632']['discount']): ?><span class="discount"><?php echo $this->_var['goods_0_01088500_1694103632']['discount']; ?></span><?php endif; ?>
            <a href="<?php echo $this->_var['goods_0_01088500_1694103632']['url']; ?>">
                <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods_0_01088500_1694103632']['goods_thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods_0_01088500_1694103632']['goods_name']); ?>">
                <h3><?php echo $this->_var['goods_0_01088500_1694103632']['goods_name']; ?></h3>
             <div class="rank_click clearfix">
                         <em class="rank rank_<?php echo $this->_var['goods_0_01088500_1694103632']['comment_rank_css']; ?>"></em><em><?php echo $this->_var['goods_0_01088500_1694103632']['comment_count']; ?> đánh giá</em>
                    </div>
                     <?php if ($this->_var['goods_0_01088500_1694103632']['price'] == 0): ?>
                    <span>Giá liên hệ</span>
                    <?php else: ?>
                    <strong><?php if ($this->_var['goods_0_01088500_1694103632']['promote_price']): ?><?php echo $this->_var['goods_0_01088500_1694103632']['promote_price']; ?><del><?php echo $this->_var['goods_0_01088500_1694103632']['shop_price']; ?></del><?php else: ?><?php echo $this->_var['goods_0_01088500_1694103632']['shop_price']; ?><del><?php echo $this->_var['goods_0_01088500_1694103632']['market_price']; ?></del><?php endif; ?></strong>
                <?php endif; ?>
            </a>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</div>
<?php endif; ?>