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
<div class="wrap">
    <h1><?php echo $title; ?> <small>version: <?php echo $version; ?></small></h1>
    <p><?php echo $description; ?></p>
<?php
if (isset($_POST['submit'])) {
    /* copy and overwrite $post for checkboxes */
    $form = $_POST;

    foreach ($settings->api_options as $name => $option) {
        /* checkboxes don't get sent if not checked */
        $value = trim( stripslashes( $form[$name]) );
        $settings->set($name, $value);
    }
?>
<div class="notice notice-success is-dismissible">
    <p><?php _e('Options Updated!', 'eventaservo-api'); ?></p>
</div>
<?php
} elseif (isset($_POST['reset'])) {
    $settings->reset();
?>
<div class="notice notice-success is-dismissible">
    <p><?php _e('Options have been reset to default values!', 'eventaservo-api'); ?></p>
</div>
<?php
}
?>
  <div class="wrap">
  <form method="post">
      <div class="container">
          <h2><?php _e('Settings', 'eventaservo-api'); ?></h2>
          <hr>
      </div>

      <div class="container">
          <h3>API Data (Mandatory fields):</h3>
          <div>
            <div>
                <label class="label">Your Eventaservo login (email):</label>
                <input id="user_mail" name="user_mail" type="email" placeholder="your@mail.here" value="<?php echo htmlspecialchars($settings->get("user_mail")); ?>">
            </div>
            <div>
              <label class="label">Your Eventaservo API Token:</label>
              <input id="user_token" name="user_token" type="text" placeholder="Your API key here" value="<?php echo htmlspecialchars($settings->get("user_token")); ?>">
            </div>
        </div>
      </div>
      <h3>Standart API filters:</h3>
      <p>These filter value will be applied to any shortcodes used in your sided, but they can be overwritten</p>
      <hr>
      <div class="container">
          <div>
              <label class="label">User event filter:</label>
              <input id="events_from_user" name="events_from_user" type="text" placeholder="If empty, show events of all users..." value="<?php echo htmlspecialchars($settings->get("events_from_user")); ?>">
          </div>
          <div>
              <label class="label">Start date:</label>
              <input id="date_start" name="date_start" type="date" placeholder="today" value="<?php echo htmlspecialchars($settings->get("date_start")); ?>">
          </div>
          <div>
              <label class="label">End date:</label>
              <input id="date_end" name="date_end" type="date" placeholder="today + 1 year" value="<?php echo htmlspecialchars($settings->get("date_end")); ?>">
          </div>
      </div>
  <div class="submit">
      <input type="submit"
          name="submit"
          id="submit"
          class="button button-primary"
          value="<?php _e('Save Changes', 'eventaservo-api'); ?>">
      <input type="submit"
          name="reset"
          id="reset"
          class="button button-secondary"
          value="<?php _e('Reset to Defaults', 'eventaservo-api'); ?>">
  </div>

  </form>
  </div>
</div>
