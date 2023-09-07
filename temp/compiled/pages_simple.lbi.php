<?php if ($this->_var['pager']): ?>
<p class="pagination">
    <?php if ($this->_var['pager']['page_prev']): ?><a href="<?php echo $this->_var['pager']['page_prev']; ?>" class="prev">Trước đó</a><?php endif; ?>
    <?php if ($this->_var['pager']['page_next']): ?><a href="<?php echo $this->_var['pager']['page_next']; ?>" class="next caret_down">Xem thêm <?php if ($this->_var['viewmore_number']): ?> <?php echo $this->_var['viewmore_number']; ?> <?php echo empty($this->_var['cat_name']) ? 'kết quả' : $this->_var['cat_name']; ?><?php endif; ?> <span></span></a><?php endif; ?>
</p>
<?php endif; ?>