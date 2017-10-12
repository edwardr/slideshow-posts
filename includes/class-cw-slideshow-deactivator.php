<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow
 * @subpackage CW_Slideshow/includes
 */

class CW_Slideshow_Deactivator {

	public static function deactivate() {
		flush_rewrite_rules();
	}

}
