<?php

namespace LetoOnlyMembersAccess\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two example hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://letowp.de
 * @since      1.0.0
 *
 * @author    LetowWPDev
 */
class Frontend {

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
	 *
	 * @since       1.0.0
	 */
	public function __construct( string $plugin_name, string $plugin_prefix_name, string $version ) {
		$this->plugin_name        = $plugin_name;
		$this->plugin_prefix_name = $plugin_prefix_name;
		$this->version            = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {
		$style_path = ONLY_MEMBERS_ACCESS_DIR_URL . 'assets/css/frontend.css';

		if ( file_exists( ONLY_MEMBERS_ACCESS_DIR_PATH . 'assets/css/frontend.css' ) ) {
			wp_enqueue_style( $this->plugin_name, $style_path, array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {
		$script_path = ONLY_MEMBERS_ACCESS_DIR_URL . 'assets/js/frontend.js';

		if ( file_exists( ONLY_MEMBERS_ACCESS_DIR_PATH . 'assets/js/frontend.js' ) ) {
			wp_enqueue_script( $this->plugin_name, $script_path, array( 'jquery' ), $this->version, false );
		}
	}
}
