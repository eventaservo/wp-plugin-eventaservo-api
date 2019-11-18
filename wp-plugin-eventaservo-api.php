<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.p4w5.eu/blog/
 * @since             1.0.0
 * @package           Wp_Plugin_Eventaservo_Api
 *
 * @wordpress-plugin
 * Plugin Name:       eventaservo
 * Plugin URI:        https://github.com/eventaservo/wp-plugin-eventaservo-api
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Paul Würtz
 * Author URI:        http://www.p4w5.eu/blog/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-plugin-eventaservo-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EVENTASERVO_API_VERSION', '1.0.0' );
define( 'EVENTASERVO_API__PLUGIN_FILE', __FILE__);
define( 'EVENTASERVO_API__PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-plugin-eventaservo-api-activator.php
 */
function activate_wp_plugin_eventaservo_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/eventaservo-api-activator.php';
	Eventaservo_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-plugin-eventaservo-api-deactivator.php
 */
function deactivate_wp_plugin_eventaservo_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/eventaservo-api-deactivator.php';
	Eventaservo_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_plugin_eventaservo_api' );
register_deactivation_hook( __FILE__, 'deactivate_wp_plugin_eventaservo_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/eventaservo-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_plugin_eventaservo_api() {

	$plugin = new Eventaservo_Api();
	$plugin->run();

}
run_wp_plugin_eventaservo_api();
