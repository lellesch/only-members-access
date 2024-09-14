<?php

namespace LetoOnlyMembersAccess\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LetoOnlyMembersAccess\Admin;
use LetoOnlyMembersAccess\Frontend;
use LetoOnlyMembersAccess\Core\I18n;

/**
 * Class Init
 *
 * The Init class is responsible for initializing and defining the core functionality of the plugin.
 *
 * @package     LetoOnlyMembersAccess
 */
class Init_Core {

	protected Loader $loader;
	protected string $plugin_basename;
	protected string $version;
	protected string $plugin_name;
	protected string $plugin_prefix_name;

	/**
	 * Initialize and define the core functionality of the plugin.
	 */
	public function __construct( $plugin_name, $plugin_prefix_name, $version, $plugin_basename ) {

		$this->plugin_name        = $plugin_name;
		$this->plugin_prefix_name = $plugin_prefix_name;
		$this->version            = $version;
		$this->plugin_basename    = $plugin_basename;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Loads the following required dependencies for this plugin.
	 */
	private function load_dependencies(): void {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale(): void {

		$plugin_i18n = new I18n( $this->plugin_name );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 */
	private function define_admin_hooks(): void {

		$plugin_admin = new Admin\Admin( $this->get_plugin_name(), $this->get_plugin_prefix_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_react_settings_page' );

		// Additional hooks can be added here.
	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 */
	private function define_public_hooks(): void {

		$plugin_public = new Frontend\Frontend( $this->get_plugin_name(), $this->get_plugin_prefix_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run(): void {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	/**
	 * The Prefix name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	 */
	public function get_plugin_prefix_name(): string {
		return $this->plugin_prefix_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(): Loader {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version(): string {
		return $this->version;
	}
}
