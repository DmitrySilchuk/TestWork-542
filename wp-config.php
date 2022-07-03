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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test_work' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'AsWtjqlt4~^!+5I rHlv=i^=}IgpI{G~:,K*KsMVt_}3f2Tx{tg)?@N32kd@WCL[' );
define( 'SECURE_AUTH_KEY',  ';yBTSmcbJ.pF4?T[w,J)o{7^)o`g^JTczN1pCE-MX{,Ym}{729Nh9B+;:%|AoLg[' );
define( 'LOGGED_IN_KEY',    'o=lv5Y)3BH.Hau||D0%cK0i%wPV9,I*ZGQ|WUYZoX9+8,SD 1#uz?#yyh/RCm^X!' );
define( 'NONCE_KEY',        'di1tX=HLw^Ru!ZV} C|1F,]pAp{@_^U,< btZ.-{x:bDVbUPlJ>*VkxgD^=aDpi=' );
define( 'AUTH_SALT',        '-Vzl,p{v3u2E:xL7hg;Z(c]z[Gp-y4];KJmYs!b3*Ej)mwse*YVsl3KRn<lnUyQ0' );
define( 'SECURE_AUTH_SALT', '{,yzO}`-DBPtm<8r_Jd0lO3VI?4K_{UZlB}0yV1?!wY|rQ_cMP&@AQzL?W3G$Gtr' );
define( 'LOGGED_IN_SALT',   '5V>J^6X(vKx;iNGgEj+~*X>PTlp,X:h2*0?<5yG##b{Jt5~A_2;pl,VnN<qrkSI>' );
define( 'NONCE_SALT',       'HROQxZN*o?e~3^4C13W;Ed:c29S);k`b5cbt&d#pbchk=Ue+L-O|m_L>(a#Rj2Jd' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
