<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       Only Members Access
 * Description:       Only Members Access beschränkt den Zugriff auf deine Website auf registrierte und eingeloggte Benutzer.
 * Version:           1.0.0
 * Author:            LetowWPDev
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author URI:        https://letowp.de/?utm_source=plugin&utm_medium=only-members-access
 * Text Domain:       only-members-access
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace LetoOnlyMembersAccess;

use LetoOnlyMembersAccess\Core\Init_Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Constants
 */
define( 'ONLY_MEMBERS_ACCESS_NAME', 'only-members-access' );
define( 'ONLY_MEMBERS_ACCESS_PREFIX', 'only_members_access_' );
define( 'ONLY_MEMBERS_ACCESS_VERSION', '1.0.0' );
define( 'ONLY_MEMBERS_ACCESS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ONLY_MEMBERS_ACCESS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ONLY_MEMBERS_ACCESS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoloading, via Composer.
 *
 * @link https://getcomposer.org/doc/01-basic-usage.md#autoloading
 */
if ( file_exists( ONLY_MEMBERS_ACCESS_DIR_PATH . 'vendor/autoload.php' ) ) {
	require_once ONLY_MEMBERS_ACCESS_DIR_PATH . 'vendor/autoload.php';
}

/**
 * Register Activation and Deactivation Hooks
 */
register_activation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Activator', 'activate' ] );
register_deactivation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Deactivator', 'deactivate' ] );

/**
 * Plugin Singleton Container
 *
 * Maintains a single copy of the plugin app object
 *
 * @since    1.0.0
 */
class Only_Members_Access {

	private static $init;

	/**
	 * Loads the plugin
	 *
	 * @access    public
	 */
	public static function init(): ?Init_Core {

		if ( null === self::$init ) {
			self::$init = new Init_Core(ONLY_MEMBERS_ACCESS_NAME, ONLY_MEMBERS_ACCESS_PREFIX, ONLY_MEMBERS_ACCESS_VERSION, ONLY_MEMBERS_ACCESS_BASENAME);
			self::$init->run();
		}

		return self::$init;
	}

}

/**
 * Initialize all the core classes of the plugin
 */
Only_Members_Access::init();
