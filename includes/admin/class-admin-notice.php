<?php

namespace LetoOnlyMembersAccess\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Notice
 */
class Admin_Notice {

	const PREFIX_NAME = ONLY_MEMBERS_ACCESS_PREFIX;

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'display_notices' ), 12 );
	}

	/**
	 * Adds a notice to the plugin's notices array.
	 *
	 * @param string $notice The notice message to be added.
	 * @param string $type The type of the notice (e.g., "success", "error", "warning").
	 * @param bool   $dismissible Optional. Whether the notice can be dismissed. Defaults to true.
	 * @return void
	 */
	public static function add_notice( string $notice = '', string $type = 'success', bool $dismissible = true ): void {

		$notices = get_option( self::PREFIX_NAME . 'notices', array() );

		$dismissible_text = ( $dismissible ) ? 'is-dismissible' : '';

		$notices[] = array(
			'notice'      => sanitize_text_field( $notice ),
			'type'        => sanitize_text_field( $type ),
			'dismissible' => $dismissible_text,
		);

		update_option( self::PREFIX_NAME . 'notices', $notices );
	}


	/**
	 * Displays the notices stored in the plugin's notices array and clears the array.
	 *
	 * @return void
	 */
	public static function display_notices(): void {
		$notices = get_option( self::PREFIX_NAME . 'notices', array() );

		foreach ( $notices as &$notice ) {
			self::format_notice( $notice );
		}

		if ( ! empty( $notices ) ) {
			delete_option( self::PREFIX_NAME . 'notices', array() );
		}
	}

	/**
	 * Formats a notice message as an HTML div element.
	 *
	 * @param array $notice An associative array containing the notice information.
	 *                      - type (string): The type of the notice.
	 *                      - dismissible (string): Whether the notice is dismissible.
	 *                      - notice (string): The notice message.
	 *
	 * @return void
	 */
	private static function format_notice( array $notice ): void {
		printf(
			'<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>',
			esc_attr( $notice['type'] ),
			esc_attr( $notice['dismissible'] ),
			esc_attr( $notice['notice'] )
		);
	}
}
