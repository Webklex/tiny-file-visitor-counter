<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://webklex.com
 * @since      1.0.0
 *
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/includes
 * @author     Malte Goldenbaum <info@webklex.com>
 */
class Tiny_File_Visitor_Counter_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tiny-file-visitor-counter',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
