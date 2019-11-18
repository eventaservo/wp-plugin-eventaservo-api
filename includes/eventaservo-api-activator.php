<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.p4w5.eu/blog/
 * @since      1.0.0
 *
 * @package    Wp_Plugin_Eventaservo_Api
 * @subpackage Wp_Plugin_Eventaservo_Api/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Plugin_Eventaservo_Api
 * @subpackage Wp_Plugin_Eventaservo_Api/includes
 * @author     Paul Würtz <paulwuertz@posteo.de>
 */
class Eventaservo_Api_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class.plugin-settings.php';
		$settings = Eventaservo_Plugin_Settings::init();
		$settings->resetDefaultSettings();
	}

}
