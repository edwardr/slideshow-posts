<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/includes
 * @author     codeWrangler, Inc. <edward@codewrangler.io>
 */
class CW_Slideshow_Posts {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cw-slideshow';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CW_Slideshow_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - CW_Slideshow_Posts_i18n. Defines internationalization functionality.
	 * - CW_Slideshow_Posts_Admin. Defines all hooks for the admin area.
	 * - CW_Slideshow_Posts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cw-slideshow-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cw-slideshow-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cw-slideshow-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cw-slideshow-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cw-slideshow-post.php';

		$this->loader = new CW_Slideshow_Posts_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the CW_Slideshow_Posts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new CW_Slideshow_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new CW_Slideshow_Posts_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'CW_Slideshow_Posts_meta_boxes' );
		$this->loader->add_action( 'save_post_cw-slideshow', $plugin_admin, 'CW_Slideshow_Posts_meta_save' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_enqueue_editor' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'updated_option', $plugin_admin, 'flush_permalinks', 100, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new CW_Slideshow_Posts_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'register_post_types' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'slideshow_page_content' );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'slideshow_query_mod', 100, 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    CW_Slideshow_Posts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns the slide index
	 * @since 1.0.0
	 * @return int/boolean
	 */

	public function get_slide_index() {
		$index = isset( $_GET['cw-slide'] ) && is_numeric( $_GET['cw-slide'] ) ? $_GET['cw-slide'] : false;

		return $index;
	}

	/**
	 * Generates the front-end slideshow markup
	 * @since 1.0.0
	 * @return  string HTML
	 */

	public function output_slideshow_markup( $post_id ) {
		$slideshow = new CW_Slideshow_Post( $post_id );
		$index = $this->get_slide_index();

		$output = '';

		$begin_class = $index ? 'cw-hide cw-begin-slideshow' : 'cw-begin-slideshow';

		$output .= '<div class="cw-begin-wrap">';
		$output .= '<button data-open="1" class="button ' . $begin_class . '">';
		$output .= __('Begin Slideshow', 'cw-slideshow') . '</button>';
		$output .= '</div>';

		if( $slideshow->slides ) {
			$slide_count = count( $slideshow->slides );
			$output .= '<div class="cw-content-slides">';
			$i = 0;
			foreach( $slideshow->slides as $slide ) {
				$i++;

				if( $i == $index ) {
					$hide = '';
				}

				$hide = $i == $index ? '' : 'cw-hide';
				$output .= '<div class="cw-slide ' . $hide . '" data-index="' . $i . '">';
				$output .= '<h4>' . $slide['title'] . '</h4>';
				$img_src = wp_get_attachment_image_src( $slide['image'], 'large', false );
				$img_srcset = wp_get_attachment_image_srcset( $slide['image'], 'large' );
				$alt = get_post_meta( $slide['image'], '_wp_attachment_image_alt', true);

				if( empty( $alt ) ) {
					$alt = $slide['title'];
				}

				if( $slide['image'] ) {
					$attachment = get_post( $slide['image'] );
					$image_caption = $attachment->post_excerpt;

					$output .= '<img src="' . esc_url( $img_src[0] ) . '" srcset="' . esc_attr( $img_srcset ) . '"
					sizes="(max-width: 50em) 87vw, 680px" alt="' . $alt . '" />';

					if( $image_caption ) {
						$output .= '<figcaption class="wp-caption-text">' . $image_caption . '</figcaption>';
					}
				}

				$output .= '<div class="cw-slide-description">' . $slide['desc'] . '</div>';
				$output .= '<div class="cw-slide-footer">';

				$output .= '<div class="cw-button-wrap">';

				$output .= '<button data-open="' . ($i - 1) . '" class="button cw-slide-nav cw-slide-prev">' . __('Previous', 'cw-slideshow') . '</button>';

				if( $slide_count > $i ) {
					$output .= '<button data-open="' . ($i + 1) . '" class="button cw-slide-nav cw-slide-next">' . __('Next', 'cw-slideshow') . '</button>';
				}

				$output .= '</div>';

				$output .= '<div class="cw-slide-legend"><span>' . $i . ' / ' . $slide_count . '</span></div>';

				$output .= '</div>';

				$output .= '</div>';
			}
			$output .= '</div>';
		}
		return $output;
	}

	/**
	 * Fetches the plugin options
	 * @since 1.0.0
	 * @return  array Options array
	 */

	public function get_options() {
		$options = get_option('CW_Slideshow_Posts');
		$force_reload = isset( $options['force_reload'] ) && !empty( $options['force_reload'] ) ? true : false;
		$slug = isset( $options['base_slug'] ) && !empty( $options['base_slug'] ) ? $options['base_slug'] : 'cw-slideshow';
		$show_in_blog = isset( $options['show_in_blog'] ) && !empty( $options['show_in_blog'] ) ? true : false;

		$return_options = array(
			'force_reload' => $force_reload,
			'base_slug'    => $slug,
			'show_in_blog' => $show_in_blog,
		);

		return $return_options;
	}

}
