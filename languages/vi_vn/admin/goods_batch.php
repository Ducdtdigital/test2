<?php

/**
 * ECSHOP Batch upload products,modification language item
 * ============================================================================
 * All right reserved (C) 2005-2007 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it mean that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: zblikai $
 * $Id: goods_batch.php 15646 2009-02-23 08:33:00Z zblikai $
 */

$_LANG['select_method'] ='Phương thức sản phẩm:';
$_LANG['by_cat'] ='Theo Danh mục, Thương hiệu.';
$_LANG['by_sn'] ='Theo mã hàng';
$_LANG['select_cat'] ='Danh mục:';
$_LANG['select_brand'] ='Thương hiệu:';
$_LANG['goods_list'] ='Danh sách:';
$_LANG['src_list'] ='Chọn sản phẩm:';
$_LANG['dest_list'] ='Sản phẩm đã chọn:';
$_LANG['input_sn'] ='Nhập mã hàng:<Br/>(một mã hàng trên một dòng)';
$_LANG['edit_method'] ='Phương thức:';
$_LANG['edit_each'] ='Sửa từng cái một';
$_LANG['edit_all'] ='Sửa toàn bộ';
$_LANG['go_edit'] ='Enter';

$_LANG['notice_edit'] = 'Giá thành viên bằng -1 thể hiện giá thành viên sẽ giảm theo cấp bậc của thành viên, có thể cài đặt tỷ lệ của từng cấp bậc khác nhau';

$_LANG['goods_class'] = 'Loại sản phẩm';
$_LANG['g_class'][G_REAL] = 'Sản phẩm thực';
$_LANG['g_class'][G_CARD] = 'Thẻ thành viên';

$_LANG['goods_sn'] = 'Mã hàng';
$_LANG['goods_name'] ='Tên sản phẩm';
$_LANG['market_price'] ='Giá thị trường';
$_LANG['shop_price'] ='Giá cửa hàng';
$_LANG['integral'] = 'Điểm dùng mua';
$_LANG['give_integral'] = 'Tặng điểm thưởng';
$_LANG['goods_number'] ='Số lượng';
$_LANG['brand'] ='Thương hiệu';

$_LANG['export_format'] = 'Định dạng dữ liệu';
$_LANG['export_ecshop'] = 'Hỗ trợ các định dạng dữ liệu ecshop';
$_LANG['export_taobao'] = 'Hỗ trợ các định dạng dữ liệu Taobao Assistant';
$_LANG['export_paipai'] = 'Hỗ trợ các định dạng dữ liệu paipai Assistant';
$_LANG['export_paipai3'] = 'Hỗ trợ các định dạng dữ liệu paipai 3.0 Assistant';
$_LANG['batch_edit_ok'] ='Chỉnh sửa hàng loạt thành công.';

$_LANG['goods_cat'] ='Danh mục:';
$_LANG['csv_file'] ='Tải lên file csv:';
$_LANG['notice_file'] ='(Số lượng sản phẩm trong file CSV tốt nhất ít hơn 1000 sản phẩm, file CSV nên có kích thước nhỏ hơn 500K)';
$_LANG['file_charset'] ='Bảng mã:';
$_LANG['download_file'] ='Tải về file CSV (%s).';
$_LANG['use_help'] ='Trợ giúp:' .
        '<ol>' .
          '<Li>Theo thói quen sử dụng, tải về file csv với ngôn ngữ tương ứng, ví dụ đang dùng tiếng Việt thì tải về file tiếng Việt, tiếng Anh thì tải về file tiếng Anh;</li>' .
          '<Li>Điền vào các file csv, có thể sử dụng excel hoặc notapad để mở file csv;<br />' .
              'Nếu "Sản phẩm xuất sắc" và tương tự, điền vào chữ số 0 hay 1, 0 có nghĩa là "KHÔNG", 1 có nghĩa là "CÓ";<br />' .
              'Xin điền vào tên tập tin với đường dẫn cho hình ảnh sản phẩm và thu nhỏ, đường dẫn này là đường dẫn tương đối [thư mục gốc]/images/, ví dụ, đường dẫn ảnh là [thư mục gốc]/images/200610/abc.jpg, điền vào 200610/abc.jpg;<br />'.
              '<font style="color:#FE596A;">Nếu định dạng mã hóa cvs là Taobao Assistant, đảm bảo rằng các mã trên trang web, nếu mã không chính xác, bạn có thể sử dụng phần mềm chuyển mã chỉnh sửa.</font></li>' .
			  '<Li>Tải lên hình ảnh sản phẩm và thu nhỏ vào tương ứng với thư mục, ví dụ: [thư mục gốc]/ images/200610/;</li>' .
              '<font style="color:#FE596A;">Xin tải lên hình ảnh và hình thu nhỏ hàng hoá trước rồi tải lên file csv sau,nếu không hình ảnh không được xử lý.</font></li>' .
          '<Li>Chọn danh mục và bảng mã , tải lên file csv.</li>'.
        '</ol>';

$_LANG['js_languages']['please_select_goods'] ='Xin chọn sản phẩm.';
$_LANG['js_languages']['please_input_sn'] ='Xin nhập mã hàng.';
$_LANG['js_languages']['goods_cat_not_leaf'] ='Xin chọn danh mục lớp dưới.';
$_LANG['js_languages']['please_select_cat'] ='Xin chọn danh mục.';
$_LANG['js_languages']['please_upload_file'] ='Xin vui lòng tải lên file csv.';

// Batch tải lên trường của sản phẩm
$_LANG['upload_goods']['goods_name'] ='Tên sản phẩm';
$_LANG['upload_goods']['goods_sn'] ='Mã hàng';
$_LANG['upload_goods']['brand_name'] ='Thương hiệu';   // Need to be convert brand_id
$_LANG['upload_goods']['market_price'] ='Giá thị trường';
$_LANG['upload_goods']['shop_price'] ='Giá cửa hàng';
$_LANG['upload_goods']['integral'] ='Số điểm giới hạn dùng mua';
$_LANG['upload_goods']['original_img'] ='Hình gốc';
$_LANG['upload_goods']['goods_img'] ='Hình ảnh';
$_LANG['upload_goods']['goods_thumb'] ='Hình thu nhỏ';
$_LANG['upload_goods']['keywords'] ='Từ khóa';
$_LANG['upload_goods']['goods_brief'] ='Mô tả ngắn';
$_LANG['upload_goods']['goods_desc'] ='Chi tiết';
$_LANG['upload_goods']['goods_weight'] ='Trọng lượng(kg)';
$_LANG['upload_goods']['goods_number'] ='Số lượng trong kho';
$_LANG['upload_goods']['warn_number'] ='Số lượng cảnh báo trong kho';
$_LANG['upload_goods']['is_best'] ='Tốt nhất';
$_LANG['upload_goods']['is_new'] ='Mới nhất';
$_LANG['upload_goods']['is_hot'] ='Ưa chuộng';
$_LANG['upload_goods']['is_on_sale'] ='Đang bán';
$_LANG['upload_goods']['is_alone_sale'] ='Có thể bán như một sản phẩm thông thường?';
$_LANG['upload_goods']['is_real'] ='Phụ kiện';

$_LANG['batch_upload_ok'] ='Tải lên hàng loạt thành công.';
$_LANG['goods_upload_confirm']='Xác nhận tải lên hàng loạt.';
?>