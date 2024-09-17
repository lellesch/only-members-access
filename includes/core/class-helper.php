<?php

namespace LetoOnlyMembersAccess\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helper {

	/**
	 * Checks the form action for security purposes.
	 *
	 * @param string $action_name The name of the form action.
	 * @param string $capability The capability required to perform the action (default: 'manage_options').
	 *
	 * @return void
	 */
	public static function post_form_action_check( string $action_name, string $capability = 'manage_options' ): void {

		if ( ! isset( $_POST['action'] ) || ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( $_POST['action'] !== $action_name ) {
			die( 'Security check' );
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) );

		if ( ! wp_verify_nonce( $nonce, $action_name ) || ! current_user_can( $capability ) ) {
			die( 'Security check' );
		}

		check_admin_referer( $action_name );
	}


	public static function set_default_settings(): void {

		$options = get_option( 'only_members_access_settings', array() );

		if ( empty( $options ) ) {
			$options = array(
				'enable_redirection_after_login' => 0,
				'url_redirection_after_login'    => '',
				'rest_api_access'                => 'only_logged_in',
				'post_exceptions_ids'            => array(),
			);
			update_option( 'only_members_access_settings', $options );
		}
	}
}
