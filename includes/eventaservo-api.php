<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.p4w5.eu/blog/
 * @since      1.0.0
 *
 * @package    Wp_Plugin_Eventaservo_Api
 * @subpackage Wp_Plugin_Eventaservo_Api/includes
 */

/**
 * @since      1.0.0
 * @package    Wp_Plugin_Eventaservo_Api
 * @subpackage Wp_Plugin_Eventaservo_Api/includes
 * @author     Paul Würtz <paulwuertz@posteo.de>
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/eventaservo-api-admin.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/shortcodes-common.php';
$settings = Eventaservo_Plugin_Settings::init();

class Eventaservo_Api {

	protected $loader;
	protected $plugin_name;
	protected $version;
	private $_shortcodes = array(
		'evento_mapo' => array(
			'file' => 'mapo-shortcode.php',
			'class' => 'Eventaservo_Api_Shortcode_Mapo'
		),
    'evento_kalendaro' => array(
			'file' => 'kalendaro-shortcode.php',
			'class' => 'Eventaservo_Api_Shortcode_Kalendaro'
		)
	);

	public function __construct(){
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'eventaservo-api';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->add_shortcodes();
	}

	private function add_shortcodes(){
		// shortcodes
		$shortcode_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/';

		foreach ($this->_shortcodes as $shortcode => $details) {
				include_once $shortcode_dir . $details['file'];
				add_shortcode($shortcode, array($details['class'], 'shortcode'));
		}
	}

private function load_dependencies(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/eventaservo-api-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/eventaservo-api-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/eventaservo-api-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/eventaservo-api-public.php';

		$this->loader = new Eventaservo_Api_Loader();

		add_action( 'wp_enqueue_scripts', array('Eventaservo_Api', 'enqueues' ));
	}

	public static function enqueues(){
		global $post;
    $cont = $post->post_content;
    $has_mapo = has_shortcode($cont, "evento_mapo");
    $has_kalendaro = has_shortcode($cont, "evento_kalendaro");
    $eventoj = getEventJSON();

    ?>
    <script type="text/javascript">
    var eventaservo_settings = {
      eventdatoj: <?php echo $eventoj?>,
    };
    </script>
    <?php
    $settings = Eventaservo_Plugin_Settings::init();
    if ($has_mapo || $has_kalendaro) {
        wp_enqueue_script("momentjs", 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js', array(), 0, true);
    }
    if ($has_mapo) {
        wp_enqueue_style('leaflet_stylesheet', $settings->get("leaflet_css_url"));
        wp_enqueue_script('wp_leaflet_map_js', $settings->get("leaflet_js_url"));
        wp_enqueue_script("PruneCluster", plugin_dir_url( __FILE__ ) . '../public/js/PruneCluster.js', array(), 0, true);
    }
    if ($has_kalendaro) {
        wp_enqueue_script("qtip", 'https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/basic/jquery.qtip.js', array(), 0, true);
        wp_enqueue_style('fullcalendar_stylesheet', $settings->get("fullcalendar_css_url"));
    		wp_enqueue_script('wp_fullcalendar_js', $settings->get("fullcalendar_js_url"), array(), 0, true);
    }
    if ($has_mapo || $has_kalendaro) {
      wp_enqueue_script("eventaservo_js" , plugin_dir_url( __FILE__ ) . '../public/js/wp-plugin-eventaservo-api-public.js', array( 'jquery' ), 0, false );
      wp_enqueue_style( "eventaservo_css", plugin_dir_url( __FILE__ ) . '../public/css/wp-plugin-eventaservo-api-public.css', array(), 0, 'all' );
    }
	}

	private function set_locale(){
		$plugin_i18n = new Eventaservo_Api_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks(){
		$plugin_admin = new Eventaservo_Api_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	private function define_public_hooks(){
		$plugin_public = new Eventaservo_Api_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	public function run(){
		$this->loader->run();
	}

	public function get_plugin_name(){
		return $this->plugin_name;
	}

	public function get_loader(){
		return $this->loader;
	}

	public function get_version(){
		return $this->version;
	}

}
