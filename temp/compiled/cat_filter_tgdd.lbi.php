<ul class="filter">
<?php if ($this->_var['catsubs']): ?>
    <li class="cato-list">
        <ul class="fsub clearfix fcat" >
        <?php $_from = $this->_var['catsubs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');$this->_foreach['catsub'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['catsub']['total'] > 0):
    foreach ($_from AS $this->_var['sub']):
        $this->_foreach['catsub']['iteration']++;
?>
            <li class="sub-catss <?php if ($this->_var['category'] == $this->_var['sub']['id']): ?>active_cat<?php endif; ?>">
                <div class="menucat-list">
                    <a href="<?php echo $this->_var['sub']['url']; ?>"><?php echo $this->_var['sub']['name']; ?></a>
                    <?php if ($this->_var['sub']['cat_id']): ?>
                        <span class="dropdown-icon">▼</span>
                    </div>
                    <ul>
                        <?php $_from = $this->_var['sub']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_66250400_1694102532');$this->_foreach['child_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['child_cat']['total'] > 0):
    foreach ($_from AS $this->_var['child_0_66250400_1694102532']):
        $this->_foreach['child_cat']['iteration']++;
?>
                            <li class="<?php if ($this->_var['category'] == $this->_var['child_0_66250400_1694102532']['id']): ?>active_sub<?php endif; ?>">
                                <a style="margin-left: 10px;" href="<?php echo $this->_var['child_0_66250400_1694102532']['url']; ?>"><span><?php if ($this->_var['category'] == $this->_var['child_0_66250400_1694102532']['id']): ?>✓<?php endif; ?></span> <?php echo $this->_var['child_0_66250400_1694102532']['name']; ?> <em></em></a>
                                <?php if ($this->_var['child_0_66250400_1694102532']['cat_id']): ?>
                                    <ul class="sub_cat">
                                        <?php $_from = $this->_var['child_0_66250400_1694102532']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'childer_0_66255400_1694102532');$this->_foreach['childer_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['childer_cat']['total'] > 0):
    foreach ($_from AS $this->_var['childer_0_66255400_1694102532']):
        $this->_foreach['childer_cat']['iteration']++;
?>
                                            <li><a href="<?php echo $this->_var['childer_0_66255400_1694102532']['url']; ?>"><?php echo $this->_var['childer_0_66255400_1694102532']['name']; ?></a></li>
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
    </li>
<?php endif; ?>



    
 <?php if ($this->_var['brands']['1']): ?>
    <li class="brand_cat">
        <span class="criteria">Hãng</span>
         <div class="fsub fbrand clearfix ">
        <?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('i', 'brand');$this->_foreach['brands'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brands']['total'] > 0):
    foreach ($_from AS $this->_var['i'] => $this->_var['brand']):
        $this->_foreach['brands']['iteration']++;
?>
           <a rel="dofolow" href="<?php echo $this->_var['brand']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['brand']['brand_name']); ?>"><span><?php if ($this->_var['brand']['selected']): ?>✓<?php endif; ?></span> <?php echo $this->_var['brand']['brand_name']; ?></a>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </li>
    <?php endif; ?>

    <?php if ($this->_var['price_grade']['1']): ?>
    <li class="ss-price">
        <span class="criteria">Mức giá</span>
        <div class="fsub fprice clearfix ">
            <?php $_from = $this->_var['price_grade']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('i', 'grade');$this->_foreach['price_grade'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['price_grade']['total'] > 0):
    foreach ($_from AS $this->_var['i'] => $this->_var['grade']):
        $this->_foreach['price_grade']['iteration']++;
?>
           <a rel="noffolow" href="<?php echo $this->_var['grade']['url']; ?>"><span><?php if ($this->_var['grade']['selected']): ?>✓<?php endif; ?></span> <?php echo $this->_var['grade']['price_range']; ?></a>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </li>
    <?php endif; ?>
    <?php if ($this->_var['filter_attr_list']): ?>
        <?php $_from = $this->_var['filter_attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'filter_attr_0_66265200_1694102532');$this->_foreach['filter_attr_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['filter_attr_list']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['filter_attr_0_66265200_1694102532']):
        $this->_foreach['filter_attr_list']['iteration']++;
?>
         <li class="filter_cat">
            <span class="criteria"> <?php echo htmlspecialchars($this->_var['filter_attr_0_66265200_1694102532']['filter_attr_name']); ?></span>
            <div class="fsub clearfix">
                <?php $_from = $this->_var['filter_attr_0_66265200_1694102532']['attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('i', 'attr');$this->_foreach['filter_attr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['filter_attr']['total'] > 0):
    foreach ($_from AS $this->_var['i'] => $this->_var['attr']):
        $this->_foreach['filter_attr']['iteration']++;
?>
                <a rel="noffolow" href="<?php echo $this->_var['attr']['url']; ?>"><span><?php if ($this->_var['attr']['selected']): ?>✓<?php endif; ?></span> <?php echo $this->_var['attr']['attr_value']; ?></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
             </div>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
     <?php endif; ?>

</ul>