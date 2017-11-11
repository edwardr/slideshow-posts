<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 */

class CW_Slideshow_Posts_Deactivator {

	public static function deactivate() {
		flush_rewrite_rules();
	}

}
