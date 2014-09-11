<?php
/*
Plugin Name: wpMAPS
Plugin URI: http://playground.ebiene.de/1379/wpmaps-wordpress-google-maps/
Description: wpMAPS generiert eine oder mehrere Google Maps direkt im Blogartikel oder nach Aufruf einer internen PHP-Funktion aus dem Template des verwendeten Theme heraus.
Author: Sergej M&uuml;ller
Version: 0.3
Author URI: http://www.wpSEO.org
*/


class wpMAPS {
function wpMAPS() {
if (!class_exists('WPlize')) {
require_once('inc/wplize.class.php');
}
$GLOBALS['MP_WPlize'] = new WPlize('wpmaps');
if (!defined('PLUGINDIR')) {
define('PLUGINDIR', 'wp-content/plugins');
}
if (!defined('MPBASENAME')) {
define('MPBASENAME', plugin_basename(__FILE__));
}
$this->init_default_options();
if (is_admin()) {
add_action(
'admin_menu',
array(
$this,
'init_admin_menu'
)
);
add_action(
'activate_' .MPBASENAME,
array(
$this,
'init_plugin_options'
)
);
add_filter(
'plugin_action_links',
array(
$this,
'init_action_links'
),
10,
2
);
add_action(
'admin_menu',
array(
$this,
'init_custom_options'
)
);
add_action(
'save_post',
array(
$this,
'save_custom_options'
)
);
} else {
add_action(
'wp_print_scripts',
array(
$this,
'show_enqueue_script'
)
);
add_shortcode(
'wpmaps',
array(
$this,
'convert_user_shortcode'
)
);
add_action(
'template_redirect',
array(
$this,
'replace_cdata_part'
),
1
);
}
}
function replace_cdata_part() {
if (is_feed() || is_trackback()) {
return;
}
ob_start(
create_function(
'$input',
'return preg_replace("#<script(.*?)\/\* \]\]&gt; \*\/<\/script>#", "<script$1/* ]]> */</script>", $input);'
)
);
}
function check_current_post() {
if ($posts = $GLOBALS['MP_WPlize']->get_option('posts')) {
$ids = explode(
',',
str_replace(
' ',
'',
$posts
)
);
if ($ids && !in_array((is_admin() ? $_GET['post'] : $GLOBALS['wp_query']->get_queried_object_id()), $ids)) {
return false;
}
}
return true;
}
function show_enqueue_script() {
if ($GLOBALS['MP_WPlize']->get_option('key') && $this->check_current_post()) {
wp_enqueue_script(
'gmaps',
'http://maps.google.com/maps?file=api&amp;v=2&amp;key=' .$GLOBALS['MP_WPlize']->get_option('key'),
array(
'jquery'
)
);
}
}
function init_default_options() {
$this->type_arr = array(
'G_NORMAL_MAP'=> __('Normal', 'wpmaps'),
'G_SATELLITE_MAP' => __('Satellit', 'wpmaps'),
'G_HYBRID_MAP'=> __('Hybrid', 'wpmaps')
);
$this->mapcontrol_arr = array(
'-1'=> __('Kein', 'wpmaps'),
'GSmallMapControl'=> __('Schaltflächen', 'wpmaps'),
'GLargeMapControl'=> __('Schaltflächen + Zoom', 'wpmaps')
);
$this->typecontrol_arr = array(
'-1'=> __('Kein', 'wpmaps'),
'GMapTypeControl'=> __('Als Schaltflächen', 'wpmaps'),
'GMenuMapTypeControl'=> __('Als Dropdown', 'wpmaps'),
'GHierarchicalMapTypeControl'=> __('Verschachtelt', 'wpmaps')
);
$this->info_arr = array(
'-1'=> __('Ausblenden', 'wpmaps'),
'1'=> __('Einblenden', 'wpmaps')
);
$this->fields = array(
'x',
'y',
'street',
'city',
'company',
'width',
'height',
'type',
'zoom',
'info',
'mapcontrol',
'typecontrol'
);
}
function init_action_links($links, $file) {
if ($file == MPBASENAME) {
return array_merge(
array(
sprintf(
'<a href="options-general.php?page=%s">%s</a>',
MPBASENAME,
__('Settings')
)
),
$links
);
}
return $links;
}
function init_plugin_options() {
$GLOBALS['MP_WPlize']->init_option(
array(
'width'=> 600,
'height'=> 300,
'type'=> 'G_NORMAL_MAP',
'zoom'=> 10,
'info'=> 1,
'mapcontrol'=> 'GSmallMapControl',
'typecontrol'=> 'GMenuMapTypeControl',
'key'=> '',
'posts'=> ''
)
);
}
function init_admin_menu() {
add_options_page(
'wpMAPS',
($this->check_plugins_url() ? '<img src="' .plugins_url('wpmaps/img/icon.png'). '" width="11" height="9" border="0" alt="wpMAPS Icon" />' : ''). 'wpMAPS',
9,
__FILE__,
array(
$this,
'show_admin_menu'
)
);
}
function check_plugins_url() {
return version_compare($GLOBALS['wp_version'], '2.6.999', '>') && function_exists('plugins_url');
}
function check_user_can() {
if (current_user_can('manage_options') === false || current_user_can('edit_plugins') === false || !is_user_logged_in()) {
wp_die('You do not have permission to access!');
}
}
function show_plugin_info() {
$data = get_plugin_data(__FILE__);
echo sprintf(
'%1$s: %2$s | %3$s: %4$s | %5$s: %6$s | %7$s: <a href="http://playground.ebiene.de/donate/">PayPal</a><br />',
__('Plugin'),
'wpMAPS',
__('Version'),
$data['Version'],
__('Author'),
$data['Author'],
__('Spende', 'wpmaps')
);
}
function show_admin_menu() {
$this->check_user_can();
if (isset($_POST) && !empty($_POST)) {
check_admin_referer('wpmaps');
$GLOBALS['MP_WPlize']->update_option(
array(
'width'=> intval($_POST['wpmaps_width']),
'height'=> intval($_POST['wpmaps_height']),
'type'=> $_POST['wpmaps_type'],
'zoom'=> intval($_POST['wpmaps_zoom']),
'info'=> (intval($_POST['wpmaps_info']) == 1 ? 1 : -1),
'mapcontrol'=> $_POST['wpmaps_mapcontrol'],
'typecontrol'=> $_POST['wpmaps_typecontrol'],
'key'=> $_POST['wpmaps_key'],
'posts'=> $_POST['wpmaps_posts']
)
); ?>
<div id="message" class="updated fade">
<p>
<strong>
<?php _e('Settings saved.') ?>
</strong>
</p>
</div>
<?php } ?>
<div class="wrap">
<?php if (version_compare($GLOBALS['wp_version'], '2.6.999', '>')) { ?>
<div class="icon32" style="background: url(<?php echo @plugins_url('wpmaps/img/icon32.png') ?>) no-repeat"><br /></div>
<?php } ?>
<h2>
wpMAPS
</h2>
<form method="post" action="">
<?php wp_nonce_field('wpmaps') ?>
<div id="poststuff" class="ui-sortable">
<div id="wp_seo_about_wpseo" class="postbox">
<h3>
<?php _e('Settings') ?>
</h3>
<div class="inside">
<table class="form-table">
<tr>
<th scope="row">
<?php _e('Google Maps-API Key', 'wpmaps') ?>
</th>
<td>
<input type="text" name="wpmaps_key" id="wpmaps_key" value="<?php echo $GLOBALS['MP_WPlize']->get_option('key') ?>" class="regular-text code" />
<p>
<?php _e('Google Maps-API Key wird <a href="http://code.google.com/intl/de/apis/maps/signup.html" target="_blank">direkt bei Google</a> angefordert', 'wpmaps') ?>
</p>
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Standard-Kartengröße', 'wpmaps') ?>
</th>
<td>
<input type="text" name="wpmaps_width" id="wpmaps_width" value="<?php echo $GLOBALS['MP_WPlize']->get_option('width') ?>" class="small-text" /> x <input type="text" name="wpmaps_height" id="wpmaps_height" value="<?php echo $GLOBALS['MP_WPlize']->get_option('height') ?>" class="small-text" /> Pixel
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Standard-Kartentyp', 'wpmaps') ?>
</th>
<td>
<select name="wpmaps_type" id="wpmaps_type">
<?php foreach ($this->type_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $GLOBALS['MP_WPlize']->get_option('type') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Standard-Zoom', 'wpmaps') ?>
</th>
<td>
<input type="text" name="wpmaps_zoom" id="wpmaps_zoom" value="<?php echo $GLOBALS['MP_WPlize']->get_option('zoom') ?>" class="small-text" />
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Standard-Bedienelement', 'wpmaps') ?>
</th>
<td>
<select name="wpmaps_mapcontrol" id="wpmaps_mapcontrol">
<?php foreach ($this->mapcontrol_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $GLOBALS['MP_WPlize']->get_option('mapcontrol') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Standard-Kartentyp-Umschalter', 'wpmaps') ?>
</th>
<td>
<select name="wpmaps_typecontrol" id="wpmaps_typecontrol">
<?php foreach ($this->typecontrol_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $GLOBALS['MP_WPlize']->get_option('typecontrol') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Standard-Infofenster', 'wpmaps') ?>
</th>
<td>
<select name="wpmaps_info" id="wpmaps_info">
<?php foreach ($this->info_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $GLOBALS['MP_WPlize']->get_option('info') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<th scope="row">
<?php _e('Nur für folgende Post-IDs', 'wpmaps') ?>
</th>
<td>
<input type="text" name="wpmaps_posts" id="wpmaps_posts" value="<?php echo $GLOBALS['MP_WPlize']->get_option('posts') ?>" class="regular-text" />
</td>
</tr>
</table>
<p>
<input type="submit" name="wpmaps_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</div>
</div>
<div class="postbox">
<h3>
<?php _e('Information', 'wpmaps') ?>
</h3>
<div class="inside">
<p>
<?php $this->show_plugin_info() ?>
</p>
</div>
</div>
</div>
</form>
</div>
<?php }
function convert_user_shortcode($attr, $content = '') {
return $this->show($attr, true);
}
function init_custom_options() {
if (function_exists('add_meta_box') && $GLOBALS['MP_WPlize']->get_option('key') && $this->check_current_post()) {
add_meta_box('wpmaps_edit', __('wpMAPS Optionen', 'wpmaps'), array($this, 'show_custom_show'), 'post', 'advanced', 'high');
add_meta_box('wpmaps_edit', __('wpMAPS Optionen', 'wpmaps'), array($this, 'show_custom_show'), 'page', 'advanced', 'high');
}
}
function save_custom_options($post_id) {
if ($_POST['post_type'] == 'page') {
if (!current_user_can('edit_page', $post_id)) {
return $post_id;
}
} else {
if (!current_user_can('edit_post', $post_id)) {
return $post_id;
}
}
if (!isset($_REQUEST['_wpmaps_edit_nonce']) || empty($_REQUEST['_wpmaps_edit_nonce']) || !wp_verify_nonce($_REQUEST['_wpmaps_edit_nonce'], '_wpmaps_edit_nonce')) {
return $post_id;
}
foreach ($this->fields as $field) {
$adv_field = '_wpmaps_edit_' .$field;
$post_field = @$_POST[$adv_field];
$prev_field = get_post_meta($post_id, $adv_field, true);
if (!empty($post_field) && !empty($prev_field) && $post_field != $prev_field) {
update_post_meta(
$post_id,
$adv_field,
$post_field
);
} else if (empty($post_field) && !empty($prev_field)) {
delete_post_meta(
$post_id,
$adv_field
);
} else if (!empty($post_field) && empty($prev_field)) {
add_post_meta(
$post_id,
$adv_field,
$post_field,
true
);
}
}
}
function get_custom_value($field) {
if ($custom = get_post_meta($GLOBALS['post_ID'], '_wpmaps_edit_' .$field, true)) {
return $custom; 
}
return $GLOBALS['MP_WPlize']->get_option($field);
}
function show_custom_show() { ?>
<style type="text/css">
div.wpmaps {
clear: both;
height: 1%;
overflow: hidden;
}
div.wpmaps p {
float: left;
}
div.wpmaps p:first-child {
width: 70px;
padding-top: 20px;
}
</style>
<div class="wpmaps">
<p>
<?php _e('Koordinaten', 'wpmaps') ?>:
</p>
<p>
<small>
<?php _e('Breitengrad', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_x" id="_wpmaps_edit_x" value="<?php echo $this->get_custom_value('x') ?>" />
</p>
<p>
<small>
<?php _e('Längengrad', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_y" id="_wpmaps_edit_y" value="<?php echo $this->get_custom_value('y') ?>" />
</p>
<p>
<input type="hidden" name="_wpmaps_edit_nonce" value="<?php echo wp_create_nonce('_wpmaps_edit_nonce') ?>" />
</p>
</div>
<div class="wpmaps">
<p>
<?php _e('Adresse', 'wpmaps') ?>:
</p>
<p>
<small>
<?php _e('Straße', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_street" id="_wpmaps_edit_street" value="<?php echo $this->get_custom_value('street') ?>" />
</p>
<p>
<small>
<?php _e('PLZ Ort', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_city" id="_wpmaps_edit_city" value="<?php echo $this->get_custom_value('city') ?>" />
</p>
<p>
<small>
<?php _e('Ortsbezeichnung', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_company" id="_wpmaps_edit_company" value="<?php echo $this->get_custom_value('company') ?>" />
</p>
</div>
<div class="wpmaps">
<p>
<?php _e('Kartengröße', 'wpmaps') ?>:
</p>
<p>
<small>
<?php _e('Breite', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_width" id="_wpmaps_edit_width" value="<?php echo $this->get_custom_value('width') ?>" />
</p>
<p>
<small>
<?php _e('Höhe', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_height" id="_wpmaps_edit_height" value="<?php echo $this->get_custom_value('height') ?>" />
</p>
</div>
<div class="wpmaps">
<p>
<?php _e('Sonstiges', 'wpmaps') ?>:
</p>
<p>
<small>
<?php _e('Kartentyp', 'wpmaps') ?>
</small>
<br />
<select name="_wpmaps_edit_type" id="_wpmaps_edit_type">
<?php foreach ($this->type_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $this->get_custom_value('type') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</p>
<p>
<small>
<?php _e('Zoom', 'wpmaps') ?>
</small>
<br />
<input type="text" name="_wpmaps_edit_zoom" id="_wpmaps_edit_zoom" value="<?php echo $this->get_custom_value('zoom') ?>" />
</p>
<p>
<small>
<?php _e('Bedienelement', 'wpmaps') ?>
</small>
<br />
<select name="_wpmaps_edit_mapcontrol" id="_wpmaps_edit_mapcontrol">
<?php foreach ($this->mapcontrol_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $this->get_custom_value('mapcontrol') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</p>
<p>
<small>
<?php _e('Kartentyp-Umschalter', 'wpmaps') ?>
</small>
<br />
<select name="_wpmaps_edit_typecontrol" id="_wpmaps_edit_typecontrol">
<?php foreach ($this->typecontrol_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $this->get_custom_value('typecontrol') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</p>
<p>
<small>
<?php _e('Infofenster', 'wpmaps') ?>
</small>
<br />
<select name="_wpmaps_edit_info" id="_wpmaps_edit_info">
<?php foreach ($this->info_arr as $key => $value) { ?>
<option value="<?php echo $key ?>" <?php echo ($key == $this->get_custom_value('info') ? 'selected="selected"' : '') ?>><?php echo $value ?></option>
<?php } ?>
</select>
</p>
</div>
<?php }
function show_user_feedback($msg, $return = true) {
if (!$msg) {
return false;
}
if ($return) {
return __($msg, 'wpmaps');
} else {
_e($msg, 'wpmaps');
return false;
}
}
function show($custom = array(), $return = false) {
$info = array();
$output = '';
$default = array(
'x'=> 0,
'y'=> 0,
'company'=> '',
'street'=> '',
'city'=> '',
'width'=> $GLOBALS['MP_WPlize']->get_option('width'),
'height'=> $GLOBALS['MP_WPlize']->get_option('height'),
'type'=> $GLOBALS['MP_WPlize']->get_option('type'),
'zoom'=> $GLOBALS['MP_WPlize']->get_option('zoom'),
'info'=> $GLOBALS['MP_WPlize']->get_option('info'),
'mapcontrol'=> $GLOBALS['MP_WPlize']->get_option('mapcontrol'),
'typecontrol'=> $GLOBALS['MP_WPlize']->get_option('typecontrol'),
'key'=> $GLOBALS['MP_WPlize']->get_option('key')
);
if (!$custom) {
$post_id = (is_singular() ? $GLOBALS['wp_query']->get_queried_object_id() : $GLOBALS['post']->ID);
foreach ($this->fields as $field) {
$value = get_post_meta(
$post_id,
'_wpmaps_edit_' .$field,
true
);
if ($value) {
$custom[$field] = $value;
}
}
}
if ($custom) {
$data = array_merge(
$default,
$custom
);
} else {
$data = $default;
}
if (!$data['key']) {
return $this->show_user_feedback('Google Maps-API Key fehlt.', $return);
}
$data['id'] = substr(md5(uniqid(rand(), true)), 0, 5);
if (empty($data['x']) && empty($data['x']) && $data['city']) {
$location = (empty($data['street']) ? $data['city'] : $data['street']. ',' .$data['city']);
$url = sprintf(
'http://maps.google.com/maps/geo?q=%s&output=csv&key=%s',
urlencode($location),
$data['key']
);
if (function_exists('wp_remote_get')) {
$response = wp_remote_get($url);
$answer = wp_remote_retrieve_body($response);
} else {
$answer = @file_get_contents($url);
}
if (isset($answer) && !empty($answer)) {
preg_match('/.*,.*,(.*),(.*)/', $answer, $matches);
if ($matches && $matches[1] && $matches[2]) {
$data['x'] = $matches[1];
$data['y'] = $matches[2];
}
} else {
return $this->show_user_feedback('Server erlaubt keine ausgehenden Verbindungen.', $return);
}
}
if (empty($data['x']) && empty($data['x'])) {
$this->show_user_feedback('Koordinaten der Karte fehlen.', $return);
}
if ($data['info'] != -1 && ($data['company'] || $data['street'] || $data['city'])) {
if ($data['company']) {
$info[] = '<strong>' .$data['company']. '</strong>';
}
if ($data['street']) {
$info[] = $data['street'];
}
if ($data['city']) {
$info[] = $data['city'];
}
}
$output .= '<div id="map_' .$data['id']. '" class="wpmaps" style="width:' .$data['width']. 'px; height:' .$data['height']. 'px; overflow:hidden"></div>';
$output .= '<script type="text/javascript">';
$output .= '/* <![CDATA[ */';
$output .= 'jQuery(document).ready(';
$output .= 'function($) {';
$output .= 'if (GBrowserIsCompatible()) {';
$output .= 'var map_' .$data['id']. ' = new GMap2($("#map_' .$data['id']. '").get(0));';
$output .= 'var lat_' .$data['id']. ' = new GLatLng(' .$data['x']. ', ' .$data['y']. ');';
$output .= 'map_' .$data['id']. '.setCenter(lat_' .$data['id']. ', ' .$data['zoom']. ');';
$output .= 'map_' .$data['id']. '.setMapType(' .$data['type']. ');';
if ($data['mapcontrol'] != -1) {
$output .= 'map_' .$data['id']. '.addControl(new ' .$data['mapcontrol']. '());';
}
if ($data['typecontrol'] != -1) {
$output .= 'map_' .$data['id']. '.addControl(new ' .$data['typecontrol']. '());';
}
$output .= 'map_' .$data['id']. '.addOverlay(new GMarker(lat_' .$data['id']. '));';
if ($data['info'] != -1 && $info) {
$output .= 'map_' .$data['id']. '.openInfoWindowHtml(lat_' .$data['id']. ', "' .implode('<br />', $info). '");';
}
$output .= '}';
$output .= '}';
$output .= ');';
$output .= '/* ]]> */';
$output .= '</script>';
if ($return) {
return $output;
} else {
echo $output;
}
}
}
if (class_exists('wpMAPS') && function_exists('is_admin')) {
$GLOBALS['wpMAPS'] = new wpMAPS();
}