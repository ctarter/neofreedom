<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'neofreedom_org');
/** MySQL database username */
define('DB_USER', 'root');
/** MySQL database password */
define('DB_PASSWORD', 'cf031ce4c9');
/** MySQL hostname */
define('DB_HOST', 'localhost:3306');
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('SUBDOMAIN_INSTALL', true);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);

// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'ca06868bf9caf54975681f427131e9bdef2498d933f09983820de1dde5039b40');
define('SECURE_AUTH_KEY', '621b42f6c58fe20c49b7ba8323f200f66c4dc60195b15b2bfaab7a1981aa8f4e');
define('LOGGED_IN_KEY', '16356afd65cc1c78adad861a0be4881dfd96013d39ef70624a32d61ad92559cf');
define('NONCE_KEY', 'a4a15455b1d255024e4fe7086d4f4d7d51c0825d362b1d70519eecd6ddf7407d');
define('AUTH_SALT', '66c828572dfe9d56803176b50804de5748bcdfa2939757631f7b5a44248fc4bc');
define('SECURE_AUTH_SALT', 'e250289c976e176c23b33c858934b4a64558a086470ef4488c8040e1fb344f59');
define('LOGGED_IN_SALT', 'e7a510c93f45858acb4d94eb652c34c26b6f88f8aaae8a02769d7fbccab3f293');
define('NONCE_SALT', 'b2e3403dac33c694f68f85c7e45410f019d0f50d9eff3adc8c08933328f67f20');
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
/* That's all, stop editing! Happy blogging. */
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
*/
$_SERVER['HTTP_HOST'] = empty($_SERVER['HTTP_HOST']) ? 'neofreedom.org' : $_SERVER['HTTP_HOST'];
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] . '/');
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
define('WP_TEMP_DIR', ABSPATH . 'wp-content/');
define('FS_METHOD','direct');
