<?php 
/* If things dont work
 * UPDATE wp_blogs***********************
 */

/* Don't try to create this file by hand. Read the README.txt and run the installer. */
// ** MySQL settings ** //
 //Added by WP-Cache Manager
// //Added by WP-Cache Manager
define('DB_NAME', 'neoxenos_dev');    // The name of the database
define('DB_USER', 'root');     // Your MySQL username
define('DB_PASSWORD', 'root'); // ...and password
define('DB_HOST', 'localhost');    // 99% chance you won't need to change this value
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('SUBDOMAIN_INSTALL', true);

$base = '/';

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'U;-aiMg9n}pm%j2&+I]S|&p>,co5JzSP<RSi}x^+kuj/D9>=* @Au)$Y,w&HI`{D');
define('SECURE_AUTH_KEY',  '/t4R+P_Fs^f7H<(%k6wRo#|n+K.Yp/{T-@!&rQiP:hP]6(2_2:`V^wO*)l4.wz[P');
define('LOGGED_IN_KEY',    ',5T8O<K^}BbKLvk`SbYAwZH*Q!yzwN)u:|aI],$lbxq;PyrS9*_Hg+?@`N?ebtxb');
define('NONCE_KEY',        'dim0[A>AvbfUaM%Gs- +E#SnT{~<4u2T+K85*Hz;Y]6IX&[6mfD/pH@pl=z45}v-');
define('AUTH_SALT',        '2wH+s)b8weCCpi}kB5L9$+feSE !]_]<gc/,^|WK3]DyEes2`1z{i[e$e*va?t3x');
define('SECURE_AUTH_SALT', 'PR!/^+L_jW?z?vq2``#,om-fpS,oTp/oUJCZvIVOIgcKw_>7c-t9m6dtLr3li(R>');
define('LOGGED_IN_SALT',   'rX2;N9!aR@)z.vUR~jyQFFjH+&oO7x~S.VD6~ylz;wsBY@+eV9Da, ,yVEV0>kM+');
define('NONCE_SALT',       '1 B!|R+gMO<}-VJ$@$ha;_wYBB9U!X/Gd:{5<P5a,n/IHXj<4tP)qucNO~XZ:U*3');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
*/
$_SERVER['HTTP_HOST'] = empty($_SERVER['HTTP_HOST']) ? 'neoxenos.local' : $_SERVER['HTTP_HOST'];
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] . '/');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
?>