<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    CW_Slideshow_Posts
 * @subpackage CW_Slideshow_Posts/admin
 * @author     codeWrangler, Inc. <edward@codewrangler.io>
 */
class CW_Slideshow_Posts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Registers the post meta boxes
	 * @since 1.0.0
	 */

	public function CW_Slideshow_Posts_meta_boxes() {
		add_meta_box( 'cw-slideshow-meta-box', __( 'Slideshow Details', 'cw-slideshow' ), array( $this, 'CW_Slideshow_Posts_meta_callback' ), 'cw-slideshow', 'normal', 'high' );
	}

	/**
	 * Post meta box callback
	 * @since 1.0.0
	 */

	public function CW_Slideshow_Posts_meta_callback( $post ) {

		wp_nonce_field( 'CW_Slideshow_Posts_meta', 'CW_Slideshow_Posts_meta_nonce' );

		$a = new CW_Slideshow_Posts( $post->ID );

		$slides = get_post_meta( $post->ID, 'cw_slides', true );

		$i = 0;

		if( $slides ):

			foreach( $slides as $slide ) {
				$i++;
				echo '<div class="wrap cw-slide-instance">';
				echo '<p><label><h4>Slide Title</h4>';
				echo '<input type="text" class="widefat" name="cw_slide_title[]" value="' . $slide['title'] . '" />';
				echo '</label></p>';
				$btn_text = $slide['image'] ? __('Change Image', 'cw-slideshow') : __('Attach Image', 'cw-slideshow');
				echo '<button class="button cw-attach-image">' . $btn_text . '</button>';
				echo '<input type="hidden" name="cw_slide_image[]" value="' . $slide['image'] . '" class="upload-image" />';
				if( $slide['image'] ) {
					$img = wp_get_attachment_image_src( $slide['image'], 'medium' );
					if( isset( $img[0] ) ) {
						$url = $img[0];
						echo '<div class="cw-slideshow-img-preview">
										<div class="cw-preview-inner">
											<span class="dashicons dashicons-no remove-slide-img"></span><img src="' . $url . '" alt="" />
										</div>
									</div>';
					}
				}

				$editor_settings = array( 'media_buttons' => false, 'textarea_name' => 'cw_slide_description[]' );
				wp_editor( $slide['desc'], 'test_' . $i, $editor_settings );
				echo '</div>';
			}

		endif;

		echo '<p><button type="button" class="button cw-add-slide">' . __('Add Slide', 'cw-slideshow') . '</button></p>';
	}

	/**
	 * Saves the custom slideshow metadata
	 * @since 1.0.0
	 */

	public function CW_Slideshow_Posts_meta_save( $post_id ) {
		if ( ! isset( $_POST['CW_Slideshow_Posts_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['CW_Slideshow_Posts_meta_nonce'], 'CW_Slideshow_Posts_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$titles = array_filter( $_POST['cw_slide_title'] );
		$images = $_POST['cw_slide_image'];
		$descs  = $_POST['cw_slide_description'];

		$slides = array();

		$i = 0;
		foreach( $titles as $k => $v ) {
			$r = array();
			$r['title'] = $v;
			$r['image'] = esc_attr( $images[$i] );
			$r['desc'] = wp_kses_post( $descs[$i] );

			$slides[] = $r;

			$i++;
		}

		$data = array(
			'cw_slides' => $slides,
		);

		foreach( $data as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cw-slideshow-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cw-slideshow-admin.js', array( 'jquery', 'editor' ), $this->version, true );

	}

	public function wp_enqueue_editor() {
		wp_enqueue_editor();
	}

	/**
	 * Registers the settings
	 *
	 * @since    1.0.0
	 */

	public function register_settings() {
		register_setting( 'CW_Slideshow_Posts_options', 'CW_Slideshow_Posts', array( $this, 'validate_options' ) );
	}

	/**
	 * Adds the options page
	 *
	 * @since    1.0.0
	 */

	public function options_page() {
		add_options_page('Slideshow ' . __('Options', 'cw-slideshow'), 'Slideshow ' . __('Options', 'cw-slideshow'), 'manage_options', 'cw-slideshow', array( $this, 'output_options' ) );
	}

	/**
	 * Outputs the options content
	 *
	 * @since    1.0.0
	 */

	public function output_options() {

		$plugin = new CW_Slideshow_Posts();
		$options = $plugin->get_options();

			echo '<h4>';
			_e('For paid support or customizations, please contact me at', 'cw-slideshow' );
			echo ' <a href="https://codewrangler.io" target="_blank">codewrangler.io</a>';
			echo '</h4>';
			?>
			<div class="wrap">
				<h2><?php _e ('Slideshow Posts Options Panel', 'cw-slideshow'); ?></h2>
				<form method="post" action="options.php">
					<?php settings_fields('CW_Slideshow_Posts_options'); ?>
					<table class="form-table">
						<tr valign="top"><th scope="row"><?php _e('Force Page Reload on Slide Navigation', 'cw-slideshow'); ?></th>
						<td><input name="CW_Slideshow_Posts[force_reload]" type="checkbox" value="1" <?php checked(1, $options['force_reload'] ); ?> />
						</td>
						</tr>
						<tr valign="top"><th scope="row"><?php _e('Slideshow URL Base', 'cw-slideshow'); ?></th>
						<td><input name="CW_Slideshow_Posts[base_slug]" type="text" value="<?php echo $options['base_slug']; ?>" />
							<p class="description"><?php _e('If this slug is in use elsewhere, there could be a conflict.', 'cw-slideshow'); ?></p>
						</td>
						</tr>
						<tr valign="top"><th scope="row"><?php _e('Show Slideshows in Blog Feed?', 'cw-slideshow'); ?></th>
						<td>
							<input name="CW_Slideshow_Posts[show_in_blog]" type="checkbox" value="1" <?php checked(1, $options['show_in_blog'] ); ?> />
							<p class="description"><?php _e('If enabled, slideshow posts will appear next to blog posts in your main content loop.', 'cw-slideshow'); ?></p>
						</td>
						</tr>
					</table>
					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'staffer') ?>" />
					</p>
				</form>

				<?php esc_attr_e('Thank you for using this plugin. A lot of time went into development. Donations small or large are always appreciated.' , 'cw-slideshow'); ?></p>
					<form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="QD8ECU2CY3N8J">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
			</div>
		<?php	
	}

	/**
	 * Validates the options data
	 *
	 * @since    1.0.0
	 */

	public function validate_options( $input ) {

		$plugin = new CW_Slideshow_Posts();
		$options = $plugin->get_options();

		$input['force_reload'] = isset( $input['force_reload'] ) ? true : false;
		$input['show_in_blog'] = isset( $input['show_in_blog'] ) ? true : false;
		
		return $input;

	}

	/**
	 * Sets a transient on option update to later trigger a rewrite flush
	 * @since 1.0.0
	 */

	public function flush_permalinks($option, $old_value, $value) {
		set_transient('CW_Slideshow_Posts_flush', true);
	}

}
