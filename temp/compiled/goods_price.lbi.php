<?php if ($this->_var['goods_price_list']): ?>
<div class="xm-plain-box clearfix" id="goods_price">
	<div class="box-hd"><h2 class="title">Tương tự cùng phân khúc giá</h2></div>
	<ul class="box-bd cate owl-carousel owl-theme">
		<?php $_from = $this->_var['goods_price_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_83477100_1694102530');$this->_foreach['goods_price'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_price']['total'] > 0):
    foreach ($_from AS $this->_var['goods_0_83477100_1694102530']):
        $this->_foreach['goods_price']['iteration']++;
?>
		<li>
            <a href="<?php echo $this->_var['goods_0_83477100_1694102530']['url']; ?>">
                <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods_0_83477100_1694102530']['goods_thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods_0_83477100_1694102530']['goods_name']); ?>">
                <h3><?php echo $this->_var['goods_0_83477100_1694102530']['goods_name']; ?></h3>
                <?php if ($this->_var['goods_0_83477100_1694102530']['price'] == 0): ?>
                    <span>Giá liên hệ</span>
                <?php else: ?>
                    <?php if ($this->_var['goods_0_83477100_1694102530']['promote_price']): ?>
                    <strong class="gs"><?php echo $this->_var['goods_0_83477100_1694102530']['formated_promote_price']; ?></strong>
                    <strong class="gg"><?php echo $this->_var['goods_0_83477100_1694102530']['shop_price']; ?></strong>
                    <?php else: ?>
                    <strong class="gs"><?php echo $this->_var['goods_0_83477100_1694102530']['shop_price']; ?></strong>
                    <?php endif; ?>
                <?php endif; ?>
            </a>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</ul>
</div>

<?php endif; ?>