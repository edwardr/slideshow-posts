<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow
 * @subpackage CW_Slideshow/includes
 */

class CW_Slideshow_Activator {

	public static function activate() {
		$options = get_option('cw_slideshow');

		if( !$options ) {
			$defaults = array(
				'force_reload' => false,
				'show_in_blog' => true,
				'slug'         => 'cw-slideshow',
			);
			update_option( 'cw_slideshow', $defaults );
		}

		flush_rewrite_rules();
	}

}
