<?php 
$k = array (
  'name' => 'history',
  'number' => '4',
);
$plugin = 'siy_'.$k['name'];
if (function_exists($plugin)) {
   echo $plugin($k);
} else {
   echo "<!-- error: ".$k['name']." not installed -->";
}
?>