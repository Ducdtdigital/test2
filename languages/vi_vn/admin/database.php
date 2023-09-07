<?php

/**
 * ECSHOP
 * ============================================================================
 * All right reserved (C) 2005-2007 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it means that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: sxc_shop $
 * $Id: database.php 16320 2009-06-23 09:42:41Z sxc_shop $
*/

$_LANG['db_manage'] ='Quản lý Cơ sở dữ liệu';
$_LANG['start_backup'] ='Bắt đầu sao lưu';
$_LANG['backup_name'] ='Tên sao lưu';
$_LANG['backup_time'] ='Thời gian sao lưu';
$_LANG['backup_size'] ='Dung lượng sao lưu';
$_LANG['restore'] ='Phục hồi sao lưu';
$_LANG['restore_ok'] ='Phục hồi thành công.';
$_LANG['download'] ='Tải xuống';
$_LANG['restored'] ='Bản sao đã được phục hồi.';
$_LANG['upload_sql'] ='Tải lên file sao lưu';

$_LANG['table'] ='Bảng Dữ liệu';
$_LANG['type'] ='Loại';
$_LANG['rec_num'] ='Số lượng truy vấn';
$_LANG['rec_size'] ='Dữ liệu';
$_LANG['rec_chip'] ='Phân mảnh';
$_LANG['start_optimize'] ='Thực hiện tối ưu hóa.';
$_LANG['chip_count'] ='Tổng số lượng phân mảnh';
$_LANG['charset'] ='Bảng mã ký tự';
$_LANG['status'] ='Trạng thái';

$_LANG['backup_type'] ='Loại sao lưu';
$_LANG['full_backup'] ='Sao lưu toàn bộ';
$_LANG['full_backup_note'] ='Sao lưu tất cả bảng dữ liệu';
$_LANG['stand_backup'] ='Sao lưu thông dụng (được đề nghị)';
$_LANG['stand_backup_note'] ='Sao lưu trong nhóm dữ liệu sử dụng phổ biến';
$_LANG['min_backup'] ='Sao lưu đơn giản';
$_LANG['min_backup_note'] ='Chỉ bao gồm các bảng sản phẩm, bảng thứ tự, bảng khách hàng.';
$_LANG['custom_backup'] ='Sao lưu tùy chọn';
$_LANG['custom_backup_note'] ='Tự chọn các thông số kỹ thuật sao lưu của mình.';

$_LANG['option'] ='Các tùy chọn khác';
$_LANG['ext_insert'] ='Sử dụng chế độ mở rộng để chèn (Insert Extended).';
$_LANG['is_pack'] ='Đóng gói dữ liệu sao lưu?';
$_LANG['notice_is_pack'] ='Đóng gói có thể giảm kích thước sao lưu, nhưng bạn phải giải nén sao lưu trước khi tải lên khi khôi phục.';
$_LANG['vol_size'] ='Kích cỡ gói - Các giới hạn kích thước file (KB).';
$_LANG['sql_name'] ='Tập tin sao lưu';
$_LANG['backup_failure'] ='Sao lưu bị lỗi';

$_LANG['sqlfile'] ='Vị trí file SQL';
$_LANG['update_table_pre'] ='Thay đổi tiền tố bảng';
$_LANG['old_table_pre'] ='Tại tiền tố bảng đầu tiên';
$_LANG['new_table_pre'] ='Tiền tố bảng mới';
$_LANG['use_new_pre'] ='Sử dụng tiền tố bảng mới.';
$_LANG['notice_use_new_pre'] ='Chỉ có chỉ có thể chọn trong khi phục hồi tất cả các bản sao lưu "Yes", nếu không nó sẽ không thể được sử dụng bảng mà không cần sao lưu. <Br/>Bạn cũng có thể sửa đổi trong data/config.php trong $prefix để quyết định sử dụng tiền tố.';
$_LANG['upload_and_exe'] = 'Tải lên file sql và thực hiện.';

