<?php

/**
 * ECSHOP Control panel authorization manage module language file
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
 * $Id: privilege.php 16320 2009-06-23 09:42:41Z sxc_shop $
*/

/* Field information */
$_LANG['user_id'] = 'ID';
$_LANG['user_name'] = 'Tên vai trò';
$_LANG['email'] = 'Email';
$_LANG['password'] = 'Mật khẩu';
$_LANG['join_time'] = 'Thời gian tham gia';
$_LANG['last_time'] = 'Thời gian đăng nhập cuối';
$_LANG['last_ip'] = 'IP truy cập cuối';
$_LANG['action_list'] = 'Quyền hạn thực hiện';
$_LANG['nav_list'] = 'Menu';
$_LANG['language'] = 'Ngôn ngữ';

$_LANG['allot_priv'] = 'Chỉ định quyền hạn';
$_LANG['allot_list'] = 'Danh sách quyền hạn';
$_LANG['go_allot_priv'] = 'Thiết lập quyền hạn quản trị';

$_LANG['view_log'] = 'Xem nhật ký';

$_LANG['back_home'] = 'Quay lại trang chủ';
$_LANG['forget_pwd'] = 'Bạn quên mật khẩu?';
$_LANG['get_new_pwd'] = 'Phục hồi mật khẩu quản trị viên';
$_LANG['pwd_confirm'] = 'Xác nhận mật khẩu';
$_LANG['new_password'] = 'Mật khẩu mới';
$_LANG['old_password'] = 'Mật khẩu cũ';
$_LANG['agency'] = 'Chi nhánh thành viên phụ trách';
$_LANG['self_nav'] = 'Menu cá nhân';
$_LANG['all_menus'] = 'Tất cả menu';
$_LANG['add_nav'] = 'Thêm';
$_LANG['remove_nav'] = 'Xóa';
$_LANG['move_up'] = 'Chuyển lên';
$_LANG['move_down'] = 'Chuyển xuống';
$_LANG['continue_add'] = 'Tiếp tục thêm quản trị viên';
$_LANG['back_list'] = 'Quay lại danh sách quản trị viên';

$_LANG['admin_edit'] = 'Chỉnh sửa quản trị viên';
$_LANG['edit_pwd'] = 'Chỉnh sửa mật khẩu';

$_LANG['back_admin_list'] = 'Quay lại danh sách vai trò';

/* Prompting message */
$_LANG['js_languages']['user_name_empty'] = 'Vui lòng nhập tên thành viên!';
$_LANG['js_languages']['password_invaild'] = 'Mật khẩu phải chứa cả ký tự chữ và chữ số, và phải nhiều hơn 6 ký tự!';
$_LANG['js_languages']['email_empty'] = 'Đại chỉ email không được để trống!';
$_LANG['js_languages']['email_error'] = 'Định dạng địa chỉ email không hợp lệ!';
$_LANG['js_languages']['password_error'] = 'Hai mật khẩu không trùng nhau. Vui lòng nhập lại!';
$_LANG['js_languages']['captcha_empty'] = 'Xin nhập mã xác nhận!';
$_LANG['action_succeed'] = 'Thực hiện thành công!';
$_LANG['edit_profile_succeed'] = 'Chỉnh sửa thông tin tài khoản thành công!';
$_LANG['edit_password_succeed'] = 'Chỉnh sửa mật khẩu thành công. Xin đăng nhập lại!';
$_LANG['user_name_exist'] = 'Quản trị viên này đã tồn tại.';
$_LANG['email_exist'] = 'Đại chỉ email này đã tồn tại.';
$_LANG['captcha_error'] = 'Mã xác nhận không hợp lệ.';
$_LANG['login_faild'] = 'Thông tin tài khoản không hợp lệ.';
$_LANG['user_name_drop'] = 'Xóa thành công!';
$_LANG['pwd_error'] = 'Mật khẩu cũ không hợp lệ!';
$_LANG['old_password_empty'] = 'Nếu bạn muốn chỉnh sửa mật khẩu, bạn phải nhập vào mật khẩu cũ!';
$_LANG['edit_admininfo_error'] = 'Bạn chỉ có thể chỉnh sửa hồ sơ của chính bạn!';
$_LANG['edit_admininfo_cannot'] = 'Bạn không có quyền hạn quản trị viên để thực hiện hành động này!';
$_LANG['edit_remove_cannot'] = 'Bạn không thể xóa quản trị viên demo!';
$_LANG['remove_self_cannot'] = 'Quản trị viên demo không thể bị xóa bỏ!';
$_LANG['remove_cannot'] = 'Bạn không có quyền hạn để xóa!';
$_LANG['remove_cannot_user'] = 'Vai trò đang sử dụng không phải quản trị viên.';

$_LANG['modif_info'] = 'Chỉnh sửa hồ sơ';
$_LANG['edit_navi'] = 'Thiết lập menu cá nhân';

/* Help */
$_LANG['password_notic'] = 'Nếu bạn muốn chỉnh sửa mật khẩu, vui lòng nhập mật khẩu cũ. Nếu để trống, mật khẩu sẽ không thay đổi.';
$_LANG['confirm_notic'] = 'Nhập lại mật khẩu quản trị viên, mật khẩu phải phù hợp.';

/* Login memu */
$_LANG['label_username'] = 'Tên thành viên:';
$_LANG['label_password'] = 'Mật khẩu:';
$_LANG['label_captcha'] = 'Mã xác nhận:';
$_LANG['click_for_another'] = 'Không nhìn rõ? Thay hình ảnh khác.';
$_LANG['signin_now'] = 'Đăng nhập';
$_LANG['remember'] = 'Ghi nhớ thông tin đăng nhập?';
?>