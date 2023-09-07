<div class="gallery" id="gallery">
    <div id="showimb">
        <?php if ($this->_var['pictures']['1']['img_url']): ?>
            <a data-options="hint: off;zoomMode: off;selectorTrigger: hover;" class="cover MagicZoomPlus" id="Zoomer" href="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['pictures']['0']['img_url']; ?>" title="<?php echo $this->_var['goods']['goods_name']; ?>">
                <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['pictures']['0']['img_url']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"/>
            </a>
        <?php else: ?>
            <span class="cover"><img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['goods']['goods_img']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" title="<?php echo $this->_var['goods']['goods_name']; ?>"/></span>
        <?php endif; ?>
    </div>
    <?php if ($this->_var['pictures']['1']['img_url']): ?>
    <div class="thumb clearfix">
        <div class="thumb_inner">
            <ul id="attr_thumb">
                <?php $_from = $this->_var['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'picture');$this->_foreach['pictures'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pictures']['total'] > 0):
    foreach ($_from AS $this->_var['picture']):
        $this->_foreach['pictures']['iteration']++;
?>
                <li>
                    <a href="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['picture']['img_url']; ?>" data-zoom-id="Zoomer" data-image="<?php echo $this->_var['option']['cdn1_path']; ?><?php echo $this->_var['picture']['img_url']; ?>" title="<?php echo $this->_var['picture']['img_desc']; ?>">
                        <img src="<?php echo $this->_var['option']['cdn1_path']; ?><?php if ($this->_var['picture']['thumb_url']): ?><?php echo $this->_var['picture']['thumb_url']; ?><?php else: ?><?php echo $this->_var['picture']['img_url']; ?><?php endif; ?>" alt="<?php echo $this->_var['picture']['img_desc']; ?>">
                    </a>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
</div>