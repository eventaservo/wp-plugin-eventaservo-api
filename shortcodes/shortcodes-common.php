<?php

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
