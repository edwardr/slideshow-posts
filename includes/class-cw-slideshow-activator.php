<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 */

class CW_Slideshow_Posts_Activator {

	public static function activate() {
		$options = get_option('CW_Slideshow_Posts');

		if( !$options ) {
			$defaults = array(
				'force_reload' => false,
				'show_in_blog' => true,
				'slug'         => 'cw-slideshow',
			);
			update_option( 'CW_Slideshow_Posts', $defaults );
		}

		flush_rewrite_rules();
	}

}