/* Prompting message */
$_LANG['fail_get_tables'] ='Các thông số kỹ thuật sao lưu bị lỗi.';
$_LANG['fail_open_file'] ='Mở tập tin thất bại.';
$_LANG['fail_remove'] ='Xóa tập tin thất bại.';
$_LANG['fail_get_content'] ='Nhận các thông số kỹ thuật nội dung thất bại.';
$_LANG['fail_upload'] ='Tải lên tập tin thất bại.';
$_LANG['fail_upload_move'] ='Tải lên để di chuyển tập tin thất bại.';
$_LANG['unrecognize_version'] ='Không thể xác định phiên bản của ECShop sao lưu sql.';
$_LANG['unrecognize_mysql_version'] ='Không thể xác định phiên bản của MySQL sao lưu sql.';
$_LANG['mysql_version_error'] ='Hiện tại phiên bản MySQL của %s và phiên bản MySQL của dữ liệu sao lưu %s là khác nhau, có thể xuất hiện một vấn đề, bạn có chắc nhập nó?';
$_LANG['confirm_ver'] = 'Có, hãy nhập nó!';
$_LANG['unconfirm_ver'] = 'Huỷ!';
$_LANG['version_error'] ='ECShop hiện tại phiên bản %s và dữ liệu của các phiên bản sao lưu %s là khác nhau, các sao lưu khôi phục lại sự thất bại.';
$_LANG['not_sql_file'] ='Đây không phải là tập tin sql, nếu tập tin là đúng, hãy thay đổi phần mở rộng tên tập tin là .sql';
$_LANG['sqlfile_error'] ='Bạn tải tập tin thất bại, khôi phục lại sao lưu bị thất bại.';
$_LANG['restore_success'] ='Khôi phục thành công.';
$_LANG['fail_optimize'] ='Tối ưu hóa datasheet %s thất bại.';
$_LANG['optimize_ok'] ='Tối ưu hóa thành công, làm sạch phân mảnh hoàn toàn mất %d.';
$_LANG['restore_confirm'] ='Khôi phục cơ sở dữ liệu sẽ xóa tất cả nội dung hiện có, bạn có chắc phục hồi nó?';
$_LANG['fail_import'] ='Không có dữ liệu nhập.';
$_LANG['no_file'] ='Các tập tin là không tồn tại.';
$_LANG['not_support_zip_format'] ='Máy chủ không hỗ trợ định dạng zip, hãy giải nén tập tin và tải lại.';

/* js */
$_LANG['js_languages']['remove_confirm'] ='Bạn có chắc xóa các sao lưu?';
$_LANG['js_languages']['lang_remove'] ='Xóa';
$_LANG['js_languages']['lang_restore'] ='Khôi phục sao lưu';
$_LANG['js_languages']['lang_download'] ='Tải về';
$_LANG['js_languages']['sql_name_not_null'] ='Tên tập tin không thể để trống.';
$_LANG['js_languages']['vol_size_not_null'] ='Xin nhập vào kích thước sao lưu.';

/* buckup center */
$_LANG['backup_title'] = 'Số phần %s của sao lưu được khởi tạo, Chúng đang chạy hoàn toàn tự động.';
$_LANG['backup_notice'] = 'Vui lòng click vào đây ,nếu trình duyệt của bạn không chuyển hướng tự động.';
$_LANG['backup_success'] = 'Thành công';

$_LANG['name'] = 'Tên file';
$_LANG['ver'] = 'Phiên bản';
$_LANG['add_time'] = 'Thời gian';
$_LANG['file_size'] = 'Kích thước';
$_LANG['empty_upload'] = 'Bạn tải lên một tập tin rỗng';
$_LANG['fail_write_file'] = 'File %s không thể ghi';
$_LANG['vol'] = 'Khối lượng';
$_LANG['import'] = 'Import';
$_LANG['server_sql'] = 'Server lưu trữ';
$_LANG['submit_remove'] = 'Xóa';
$_LANG['remove_success'] = 'Xóa thành công';
$_LANG['confirm_import'] = 'Bạn có muốn nhập vào bảng sao lưu khác?';
$_LANG['also_continue'] = 'Yes, Tôi muốn thực hiện';

$_LANG['dir_priv'] = 'Có vấn đề với thư mục %s :';
$_LANG['dir_not_exist'] = 'Thư mục %s không tồn tại, vui lòng tạo chúng.';
$_LANG['cannot_read'] = 'Không thể đọc';
$_LANG['cannot_write'] = 'Không thể ghi';
$_LANG['cannot_add'] = 'Không thể thêm dữ liệu';
$_LANG['cannot_modify'] = 'Không thể chỉnh sửa';

$_LANG['confirm_remove'] = 'Bạn có chắc bạn muốn xóa các dữ liệu được lựa chọn?';

?>