<?php



// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'thbvietnam_1s' );

/** Username của database */
define( 'DB_USER', 'thbvietnam_1s' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', 'thbvietnam_1s' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '$Uq5Wp- z[pk`D)&{CF7A=]9f;TK]D}}[@nvCSEQJ_36&OaGn:ccTjH^!eo:J>oF' );
define( 'SECURE_AUTH_KEY',  '/<fIN&_@o626{Wv!z?3rW~RhUSM~$955G_}>e(yh.g|F/(T0#u{P>=T|Z!q5-!}t' );
define( 'LOGGED_IN_KEY',    'v}a@Rg(7lrFq2Uf6Vv6!rl#G9<LH&<8`h3B`s/->Q3r8|!y$HD~C5Iyu,0x+F(F0' );
define( 'NONCE_KEY',        '/ug_[66 c}>E7Bl+,SP/eg/-/`<a~`<rEZTBrA[Zl[&|5L,qF+RNv97Zv3MuHA7$' );
define( 'AUTH_SALT',        '}B<YwA)!&*R[D/K&9I@`vwV.[)LLfrp>=*%{ N&1X;i*-3X0-/ER79d|_ED*.ir[' );
define( 'SECURE_AUTH_SALT', 'pq=SuXab3,9I6/j6y<d.sA9wIcv0vo~xUl{W/9.+Go4W5.W]AvcZe>2Z::z)5#!g' );
define( 'LOGGED_IN_SALT',   'SK`M[7HME].p,)G`SC!>X0`I.fr9(>Ne9&h56^sK4RHY2;%erG__$:}KM;-ei6x?' );
define( 'NONCE_SALT',       'm;,u*b~NAXUUpMR&PNu=(k2Mcgecw7x:Xk)-?v9lntJ6p&DBB+u:gA|c:nb&j@6s' );

/**#@-*/



$table_prefix = 'wp_';



 define('WP_ADMIN_DIR', 'indexing');

define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . WP_ADMIN_DIR);
define('WP_DEBUG', false);

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'index-settings.php');
