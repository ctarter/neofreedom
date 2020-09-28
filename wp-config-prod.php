<?php 
/* Don't try to create this file by hand. Read the README.txt and run the installer. */
// ** MySQL settings ** //
 //Added by WP-Cache Manager
// //Added by WP-Cache Manager
define('DB_NAME', 'neoxenosorg_wpmu');    // The name of the database
define('DB_USER', 'neoxenos');     // Your MySQL username
define('DB_PASSWORD', '5opcomm01'); // ...and password
define('DB_HOST', 'mysql.neoxenos.org');    // 99% chance you won't need to change this value
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('SUBDOMAIN_INSTALL', true);
define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');
define('DOMAIN_CURRENT_SITE', 'www.neoxenos.org');
$base = '/';
// Change each KEY to a different unique phrase.  You won't have to remember the phrases later,
// so make them long and complicated.  You can visit http://api.wordpress.org/secret-key/1.1/
// to get keys generated for you, or just make something up.  Each key should have a different phrase.
define('AUTH_KEY', 'BK_0%<|W@bO)eG0t6}mo_u-<B,IV@pX3[m(w;S$-gpv<.GyK5o=;kj-JH(}|GK5I');
define('SECURE_AUTH_KEY', 'udh0tg_6}]2du}w73r,e>v*0 Pi<f2ppwz)[-=|0K%v0XA!&sip7c2/^*3/?&!+;');
define('SECURE_AUTH_SALT', 'Y*Yh=:,[8+>j$ GH{BA^}F^g]9hEpwG*<F2IW5Nz=Sed`=k9AZLS(t*{A.dH^Y{@');
define('LOGGED_IN_KEY', 'Cp}[G~M$}u}L0wjzUr4z2)n44sp3StDX|BX-yEhQoGqxxFyzRg9W|+`+R. BYeU_');
define('SECRET_KEY', 'aafd3b3d74f02b7e2abfaaa76851087264e38008f98fb9e9de8cb439c14cc003'); // Change these to unique phrases.
define('SECRET_SALT', '71c6a1a4db17668e754296a44e3c086236498f4557b1b7c16df7d02cf0e87884');
define('LOGGED_IN_SALT', '-; lXotMA-w,HK+tE@k8{mO4b?-3&lyr-jv&(&KGb$-bCDpn]|!8%+1$S.!FD;?%');
// double check $base
if( $base == 'BASE' )
	die( 'Problem in wp-config.php - $base is set to BASE when it should be the path like "/" or "/blogs/"! Please fix it!' );
// You can have multiple installations in one database if you give each a unique prefix
$table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!
// Change this to localize WordPress.  A corresponding MO file for the
// chosen language must be installed to wp-content/languages.
// For example, install de.mo to wp-content/languages and set WPLANG to 'de'
// to enable German language support.
define ('WPLANG', '');
// uncomment this to enable wp-content/sunrise.php support
//define( 'SUNRISE', 'on' );
// Uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
// define( 'NOBLOGREDIRECT', '' );
define( "WP_USE_MULTIPLE_DB", false );
define('NONCE_KEY', 'Mg/6,W!!A|~(WqheFYhpsV0X2!Um9Ay<Y+40x|N>|7:v`+Wi$_<%TZ1Rsb+aX}M.');
define('AUTH_SALT', 'o&bzlVvFv&FhkG-}+2b!j?V<t9O>N+R`g_QMRu3+njIm7~my=:anKO=>p0Xa<(l5');
define('NONCE_SALT', 'K/gW/M%gMHjbFm}NP3-&r{{-Sj1+`8k2M+dd!nBFcGeg|5.YCIw1~(|->qPk/tlq');
/* That's all, stop editing! Happy blogging. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
?>
