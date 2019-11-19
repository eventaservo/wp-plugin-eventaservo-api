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
    private static $_default_options;
    public $options;

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function resetDefaultSettings(){
      foreach (self::$_default_options as $name => $option) {
          self::$_instance->set($name, $option);
      }
    }

    private function __construct()
    {
        /* update leaflet version from main class */
        $leaflet_version = Eventaservo_Api_Admin::$leaflet_version;
        $this->api_options = array(
            'user_mail'        => '',
            'user_token'       => '',
            'events_from_user' => '',
            'date_start'       => '',
            'date_end'         => '',
            'country_filter'   => '',
            'country_radius'   => 0,
            'cache_time'       => 900,
        );
        $this->map_options = array(
            "clustering"   => "no",
            "map-width"    => "100%",
            "map-height"   => "500px",
            "cord-lat"     => "0.000000",
            "cord-lon"     => "0.000000",
            "zoom-min"     => "1",
            "zoom-max"     => "18",
            "zoom-start"   => "5",
            'map_tile_url' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'show_zoom_controls' => '1',
            'allow_map_scroll' => '1',
            'doubleclickzoom' => '1',
            'scrollwheel' => '1',
        );

        $this->calendar_options = array(
            "calendar-width"    => "100%",
            "calendar-height"   => "500px",
            "list_view"   => '1',
        );
        $options = array_merge($this->api_options, $this->map_options, $this->calendar_options);
        self::$_default_options = array_merge($this->api_options, $this->map_options, $this->calendar_options);
    }

    public function get($key)
    {
        $default = $this->options[ $key ];
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
