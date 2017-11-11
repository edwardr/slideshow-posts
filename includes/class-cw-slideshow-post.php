<?php

/**
 * Defines the Slideshow Post class
 *
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 */

/**
 * @since      2.0.0
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 * @author     codeWrangler, Inc. <edward@codewrangler.io>
 */
class CW_Slideshow_Post {

	public $ID;
	public $name;
	public $excerpt;
	public $slides;

	public function __construct( $post_id ) {

		$post = get_post( $post_id );

		$this->ID = $post->ID;
		$this->name = $post->post_title;
		$excerpt = $post->post_excerpt;
		$this->excerpt = empty( $excerpt ) ? wp_trim_words( $post->post_content, 55 ) : $excerpt;

		$slides = get_post_meta( $post_id, 'cw_slides', true ) ? get_post_meta( $post_id, 'cw_slides', true ) : false;
		$this->slides = $slides;
	}
}