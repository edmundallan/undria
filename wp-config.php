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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'undria' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'nE@@L1C9p.=&,?[D<ze9aQ^h,wihwEnhIwNub!b:@~Dl*<fG/Ooevwc`Bbpp=e6=' );
define( 'SECURE_AUTH_KEY',  '6BJ_Q,@ihQ*kB1xveDqL&8p(m[3U=0-nJkHbAs<kG7bTyb(Uk)=UB@~xSpbrG}(6' );
define( 'LOGGED_IN_KEY',    'S,H=l0]aJR9WC~.7W5%M_ = n_dHROfC`agecH3c~qmR<Xo7E9k=S>jK[2;=b&Y}' );
define( 'NONCE_KEY',        '/!}1yMC1>`Bf]fOo^?]87jR(ZTUMa_5LS$THCx{Wz*3JynU0C}p,F#>7H0g#g4#v' );
define( 'AUTH_SALT',        'gfM;8c{x>dm]m#$cF6T$2RE0?+mWZf!NuB|&W*/E*26%=fx.?j;5k%!vF}ErAKn]' );
define( 'SECURE_AUTH_SALT', 'ut%Ia(}e!NfJdkqc|w6j^m`ThE9`XP*,]kHj5LPHD-Lt3E>e8Nz($3Qc>On=pN-t' );
define( 'LOGGED_IN_SALT',   'le] X5J}yjVU+[_s5}, 2&+,#ZJLY91wC:f*KEUnXX_gvQ_KQd#{($g&(fn1+NeG' );
define( 'NONCE_SALT',       'TF}#yLTgP7vaZbD85C);?k @hs1nZ6:gRrG#=t~S,]I.I.JU[ zT-jA?x[-5=(M:' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

// linux image upload
define('FS_METHOD','direct');