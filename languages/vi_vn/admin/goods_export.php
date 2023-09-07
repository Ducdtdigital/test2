<?php



/**

 * ECSHOP Vote management

 * ============================================================================

 * All right reserved (C) 2005-2007 Beijing Yi Shang Interactive Technology

 * Development Ltd.

 * Web site: http://www.ecshop.com

 * ----------------------------------------------------------------------------

 * This is a free/open source software；it means that you can modify, use and

 * republish the program code, on the premise of that your behavior is not for

 * commercial purposes.

 * ============================================================================

 * $Author: liubo $

 * $Id: goods_export.php 16308 2009-06-23 03:25:34Z liubo $

*/



$_LANG['user_guide'] =

        '<br/>Trợ giúp:' .

        '<ol>' .

          '<li>Sử dụng các tiêu chí tìm kiếm chỉ cho phép một tìm kiếm cho 50 sản phẩm.</li>' .

          '<li>Nếu người dùng yêu cầu Export loại hàng hóa nhất định theo các điều kiện lựa chọn để phân loại, không nhấp vào tìm kiếm, lựa chọn trực tiếp của các định dạng dữ liệu và mã hóa có thể được xuất.</li>' .

        '</ol>';

$_LANG['export_taobao'] = 'Hỗ trợ các định dạng xuất Taobao Assistant';

$_LANG['export_taobao_v43'] = 'Hỗ trợ các định dạng xuất Taobao AssistantV4.3';

$_LANG['good_cat'] = 'Danh mục hàng hóa';

$_LANG['select_please'] = 'Xin chọn danh mục xuất';

$_LANG['select_charset'] = 'Xin chọn bảng mã xuất';



$_LANG['goods_class'] = 'Kiểu cột ID';

$_LANG['tabobao_shipping'] = 'Phân phối Taobao';

$_LANG['post_express'] = 'Giá bình thường';

$_LANG['express'] = 'Giá cấp tốc';

$_LANG['ems'] = 'Giá EMS';

$_LANG['notice_goods_class'] = 'ID cho các phần phân loại ID Taobao nhỏ, nếu không chắc chắn, hãy điền 0';



$_LANG['post_express_not_null'] = 'Giá bình thường phải lớn hơn 0';

$_LANG['express_not_null'] = 'Giá cấp tốc phải lớn hơn 0';

$_LANG['ems_not_null'] = 'Giá EMS phải lớn hơn 0';





/* Taobao */

$_LANG['taobao']['goods_name'] = 'Tên hàng hóa';

$_LANG['taobao']['goods_class'] = 'Loại hàng hóa';

$_LANG['taobao']['shop_class'] = 'Loại cửa hàng';

$_LANG['taobao']['new_level'] = 'Cấp độ mới';

$_LANG['taobao']['province'] = 'Tỉnh';

$_LANG['taobao']['city'] = 'Thành phố';

$_LANG['taobao']['sell_type'] = 'Phương thức bán hàng';

$_LANG['taobao']['shop_price'] = 'Giá cửa hàng';

$_LANG['taobao']['add_price'] = 'Tỷ lệ tăng';

$_LANG['taobao']['goods_number'] = 'Số lượng hàng hóa';

$_LANG['taobao']['die_day'] = 'Thời hạn';

$_LANG['taobao']['load_type'] = 'Phương thức vận chuyển';

$_LANG['taobao']['post_express'] = 'Mã vùng';

$_LANG['taobao']['ems'] = 'EMS';

$_LANG['taobao']['express'] = 'Cấp tốc';

$_LANG['taobao']['pay_type'] = 'Phương thức thanh toán';

$_LANG['taobao']['allow_alipay'] = 'Cho phép';

$_LANG['taobao']['invoice'] = 'Hóa đơn';

$_LANG['taobao']['repair'] = 'Bảo hành';

$_LANG['taobao']['resend'] = 'Gởi lại';

$_LANG['taobao']['is_store'] = 'Có trong kho';

$_LANG['taobao']['window'] = 'Được giới thiệu';

$_LANG['taobao']['add_time'] = 'Đăng';

$_LANG['taobao']['story'] = 'Dự trữ';

$_LANG['taobao']['goods_desc'] = 'Mô tả';

$_LANG['taobao']['goods_img'] = 'Hình ảnh';

$_LANG['taobao']['goods_attr'] = 'Thuộc tính';

$_LANG['taobao']['group_buy'] = 'Mua theo nhóm';

$_LANG['taobao']['group_buy_num'] = 'Số lượng mua theo nhóm';

$_LANG['taobao']['template'] = 'ID giao diện';

$_LANG['taobao']['discount'] = 'Giảm giá';

$_LANG['taobao']['modify_time'] = 'Sửa đổi';

$_LANG['taobao']['upload_status'] = 'Tình trạng tải lên';

$_LANG['taobao']['img_status'] = 'Tình trạng hình';





$_LANG['export_paipai'] = 'Xuất hỗ trợ các định dạng patted Assistant';

$_LANG['paipai']['id'] = 'id';

$_LANG['paipai']['tree_node_id'] = 'cây điểm id';

