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
        if ($name == 'show_zoom_controls' || $name == 'allow_map_scroll' || $name == 'scrollwheel' || $name == 'doubleclickzoom'
        ) {
            $form[$name] = $settings->set($name, isset($_POST[ $name ]) ? 1 : 0);
            continue;
        }
        if ($name == 'allow_map_scroll' && !isset($_POST['clustering']))
            continue;
        $value = trim( stripslashes( $form[$name]) );
        $settings->set($name, $value);
    }
?>
<div class="notice notice-success is-dismissible">
    <p><?php _e('Options Updated!', 'eventaservo-api'); ?></p>
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
                <input type="radio" name="clustering" id="no-clustering" value="no" <?php if ($settings->get("clustering")=="no") {echo "checked";} ?>>
                <label for="no-clustering">No clustering</label>
              </div>
              <div>
                <input type="radio" name="clustering" id="markercluster-clustering" value="markercluster" <?php if ($settings->get("clustering")=="markercluster") {echo "checked";} ?>>
                <label for="markercluster-clustering">Marker Cluster</label>
              </div>
              <div>
                <input type="radio" name="clustering" id="prunecluster-clustering" value="prunecluster" <?php if ($settings->get("clustering")=="prunecluster") {echo "checked";} ?>>
                <label for="prunecluster-clustering">Prunecluster</label>
              </div>
          </div>
          <div class="">
              <label class="label">Width:</label>
              <input id="map-width" name="map-width" type="text" placeholder="100%" value="<?php echo htmlspecialchars($settings->get("map-width")); ?>">
          </div>
          <div class="">
              <label class="label">Height:</label>
              <input id="map-height" name="map-height" type="text" placeholder="500px" value="<?php echo htmlspecialchars($settings->get("map-height")); ?>">
          </div>
        </div>
        <div class="container">
          <h4>Start Coordinates:</h4>
          <hr>
          <div class="">
              <label class="label">Lat:</label>
              <input id="cord-lat" name="cord-lat" type="number" placeholder="53.350140" step="0.000001" min="-180" max="180" value="<?php echo htmlspecialchars($settings->get("cord-lat")); ?>">
          </div>
          <div class="">
              <label class="label">Lon:</label>
              <input id="cord-lon" name="cord-lon" type="number" placeholder="-6.266155" step="0.000001" min="-90" max="90" value="<?php echo htmlspecialchars($settings->get("cord-lon")); ?>">
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
                <input id="zoom-min"   name="zoom-min"   type="number" placeholder="1" step="1" min="1" max="18" value="<?php echo htmlspecialchars($settings->get("zoom-min")); ?>">
            </div>
            <div class="">
                <label class="label">Maximal zoom:</label>
                <input id="zoom-max"   name="zoom-max"   type="number" placeholder="18" step="1" min="1" max="18" value="<?php echo htmlspecialchars($settings->get("zoom-max")); ?>">
            </div>
            <div class="">
                <label class="label">Start zoom:</label>
                <input id="zoom-start" name="zoom-start" type="number" placeholder="5" step="1" min="1" max="18" value="<?php echo htmlspecialchars($settings->get("zoom-start")); ?>">
            </div>
        </div>
        <div class="container">
            <h4>Config CDN URLs</h4>
            <hr>
              <div class="">
                  <label class="label">Tile URL:</label>
                  <input id="map_tile_url" name="map_tile_url" type="url" placeholder="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" value="<?php echo htmlspecialchars($settings->get("map_tile_url")); ?>">
              </div>
              <div>
                <label class="label">Leaflet JS:</label>
                <input id="leaflet_js_url" name="leaflet_js_url" type="url" placeholder="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" value="<?php echo htmlspecialchars($settings->get("leaflet_js_url")); ?>">
              </div>
              <div>
                  <label class="label">Leaflet CSS:</label>
                  <input id="leaflet_css_url" name="leaflet_css_url" type="url" placeholder="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" value="<?php echo htmlspecialchars($settings->get("leaflet_css_url")); ?>">
              </div>
              <div>
                <label class="label">Leaflet Cluster JS:</label>
                <input id="leaflet_cluster_js_url" name="leaflet_cluster_js_url" type="url" placeholder="https://unpkg.com/leaflet.markercluster@1.4.1/dist/" value="<?php echo htmlspecialchars($settings->get("leaflet_cluster_js_url")); ?>">
              </div>
              <h4>Other Options</h4>
              <hr>
              <div class="">
                  <label class="label">Show Zoom Controls:</label>
                  <input id="show_zoom_controls" name="show_zoom_controls" type="checkbox" <?php if ($settings->get("show_zoom_controls")=="1") {echo "checked";} ?>>
              </div>
              <div class="">
                  <label class="label">Allow map scroll:</label>
                  <input id="allow_map_scroll" name="allow_map_scroll" type="checkbox" <?php if ($settings->get("allow_map_scroll")=="1") {echo "checked";} ?>>
              </div>
              <div class="">
                  <label class="label">Scrollwheel:</label>
                  <input id="scrollwheel" name="scrollwheel" type="checkbox" <?php if ($settings->get("scrollwheel")=="1") {echo "checked";} ?>>
              </div>
              <div class="">
                  <label class="label">Doubleclickzoom:</label>
                  <input id="doubleclickzoom" name="doubleclickzoom" type="checkbox" <?php if ($settings->get("doubleclickzoom")=="1") {echo "checked";} ?>>
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
