<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.p4w5.eu/blog/
 * @since      1.0.0
 *
 * @package    Wp_Plugin_Eventaservo_Api
 * @subpackage Wp_Plugin_Eventaservo_Api/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Plugin_Eventaservo_Api
 * @subpackage Wp_Plugin_Eventaservo_Api/includes
 * @author     Paul Würtz <paulwuertz@posteo.de>
 */
class Eventaservo_Api_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'eventaservo-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
