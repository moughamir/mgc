<?php
if (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") $_SERVER["HTTPS"] = "on";
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
define('DB_NAME', 'c9');

/** MySQL database username */
define('DB_USER', substr(getenv('C9_USER'), 0, 16));

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', getenv('IP'));

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'mfiqPf%>3}=H{4m%=|nJ>W8JGua}ZeQBb}+DI,Ab~)>ye~}A3|OJT39g8|sa!;|W');
define('SECURE_AUTH_KEY',  '!dPEh@Q/].&kDj#V2dG=1vgLyHnb|y=G[3!+&o*|=!c_PN}HnWKHnxh{*A9&j)N-');
define('LOGGED_IN_KEY',    '8KV[F Aoc)&h~1|{@-DRugwA0xbNa+Q?wL3wgSriX,FVm+]F[DbErv-}6R;S+4$2');
define('NONCE_KEY',        '1-WNqW@??$f-PlKLb}E5|u92*u[AZmX2L?GT&~^4P<CL>kBabJVZ>/u$_#sKXD$y');
define('AUTH_SALT',        'sl^:j8:0V; ZLe}xh^-CA|$#RwD>2K2bD:iD|i9P$+}`S+}R#N[tY2jVclZWS7*4');
define('SECURE_AUTH_SALT', '}O,JI}MI?0$A}Tel;4}+%:tR5Yf;#aca+h+r_l0-Rux~0[;aSoCFgDNjp/GMFl*j');
define('LOGGED_IN_SALT',   'qVq|+F<0mzk-0a5YX*l=Cd h?K1}hMHu2$Xog-H*PZ6/cn)&%=/|/:zNs{=7NwDJ');
define('NONCE_SALT',       '|XW*2IG$h6{hk8?B^0qpS`5!5+TX5[{iWVeU)6RNHX<7mx@95aw3]nm9]dw)6+s,');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'oz_';

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
$_SERVER["HTTP_HOST"] = $_SERVER["SERVER_NAME"];
$_SERVER["HTTP_HOST"] = $_SERVER["SERVER_NAME"];
define( 'WP_MAX_MEMORY_LIMIT', '256M' );
define( 'WP_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
