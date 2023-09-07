<?php

/**
 * ECSHOP Convert a program language file
 * ============================================================================
 * All right reserved (C) 2005-2007 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it means that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: zblikai $
 * $Id: convert.php 15646 2009-02-23 08:33:00Z zblikai $
 */

$_LANG['confirm_convert'] ='Chú ý: Để chuyển đổi chương trình sẽ bị mất những dữ liệu hiện có, xin vui lòng thực hiện một cách thận trọng.';
$_LANG['backup_data'] ='Nếu dữ liệu hiện tại vẫn còn có giá trị cho bạn, xin vui lòng sao lưu trước tiên.';
$_LANG['backup'] ='Sao lưu ngay.';
$_LANG['select_system'] ='Xin vui lòng lựa chọn hệ thống mà bạn muốn chuyển đổi:';
$_LANG['note_select_system'] ='(Nếu hệ thống của bạn không trong danh sách, bạn có thể đến <a href="http://www.ecshop.com" target="_ blank"> <strong> website hỗ trợ </strong></a>tìm kiếm trợ giúp).';
$_LANG['select_charset'] ='Xin vui lòng chọn danh sách ký tự mà hệ thống mà bạn có để chuyển đổi để sử dụng:';
$_LANG['note_select_charset']='(Nếu hệ thống của bạn không có ký tự UTF-8, chuyển đổi cần nhiều thời gian.)';
$_LANG['dir_notes'] ='Xin vui lòng thông báo đường dẫn thư mục gốc của cửa hàng cũ, hãy sử dụng đường đối diện của các thư mục admin.<Br/>Ví dụ: thư mục cũ cửa hàng tại thư mục gốc, nhưng ecshop đặt vào trong thư mục gốc, đó là đường dẫn ../shop.';
$_LANG['your_config'] ='Xin vui lòng cấu hình hệ thống ban đầu để cài đặt thông tin:';
$_LANG['your_host'] ='Tên máy chủ hoặc địa chỉ:';
$_LANG['your_user'] ='Tên đăng nhập:';
$_LANG['your_pass'] ='Mật khẩu:';
$_LANG['your_db'] ='Tên dữ liệu:';
$_LANG['your_prefix'] ='Tiền tố dữ liệu:';
$_LANG['your_path'] ='Thư mục gốc cửa hàng cũ:';
$_LANG['convert'] ='Chuyển đổi dữ liệu';
$_LANG['remark'] ='Chú thích:';
$_LANG['remark_info'] = '<ul>' .
        '<Li>Đối với các sản phẩm khuyến mãi, bạn cần phải chỉnh sửa giá ban đầu của nó ( bán giá của cửa hàng) và ngày khuyến mại;</li>' .
        '<Li>Vui lòng tạo lại ảnh chìm;</li>' .
        '<Li>Vui lòng tạo lại quảng cáo;</li>' .
        '<Li>Vui lòng tạo lại phương thức vận chuyển;</li>' .
        '<Li>Vui lòng tạo lại phương thức thanh toán;</li>' .
        '<li>Xin vui lòng di chuyển sản phẩm trước đây của danh mục lớp khác đến danh mục lớp cuối:</li>' .
        '</ul>';

$_LANG['connect_db_error'] ='Không thể kết nối cơ sở dữ liệu, vui lòng kiểm tra thông tin cài đặt.';
$_LANG['table_error'] ='Thiếu các mẫu thiết yếu %s, vui lòng kiểm tra thông tin cài đặt.';
$_LANG['dir_error'] ='Thiếu các thư mục cần thiết %s, vui lòng kiểm tra thông tin cài đặt.';
$_LANG['dir_not_readable'] ='Thư mục không thể đọc %s.';
$_LANG['dir_not_writable'] ='Thư mục không thể ghi %s.';
$_LANG['file_not_readable'] ='File không thể đọc %s';
$_LANG['js_languages']['check_your_db'] ='Đang kiểm tra cơ sở dữ liệu hệ thống của bạn...';
$_LANG['js_languages']['act_ok'] ='Xin chúc mừng, thực hiện thành công!';

$_LANG['js_languages']['no_system'] ='Không có chương trình chuyển đổi có sẵn.';
$_LANG['js_languages']['host_not_null'] ='Các tên host hay địa chỉ không được để trống.';
$_LANG['js_languages']['db_not_null'] ='Tên data không được để trống!';
$_LANG['js_languages']['user_not_null'] ='ID người đăng ký không được để trống!';
$_LANG['js_languages']['path_not_null'] ='Bản gốc thư mục gốc của cửa hàng không được để trống.';
?>