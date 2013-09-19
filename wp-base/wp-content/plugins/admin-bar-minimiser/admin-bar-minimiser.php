<?php
/*
Plugin Name: Admin Bar Minimiser
Plugin URI: http://www.presscoders.com/plugins/admin-bar-minimiser/
Description: Easy way to minimise the new WordPress 3.1 admin bar at the click of a button. Make it appear again just as easily!
Version: 1.21
Author: David Gwyer
Author URI: http://www.presscoders.com
*/

/*  Copyright 2009 David Gwyer (email : d.v.gwyer@presscoders.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $wp_version;
if ( version_compare($wp_version, "3.1", "<" ) ) {
	exit("'Admin Bar Minimiser' Plugin requires WordPress 3.1 or higher!");
}

// Note: jq_min prefix is derived from [jq]uery [min]imiser
define("JQ_MIN", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));

// Plugin hooks/filters
add_action( 'init', 'jq_min_init' );
add_action( 'admin_menu', 'jq_min_add_options_page' );
add_action( 'admin_init', 'jq_min_admin_init' );
register_activation_hook( __FILE__, 'jq_min_add_defaults' );
register_uninstall_hook( __FILE__, 'jq_min_delete_plugin_options' );
add_filter( 'plugin_action_links', 'jq_min_plugin_action_links', 10, 2 );

// Register the blog scripts
function jq_min_init(){
	// Only attempt to minimise admin bar if it is enabled
	if ( is_admin_bar_showing() ) {

		// Register scripts
		wp_register_script('jq_min', JQ_MIN.'/js/minimise_admin_bar.js', array('jquery'));

		// Enqueue them
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jq_min' );

		// Add these actions only if admin bar is enabled
		add_action('wp_head', 'jq_min_add_css');
		add_action('admin_head', 'jq_min_add_admin_css');
	}
}

function jq_min_admin_init(){
	register_setting( 'jq_min_plugin_options', 'jq_min_options' );
}

// Add a little front end CSS and jQuery
function jq_min_add_css() {
	$options = get_option('jq_min_options');
	$normal_opacity = ((float)$options['drp_front_opacity_normal_value'])/100;
	$hover_opacity = ((float)$options['drp_front_opacity_hover_value'])/100;

	if (isset($options['chk_front_end'])) {
		$maximised = $options['chk_front_end'];
	}
	else {
		$maximised = '0';
	}

	if ( $maximised != '1' ) {
		echo "<style type=\"text/css\">#wpadminbar { top: -28px; } html { margin-top: 0px !important; }</style>\r\n";
	}
	else {
		echo "<script type=\"text/javascript\">jQuery(document).ready(function($) { $(\"#adminbar_tab\").text(\"Hide\"); });</script>";
	}

	echo "<style type=\"text/css\">div#adminbar_tab { clear:both;text-align:center;background:transparent;width:38px;position: relative;top:0px;color:#000;font-size:12px;font-style:italic;background: #aaa;opacity:".$normal_opacity.";padding:4px;cursor:pointer; } div#adminbar_tab:hover {opacity:".$hover_opacity.";} </style>";
}

// Add a little admin css - opacity set to zero for admin bar tab as it displays better
function jq_min_add_admin_css() {
	$options = get_option('jq_min_options');
	$normal_opacity = ((float)$options['drp_admin_opacity_normal_value'])/100;
	$hover_opacity = ((float)$options['drp_admin_opacity_hover_value'])/100;

	if (isset($options['chk_wpadmin'])) {
		$maximised = $options['chk_wpadmin'];
	}
	else {
		$maximised = '0';
	}

	if ( $maximised != '1' ) {
		echo "<style type=\"text/css\">#wpadminbar { top: -28px; } body.admin-bar #wphead { padding-top: 0px; }</style>\r\n";
	}
	else {
		echo "<script type=\"text/javascript\">jQuery(document).ready(function($) { $(\"#adminbar_tab\").text(\"Hide\"); });</script>";
	}

	echo "<style type=\"text/css\">div#adminbar_tab { clear:both;text-align:center;background:transparent;width:38px;position: relative;top:0px;color:#000;font-size:12px;font-style:italic;background: #aaa;opacity:".$normal_opacity.";padding:4px;cursor:pointer; } div#adminbar_tab:hover {opacity:".$hover_opacity.";} </style>";
}

// Delete options table entries ONLY when plugin deactivated AND deleted
function jq_min_delete_plugin_options() {
	delete_option('jq_min_options');
}

// Define default option settings
function jq_min_add_defaults() {

	// This function updates the Plugin options with the specified defaults ONLY
	// if they dont exist. If an option already exists then it is NOT overwritten.

	// Get a copy of the current theme options
	$current_options = get_option('jq_min_options');

	// Default options include any new optins added since last Plugin update
	$defaults_options = array( "chk_front_end" => "0", "chk_wpadmin" => "0", "drp_front_opacity_normal_value" => "20", "drp_admin_opacity_normal_value" => "0", "drp_front_opacity_hover_value" => "70", "drp_admin_opacity_hover_value" => "70" );

	// *Important* Any checkboxes you need to be ON by default just add them to an array as
	// shown below, but with a "0" value. Otherwise when the Plugin is deactivated/reactivated
	// the ON by default checkboxes will always be set to ON even if the user set to off.
	//$default_off_checkboxes = array( "chk_front_end" => "0" );
	//$current_options = array_merge($default_off_checkboxes, $current_options);

	// If there are no existing options just use defaults (no merge)
	if ( !$current_options || empty($current_options) ) {
		// Update options in db
		update_option('jq_min_options', $defaults_options);
	}
	// Else merge existing options with current ones (new options are added, but none are overwritten)
	else {
		// Merge current options with the defaults, i.e. add any new options but don't
		// overwrite existing ones
		$result = array_merge($defaults_options, $current_options);

		// Update options in db
		update_option('jq_min_options', $result);
	}
}

// Add admin menu page
function jq_min_add_options_page() {
	add_options_page('Admin Bar Minimiser Options Page', 'Admin Bar Minimiser', 'manage_options', __FILE__, 'jq_min_render_form');
}

// Draw the menu page itself
function jq_min_render_form() {
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Admin Bar Minimiser</h2>
		<p style="float: left;">Configure the WordPress 3.1 Admin Bar options below.</p>
		<p style="float: right;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="6S23EDELXPM7L">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
		<form method="post" action="options.php">
			<?php settings_fields('jq_min_plugin_options'); ?>
			<?php $options = get_option('jq_min_options'); ?>
			<h3 style="margin: 20px 0px 0px 0px;">Default Maximised Status</h3>
			<table class="form-table">
				<tr valign="top"><td colspan="2"><p>If the Admin Bar is enabled (via the user profile page) then you can specify below whether it is shown maximised or minimised by default.</p></td></tr>				
				<tr valign="top">
					<th scope="row">Front end settings</th>
					<td>
						<label><input name="jq_min_options[chk_front_end]" type="checkbox" value="1" <?php if (isset($options['chk_front_end'])) { checked('1', $options['chk_front_end']); } ?> /> Maximise by default</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Admin settings</th>
					<td>
						<label><input name="jq_min_options[chk_wpadmin]" type="checkbox" value="1" <?php if (isset($options['chk_wpadmin'])) { checked('1', $options['chk_wpadmin']); } ?> /> Maximise by default</label>
					</td>
				</tr>
			</table>

			<h3 style="margin: 20px 0px 0px 0px;">Show/Hide Tab Opacity</h3>
			<table class="form-table">
				<tr>
					<th scope="row">Front end tab opacity</th>
					<td>Normal visibility
						<select name='jq_min_options[drp_front_opacity_normal_value]'>
							<option value='0' <?php selected('0', $options['drp_front_opacity_normal_value']); ?>>0%</option>
							<option value='10' <?php selected('10', $options['drp_front_opacity_normal_value']); ?>>10%</option>
							<option value='20' <?php selected('20', $options['drp_front_opacity_normal_value']); ?>>20%</option>
							<option value='30' <?php selected('30', $options['drp_front_opacity_normal_value']); ?>>30%</option>
							<option value='40' <?php selected('40', $options['drp_front_opacity_normal_value']); ?>>40%</option>
							<option value='50' <?php selected('50', $options['drp_front_opacity_normal_value']); ?>>50%</option>
							<option value='60' <?php selected('60', $options['drp_front_opacity_normal_value']); ?>>60%</option>
							<option value='70' <?php selected('70', $options['drp_front_opacity_normal_value']); ?>>70%</option>
							<option value='80' <?php selected('80', $options['drp_front_opacity_normal_value']); ?>>80%</option>
							<option value='90' <?php selected('90', $options['drp_front_opacity_normal_value']); ?>>90%</option>
							<option value='100' <?php selected('100', $options['drp_front_opacity_normal_value']); ?>>100%</option>
						</select>
						<span style="color:#666666;margin-left:2px;">&nbsp;&nbsp;(0% => invisible)</span>
					</td>
				</tr>
				<tr>
					<th scope="row"></th>
					<td>On mouse hover
						<select name='jq_min_options[drp_front_opacity_hover_value]'>
							<option value='0' <?php selected('0', $options['drp_front_opacity_hover_value']); ?>>0%</option>
							<option value='10' <?php selected('10', $options['drp_front_opacity_hover_value']); ?>>10%</option>
							<option value='20' <?php selected('20', $options['drp_front_opacity_hover_value']); ?>>20%</option>
							<option value='30' <?php selected('30', $options['drp_front_opacity_hover_value']); ?>>30%</option>
							<option value='40' <?php selected('40', $options['drp_front_opacity_hover_value']); ?>>40%</option>
							<option value='50' <?php selected('50', $options['drp_front_opacity_hover_value']); ?>>50%</option>
							<option value='60' <?php selected('60', $options['drp_front_opacity_hover_value']); ?>>60%</option>
							<option value='70' <?php selected('70', $options['drp_front_opacity_hover_value']); ?>>70%</option>
							<option value='80' <?php selected('80', $options['drp_front_opacity_hover_value']); ?>>80%</option>
							<option value='90' <?php selected('90', $options['drp_front_opacity_hover_value']); ?>>90%</option>
							<option value='100' <?php selected('100', $options['drp_front_opacity_hover_value']); ?>>100%</option>
						</select>
						<span style="color:#666666;margin-left:2px;">&nbsp;&nbsp;(0% => invisible)</span>
					</td>
				</tr>

				<tr>
					<th scope="row">Admin tab opacity</th>
					<td>Normal visibility
						<select name='jq_min_options[drp_admin_opacity_normal_value]'>
							<option value='0' <?php selected('0', $options['drp_admin_opacity_normal_value']); ?>>0%</option>
							<option value='10' <?php selected('10', $options['drp_admin_opacity_normal_value']); ?>>10%</option>
							<option value='20' <?php selected('20', $options['drp_admin_opacity_normal_value']); ?>>20%</option>
							<option value='30' <?php selected('30', $options['drp_admin_opacity_normal_value']); ?>>30%</option>
							<option value='40' <?php selected('40', $options['drp_admin_opacity_normal_value']); ?>>40%</option>
							<option value='50' <?php selected('50', $options['drp_admin_opacity_normal_value']); ?>>50%</option>
							<option value='60' <?php selected('60', $options['drp_admin_opacity_normal_value']); ?>>60%</option>
							<option value='70' <?php selected('70', $options['drp_admin_opacity_normal_value']); ?>>70%</option>
							<option value='80' <?php selected('80', $options['drp_admin_opacity_normal_value']); ?>>80%</option>
							<option value='90' <?php selected('90', $options['drp_admin_opacity_normal_value']); ?>>90%</option>
							<option value='100' <?php selected('100', $options['drp_admin_opacity_normal_value']); ?>>100%</option>
						</select>
						<span style="color:#666666;margin-left:2px;">&nbsp;&nbsp;(0% => invisible)</span>
					</td>
				</tr>
				<tr>
					<th scope="row"></th>
					<td colspan="2">On mouse hover
						<select name='jq_min_options[drp_admin_opacity_hover_value]'>
							<option value='0' <?php selected('0', $options['drp_admin_opacity_hover_value']); ?>>0%</option>
							<option value='10' <?php selected('10', $options['drp_admin_opacity_hover_value']); ?>>10%</option>
							<option value='20' <?php selected('20', $options['drp_admin_opacity_hover_value']); ?>>20%</option>
							<option value='30' <?php selected('30', $options['drp_admin_opacity_hover_value']); ?>>30%</option>
							<option value='40' <?php selected('40', $options['drp_admin_opacity_hover_value']); ?>>40%</option>
							<option value='50' <?php selected('50', $options['drp_admin_opacity_hover_value']); ?>>50%</option>
							<option value='60' <?php selected('60', $options['drp_admin_opacity_hover_value']); ?>>60%</option>
							<option value='70' <?php selected('70', $options['drp_admin_opacity_hover_value']); ?>>70%</option>
							<option value='80' <?php selected('80', $options['drp_admin_opacity_hover_value']); ?>>80%</option>
							<option value='90' <?php selected('90', $options['drp_admin_opacity_hover_value']); ?>>90%</option>
							<option value='100' <?php selected('100', $options['drp_admin_opacity_hover_value']); ?>>100%</option>
						</select>
						<span style="color:#666666;margin-left:2px;">&nbsp;&nbsp;(0% => invisible)</span>
					</td>
				</tr>

			</table>

			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</form>

		<p style="margin-top:20px;font-style:italic;">
			<span><a href="http://www.facebook.com/PressCoders" title="Our Facebook page" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/admin-bar-minimiser/images/facebook-icon.png" /></a></span>
			&nbsp;&nbsp;<span><a href="http://www.twitter.com/dgwyer" title="Follow on Twitter" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/admin-bar-minimiser/images/twitter-icon.png" /></a></span>
			&nbsp;&nbsp;<span><a href="http://www.presscoders.com" title="PressCoders.com" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/admin-bar-minimiser/images/pc-icon.png" /></a></span>
		</p>

	</div>
	<?php	
}

// Display a Settings link on the main Plugins page
function jq_min_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$jq_min_links = '<a href="'.get_admin_url().'options-general.php?page=admin-bar-minimiser/admin-bar-minimiser.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $jq_min_links );
	}

	return $links;
}