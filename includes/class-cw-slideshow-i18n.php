<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 * @author     codeWrangler, Inc. <edward@codewrangler.io>
 */
class CW_Slideshow_Posts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cw-slideshow',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
