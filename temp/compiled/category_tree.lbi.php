<ul class="categories">
    <?php $_from = get_categories_tree(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['categories'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categories']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['categories']['iteration']++;
?>
    <li>
        <span>
            
            <a href="<?php echo $this->_var['cat']['url']; ?>"><?php echo $this->_var['cat']['name']; ?></a>
        </span>
        <?php if ($this->_var['cat']['cat_id']): ?>
        <ul class="sub_cat">
        <?php if ($this->_var['cat']['nav_data']): ?>
            <ul class="brand_cat_mn">
            <span>Nổi bật</span>
                
            <?php $_from = $this->_var['cat']['nav_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_78276800_1694102530');if (count($_from)):
    foreach ($_from AS $this->_var['nav_0_78276800_1694102530']):
?>
                <li class="brand_catli"><a href="<?php echo $this->_var['nav_0_78276800_1694102530']['url']; ?>" <?php if ($this->_var['nav_0_78276800_1694102530']['opennew'] == '1'): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav_0_78276800_1694102530']['name']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php endif; ?>
            <?php $this->assign('group_count','0'); ?>
            <?php $_from = $this->_var['cat']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['child_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child_cat']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['child_cat']['iteration']++;
?>
                <li>
                    <a href="<?php echo $this->_var['child']['url']; ?>" class="child_menu <?php if ($this->_var['child']['cat_id']): ?>has_sub<?php endif; ?>"><?php echo $this->_var['child']['name']; ?> <em></em></a>
                    <?php if ($this->_var['child']['cat_id']): ?>
                    <ul class="sub_cat_2">
                        <?php $_from = $this->_var['child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'childer');$this->_foreach['childer_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['childer_cat']['total'] > 0):
    foreach ($_from AS $this->_var['childer']):
        $this->_foreach['childer_cat']['iteration']++;
?>
                            <?php $this->assign('group_count',$this->_var['group_count+1']); ?>
                            <li><a href="<?php echo $this->_var['childer']['url']; ?>"><?php echo $this->_var['childer']['name']; ?></a></li>
                            <?php if ($this->_var['group_count'] >= 10): ?>
                                <?php $this->assign('group_count','0'); ?>
                                </ul>
                            </li>
                            <li class="group_menu">
                                <a href="<?php echo $this->_var['child']['url']; ?>" class="child_menu <?php if ($this->_var['child']['cat_id']): ?>has_sub<?php endif; ?>"><?php echo $this->_var['child']['name']; ?> <em></em></a>
                                <ul class="sub_cat_2">
                            <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
