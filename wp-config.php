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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '^K9b!M^{8X]XleNFX 6nXom+5s<k]D_du??RnN^~RNsWz&X7|qBj&=|Z99-Z2FGV');
define('SECURE_AUTH_KEY',  'R;}Crpa*Xlj/X[z|+%cl~p]5tR_F^ y!j~2kL!?gW&IlfY,$jM ~]+[YWa?8WiN}');
define('LOGGED_IN_KEY',    '+]@dLh~8)f+%Z@ueujY5[Gn)F4g8YB%;|A-%#XWJ-,:IHx=*,Tb+/%J~W3e1S!)D');
define('NONCE_KEY',        'Ihj3a^VUuB#z9Q_E*KI6%,h:An~,X*aa)*De{WQMv+xSQo@-uFLGyhh{teh8%^ D');
define('AUTH_SALT',        'pVX;|n|aTe9;OBD{<{cu#CRN)6=VBRT>F@Ntyk%fogz{sZPFXD94v} )|Tx=T&y7');
define('SECURE_AUTH_SALT', 'L]SN(66mG_j9=M#-Q^7Ml6]_ofOtoF9:/uzPzS[JNo-,&ksoa[NS5q+gg^JG&Mrw');
define('LOGGED_IN_SALT',   '!slhW,DenCL+rNzs:.l16}-&QiF Bq!OH8)@w[J:7H|C<WQY10-YV#LXp7rHW|f9');
define('NONCE_SALT',       'V3n.5:2r+nC5Bx)f-;}ul|_PLY&g]NMyDexvh=_2j0ghbG<N)M;Ir$+4{5# .n9f');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
