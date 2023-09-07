<?php

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$shipping_lang = ROOT_PATH.'languages/' .$GLOBALS['_CFG']['lang']. '/shipping/shop.php';
if (file_exists($shipping_lang))
{
    global $_LANG;
    include_once($shipping_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    $modules[$i]['code']    = 'shop';
    $modules[$i]['version'] = '1.0.0';
    $modules[$i]['desc']    = 'shop_desc';
    $modules[$i]['insure']  = false;
    $modules[$i]['cod']     = TRUE;
    $modules[$i]['author']  = 'Agency1s';
    $modules[$i]['website'] = 'https://Agency1s.com';
    $modules[$i]['configure'] = array();
    $modules[$i]['print_model'] = 2;
    $modules[$i]['print_bg'] = '';
    $modules[$i]['config_lable'] = '';
    return;
}

class shop
{

    var $configure;

    function __construct($cfg = array()){
    }

    /**
     *
     *
     * @param   float   $goods_weight
     * @param   float   $goods_amount
     * @return  decimal
     */
    function calculate($goods_weight, $goods_amount)
    {
        return 0;
    }

    /**
     *
     *
     *
     * @access  public
     * @param   string  $invoice_sn
     * @return  string
     */
    function query($invoice_sn)
    {
        return $invoice_sn;
    }
}

?>