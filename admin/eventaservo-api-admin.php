<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.p4w5.eu/blog/
 * @since      1.0.0
 */

 include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class.plugin-settings.php';

class Eventaservo_Api_Admin {

    private $plugin_name;
    private $version;
    public static $leaflet_version = '1.4.0';

    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('admin_menu', array($this, 'admin_menu'));

    }

    /**
     * Add admin menu page when user in admin area
     */
    public function admin_menu()
    {   //if not admin only show preview, no options
        if (current_user_can('manage_options')) {
                $main_link = 'eventaservo-api';
        } else {
                $main_link = 'leaflet-shortcode-helper';
        }

        add_menu_page("Eventaservo API", "Eventaservo Api", 'manage_options', $main_link, array($this, "api_settings_page"), plugins_url('admin/assets/es.png', EVENTASERVO_API__PLUGIN_FILE));
        add_submenu_page("eventaservo-api", "Map Settings", "Map Settings", 'manage_options', "eventaservo-map", array($this, "map_settings_page"));
        add_submenu_page("eventaservo-api", "Calendar Settings", "Calendar Settings", 'manage_options', "eventaservo-calendar", array($this, "calendar_settings_page"));
        add_submenu_page("eventaservo-api", "Shortcode Helper", "Shortcode Helper", 'edit_posts', "eventaservo-shortcode-helper", array($this, "shortcode_page"));
    }

    public function api_settings_page()
    {
        wp_enqueue_style('leaflet_admin_stylesheet');
        $settings = Eventaservo_Plugin_Settings::init();
        $plugin_data = get_plugin_data(EVENTASERVO_API__PLUGIN_FILE);
        include 'partials/settings_api.php';
    }

    public function map_settings_page()
    {
        wp_enqueue_style('leaflet_admin_stylesheet');
        $settings = Eventaservo_Plugin_Settings::init();
        $plugin_data = get_plugin_data(EVENTASERVO_API__PLUGIN_FILE);
        include 'partials/settings_map.php';
    }

    public function calendar_settings_page()
    {
        wp_enqueue_style('leaflet_admin_stylesheet');
        $settings = Eventaservo_Plugin_Settings::init();
        $plugin_data = get_plugin_data(EVENTASERVO_API__PLUGIN_FILE);
        include 'partials/settings_calendar.php';
    }

    public function shortcode_page()
    {
        $plugin_data = get_plugin_data(EVENTASERVO_API__PLUGIN_FILE);
        include 'partials/shortcode_page.php';
    }

    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-plugin-eventaservo-api-admin.js', array( 'jquery' ), $this->version, false );
    }

}
