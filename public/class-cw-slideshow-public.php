<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/public
 */

class CW_Slideshow_Posts_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the post type
	 *
	 * @since    1.0.0
	 */
	public function register_post_types() {

		$plugin = new CW_Slideshow_Posts();
		$options = $plugin->get_options();

		register_post_type(
			'cw-slideshow',
			array(
				'labels'             => array(
					'name'               => _x( 'Slideshows', 'post type general name', 'cw-slideshow' ),
					'singular_name'      => _x( 'Slideshow', 'post type singular name', 'cw-slideshow' ),
					'menu_name'          => _x( 'Slideshows', 'admin menu', 'cw-slideshow' ),
					'name_admin_bar'     => _x( 'Slideshow', 'add new on admin bar', 'cw-slideshow' ),
					'add_new'            => _x( 'Add New', 'cw-slideshow', 'cw-slideshows' ),
					'add_new_item'       => __( 'Add New Slideshow', 'cw-slideshow' ),
					'new_item'           => __( 'New Slideshow', 'cw-slideshow' ),
					'edit_item'          => __( 'Edit Slideshow', 'cw-slideshow' ),
					'view_item'          => __( 'View Slideshow', 'cw-slideshow' ),
					'all_items'          => __( 'All Slideshows', 'cw-slideshow' ),
					'search_items'       => __( 'Search Slideshows', 'cw-slideshow' ),
					'parent_item_colon'  => __( 'Parent Slideshows:', 'cw-slideshow' ),
					'not_found'          => __( 'No Slideshows Found.', 'cw-slideshow' ),
					'not_found_in_trash' => __( 'No Slideshows Found in Trash.', 'cw-slideshow' )
					),
				'description'        => __( 'Description.', 'cw-slideshow' ),
				'menu_icon'          => 'dashicons-media-interactive',
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $options['base_slug'] ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'taxonomies'         => array('category', 'post_tag'),
				'supports'           => array(
						'title',
						'editor',
						'author',
						'comments',
					),
			)
		);

		// Flushes permalinks if slug-change transient has been set
		if (delete_transient('CW_Slideshow_Posts_flush')) {
			flush_rewrite_rules();
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cw-slideshow-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		$plugin = new CW_Slideshow_Posts();
		$options = $plugin->get_options();

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cw-slideshow-public.js', array( 'jquery' ), $this->version, true );

		$data = array(
			'plugin_path' => plugin_dir_url( __FILE__ ),
			'ajaxurl'     => admin_url( 'admin-ajax.php' ),
			'options'     => $options,
		);

		wp_localize_script( $this->plugin_name, 'cwSlideshow', $data );

	}

	/**
	 * Appends slideshow content to the slideshow template output
	 * @since     1.0.0
	 * @return  string post content
	 */

	public function slideshow_page_content( $content ) {

		global $post;

		$plugin = new CW_Slideshow_Posts();

		if( $post->post_type == 'cw-slideshow' ) {

			if( !is_admin() && is_singular() && is_main_query() ) {
				$slideshow = $plugin->output_slideshow_markup( $post->ID );
				$index = $plugin->get_slide_index();
				$wrapper_class = $index ? 'cw-hide cw-slide-overview' : 'cw-slide-overview';
				$content = '<div class="' . $wrapper_class . '">' . $content . '</div>' . $slideshow;
			}

		}

		return $content;

	}

	/**
	 * Modifies the query if slideshow posts are included in blog feeds
	 * @since  1.0.0
	 */

	public function slideshow_query_mod( $query ) {

		$plugin = new CW_Slideshow_Posts();
		$options = $plugin->get_options();

		if( $options['show_in_blog'] == true ) {
		  if ( !is_admin() && $query->is_main_query() ) {
		  	if( $query->is_feed() || $query->is_archive() || $query->is_search() || $query->is_posts_page() || $query->is_home() ) {
		  		if( !is_post_type_archive() ) {
		  			$query->set('post_type', array( 'cw-slideshow', 'post' ) );
		  		}
		  	}
		  }
		}

	}

}
