<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/eventaservo-api-admin.php';

function getEventJSON(){
    $settings = Eventaservo_Plugin_Settings::init();

    $cached_at = $settings->get('cached_at');
    $cache_time = $settings->get('cache_time');
    if($cached_at && (time()-($cache_time)) < $cached_at){
      return $settings->get('event_json');
    } else {
        //get event JSON
        $mail = $settings->get('user_mail');
        $api_key = $settings->get('user_token');
        $date_start = $settings->get('date_start');
        $date_end = $settings->get('date_end');
        $filters = "";
        $url = "https://eventaservo.org/api/v1/events.json?user_email=$mail&user_token=$api_key&komenca_dato=$date_start&fina_dato=$date_end"; //&$filters
        $json = file_get_contents($url);
        $obj = json_decode($json);
        $json_string = json_encode($obj, JSON_UNESCAPED_UNICODE);
        $settings->set('cached_at', time());
        $settings->set('event_json', $json_string);
        return $json_string;
    }
}
