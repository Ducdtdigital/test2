<?php

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
admin_priv('goods_batch');

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Ho_Chi_Minh");
require(dirname(__FILE__) . '/lib/PHPExcel.php');


if(isset($_POST['btnGui'])){
    $file = $_FILES['file']['tmp_name'];
    
    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $listWorkSheets = $objReader->listWorksheetNames($file);
    $objReader ->setLoadSheetsOnly($listWorkSheets);
    
    $objExcel = $objReader->load($file);
    $sheetData = $objExcel->getActiveSheet()->toArray('null', true,true,true);
    
    $highestRow = $objExcel->setActiveSheetIndex()->getHighestRow();
    
    //echo $highestRow;
   
    foreach($sheetData as $key => $item){
        if($key > 1){
        
            //print_r ($item);
            $excel_row_id = $item[B];
            $excel_price = str_replace(",", "", $item[D]);
            $excel_vat = $item[H]; // trạng thái VAT (Full, Yes, No)
            $excel_number = $item[I] ; //Còn hàng - Hết hàng
            $excel_date = $item[J]; // Thời gian bảo hành
            $excel_market= $excel_price + $excel_price * 0.1; // giá vat tự tính
            $excel_sort_order = $item[K]; // Sắp xếp sản phẩm
            $excel_shop_price_hcm = str_replace(",", "", $item[G]); //Giá hà Nội
            $excel_shop_price_hn = str_replace(",", "", $item[F]);; //Giá hồ chí minh
            $excel_sx = $item[L]; // xuất xứ
            
               //  update
            $sql_update = "UPDATE nb_goods SET shop_price='" . $excel_price . "' WHERE goods_sn = '". $excel_row_id  ."'";
            $db->query($sql_update);
            if($excel_vat == "Yes"){
                $sql_update = "UPDATE nb_goods SET is_vat='" . 1 . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
                
            }
            if($excel_vat == "No"){
                    $sql_update = "UPDATE nb_goods SET is_vat='" . 0 . "' WHERE goods_sn = '". $excel_row_id  ."'";
                    $db->query($sql_update);
                }
            if($excel_vat == "Full"){
                    $sql_update = "UPDATE nb_goods SET is_vat='" . 2 . "' WHERE goods_sn = '". $excel_row_id  ."'";
                    $db->query($sql_update);
                }    
           if(convert_vi_to_en($excel_number) === 'Het hang') {
                $sql_update = "UPDATE nb_goods SET goods_number='" . 0 . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
            }
            if(convert_vi_to_en($excel_number) === 'Con hang') {
                $sql_update = "UPDATE nb_goods SET goods_number='" . 99 . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
            }
            
                $sql_update = "UPDATE nb_goods SET warranty='" . $excel_date . "' WHERE goods_sn = '". $excel_row_id  ."'"; // Thời gian bảo hành
                $db->query($sql_update);
                $sql_update = "UPDATE nb_goods SET market_price='" . $excel_market . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
                $sql_update = "UPDATE nb_goods SET sort_order='" . $excel_sort_order . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
                // Cập nhật cột shop_price_hcm
                $sql_update = "UPDATE nb_goods SET shop_price_hcm='" . $excel_shop_price_hcm . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
                // Cập nhật cột shop_price_hn
                $sql_update = "UPDATE nb_goods SET shop_price_hn='" . $excel_shop_price_hn . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
                // Cập nhật cột xuất sứ
                $sql_update = "UPDATE nb_goods SET goods_sx='" . $excel_sx . "' WHERE goods_sn = '". $excel_row_id  ."'";
                $db->query($sql_update);
            // Kiểm tra xem $excel_row_id có tồn tại trong bảng nb_goods hay không
                $sql_check = "SELECT COUNT(*) as count FROM nb_goods WHERE goods_sn = '" . $excel_row_id . "'";
                $result_check = $db->query($sql_check);
                $row_check = $result_check->fetch_assoc();
                
                if($row_check['count'] == 0) {
                    // Nếu $excel_row_id không tồn tại trong bảng nb_goods, kiểm tra trong bảng nb_goods_attr
                    $sql_check = "SELECT COUNT(*) as count FROM nb_goods_attr WHERE goods_code = '" . $excel_row_id . "'";
                    $result_check = $db->query($sql_check);
                    $row_check = $result_check->fetch_assoc();
                
                    if($row_check['count'] > 0) {
                        // Nếu $excel_row_id tồn tại trong bảng nb_goods_attr, thực hiện cập nhật attr_price
                        $sql_update = "UPDATE nb_goods_attr SET attr_price='" . $excel_price . "' WHERE goods_code = '". $excel_row_id  ."'";
                        $db->query($sql_update);
                    }
                }

        }
    }
            admin_log('', 'batch_upload', 'goods');
        $link[] = array('href' => 'goods.php?act=list', 'text' => 'Xem danh sách SP');
    sys_msg('Update thành công', 0, $link);

}
    assign_query_info();
    $smarty->display('updatesp.htm');
?>

