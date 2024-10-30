<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.weblineindia.com
 * @since             1.0.0
 * @package           MABOX_Author_Box
 *
 * @wordpress-plugin
 * Plugin Name:       Magic Author Box
 * Plugin URI:        https://wordpress.org/plugins/magic-author-box
 * Description:       Magic Author Box display a responsive author box with author information on frontend.
 * Version:           1.0.5
 * Author:            Weblineindia
 * Author URI:        http://www.weblineindia.com
 * Text Domain:       magic-author-box
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constants
 */

define ( 'MABOX_VERSION', '1.0.5' );
define ( 'MABOX_OPTION_NAME', 'MABox_Settings' );
define ( 'MABOX_PLUGIN_FILE', basename ( __FILE__ ) );
define ( 'MABOX_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define ( 'MABOX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-author_box-by-webline-activator.php
 */
function mabox_activate_author_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-author-box-activator.php';
	MABOX_Activator::activate();
	update_option('ma_box_activation_date', time());
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-author_box-by-webline-deactivator.php
 */
function mabox_deactivate_author_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-author-box-deactivator.php';
	MABOX_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'mabox_activate_author_box' );
register_deactivation_hook( __FILE__, 'mabox_deactivate_author_box' );

/* Check update hook Start */
add_filter('pre_set_site_transient_update_plugins', 'update_magic_author_box');
function update_magic_author_box($transient)
{
    if (empty($transient->checked)) {
        return $transient;
    }
    $plugin_folder = plugin_basename(__FILE__);
    if (isset($transient->checked[$plugin_folder])) {
        update_option('ma_box_activation_date', time());
    }
    return $transient;
}   
/* Check update hook End */

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-author-box.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mabox_run_author_box() {

	$plugin = new MABOX_Author_Box();
	$plugin->run();

}
mabox_run_author_box();