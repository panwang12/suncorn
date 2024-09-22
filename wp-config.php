<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'suncorn_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '123456' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '>gX>^!tHY51Ohfq%1AKyK1}.p(o{h,7:JW&bqIp)bG(Nf)m,4|%TtD5=i7r~o?zh' );
define( 'SECURE_AUTH_KEY',   'I^A`)EqJ3pkOZ*K`Pf$S:w~`?j-7@>VO4.)Ef8fH@fxj*1LNRVv.n?-T{k3!@tfB' );
define( 'LOGGED_IN_KEY',     'UUzF*sno;wz7)]A4lYqiIJ~+5_N..<m*D~s5__Wc$|DvlT&],2ujHksmy@|+=5o~' );
define( 'NONCE_KEY',         '^:r%6_+s:WfU%Oek`Z:R!9A8W*])WRh*2?T Z0g*lCdC6rvC}m_h]F16gg#z#V_<' );
define( 'AUTH_SALT',         'zBt16O4Yx3DQqkPP3i7R:&QmQ@.f<Pl]DkE56YW>iX/*Ro~Z[#}g.,jAk@J %PJi' );
define( 'SECURE_AUTH_SALT',  '#b]$gHHKC6tSeiQ_S.IW|CDO,,V#MvQy@xxCz[CUz3lkJGAtP^NM/hq Qi}7F8oF' );
define( 'LOGGED_IN_SALT',    'V1,->d`)z~}lGy@Map1{tE|#2rC8stUpzdO=-u8Pkql7gv(<sk*]pU*rdz3m[aJK' );
define( 'NONCE_SALT',        'TlTtF8U!X#_phWHmR?yg%_+169B?0(]8P3_3s9klirp$n4@?Jw1S*^`B Sm2MUmz' );
define( 'WP_CACHE_KEY_SALT', '^f}1K2g[5;imkq9jxo?Qk($[|TcFqND1*p7!^,jTZ:j|2[/;Wt$0R~Fh n]vQZDo' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// if ( ! defined( 'WP_DEBUG' ) ) {
// 	define( 'WP_DEBUG', false );
// }
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
