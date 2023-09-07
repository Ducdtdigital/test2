<?php if ($this->_var['pager']): ?>
<?php if ($this->_var['pager']['styleid'] == 0): ?>
    <?php if ($this->_var['pager']['record_count'] > 3): ?>
        <form name="selectPageForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <p class="pagination">
            <a href="<?php echo $this->_var['pager']['page_first']; ?>" class="first">«</a>
            <a href="<?php echo $this->_var['pager']['page_prev']; ?>" class="prev">‹</a>
            <?php if ($this->_var['comments'] || $this->_var['notes']): ?><?php else: ?>
            <select name="page" id="page" onchange="selectPage(this)">
            <?php echo $this->html_options(array('options'=>$this->_var['pager']['array'],'selected'=>$this->_var['pager']['page'])); ?>
            </select><?php endif; ?>
            <a href="<?php echo $this->_var['pager']['page_next']; ?>" class="next">›</a>
            <a href="<?php echo $this->_var['pager']['page_last']; ?>" class="last">»</a>
            <?php $_from = $this->_var['pager']['search']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
            <?php if ($this->_var['key'] == 'keywords'): ?>
            <input type="hidden" name="<?php echo $this->_var['key']; ?>" value="<?php echo urldecode($this->_var['item']); ?>" />
            <?php else: ?>
            <input type="hidden" name="<?php echo $this->_var['key']; ?>" value="<?php echo $this->_var['item']; ?>" />
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </p>
        </form>
        <script type="text/javascript">
        function selectPage(sel){sel.form.submit();}
        </script>
    <?php endif; ?>
<?php else: ?>
<p class="pagination">
	<?php if ($this->_var['pager']['page_first']): ?><a href="<?php echo $this->_var['pager']['page_first']; ?>" class="first"><?php echo $this->_var['lang']['page_first']; ?></a><span class="gap">...</span><?php endif; ?>
	<?php if ($this->_var['pager']['page_prev']): ?><a href="<?php echo $this->_var['pager']['page_prev']; ?>" class="prev"><?php echo $this->_var['lang']['page_prev']; ?></a><?php endif; ?>
	<?php if ($this->_var['pager']['page_count'] != 1): ?>
	<?php $_from = $this->_var['pager']['page_number']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
        <?php if ($this->_var['pager']['page'] == $this->_var['key']): ?>
	   <a class="current"><?php echo $this->_var['key']; ?></a>
       <?php else: ?>
       <a href="<?php echo $this->_var['item']; ?>"><?php echo $this->_var['key']; ?></a>
       <?php endif; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endif; ?>
	<?php if ($this->_var['pager']['page_next']): ?><a href="<?php echo $this->_var['pager']['page_next']; ?>" class="next"><?php echo $this->_var['lang']['page_next']; ?></a><?php endif; ?>
	<?php if ($this->_var['pager']['page_last']): ?><span class="gap">...</span><a href="<?php echo $this->_var['pager']['page_last']; ?>" class="last"><?php echo $this->_var['lang']['page_last']; ?></a><?php endif; ?>
</p>
<?php endif; ?>
<?php endif; ?>
