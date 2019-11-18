<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.p4w5.eu/blog/
 * @since      1.0.0
 */

$title = $plugin_data['Name'];
$description = __('A plugin for using the eventaservo.org API in wordpress.', 'eventaservo-api');
$version = $plugin_data['Version'];
?>
<script type="text/javascript">
var eventaservo_settings = {
  eventdatoj: <?php echo $eventoj?>,
};
</script>

<div class="wrap">
    <h1><?php echo $title; ?> <small>version: <?php echo $version; ?></small></h1>
    <p><?php echo $description; ?></p>
    <div class="wrap">
        <div class="wrap">
          <div class="container">
              <h2><?php _e('Shortcode helper', 'eventaservo-api'); ?></h2>
              <hr>
          </div>
          <div class="container">
            <h4>Calendar Examples:</h4>
            <hr>
            <?php echo do_shortcode("[evento_mapo]") ?>
            <p>...</p>
            <ul>
              <li>bla</li>
            </ul>
            <h4>Map Examples:</h4>
            <hr>
            <p>...</p>
            <h4>Calendar Examples:</h4>
            <?php echo do_shortcode("[evento_kalendaro]") ?>
        </div>
    </div>
