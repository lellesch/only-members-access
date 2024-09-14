<?php

namespace LetoOnlyMembersAccess\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://letowp.de
 * @since      1.0.0
 *
 * @author    LetowWPDev
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The Prefix ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_prefix_name The prefix used for this plugin.
	 */
	private string $plugin_prefix_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private string $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $plugin_prefix_name The prefix for this plugin.
	 * @param string $version The version of this plugin.
	 * @since       1.0.0
	 */
	public function __construct( string $plugin_name, string $plugin_prefix_name, string $version ) {
		$this->plugin_name        = $plugin_name;
		$this->plugin_prefix_name = $plugin_prefix_name;
		$this->version            = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {
		$style_path = ONLY_MEMBERS_ACCESS_DIR_URL . 'assets/css/admin.css';

		if ( file_exists( ONLY_MEMBERS_ACCESS_DIR_PATH . 'assets/css/admin.css' ) ) {
			wp_enqueue_style( $this->plugin_name, $style_path, array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $admin_page ): void {
		$script_path = ONLY_MEMBERS_ACCESS_DIR_URL . 'assets/js/admin.js';

		if ( file_exists( ONLY_MEMBERS_ACCESS_DIR_PATH . 'assets/js/admin.js' ) ) {
			wp_enqueue_script( $this->plugin_name, $script_path, array( 'jquery' ), $this->version, false );
		}

		if ( 'settings_page_only-members-access-settings' !== $admin_page ) {
			return;
		}

		$asset_file = ONLY_MEMBERS_ACCESS_DIR_PATH . 'build/index.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			'only-members-access-settings-script',
			ONLY_MEMBERS_ACCESS_DIR_URL . 'build/index.js',
			$asset['dependencies'],
			$asset['version'],
			array(
				'in_footer' => true,
			)
		);

		wp_enqueue_style(
			'only-members-access-settings-style',
			ONLY_MEMBERS_ACCESS_DIR_URL . 'build/index.css',
			array_filter(
				$asset['dependencies'],
				function ( $style ) {
					return wp_style_is( $style, 'registered' );
				}
			),
			$asset['version'],
		);
	}

	/**
	 * Add a menu page to the WordPress admin dashboard.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu(): void {
		add_menu_page(
			__( 'Test Menu', 'only-members-access' ),
			__( 'Test Menu', 'only-members-access' ),
			'manage_options',
			$this->plugin_prefix_name . 'test',
			array( $this, 'render_test_page' ),
			'dashicons-admin-generic'
		);
	}

	/**
	 * Render the admin page.
	 *
	 * @since    1.0.0
	 */
	public function render_test_page(): void {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Test Page', 'only-members-access' ) . '</h1>';
		echo '<p>' . esc_html__( 'This is a placeholder for the admin page content.', 'only-members-access' ) . '</p>';
		echo '</div>';
	}

	/**
	 * Adds a React-based settings page to the WordPress admin area.
	 *
	 * @return void
	 */
	public function add_react_settings_page(): void {
		add_options_page(
			__( 'Plugin Settings', 'only-members-access' ), // The title to be displayed in the browser window for this page.
			__( 'My Singleton Plugin', 'only-members-access' ), // The text to be used for the menu.
			'manage_options', // The capability required for this menu to be displayed to the user.
			'only-members-access-settings', // The unique identifier of this menu item.
			array( $this, 'display_options_page' ) // The function to be called to render the menu's page content.
		);
	}

	public function display_options_page(): void {
		printf(
			'<div class="wrap" id="only-members-access-settings">%s</div>',
			esc_html__( 'Loading…', 'only-members-access' )
		);
	}
}
