
<div class="comment_box clearfix" id="comment">
    <div class="clearfix">
    	<h4><span class="_countcm"><?php 
$k = array (
  'name' => 'comment_count',
  'id' => $this->_var['id'],
  'type' => $this->_var['type'],
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?></span> <?php echo $this->_var['lang']['goods_comment']; ?> sản phẩm này</h4>
    	<span id="btnrank" hidden>Gửi đánh giá của bạn</span>
    </div>
    <div id="comment_wrapper"><?php 
$k = array (
  'name' => 'comments',
  'type' => $this->_var['type'],
  'id' => $this->_var['id'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?></div>
</div>