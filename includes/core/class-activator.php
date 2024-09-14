<?php

namespace LetoOnlyMembersAccess\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://letowp.de
 * @since      1.0.0
 *
 * @author     LetowWPDev
 **/
class Activator {


	/**
	 * The $_REQUEST during plugin activation.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $request The $_REQUEST array during plugin activation.
	 */
	private static array $request = array();


	/**
	 * The basename of the current WordPress plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin The basename of the current WordPress plugin.
	 */
	private static $plugin = ONLY_MEMBERS_ACCESS_BASENAME;

	/**
	 * Activate the plugin.
	 *
	 * Checks if the plugin was (safely) activated.
	 * Place to add any custom action during plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate(): void {

		if ( false === self::get_request()
			|| false === self::validate_request( self::$plugin )
			|| false === self::check_caps()
		) {
			if ( isset( $_REQUEST['plugin'] ) ) {
				if ( ! check_admin_referer( 'activate-plugin_' . self::$request['plugin'] ) ) {
					exit;
				}
			} elseif ( isset( $_REQUEST['checked'] ) ) {
				if ( ! check_admin_referer( 'bulk-plugins' ) ) {
					exit;
				}
			}
		}

		/**
		 * The plugin is now safely activated.
		 * Perform your activation actions here.
		 */
	}

	/**
	 * Get the request.
	 *
	 * Gets the $_REQUEST array and checks if necessary keys are set.
	 * Populates self::request with necessary and sanitized values.
	 *
	 * @return bool|array false or self::$request array.
	 * @since    1.0.0
	 */
	private static function get_request(): bool|array {

		if ( ! empty( $_REQUEST )
			&& isset( $_REQUEST['_wpnonce'] )
			&& isset( $_REQUEST['action'] )
		) {
			if ( isset( $_REQUEST['plugin'] ) ) {
				if ( false !== wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'activate-plugin_' . sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) ) ) {

					self::$request['plugin'] = sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) );
					self::$request['action'] = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) );

					return self::$request;

				}
			} elseif ( isset( $_REQUEST['checked'] ) ) {
				if ( false !== wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'bulk-plugins' ) ) {

					self::$request['action']  = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) );
					self::$request['plugins'] = array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['checked'] ) );

					return self::$request;

				}
			}
		} else {

			return false;
		}

		return false;
	}

	/**
	 * Validate the Request data.
	 *
	 * Validates the $_REQUESTed data is matching this plugin and action.
	 *
	 * @param string $plugin The Plugin folder/name.php.
	 * @return bool false if either plugin or action does not match, else true.
	 * @since    1.0.0
	 */
	private static function validate_request( $plugin ): bool {

		if ( isset( self::$request['plugin'] )
			&& $plugin === self::$request['plugin']
			&& 'activate' === self::$request['action']
		) {

			return true;

		} elseif ( isset( self::$request['plugins'] )
			&& 'activate-selected' === self::$request['action']
			&& in_array( $plugin, self::$request['plugins'], true )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Check Capabilities.
	 *
	 * We want no one else but users with activate_plugins or above to be able to active this plugin.
	 *
	 * @return bool false if no caps, else true.
	 * @since    1.0.0
	 */
	private static function check_caps(): bool {

		if ( current_user_can( 'activate_plugins' ) ) {
			return true;
		}

		return false;
	}
}