$_LANG['paipai']['old_tree_node_id'] = 'cây điểm id cũ';

$_LANG['paipai']['title'] = 'Tiêu đề';

$_LANG['paipai']['id_in_web'] = 'id trên web';

$_LANG['paipai']['auctionType'] = 'Loại đấu giá';

$_LANG['paipai']['category'] = 'Danh mục';

$_LANG['paipai']['shopCategoryId'] = 'ID danh mục cửa hàng';

$_LANG['paipai']['pictURL'] = 'URL hình ảnh';

$_LANG['paipai']['quantity'] = 'Số lượng';

$_LANG['paipai']['duration'] = 'Thời gian';

$_LANG['paipai']['startDate'] = 'Thời gian bắt đầu';

$_LANG['paipai']['stuffStatus'] = 'Tình trạng công cụ';

$_LANG['paipai']['price'] = 'Giá';

$_LANG['paipai']['increment'] = 'Tăng';

$_LANG['paipai']['prov'] = 'Tỉnh';

$_LANG['paipai']['city'] = 'Thành phố';

$_LANG['paipai']['shippingOption'] = 'Tùy chọn vận chuyển';

$_LANG['paipai']['ordinaryPostFee'] = 'Phí chuyển bình thường';

$_LANG['paipai']['fastPostFee'] = 'Phí chuyển nhanh';

$_LANG['paipai']['paymentOption'] = 'Tùy chọn thanh toán';

$_LANG['paipai']['haveInvoice'] = 'Có hóa đơn';

$_LANG['paipai']['haveGuarantee'] = 'Có bảo hiểm';

$_LANG['paipai']['secureTradeAgree'] = 'Đồng ý bảo mật thương mại';

$_LANG['paipai']['autoRepost'] = 'Tự động báo cáo lại';

$_LANG['paipai']['shopWindow'] = 'Hiện cửa sổ';

$_LANG['paipai']['failed_reason'] = 'Lý do thất bại';

$_LANG['paipai']['pic_size'] = 'Kích cỡ hình';

$_LANG['paipai']['pic_filename'] = 'Tên file hình';

$_LANG['paipai']['pic'] = 'Hình ảnh';

$_LANG['paipai']['description'] = 'Mô tả';

$_LANG['paipai']['story'] = 'Lưu trữ';

$_LANG['paipai']['putStore'] = 'Nhấp lưu trữ';

$_LANG['paipai']['pic_width'] = 'Chiều ngang hình';

$_LANG['paipai']['pic_height'] = 'Chiều cao hình';

$_LANG['paipai']['skin'] = 'Mặt nạ';

$_LANG['paipai']['prop'] = 'Đặc tính';





$_LANG['export_paipai4'] = 'Export các định dạng dữ liệu patted Assistant Supporting 3.0';

$_LANG['paipai4']['id'] = 'ID';

$_LANG['paipai4']['goods_name'] = 'Tên sản phẩm';

$_LANG['paipai4']['auctionType'] = 'Phương thức bán hàng';

$_LANG['paipai4']['category'] = 'Danh mục';

$_LANG['paipai4']['shopCategoryId'] = 'Chuyên mục cửa hàng';

$_LANG['paipai4']['quantity'] = 'Số lượng';

$_LANG['paipai4']['duration'] = 'Khoảng thời gian';

$_LANG['paipai4']['startDate'] = 'Thời gian bắt đầu';

$_LANG['paipai4']['stuffStatus'] = 'Thời gian kết thúc';

$_LANG['paipai4']['price'] = 'Giá';

$_LANG['paipai4']['increment'] = 'Giá tăng';

$_LANG['paipai4']['prov'] = 'Tỉnh';

$_LANG['paipai4']['city'] = 'Thành phố';

$_LANG['paipai4']['shippingOption'] = 'Phương thức vận chuyển';

$_LANG['paipai4']['ordinaryPostFee'] = 'Phí chuyển bình thường';

$_LANG['paipai4']['fastPostFee'] = 'Phí chuyển nhanh';

$_LANG['paipai4']['buyLimit'] = 'Giới hạn mua';

$_LANG['paipai4']['paymentOption'] = 'Tùy chọn thanh toán';

$_LANG['paipai4']['haveInvoice'] = 'Hóa đơn';

$_LANG['paipai4']['haveGuarantee'] = 'Bảo hiểm';

$_LANG['paipai4']['secureTradeAgree'] = 'Đồng ý Bảo hiểm';

$_LANG['paipai4']['autoRepost'] = 'Tự động hồi báo';

$_LANG['paipai4']['failed_reason'] = 'Lý do thất bại';

$_LANG['paipai4']['pic_filename'] = 'Tên hình';

$_LANG['paipai4']['description'] = 'Mô tả';

$_LANG['paipai4']['shelfOption'] = 'Tùy chọn nhập';

$_LANG['paipai4']['skin'] = 'Đóng gói';

$_LANG['paipai4']['attr'] = 'Thuộc tính';

$_LANG['paipai4']['chengBao'] = 'chengBao';

$_LANG['paipai4']['shopWindow'] = 'Hiện cửa sổ';



