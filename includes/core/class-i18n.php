<?php

namespace LetoOnlyMembersAccess\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://letowp.de
 * @since      1.0.0
 *
 * @author     LetowWPDev
 */
class I18n {


	/**
	 * The text domain of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $text_domain The text domain of the plugin.
	 */
	private string $text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_text_domain The text domain of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_text_domain ) {

		$this->text_domain = $plugin_text_domain;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain(): void {
		$loaded = load_plugin_textdomain(
			$this->text_domain,
			false,
			plugin_basename( dirname( __DIR__, 2 ) ) . '/languages'
		);
	}
}
