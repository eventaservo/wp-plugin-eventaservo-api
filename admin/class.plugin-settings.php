<?php
/**
 * Class for getting and setting db/default values
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get and set options:
 *  * Add prefixes to db options
 *  * built-in admin settings page method
 */
class Eventaservo_Plugin_Settings
{
    public $prefix = 'eventaservo_';

    //Singleton instance
    private static $_instance = null;
    public $options = array();

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private function __construct()
    {
        /* update leaflet version from main class */
        $leaflet_version = Eventaservo_Api_Admin::$leaflet_version;
        $this->api_options = array(
            'user_mail'  => '',
            'user_token' => '',
            'events_from_user' => '',
            'date_start' => '',
            'date_end'   => '',
        );
        $this->map_options = array(
            'show_zoom_controls' => '0',
            'allow_map_scroll' => '1',
            'map_tile_url' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'leaflet_js_url' => sprintf('https://unpkg.com/leaflet@%s/dist/leaflet.js', $leaflet_version),
            'leaflet_cluster_js_url' => sprintf('https://unpkg.com/leaflet@%s/dist/leaflet.js', $leaflet_version),
            'leaflet_prunecluster_js_url' => sprintf('https://unpkg.com/leaflet@%s/dist/leaflet.js', $leaflet_version),
            'leaflet_css_url' => sprintf('https://unpkg.com/leaflet@%s/dist/leaflet.css', $leaflet_version),
            "cord-lat"     => "0.000000",
            "cord-lon"     => "0.000000",
            "zoom-min"     => "1",
            "zoom-max"     => "18",
            "zoom-start"   => "5",
            "map-width"    => "100%",
            "map-height"   => "500px",
        );

        $this->calendar_options = array(
            "calendar-width"    => "100%",
            "calendar-height"   => "500px",
            'fullcalendar_js_url' => sprintf('https://unpkg.com/fullcalendar@3.10.0/dist/fullcalendar.js', $leaflet_version),
            'fullcalendar_css_url' => sprintf('https://unpkg.com/fullcalendar@3.10.0/dist/fullcalendar.css', $leaflet_version),
        );
    }

    public function get($key)
    {
        $default = $this->options[ $key ]->default;
        $key = $this->prefix . $key;
        return get_option($key, $default);
    }

    public function set ($key, $value) {
        $key = $this->prefix . $key;
        update_option($key, $value);
        return $this;
    }

    public function delete($key)
    {
        $key = $this->prefix . $key;
        return delete_option($key);
    }

    public function reset()
    {
        foreach ($this->options as $name => $option) {
            if (
                !array_key_exists('noreset', $options) ||
                $options['noreset'] != true
            ) {
                $this->delete($name);
            }
        }
        return $this;
    }
}
