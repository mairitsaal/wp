<?php

/**
 * Cloudimage - Responsive Images as a Service
 *
 *
 * @link              https://cloudimage.io
 * @since             1.0.0
 * @package           Cloudimage
 *
 * @wordpress-plugin
 * Plugin Name:       Cloudimage - Responsive Images as a Service
 * Description:       The easiest way to <strong>deliver lightning fast images</strong> to your users.
 * Version:           2.3.0
 * Author:            Cloudimage
 * Author URI:        https://cloudimage.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cloudimage
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 */
define( 'CLOUDIMAGE_VERSION', '2.3.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cloudimage-activator.php
 */
function activate_cloudimage() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cloudimage-activator.php';
	Cloudimage_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cloudimage-deactivator.php
 */
function deactivate_cloudimage() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cloudimage-deactivator.php';
	Cloudimage_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cloudimage' );
register_deactivation_hook( __FILE__, 'deactivate_cloudimage' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cloudimage.php';

/**
 * Install composer dependencies
 */
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cloudimage() {

	$plugin = new Cloudimage();
	$plugin->run();

}
run_cloudimage();
