<?php if ($this->_var['goods_list2']): ?>
<ul class="cate normal">
        <?php $_from = $this->_var['goods_list2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods_list']['iteration']++;
?>
        <?php if ($this->_var['goods']['goods_id']): ?>
        <li>
            <?php if ($this->_var['goods']['promote_price']): ?><span class="discount"><?php echo $this->_var['goods']['discount']; ?></span><?php endif; ?>
            <a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
            <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_thumb']; ?>" width="150" height="150" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
            <?php if ($this->_var['goods']['brand_logo']): ?>
                    <div class="brand_goods_in">
                <img src="<?php echo $this->_var['option']['cdn_path']; ?>brandlogo/<?php echo $this->_var['goods']['brand_logo']; ?>" alt="<?php echo $this->_var['goods']['brand_name']; ?>(<?php echo $this->_var['goods']['goods_num']; ?>)"/>
                </div>
              
            <?php else: ?>
             <div class="brand_goods_in">  </div>
            <?php endif; ?>

            <h3><?php echo $this->_var['goods']['goods_style_name']; ?></h3>
            <div class="rank_click clearfix">
                <em class="rank rank_<?php echo $this->_var['goods']['comment_rank']; ?>"></em><em><?php echo $this->_var['goods']['comment_count']; ?> đánh giá</em>
            </div>
            <?php if ($this->_var['goods']['price'] == 0): ?>
            <span>Giá liên hệ</span>
            <?php else: ?>
            <strong><?php if ($this->_var['goods']['promote_price']): ?><?php echo $this->_var['goods']['promote_price']; ?><del><?php echo $this->_var['goods']['shop_price']; ?></del><?php else: ?><?php echo $this->_var['goods']['shop_price']; ?><?php endif; ?></strong>
            <?php endif; ?>
            <div class="promotion">
                <?php echo $this->_var['goods']['seller_note']; ?>
            </div>
            </a>
        </li>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
<?php echo $this->fetch('library/pages_simple.lbi'); ?>
<?php else: ?>
<p class="empty"><?php echo $this->_var['lang']['goods_empty']; ?> cho danh mục <?php echo $this->_var['cat_name']; ?></p>
<?php endif; ?>