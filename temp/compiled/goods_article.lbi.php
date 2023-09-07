<?php if ($this->_var['goods_article_list']): ?>
<div class="articles_cat">
        <h2><?php echo $this->_var['lang']['article_releate']; ?></h2>
        <ul>
            <?php $_from = $this->_var['goods_article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['goods_article_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_article_list']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['goods_article_list']['iteration']++;
?>
            <li>
                <a href="<?php echo $this->_var['article']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['article']['title']); ?>">
                    <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['article']['article_sthumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['article']['title']); ?>">
                    <h3><?php echo htmlspecialchars($this->_var['article']['title']); ?></h3>
                    <span><?php echo $this->_var['article']['add_time']; ?> - Lượt xem: <?php echo $this->_var['article']['viewed']; ?></span>
                </a>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</div>
<?php endif; ?>