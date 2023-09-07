<?php if ($this->_var['package_goods_list']): ?>
        <?php $_from = $this->_var['package_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'package_goods');$this->_foreach['package_goods_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['package_goods_list']['total'] > 0):
    foreach ($_from AS $this->_var['package_goods']):
        $this->_foreach['package_goods_list']['iteration']++;
?>
        <div class="combo clearfix">
            <h2 class="name">Mua <?php echo $this->_var['package_goods']['act_name']; ?> - Tiết kiệm hơn</h2>
            <ul class="cate normal">
                <?php $_from = $this->_var['package_goods']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_list');$this->_foreach['goods_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_list']['total'] > 0):
    foreach ($_from AS $this->_var['goods_list']):
        $this->_foreach['goods_list']['iteration']++;
?>
                <li>
                    <a href="<?php 
$k = array (
  'name' => 'build_uri',
  'app' => 'goods',
  'gid' => $this->_var['goods_list']['goods_id'],
  'append' => $this->_var['goods_list']['goods_name'],
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>">
                        <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods_list']['goods_thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods_list']['goods_name']); ?>"/>
                    <h3><?php echo $this->_var['goods_list']['goods_name']; ?><?php echo $this->_var['goods_list']['goods_attr_str']; ?></h3>
                    <strong><?php echo $this->_var['goods_list']['rank_price']; ?> x <?php echo $this->_var['goods_list']['goods_number']; ?></strong>
                    </a>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <div class="combototal">
                 <div class="combototal_wrap">
                    <p><?php echo $this->_var['lang']['package_price']; ?><strong class="price"><?php echo $this->_var['package_goods']['package_price']; ?></strong></p>
                    <del><?php echo $this->_var['package_goods']['subtotal']; ?></del>
                    <p><?php echo $this->_var['lang']['then_old_price']; ?><?php echo $this->_var['package_goods']['saving']; ?></p>
                    <div class="action">
                    <a href="javascript:addPackageToCart(<?php echo $this->_var['package_goods']['act_id']; ?>)" class="button brighter_button"><span><?php echo $this->_var['lang']['add_to_cart']; ?></span></a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <div class="clearfix"></div>
<?php endif; ?>