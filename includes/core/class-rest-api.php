<?php

namespace LetoOnlyMembersAccess\Core;

use WP_Error;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rest_Api {

	/**
	 * Initializes the REST API routes for the plugin.
	 *
	 * Registers the routes for retrieving and saving settings with appropriate
	 * methods, callbacks, and permission checks.
	 *
	 * @return void
	 */
	public function rest_api_init(): void {
		register_rest_route(
			'only-members-access/v1',
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);
		register_rest_route(
			'only-members-access/v1',
			'/settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_settings' ),
				'permission_callback' => array( $this, 'permission_callback' ),
				'args'                => array(
					'users_can_register'             => array(
						'required'          => true,
						'type'              => 'boolean',
						'validate_callback' => 'rest_validate_request_arg',

					),
					'enable_redirection_after_login' => array(
						'required'          => true,
						'type'              => 'boolean',
						'validate_callback' => 'rest_validate_request_arg',
					),
					'url_redirection_after_login'    => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'esc_url_raw',
						'validate_callback' => 'rest_validate_request_arg',
					),
					'rest_api_access'                => array(
						'required'          => true,
						'type'              => 'string',
						'enum'              => array(
							'only_logged_in',
							'full_website',
							'role_based',
						),
						'validate_callback' => 'rest_validate_request_arg',
					),
					'post_exceptions_ids'            => array(
						'type'              => 'array',
						'default'           => array(),
						'sanitize_callback' => 'wp_parse_id_list',
						'validate_callback' => 'rest_validate_request_arg',
					),
				),
			)
		);
	}


	/**
	 * Checks if the current user has the capability to manage options.
	 *
	 * @return bool True if the current user can manage options, false otherwise.
	 */
	public function permission_callback(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Retrieves the user registration and member access settings.
	 *
	 * @return array Associative array containing the settings for user registration and member access.
	 */
	public function get_settings(): array {
		$users_can_register           = get_option( 'users_can_register' );
		$only_members_access_settings = get_option( 'only_members_access_settings' );

		$out                       = array();
		$out['users_can_register'] = (int) $users_can_register;

		return array_merge( $out, $only_members_access_settings );
	}

	/**
	 * Saves the settings provided in the WP_REST_Request.
	 *
	 * @param WP_REST_Request $request The REST request containing the settings.
	 *
	 * @return WP_Error|\WP_HTTP_Response|\WP_REST_Response The response indicating the status of the save operation.
	 */
	public function save_settings( WP_REST_Request $request ): WP_Error|\WP_HTTP_Response|\WP_REST_Response {

		$users_can_register             = absint( $request->get_param( 'users_can_register' ) );
		$enable_redirection_after_login = absint( $request->get_param( 'enable_redirection_after_login' ) );
		$url_redirection_after_login    = empty( $request->get_param( 'url_redirection_after_login' ) ) ? '' : esc_url_raw( $request->get_param( 'url_redirection_after_login' ) );
		$rest_api_access                = sanitize_key( $request->get_param( 'rest_api_access' ) );
		$post_exceptions_ids            = $request->get_param( 'post_exceptions_ids' );

		if ( empty( $post_exceptions_ids ) ) {
			$post_exceptions_ids_filtered = array();
		} else {
			$post_exceptions_ids_filtered = array_filter(
				$post_exceptions_ids,
				function ( $value ) {
					$post = get_post( $value );
					if ( $post ) {
						return $post->ID;
					}

					return false;
				}
			);
		}

		$only_members_access_settings = array(
			'enable_redirection_after_login' => $enable_redirection_after_login,
			'url_redirection_after_login'    => $url_redirection_after_login,
			'rest_api_access'                => $rest_api_access,
			'post_exceptions_ids'            => $post_exceptions_ids_filtered,
		);

		update_option( 'users_can_register', $users_can_register );
		update_option( 'only_members_access_settings', $only_members_access_settings );
		$data_new = array_merge( array( 'users_can_register' => $users_can_register ), $only_members_access_settings );

		return rest_ensure_response(
			array(
				'status' => 'success',
				'data'   => $data_new,
			)
		);
	}
}