// Tải lên hàng loạt lĩnh vực hàng hóa

$_LANG['export_ecshop'] = 'Xuất ra định dạng dữ liệu ECShop';

$_LANG['ecshop']['goods_name'] = 'Tên hàng hóa';

$_LANG['ecshop']['goods_sn'] = 'Mã số';

$_LANG['ecshop']['brand_name'] = 'Thương hiệu';  // Cần phải chuyển đổi brand_id

$_LANG['ecshop']['market_price'] = 'Giá thị trường';

$_LANG['ecshop']['shop_price'] = 'Giá cửa hàng';

$_LANG['ecshop']['integral'] = 'Giới hạn điểm mua';

$_LANG['ecshop']['original_img'] = 'Hình gốc sản phẩm';

$_LANG['ecshop']['goods_img'] = 'Hình sản phẩm';

$_LANG['ecshop']['goods_thumb'] = 'Hình thu nhỏ';

$_LANG['ecshop']['keywords'] = 'Từ khóa';

$_LANG['ecshop']['goods_brief'] = 'Mô tả ngắn';

$_LANG['ecshop']['goods_desc'] = 'Mô tả';

$_LANG['ecshop']['goods_weight'] = 'Trọng lượng(kg)';

$_LANG['ecshop']['goods_number'] = 'Số lượng';

$_LANG['ecshop']['warn_number'] = 'Số lượng cảnh báo';

$_LANG['ecshop']['is_best'] = 'Sản phẩm tốt nhất';

$_LANG['ecshop']['is_new'] = 'Sản phẩm mới nhất';

$_LANG['ecshop']['is_hot'] = 'Sản phẩm ưa chuộng';

$_LANG['ecshop']['is_on_sale'] = 'Sản phẩm khuyến mãi';

$_LANG['ecshop']['is_alone_sale'] = 'Sản phẩm độc lập';

$_LANG['ecshop']['is_real'] = 'Phụ kiện';



//Tùy chọn xuất định dạng dữ liệu

$_LANG['export_custom'] = 'Xuất ra định dang dữ liệu tùy chọn';

$_LANG['custom']['goods_name'] = 'Tên sản phẩm';

$_LANG['custom']['goods_sn'] = 'Mã số';

$_LANG['custom']['brand_name'] = 'Thương hiệu';

$_LANG['custom']['market_price'] = 'Giá thị trường';

$_LANG['custom']['shop_price'] = 'Giá cửa hàng';

$_LANG['custom']['integral'] = 'Giới hạn điểm mua';

$_LANG['custom']['original_img'] = 'Hình ban đầu';

$_LANG['custom']['goods_img'] = 'Hình sản phẩm';

$_LANG['custom']['goods_thumb'] = 'Hình thu nhỏ';

$_LANG['custom']['keywords'] = 'Từ khóa';

$_LANG['custom']['goods_brief'] = 'Mô tả ngắn';

$_LANG['custom']['goods_desc'] = 'Mô tả';

$_LANG['custom']['goods_weight'] = 'Trọng lượng(kg)';

$_LANG['custom']['goods_number'] = 'Số lượng';

$_LANG['custom']['warn_number'] = 'Số lượng cảnh báo';

$_LANG['custom']['is_best'] = 'Tốt nhất';

$_LANG['custom']['is_new'] = 'Mới nhất';

$_LANG['custom']['is_hot'] = 'Ưa chuộng nhất';

$_LANG['custom']['is_on_sale'] = 'Khuyến mãi';

$_LANG['custom']['is_alone_sale'] = 'Sản phẩm Độc lập';

$_LANG['custom']['is_real'] = 'Phụ kiện';



$_LANG['custom_keyword'] = 'Từ khóa';

$_LANG['custom_goods_cat'] = 'Tất cả danh mục';

$_LANG['custom_goods_brand'] = 'Tất cả thương hiệu';

$_LANG['intro_type'] =' Tất cả';

$_LANG['custom_goods_list'] = 'Chọn cột dữ liệu sản phẩm';

$_LANG['custom_goods_type'] = 'Tất cả loại hàng hóa';

$_LANG['custom_export_list'] = 'Số cột hàng hóa xuất';

$_LANG['custom_up'] = 'Lên';

$_LANG['custom_down'] = 'Xuống';

$_LANG['custom_goods_search'] = 'Điều kiện xuất';

$_LANG['custom_goods_field_not_null'] = 'Dữ liệu xuất của hàng hóa không thể rỗng';



$_LANG['custom_date_export'] = 'Ngày xuất báo giá';

$_LANG['custom_export_to'] = 'Kính gửi';

$_LANG['custom_export_address'] = 'Địa chỉ';

$_LANG['custom_export_tel'] = 'Điện thoại';

$_LANG['custom_export_email'] = 'Email';



// Điều kiện xuất

$_LANG['export_condition'] = 'Điều kiện xuất dữ liệu sản phẩm';

$_LANG['export_condition_search'] = 'Tìm kiếm';

$_LANG['export_format'] = 'Định dạng dữ liệu';



$_LANG['specifications'] = 'Thông số kỹ thuật';



?>

