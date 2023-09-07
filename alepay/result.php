<?php

define('IN_ECS', true);
define('IS_AJAX', true);

require __DIR__. '/../includes/init.php';

show_message('Chúng tôi sẽ kiểm tra giao dịch trả góp và thông báo sớm tình trạng cho bạn.', $_LANG['back_home'], './', 'warning');
exit;

 ?>