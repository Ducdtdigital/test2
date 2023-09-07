
<?php if ($this->_var['article_categories']): ?>
    <ul>
            <?php $_from = $this->_var['article_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['categories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categories']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['categories']['iteration']++;
?>
            <li <?php if ($this->_var['cat']['children']): ?>class="hasub"<?php endif; ?>>
                <a <?php if ($this->_var['cat_id'] == $this->_var['cat']['id']): ?>class="active"<?php endif; ?> href="<?php echo $this->_var['cat']['url']; ?>"><span><?php echo $this->_var['cat']['name']; ?></span></a>
                <?php if ($this->_var['cat']['children']): ?>
                <div class="sub_cat">
                    <ul>
                        <?php $_from = $this->_var['cat']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
                        <li><a href="<?php echo $this->_var['child']['url']; ?>"><span><?php echo $this->_var['child']['name']; ?></span></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <?php endif; ?>


