<?php if ($this->_var['fittings']): ?>
<div class="xm-plain-box clearfix" id="goods_price">
<div class="clearfix" id="fittings">
    <h2 class="title_heading">Ưu đãi khi mua cùng <?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h2>
    <div class="overpromo _goods_fittings">
    <ul class="box-bd cate owl-carousel owl-theme">
        <?php $_from = $this->_var['fittings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_00913800_1694103632');$this->_foreach['fittings'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fittings']['total'] > 0):
    foreach ($_from AS $this->_var['goods_0_00913800_1694103632']):
        $this->_foreach['fittings']['iteration']++;
?>
        <li>
            <?php if ($this->_var['goods_0_00913800_1694103632']['discount']): ?><span class="discount"><?php echo $this->_var['goods_0_00913800_1694103632']['discount']; ?></span><?php endif; ?>
            <a href="<?php echo $this->_var['goods_0_00913800_1694103632']['url']; ?>">
                <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods_0_00913800_1694103632']['goods_thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods_0_00913800_1694103632']['goods_name']); ?>">
                <h3><?php echo $this->_var['goods_0_00913800_1694103632']['short_name']; ?></h3>
                <strong class="gs">
                <?php if ($this->_var['goods_0_00913800_1694103632']['is_discount'] == 1): ?><?php echo $this->_var['goods_0_00913800_1694103632']['fittings_price']; ?><del><?php echo $this->_var['goods_0_00913800_1694103632']['shop_price']; ?></del><?php else: ?><?php echo $this->_var['goods_0_00913800_1694103632']['fittings_price']; ?><?php endif; ?>
                </strong>
            </a>
            <p class="actions">
                <a href="javascript:fittings_to_flow(<?php echo $this->_var['goods_0_00913800_1694103632']['goods_id']; ?>,<?php echo $this->_var['goods_0_00913800_1694103632']['parent_id']; ?>)" class="btn_fittings">Mua kèm</a>
            </p>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    </div>
</div>
</div>
<?php endif; ?>