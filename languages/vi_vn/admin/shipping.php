<?php

/**
 * ECSHOP Mangement center shipping method management language file
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
 * $Id: shipping.php 15646 2009-02-23 08:33:00Z zblikai $
*/

$_LANG['shipping_name'] = 'Tên';
$_LANG['shipping_version'] = 'Phiên bản';
$_LANG['shipping_desc'] = 'Mô tả';
$_LANG['shipping_author'] = 'Tác giả';
$_LANG['insure'] = 'Bảo hiểm';
$_LANG['support_cod'] = 'COD - Nhận hàng rồi trả tiền?';
$_LANG['shipping_area'] = 'Cấu hình khu vực';
$_LANG['shipping_print_edit'] = 'Chỉnh sửa mẫu in';
$_LANG['shipping_print_template'] = 'Thể hiện một mẫu đơn';
$_LANG['shipping_template_info'] = 'Biến mô tả mẫu đơn đặt hàng:<br/>{$shop_name}Thể hiện tên cửa hàng<br/>{$province}Cửa hàng thể hiện các địa phương tương ứng của họ<br/>{$city}Cửa hàng thể hiện thành phố trực thuộc<br/>{$shop_address}Thể hiện địa chỉ cửa hàng<br/>{$service_phone}Thể hiện số điện thoại cửa hàng<br/>{$order.order_amount}Thể hiện đơn đặt hàng<br/>{$order.region}Thể hiện khu vực người nhận<br/>{$order.tel}Thể hiện số điện thoại người nhận<br/>{$order.mobile}Thể hiện số di động người nhận<br/>{$order.zipcode}Thể hiện mã vùng người nhận<br/>{$order.address}Thể hiện địa chỉ đầy đủ của người nhận<br/>{$order.consignee}Thể hiện tên người nhận<br/>{$order.order_sn}Thể hiện mã số đơn đặt hàng';

/* Memu */
$_LANG['shipping_install'] = 'Cài đặt phương thức vận chuyển';
$_LANG['install_succeess'] = 'Phương thức vận chuyển %s cài đặt thành công!';
$_LANG['del_lable'] = 'Xóa nhãn';
$_LANG['upload_shipping_bg'] = 'Upload để in một hình ảnh duy nhất';
$_LANG['del_shipping_bg'] = 'Hủy bỏ in một hình ảnh duy nhất';
$_LANG['save_setting'] = 'Lưu cài đặt';
$_LANG['recovery_default'] = 'Khôi phục mặc định';

/* Express single-part */
$_LANG['lable_select_notice'] = '--Chọn tab để chèn--';
$_LANG['lable_box']['shop_country'] = 'Quốc gia cửa hàng';
$_LANG['lable_box']['shop_province'] = 'Tỉnh cửa hàng';
$_LANG['lable_box']['shop_city'] = 'Thành phố cửa hàng';
$_LANG['lable_box']['shop_name'] = 'Tên cửa hàng';
$_LANG['lable_box']['shop_district'] = 'Quận huyện cửa hàng';
$_LANG['lable_box']['shop_tel'] = 'Điện thoại cửa hàng';
$_LANG['lable_box']['shop_address'] = 'Địa chỉ cửa hàng';
$_LANG['lable_box']['customer_country'] = 'Quốc gia người nhận';
$_LANG['lable_box']['customer_province'] = 'Tỉnh người nhận';
$_LANG['lable_box']['customer_city'] = 'Thành phố người nhận';
$_LANG['lable_box']['customer_district'] = 'Quận huyện người nhận';
$_LANG['lable_box']['customer_tel'] = 'Điện thoại người nhận';
$_LANG['lable_box']['customer_mobel'] = 'Di động người nhận';
$_LANG['lable_box']['customer_post'] = 'Mã bưu điện người nhận';
$_LANG['lable_box']['customer_address'] = 'Địa chỉ đầy đủ người nhận';
$_LANG['lable_box']['customer_name'] = 'Tên người nhận';
$_LANG['lable_box']['year'] = 'Năm';
$_LANG['lable_box']['months'] = 'Tháng';
$_LANG['lable_box']['day'] = 'Ngày';
$_LANG['lable_box']['order_no'] = 'Mã số đơn hàng';
$_LANG['lable_box']['order_postscript'] = 'Chú thích đơn hàng';
$_LANG['lable_box']['order_best_time'] = 'Thời gian giao hàng đơn hàng';
$_LANG['lable_box']['pigeon'] = 'Chim bồ câu';
//$_LANG['lable_box']['custom_content'] = 'Custom content';

/* Tin nhắn nhắc nhở */
$_LANG['no_shipping_name'] = 'Xin lỗi, tên phương thức vận chuyển không thể để trống.';
$_LANG['no_shipping_desc'] = 'Xin lỗi, mô tả phương thức vận chuyển không thể để trống.';
$_LANG['repeat_shipping_name'] = 'Xin lỗi, phương thức vận chuyển đã tồn tại.';
$_LANG['uninstall_success'] = 'Phương thức vận chuyển %s đã gỡ bỏ thành công.';
$_LANG['add_shipping_area'] = 'Tạo mới khu vực vận chuyển cho phương thức vận chuyển';
$_LANG['no_shipping_insure'] = 'Xin lỗi, tiền bảo hiểm không thể để trống, nếu bạn không sử dụng chúng vui lòng cấu hình như là số 0.';
$_LANG['not_support_insure'] = 'Phương thức vận chuyển không hỗ trợ bảo hiểm, cấu hình chi phí bảo hiểm thất bại.';
$_LANG['invalid_insure'] = 'Số tiền bảo hiểm vận chuyển không hợp lệ.';
$_LANG['no_shipping_install'] = 'Phương thức Phân phối chưa cài đặt, tạm thời không thể chỉnh sửa mẫu';
$_LANG['edit_template_success'] = 'Mẫu đã được chỉnh sửa thành công.';

/* Mục ngôn ngữ JS */
$_LANG['js_languages']['lang_removeconfirm'] = 'Bạn có chắc bỏ cài đặt phương thức vận chuyển?';
$_LANG['js_languages']['shipping_area'] = 'Cấu hình khu vực';
$_LANG['js_languages']['upload_falid'] = 'Lỗi: loại tập tin là không đúng. Xin upload "%s" loại tập tin.';
$_LANG['js_languages']['upload_del_falid'] = 'Lỗi: Xóa thất bại!';
$_LANG['js_languages']['upload_del_confirm'] = "Mẹo: Bạn có chắc chắn xóa bảng in hình ảnh đơn?";
$_LANG['js_languages']['no_select_upload'] = "Lỗi: Bạn chưa chọn để in hình ảnh đơn. Vui lòng sử dụng nút 'Browse ...' để chọn!";
$_LANG['js_languages']['no_select_lable'] = "Chấm dứt hoạt động! Bạn đã không chọn bất kỳ nhãn.";
$_LANG['js_languages']['no_add_repeat_lable'] = "Hoạt động không thành công! Không thể thêm một nhãn đã tồn tại.";
$_LANG['js_languages']['no_select_lable_del'] = "Xóa thất bại! Bạn chưa chọn bất kỳ nhãn.";
$_LANG['js_languages']['recovery_default_suer'] = "Bạn có xác nhận rằng muốn khôi phục lại mặc định? Khôi phục mặc định sẽ hiển thị nội dung của trình cài đặt.";
?>