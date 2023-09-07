<form action="javascript:;" onsubmit="submitComment(this)" method="post" class="clearfix">
	<div class="star-rank">
        <span>Chọn đánh giá của bạn</span>
        <input type="radio" name="comment_rank" value="1" tabindex="6" class="radio rank_star" title="<?php echo $this->_var['lang']['comment_rank_1']; ?>"/>
        <input type="radio" name="comment_rank" value="2" tabindex="5" class="radio rank_star" title="<?php echo $this->_var['lang']['comment_rank_2']; ?>"/>
        <input type="radio" name="comment_rank" value="3" tabindex="4" class="radio rank_star" title="<?php echo $this->_var['lang']['comment_rank_3']; ?>"/>
        <input type="radio" name="comment_rank" value="4" tabindex="3" class="radio rank_star" title="<?php echo $this->_var['lang']['comment_rank_4']; ?>"/>
        <input type="radio" name="comment_rank" value="5" checked="checked" class="radio rank_star" title="<?php echo $this->_var['lang']['comment_rank_5']; ?>"/>
        <em id="star_tip"></em>
    </div>
	<div class="action-comment clearfix">
        <textarea name="content" id="cmcontent" rows="2" cols="20" required min-lenght="60" max-lenght="500" placeholder="Nhập đánh giá về dịch vụ, ít nhất 80 ký tự"></textarea>
		<div class="col-input">
		<input type="text" name="user_name" class="input-text"  required placeholder="Tên (Bắt buộc)">
		<input type="tel" name="telephone" style="width:160px" required class="input-text"  placeholder="Số điện thoại (tùy chọn)">
		<?php if ($this->_var['enabled_captcha']): ?>
        <input type="text" style="width:80px" placeholder="Mã BV" class="input-text" name="captcha" required autocomplete="off">
        <img src="<?php echo $this->_var['option']['static_path']; ?>ajax/captcha.php?<?php echo $this->_var['rand']; ?>" alt="<?php echo $this->_var['lang']['comment_captcha']; ?>" class="captcha tip" title="<?php echo $this->_var['lang']['captcha_tip']; ?>" onClick="this.src='<?php echo $this->_var['option']['static_path']; ?>ajax/captcha.php?'+Math.random()"/>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?php echo $this->_var['lang']['send_rank']; ?></button>
		</div>
		<div class="col-submit">
		<input type="hidden" name="cmt_type" value="<?php echo $this->_var['comment_type']; ?>">
		<input type="hidden" name="id" value="<?php echo $this->_var['id']; ?>">
		<input type="hidden" name="comment_rank" value="5">
		</div>
		<div class="clear"></div>
	</div>
</form>
<div class="clearfix"></div>
<?php if ($this->_var['comments']): ?>
<ul class="comment_list clearfix">
	<?php $_from = $this->_var['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');$this->_foreach['comments'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['comments']['total'] > 0):
    foreach ($_from AS $this->_var['comment']):
        $this->_foreach['comments']['iteration']++;
?>
	<li class="<?php echo $this->cycle(array('values'=>'odd,even')); ?><?php if (($this->_foreach['comments']['iteration'] <= 1)): ?> first<?php endif; ?>">
			<span class="iconcom-user"><?php echo sub_str($this->_var['comment']['username'],1); ?></span><span class="name"><?php if ($this->_var['comment']['username']): ?><?php echo htmlspecialchars($this->_var['comment']['username']); ?><?php else: ?><?php echo $this->_var['lang']['anonymous']; ?><?php endif; ?></span>
		<div class="talk">
			<p class="text"><i class="rank rank_<?php echo $this->_var['comment']['rank']; ?>"></i> <?php echo $this->_var['comment']['content']; ?></p>
			<span class="time"><?php echo $this->_var['comment']['add_time']; ?></span>
			<?php if ($this->_var['comment']['re_content']): ?>
			<blockquote class="reply"><span class="iconcom-user"><?php echo sub_str($this->_var['comment']['re_username'],1); ?></span> <span class="name"><?php echo $this->_var['comment']['re_username']; ?>  <font><?php echo $this->_var['lang']['re_name']; ?></font></span>
			<p><?php echo $this->_var['comment']['re_content']; ?></p><span class="time"><?php echo $this->_var['comment']['re_add_time']; ?></span>
			</blockquote>
			<?php endif; ?>
		</div>
	</li>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php echo $this->fetch('library/pages.lbi'); ?>
<?php endif; ?>
