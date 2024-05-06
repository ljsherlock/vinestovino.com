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
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',          '}EIWA f?#%n4EV>YoaW? ZGTC1TBIP**J j~aK!KC5zs?!,aY<?fYcX_N)3|ewK5' );
define( 'SECURE_AUTH_KEY',   'vnEl]?INLY1}#Lo Yy1 -yn[QN3mF-VR$^9Ti0B6R&M#r;[z.i-k67 aD;B0~C$Q' );
define( 'LOGGED_IN_KEY',     'm$3$`95j`8CPAi9$(<@X*VD@B<95:yCYmv(JxdTc^.~7G=vz@5foYj{Cr *NOPTS' );
define( 'NONCE_KEY',         '4euo!~KU%t.RZlWa~g?OB&fJ;C tn>eDr+|u]b-:-L|@(*t}ygodMhDp?Hh(*+Ey' );
define( 'AUTH_SALT',         'v<IdWFO[^6&g{,;KDo?3O=GfrIu7Ub1j3Z#Q-+7zp|Bu(t!ay#82QP95dG/Ffb_k' );
define( 'SECURE_AUTH_SALT',  'u[QtJPHAd6N9~h?caa5s8$`l>0^Y/WC!CIm<e0qF!$=85;AE^i?+K|mH0_*bZHfK' );
define( 'LOGGED_IN_SALT',    '/9#/+dEXg_6F$~dQ[{FTH cm!8OcUPW%yf^/lVOT>W7*!3EW& yi[|MjLeN+au_$' );
define( 'NONCE_SALT',        '1ccuI>0w5+,oK:0ldtWb]Hm=>rG*i1/O?[|Ejl=&mdepge`.]|{LC_6)$5>Uw>[L' );
define( 'WP_CACHE_KEY_SALT', 'Iy~QH]8 08ehw <e_|zZ7kdM F/#0h^K$qu_+GT&WM6-b7Rh$F{$[pN>_< nfPMj' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'EDz_';


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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
