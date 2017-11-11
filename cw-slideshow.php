<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codewrangler.io
 * @since             1.0.0
 * @package           CW_Slideshow_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Slideshow Posts
 * Plugin URI:        https://codewrangler.io/
 * Description:       Lets you create slideshow articles/posts, such as Top 10 lists, etc.
 * Version:           1.0.0
 * Author:            codeWrangler, Inc.
 * Author URI:        https://codewrangler.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cw-slideshow
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CW_SLIDESHOW_POSTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cw-slideshow-activator.php
 */
function activate_CW_Slideshow_Posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cw-slideshow-activator.php';
	CW_Slideshow_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cw-slideshow-deactivator.php
 */
function deactivate_CW_Slideshow_Posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cw-slideshow-deactivator.php';
	CW_Slideshow_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_CW_Slideshow_Posts' );
register_deactivation_hook( __FILE__, 'deactivate_CW_Slideshow_Posts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cw-slideshow.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_CW_Slideshow_Posts() {

	$plugin = new CW_Slideshow_Posts();
	$plugin->run();

}

run_CW_Slideshow_Posts();
