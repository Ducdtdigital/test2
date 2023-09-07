<?php if ($this->_var['nav']): ?>
<div class="txtbanner">
    <?php $_from = $this->_var['nav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navi_0_83025200_1694102530');$this->_foreach['navigation'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['navigation']['total'] > 0):
    foreach ($_from AS $this->_var['navi_0_83025200_1694102530']):
        $this->_foreach['navigation']['iteration']++;
?>
    <a class="<?php if ($this->_var['navi_0_83025200_1694102530']['active']): ?>current<?php endif; ?>" href="<?php echo $this->_var['navi_0_83025200_1694102530']['url']; ?>" <?php if ($this->_var['navi_0_83025200_1694102530']['opennew']): ?> target="_blank"<?php endif; ?> title="<?php echo $this->_var['navi_0_83025200_1694102530']['title']; ?>"><?php echo $this->_var['navi_0_83025200_1694102530']['name']; ?></a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>