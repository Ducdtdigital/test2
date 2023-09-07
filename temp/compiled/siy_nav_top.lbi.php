<?php if ($this->_var['nav']): ?>
     <div class="topbar-nav">
     <p style="float:left;margin-right: 5px;">Tìm nhanh: </p>
	<?php $_from = $this->_var['nav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navi');$this->_foreach['navigation'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['navigation']['total'] > 0):
    foreach ($_from AS $this->_var['navi']):
        $this->_foreach['navigation']['iteration']++;
?>
		<a href="<?php echo $this->_var['navi']['url']; ?>" <?php if ($this->_var['navi']['opennew']): ?> target="_blank"<?php endif; ?>><span><?php echo $this->_var['navi']['name']; ?></span></a>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
<?php endif; ?>