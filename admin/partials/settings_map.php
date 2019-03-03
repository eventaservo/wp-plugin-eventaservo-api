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

    foreach ($settings->map_options as $name => $option) {
        /* checkboxes don't get sent if not checked */
        if ($option->type === 'checkbox') {
            $form[$name] = isset($_POST[ $name ]) ? 1 : 0;
        }
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
} elseif (isset($_POST['clear-geocoder-cache'])) {
    echo "HALLO";
?>
<div class="notice notice-success is-dismissible">
    <p><?php _e('Location caches have been cleared!', 'eventaservo-api'); ?></p>
</div>
<?php
}
?>
<div class="wrap">
    <div class="wrap">
    <form method="post">
        <div class="container">
            <h2><?php _e('Map Settings', 'eventaservo-api'); ?></h2>
            <hr>
        </div>
        <div class="container">
          <h4>Map Apperance:</h4>
          <hr>
          <div class="">
              <label class="label">Marker Clustering:</label>
              <div>
                <input type="radio" name="clustering" id="no-clustering" value="no">
                <label for="no-clustering">Male</label>
              </div>
              <div>
                <input type="radio" name="clustering" id="markercluster-clustering" value="markercluster">
                <label for="markercluster-clustering">Marker Cluster</label>
              </div>
              <div>
                <input type="radio" name="clustering" id="prunecluster-clustering" value="prunecluster">
                <label for="prunecluster-clustering">Prunecluster</label>
              </div>
          </div>
          <div class="">
              <label class="label">Width:</label>
              <input id="map-width" name="map-width" type="text" placeholder="100%">
          </div>
          <div class="">
              <label class="label">Height:</label>
              <input id="map-height" name="map-height" type="text" placeholder="500px">
          </div>
        </div>
        <div class="container">
          <h4>Start Coordinates:</h4>
          <hr>
          <div class="">
              <label class="label">Lat:</label>
              <input id="cord-lat" name="cord-lat" type="number" placeholder="53.350140" step="0.000001" min="-180" max="180">
          </div>
          <div class="">
              <label class="label">Lon:</label>
              <input id="cord-lon" name="cord-lon" type="number" placeholder="-6.266155" step="0.000001" min="-90" max="90">
          </div>
          <p>Or choose a start country, beeing focused on page load:</p>
          <div class="">
              <label class="label">Start Country:</label>
              <select style="float:right;" name="country">
                <option value="">-----------------</option>
                <?php
                $str = file_get_contents( dirname( __FILE__ )  . '/../assets/countries.json');
                $j = json_decode($str, true);
                foreach($j as $key => $value){
                  echo '<option value="' . $value['latlng'] . '">'.$value['name'].'</option>'; //close your tags!!
                }
                ?>
              </select>
          </div>
          </span>
        </div>
        <div class="container">
            <h4>Zoom Settings:</h4>
            <hr>
            <div class="">
                <label class="label">Minimal zoom:</label>
                <input id="zoom-min"   name="zoom-min"   type="number" placeholder="1" step="1" min="1" max="18">
            </div>
            <div class="">
                <label class="label">Maximal zoom:</label>
                <input id="zoom-max"   name="zoom-max"   type="number" placeholder="18" step="1" min="1" max="18">
            </div>
            <div class="">
                <label class="label">Start zoom:</label>
                <input id="zoom-start" name="zoom-start" type="number" placeholder="5" step="1" min="1" max="18">
            </div>
        </div>
        <div class="container">
            <h4>Config CDN URLs</h4>
            <hr>
              <div class="">
                  <label class="label">Tile URL:</label>
                  <input id="map_tile_url" name="map_tile_url" type="url" placeholder="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png">
              </div>
              <div>
                <label class="label">Leaflet JS:</label>
                <input id="js_url" name="js_url" type="url" placeholder="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js">
              </div>
              <div>
                  <label class="label">Leaflet CSS:</label>
                  <input id="css_url" name="css_url" type="url" placeholder="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css">
              </div>
              <h4>Other Options</h4>
              <hr>
              <div class="">
                  <label class="label">Show Zoom Controls:</label>
                  <input id="show_zoom_controls" name="show_zoom_controls" type="checkbox">
              </div>
              <div class="">
                  <label class="label">Allow map scroll:</label>
                  <input id="allow_map_scroll" name="allow_map_scroll" type="checkbox">
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
        <input type="submit"
            name="clear-geocoder-cache"
            id="clear-geocoder-cache"
            class="button button-secondary"
            value="<?php _e('Clear Geocoder Cache', 'eventaservo-api'); ?>">
    </div>

    </form>
    </div>
</div>
