<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/eventaservo-api-admin.php';
$settings = Eventaservo_Plugin_Settings::init();

class Eventaservo_Api_Shortcode_Kalendaro
{
    protected $map_id = 0;
    private $eventJSON;

    /**
     * Get script for shortcode
     *
     * @param array  $atts    sometimes this is null
     * @param string $content anything within a shortcode
     *
     * @return string HTML
     */
    protected function getHTML($atts='', $content=null){
        extract($this->getAtts($atts));
        $settings = Eventaservo_Plugin_Settings::init();
        $lat = empty($lat) ? $settings->get('cord-lat') : $lat;
        $lng = empty($lng) ? $settings->get('cord-lon') : $lng;
        $tileurl = empty($tileurl) ? $settings->get('map_tile_url') : $tileurl;
        $list_view = empty($list_view) ? $settings->get('list_view') : $list_view;
        /* should be iterated for multiple maps */
        ob_start(); ?>
        <div id="kalendaro" class="leaflet-map"
            style="height:<?php echo $height; ?>; width:<?php echo $width; ?>;"></div> <!--TODO -<?php echo $this->map_id; ?>-->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script type="text/javascript">
        eventaservo_settings.kalendaro = {
          list_view: <?php echo $list_view ?>,
        };
        </script>
        <?php
        return ob_get_clean();
    }

    public static function getClass(){
        return function_exists('get_called_class') ? get_called_class() : __CLASS__;
    }

    /**
     * Instantiate class and get HTML for shortcode
     *
     * @param array  $atts    string|array
     * @param string $content Optional
     *
     * @return string (see above)
     */
    public static function shortcode($atts = '', $content = null){
        $class = self::getClass();
        $instance = new $class();

        if (!empty($atts)) {
            foreach($atts as $k => $v) {
                if (
                    is_numeric($k) &&
                    !key_exists($v, $atts) &&
                    !!$v
                ) {
                    // false if starts with !, else true
                    if ($v[0] == '!') {
                        $atts[substr($v, 1)] = 0;
                    } else {
                        $atts[$v] = 1;
                    }
                }
            }
        }
        return $instance->getHTML($atts, $content);
    }

    protected function __construct(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class.plugin-settings.php';
    }

    protected function getAtts($atts=''){
        $atts = (array) $atts;
        extract($atts);

        $settings = Eventaservo_Plugin_Settings::init();

        $atts['zoom'] = array_key_exists('zoom', $atts) ?
            $zoom : $settings->get('zoom-start');
        $atts['height'] = empty($height) ?
            $settings->get('map-height') : $height;
        $atts['width'] = empty($width) ? $settings->get('map-width') : $width;
        $atts['zoomcontrol'] = array_key_exists('zoomcontrol', $atts) ?
            $zoomcontrol : $settings->get('show_zoom_controls');
        $atts['min_zoom'] = array_key_exists('min_zoom', $atts) ?
            $min_zoom : $settings->get('zoom-min');
        $atts['max_zoom'] = empty($max_zoom) ?
            $settings->get('zoom-max') : $max_zoom;
        $atts['scrollwheel'] = true;//array_key_exists('scrollwheel', $atts) ?
            //$scrollwheel : $settings->get('scroll_wheel_zoom');
        $atts['doubleclickzoom'] = true;//array_key_exists('doubleclickzoom', $atts) ?
        $atts['list_view'] = true;//array_key_exists('doubleclickzoom', $atts) ?
            //$doubleclickzoom : $settings->get('double_click_zoom');

        // @deprecated backwards-compatible fit_markers
        $atts['fit_markers'] = array_key_exists('fit_markers', $atts) ?
            $fit_markers : $settings->get('fit_markers');

        // fitbounds is what it should be called @since v2.12.0
        $atts['fitbounds'] = array_key_exists('fitbounds', $atts) ?
            $fitbounds : $atts['fit_markers'];

        /* allow percent, but add px for ints */
        $atts['height'] .= is_numeric($atts['height']) ? 'px' : '';
        $atts['width'] .= is_numeric($atts['width']) ? 'px' : '';

        // maxbounds as string: maxbounds="50, -114; 52, -112"
        $maxBounds = isset($maxbounds) ? $maxbounds : null;

        if ($maxBounds) {
            try {
                // explode by semi-colons and commas
                $maxBounds = preg_split("[;|,]", $maxBounds);
                $maxBounds = array(
                    array(
                        $maxBounds[0], $maxBounds[1]
                    ),
                    array(
                        $maxBounds[2], $maxBounds[3]
                    )
                );
            } catch (Exception $e) {
                $maxBounds = null;
            }
        }

        /*
        need to allow 0 or empty for removal of attribution
        */
        if (!array_key_exists('attribution', $atts)) {
            $atts['attribution'] = $settings->get('default_attribution');
        }

        /* allow a bunch of other options */
        // http://leafletjs.com/reference-1.0.3.html#map-closepopuponclick
        $more_options = array(
            'closePopupOnClick' => isset($closepopuponclick) ?
                $closepopuponclick : null,
            'trackResize' => isset($trackresize) ? $trackresize : null,
            'boxZoom' => isset($boxzoom)
                ? $boxzoom
                : isset($boxZoom)
                    ? $boxZoom
                    : null,
            'touchZoom' => isset($touchZoom) ? $touchZoom : null,
            'dragging' => isset($dragging) ? $dragging : null,
            'keyboard' => isset($keyboard) ? $keyboard : null,
        );

        // filter out nulls
        //$more_options = $this->LM->filter_null($more_options);

        // change string booleans to booleans
        $more_options = filter_var_array($more_options, FILTER_VALIDATE_BOOLEAN);

        if ($maxBounds) {
            $more_options['maxBounds'] = $maxBounds;
        }

        // wrap as JSON
        if ($more_options) {
            $more_options = json_encode($more_options);
        } else {
            $more_options = '{}';
        }

        $atts['more_options'] = $more_options;

        return $atts;
    }

}
