<?php

$lang = (!empty($_GET['lang'])) ? trim($_GET['lang']) : 'vi_vn';

if (!file_exists('../languages/' . $lang . '/calendar.php') || strrchr($lang,'.'))
{
    $lang = 'vi_vn';
}

require('../includes/config.php');
//require(dirname(dirname(__FILE__)) . '/inlcudes/config.php');
header('Content-type: application/x-javascript; charset=' . EC_CHARSET);

include_once('../languages/' . $lang . '/calendar.php');

foreach ($_LANG['calendar_lang'] AS $cal_key => $cal_data)
{
    echo 'var ' . $cal_key . " = \"" . $cal_data . "\";\r\n";
}

include_once('./calendar/calendar.js');

?>