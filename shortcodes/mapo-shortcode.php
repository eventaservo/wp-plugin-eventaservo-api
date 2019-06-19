<?php

class Eventaservo_Api_Shortcode_Mapo
{
    protected $map_id = 0;
    private $eventJSON;
    private $settings;

    public function getEventJSON(){
        //get event JSON
        $mail = $this->settings->get('user_mail');
        $api_key = $this->settings->get('user_token');
        $date_start = $this->settings->get('date_start');
        $date_end = $this->settings->get('date_end');
        $filters = "";
        $url = "https://eventaservo.org/api/v1/events.json?user_email=$mail&user_token=$api_key&komenca_dato=$date_start&fina_dato=$date_end"; //&$filters
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $json_string = json_encode($obj, JSON_UNESCAPED_UNICODE);
        return $json_string;
    }

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
        $settings = $this->settings;

        //TODO pensu pri caching
        //$time = $this->settings->get('lastEventFetch');
        //if ($time + 1000 * 60 * 15 < microtime(true)) {
        //  $this->eventJSON = $settings->get('eventJSON');
        //} else {
        //  $this->eventJSON = $this->getEventJSON();
        //  $settings->set('lastEventFetch', $time);
        //  $settings->set('eventJSON', $this->eventJSON);
        //}
        $eventoj = $this->getEventJSON();
        $lat = empty($lat) ? $settings->get('cord-lat') : $lat;
        $lng = empty($lng) ? $settings->get('cord-lon') : $lng;
        $zoom = empty($zoom) ? $settings->get('zoom-start') : $zoom;
        $tileurl = empty($tileurl) ? $settings->get('map_tile_url') : $tileurl;
        /* should be iterated for multiple maps */
        ob_start(); ?>
        <div id="eventaservo_leaflet-map" class="leaflet-map"
            style="height:<?php echo $height; ?>; width:<?php echo $width; ?>;"></div>  <!-- php echo $this->map_id;  -->
        <script type="text/javascript">
        var eventaservo_settings = {
          eventdatoj: <?php echo $eventoj?>,
          mapo: {
              map_tile_url: '<?php echo $tileurl?>',
              max_zoom: <?php echo $max_zoom; ?>,
              min_zoom: <?php echo $min_zoom; ?>,
              zoomcontrol: <?php echo $zoomcontrol; ?>,
              scrollwheel: <?php echo $scrollwheel; ?>,
              doubleclickzoom: <?php echo $doubleclickzoom; ?>,
              more_options: <?php echo $more_options; ?>,
              lat: <?php echo $lat ?>,
              lng: <?php echo $lng ?>,
              zoom: <?php echo $zoom ?>,
          }
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
        $this->settings = Eventaservo_Plugin_Settings::init();
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
