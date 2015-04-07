<?php
/*
Plugin Name: Cimy Header Image Rotator
Plugin URI: http://www.marcocimmino.net/cimy-wordpress-plugins/cimy-header-image-rotator/
Description: Displays an image that automatically rotate depending on setting. You can setup from one second up to one day and more. Intermediate settings are also possible.
Author: Marco Cimmino
Version: 6.1.1
Author URI: mailto:cimmino.marco@gmail.com

This plugin is based on the "Header Image Rotator - Basic" v2.1 plugin by Matthew Hough (http://www.wpimagerotator.com). Thanks!

Matthew's plugin was based on the "Header Randomizer" plugin by Lennart Groetzbach (http://www.lennartgroetzbach.de/blog/?p=1040). Thanks!

This plugin uses jquery.cross-slide.js by Tobia Conforto <tobia.conforto@gmail.com> (http://tobia.github.com/CrossSlide/) (GPL v2 license). Thanks!

Copyright (C) 2009-2013 Marco Cimmino (cimmino.marco@gmail.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.


The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt
 */

$cimy_hir_domain = 'cimy_hir';
$cimy_hir_options = "cimy_hir_options";
$cimy_hir_options_descr = "Cimy Header Image Rotator options are stored here and modified only by admin";

$cimy_hir_name = "Cimy Header Image Rotator";
$cimy_hir_version = "6.1.1";

$cimy_hir_url = "http://www.marcocimmino.net/cimy-wordpress-plugins/cimy-header-image-rotator/";
$cimy_project_url = "http://www.marcocimmino.net/cimy-wordpress-plugins/support-the-cimy-project-paypal/";

$start_cimy_hir_comment = "<!--";
$start_cimy_hir_comment .= "\tStart code from ".$cimy_hir_name." ".$cimy_hir_version;
$start_cimy_hir_comment .= "\tCopyright (c) 2009-2013 Marco Cimmino";
$start_cimy_hir_comment .= "\t".$cimy_hir_url;
$start_cimy_hir_comment .= "\t-->\n";

$end_cimy_hir_comment = "\n<!--";
$end_cimy_hir_comment .= "\tEnd of code from ".$cimy_hir_name;
$end_cimy_hir_comment .= "\t-->\n";

// pre 2.6 compatibility or if not defined
if (!defined("WP_CONTENT_DIR"))
	define("WP_CONTENT_DIR", ABSPATH."/wp_content");

$chir_plugin_path = plugin_basename(dirname(__FILE__))."/";

/* Define Constants and variables*/
if (is_multisite()) {
	global $blog_id;

	$chir_plugins_dir = __FILE__;
	if (!stristr($chir_plugins_dir, "mu-plugins") === false)
		$chir_plugins_dir = "mu-plugins";
	else
		$chir_plugins_dir = "plugins";
	$blog_path = $blog_id."/";
}
else {
	$blog_path = "";
	$chir_plugins_dir = "plugins";
}

define('IMAGE_FOLDER', 'Cimy_Header_Images/');
define('IMAGE_PATH', WP_CONTENT_DIR."/".IMAGE_FOLDER.$blog_path);
define('IMAGE_URI', content_url(IMAGE_FOLDER.$blog_path));
define('PLUGIN_URI', content_url("/".$chir_plugins_dir.'/'.$chir_plugin_path));

if ((!is_dir(WP_CONTENT_DIR.'/'.IMAGE_FOLDER)) && (is_writable(WP_CONTENT_DIR))) {
	if (defined("FS_CHMOD_DIR"))
		@mkdir(WP_CONTENT_DIR.'/'.IMAGE_FOLDER, FS_CHMOD_DIR);
	else
		wp_mkdir_p(WP_CONTENT_DIR.'/'.IMAGE_FOLDER);
}

if ((is_multisite()) && (!is_dir(IMAGE_PATH)) && (is_writable(WP_CONTENT_DIR.'/'.IMAGE_FOLDER))) {
	if (defined("FS_CHMOD_DIR"))
		@mkdir(IMAGE_PATH, FS_CHMOD_DIR);
	else
		wp_mkdir_p(IMAGE_PATH);
}

/* Check to see if options already exist in the database */
$cimy_hir_curr_options = get_option($cimy_hir_options);

// invalidate v1.x.x options so new ones are created
if (isset($cimy_hir_curr_options['span_id']))
	$cimy_hir_curr_options = "";

// updating from pre v5.0.0
if (empty($cimy_hir_curr_options["configs"])) {
	$cimy_hir_curr_options["configs"] = array();
	$new_dir = IMAGE_PATH.'0/';
	if (defined("FS_CHMOD_DIR"))
		@mkdir($new_dir, FS_CHMOD_DIR);
	else
		wp_mkdir_p($new_dir);

	$files = get_files(IMAGE_PATH, "*", '');
	if (!empty($files)) {
		foreach ($files as $file)
			rename($file, $new_dir.basename($file));
	}

	foreach ($cimy_hir_curr_options as $key=>$value) {
		if ($key != "configs") {
			$cimy_hir_curr_options["configs"][0][$key] = $value;
			unset($cimy_hir_curr_options[$key]);
		}
	}

	$cimy_hir_curr_options = cimy_hir_set_default_config($cimy_hir_curr_options, 0);
	update_option($cimy_hir_options, $cimy_hir_curr_options);
}

// updating from pre v5.0.5
if (empty($cimy_hir_curr_options['configs'][0]['start_from'])) {
	foreach ($cimy_hir_curr_options['configs'] as $id=>$opt) {
		$cimy_hir_curr_options['configs'][$id]['start_from'] = 'image_in_slot';
	}
	update_option($cimy_hir_options, $cimy_hir_curr_options);
}

// updating from pre v6.0.0
if (empty($cimy_hir_curr_options['configs'][0]['images_source'])) {
	foreach ($cimy_hir_curr_options['configs'] as $id=>$opt) {
		$cimy_hir_curr_options['configs'][$id]['images_source'] = 'plugin';
	}
	update_option($cimy_hir_options, $cimy_hir_curr_options);
}

// updating from pre v6.0.2
if (empty($cimy_hir_curr_options['configs'][0]['double_fade'])) {
	foreach ($cimy_hir_curr_options['configs'] as $id=>$opt) {
		$cimy_hir_curr_options['configs'][$id]['double_fade'] = false;
	}
	update_option($cimy_hir_options, $cimy_hir_curr_options);
}

// updating from pre v6.1.0
if (empty($cimy_hir_curr_options['configs'][0]['div_size_adaptable'])) {
	foreach ($cimy_hir_curr_options['configs'] as $id=>$opt) {
		$cimy_hir_curr_options['configs'][$id]['div_size_adaptable'] = false;
	}
	update_option($cimy_hir_options, $cimy_hir_curr_options);
}

function cimy_hir_set_default_config($options, $id) {
	global $cimy_hir_domain;

	if ($id == 0)
		$options["configs"][$id]['name'] = __("Main", $cimy_hir_domain);
	else
		$options["configs"][$id]['name'] = sprintf(__("Config #%d", $cimy_hir_domain), $id);

	$options["configs"][$id]['images_source'] = (empty($options["configs"][$id]['images_source'])) ? "plugin" : $options["configs"][$id]['images_source'];
	$options["configs"][$id]['link_target'] = (empty($options["configs"][$id]['link_target'])) ? "_self" : $options["configs"][$id]['link_target'];
	$options["configs"][$id]['swap_rate'] = (empty($options["configs"][$id]['swap_rate'])) ? 3 : $options["configs"][$id]['swap_rate'];
	$options["configs"][$id]['swap_type'] = (empty($options["configs"][$id]['swap_type'])) ? 's' : $options["configs"][$id]['swap_type'];
	$options["configs"][$id]['div_id'] = (empty($options["configs"][$id]['div_id'])) ? 'cimy_div_id_'.$id : $options["configs"][$id]['div_id'];
	$options["configs"][$id]['div_width'] = (empty($options["configs"][$id]['div_width'])) ? 400 : $options["configs"][$id]['div_width'];
	$options["configs"][$id]['div_height'] = (empty($options["configs"][$id]['div_height'])) ? 200 : $options["configs"][$id]['div_height'];
	$options["configs"][$id]['border'] = (empty($options["configs"][$id]['border'])) ? 1 : $options["configs"][$id]['border'];
	$options["configs"][$id]['fade'] = (empty($options["configs"][$id]['fade'])) ? 0 : $options["configs"][$id]['fade'];
	$options["configs"][$id]['double_fade'] = (empty($options["configs"][$id]['double_fade'])) ? false : $options["configs"][$id]['double_fade'];
	$options["configs"][$id]['div_size_adaptable'] = (empty($options["configs"][$id]['div_size_adaptable'])) ? false : $options["configs"][$id]['div_size_adaptable'];
	$options["configs"][$id]['start_from'] = (empty($options["configs"][$id]['start_from'])) ? "image_in_slot" : $options["configs"][$id]['start_from'];
	$options["configs"][$id]['shuffle'] = (empty($options["configs"][$id]['shuffle'])) ? false : $options["configs"][$id]['shuffle'];
	$options["configs"][$id]['move_effect'] = (empty($options["configs"][$id]['move_effect'])) ? 'none' : $options["configs"][$id]['move_effect'];
	$options["configs"][$id]['speed'] = (empty($options["configs"][$id]['speed'])) ? 10 : $options["configs"][$id]['speed'];
	$options["configs"][$id]['file_link'] = (empty($options["configs"][$id]['file_link'])) ? '' : $options["configs"][$id]['file_link'];
	$options["configs"][$id]['file_text'] = (empty($options["configs"][$id]['file_text'])) ? '' : $options["configs"][$id]['file_text'];

	$options["configs"][$id]['left'] = (empty($options["configs"][$id]['left'])) ? false : $options["configs"][$id]['left'];
	$options["configs"][$id]['right'] = (empty($options["configs"][$id]['right'])) ? false : $options["configs"][$id]['right'];
	$options["configs"][$id]['down'] = (empty($options["configs"][$id]['down'])) ? false : $options["configs"][$id]['down'];
	$options["configs"][$id]['up'] = (empty($options["configs"][$id]['up'])) ? false : $options["configs"][$id]['up'];

	$options["configs"][$id]['bottomright'] = (empty($options["configs"][$id]['bottomright'])) ? false : $options["configs"][$id]['bottomright'];
	$options["configs"][$id]['from_topleft_zoom'] = (empty($options["configs"][$id]['from_topleft_zoom'])) ? '1.0' : $options["configs"][$id]['from_topleft_zoom'];
	$options["configs"][$id]['to_bottomright_zoom'] = (empty($options["configs"][$id]['to_bottomright_zoom'])) ? '1.5' : $options["configs"][$id]['to_bottomright_zoom'];

	$options["configs"][$id]['topright'] = (empty($options["configs"][$id]['topright'])) ? false : $options["configs"][$id]['topright'];
	$options["configs"][$id]['from_bottomleft_zoom'] = (empty($options["configs"][$id]['from_bottomleft_zoom'])) ? '1.0' : $options["configs"][$id]['from_bottomleft_zoom'];
	$options["configs"][$id]['to_topright_zoom'] = (empty($options["configs"][$id]['to_topright_zoom'])) ? '1.5' : $options["configs"][$id]['to_topright_zoom'];

	$options["configs"][$id]['bottomleft'] = (empty($options["configs"][$id]['bottomleft'])) ? false : $options["configs"][$id]['bottomleft'];
	$options["configs"][$id]['from_topright_zoom'] = (empty($options["configs"][$id]['from_topright_zoom'])) ? '1.0' : $options["configs"][$id]['from_topright_zoom'];
	$options["configs"][$id]['to_bottomleft_zoom'] = (empty($options["configs"][$id]['to_bottomleft_zoom'])) ? '1.5' : $options["configs"][$id]['to_bottomleft_zoom'];

	$options["configs"][$id]['topleft'] = (empty($options["configs"][$id]['topleft'])) ? false : $options["configs"][$id]['topleft'];
	$options["configs"][$id]['from_bottomright_zoom'] = (empty($options["configs"][$id]['from_bottomright_zoom'])) ? '1.0' : $options["configs"][$id]['from_bottomright_zoom'];
	$options["configs"][$id]['to_topleft_zoom'] = (empty($options["configs"][$id]['to_topleft_zoom'])) ? '1.5' : $options["configs"][$id]['to_topleft_zoom'];

	return $options;
}

$cimy_hir_i18n_is_setup = false;
cimy_hir_i18n_setup();

function cimy_hir_my_init() {
	$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	wp_register_script("cimy_hir_cross-slide", PLUGIN_URI."js/jquery.cross-slide$suffix.js", array("jquery"), false);
}

function cimy_hir_my_admin_init() {
	$role = & get_role('administrator');
	$role->add_cap('manage_cimy_image_rotator');

	wp_register_script("cimy_hir_upload_pic", PLUGIN_URI."js/upload_file.js", false, false);
}

function cimy_hir_js_enqueue() {
	wp_enqueue_script("cimy_hir_cross-slide");
}

function cimy_hir_admin_js_enqueue() {
	wp_enqueue_script("cimy_hir_upload_pic");
}

/* Add actions */
add_action('admin_menu', 'rotator_admin_menu');
add_action('wp_head', 'hir_add_css');
add_action('init', 'cimy_hir_my_init');
add_action('admin_init', 'cimy_hir_my_admin_init');
add_action('wp_enqueue_scripts', 'cimy_hir_js_enqueue');

function cimy_hir_i18n_setup() {
	global $cimy_hir_domain, $cimy_hir_i18n_is_setup, $chir_plugin_path, $chir_plugins_dir;

	if ($cimy_hir_i18n_is_setup)
		return;

	load_plugin_textdomain($cimy_hir_domain, false, '../'.$chir_plugins_dir.'/'.$chir_plugin_path.'langs');
}

function rotator_admin_menu() {
	$page = add_submenu_page('options-general.php', 'Cimy Header Image Rotator', 'Cimy Header Image Rotator', 'manage_cimy_image_rotator', 'cimy_header_image_rotator', 'rotator_options');
	add_action('admin_print_scripts-'.$page, 'cimy_hir_admin_js_enqueue');
	add_action('admin_print_scripts-'.$page, 'hir_add_js');
}

function get_files($path, $type, $id) {
	$files = glob($path.$id.'/'.$type);

	if ($files === FALSE)
		return array();

	$i = 0;
	$tot_files = count($files);
	while ($i < $tot_files) {
		if (!is_file($files[$i]))
			unset($files[$i]);
		$i++;
	}

	return array_values($files);
}

function get_image_key($img_array, $id) {
	global $cimy_hir_curr_options;

	$type = $cimy_hir_curr_options['configs'][$id]["swap_type"];
	$rate = $cimy_hir_curr_options['configs'][$id]["swap_rate"];

	if ($img_array === false)
		return false;

	$count = count($img_array);

	if (empty($img_array))
		return false;

	$date = date($type);

	$swap_types = array("s", "i", "G", "d", "W", "n");
	$swap_types_keys = array_keys($swap_types, $type);
	$type_key = $swap_types_keys[0];
	$type_key++;
	$offset = date($swap_types[$type_key]);

	// divide per actual time per $rate so you change every $rate then % # images
	// $offset is needed to not depend on the custom user input and so loads all images
	$key = ($offset + intval($date / $rate)) % $count;

	return $key;
}

function switch_image($img_array, $id) {
	return IMAGE_URI.basename($img_array[get_image_key($img_array, $id)]);
}

function header_img_check($id) {
	global $cimy_hir_curr_options;

	if (!empty($cimy_hir_curr_options['configs'][$id]["div_id"])) {
		echo PLUGIN_URI.'img/ok.gif';
	} else {
		echo PLUGIN_URI.'img/error.gif';
	}
}

function image_path_check($image_array) {
	$pics = count($image_array);

	if ((!($image_array === false)) && ($pics != 0)) {
		echo PLUGIN_URI.'img/ok.gif';
	} else {
		echo PLUGIN_URI.'img/error.gif';
	}
}

function image_path_message($image_array) {
	global $cimy_hir_domain;

	//$dir = is_dir(IMAGE_PATH);
	$pics = count($image_array);

	if ($image_array === false) {
		echo "<font color=\"red\">".__("The plugin could not find or open the above image directory. Please make sure you have created a folder called 'Cimy_Header_Images' (without the quote marks) in your wp-content folder.", $cimy_hir_domain)."</font>";
	} else if ($pics == 0) {
		echo "<font color=\"red\">".__("There are no pictures in your image folder. Please add some images for the plugin to work.", $cimy_hir_domain)."</font>";
	}
}

function hir_theme_images_array_to_local_array($theme_images_array) {
	$img_array = array();
	foreach ($theme_images_array as $val)
		$img_array[] = sprintf($val['url'], get_template_directory_uri(), get_stylesheet_directory_uri());

	return $img_array;
}

function hir_add_css() {
	global $cimy_hir_curr_options, $start_cimy_hir_comment, $end_cimy_hir_comment, $_wp_default_headers;

	foreach ($cimy_hir_curr_options['configs'] as $id=>$opt) {
		if ($cimy_hir_curr_options['configs'][$id]['images_source'] == 'theme_default')
			$img_array = hir_theme_images_array_to_local_array($_wp_default_headers);
		else if ($cimy_hir_curr_options['configs'][$id]['images_source'] == 'theme_uploaded')
			$img_array = hir_theme_images_array_to_local_array(get_uploaded_header_images());
		else
			$img_array = get_files(IMAGE_PATH, "*", $id);

		if ($img_array === false)
			continue;

		$tot_images = count($img_array);

		if (empty($img_array))
			continue;

		$div_id = esc_js($cimy_hir_curr_options['configs'][$id]['div_id']);
		$swap_type = $cimy_hir_curr_options['configs'][$id]['swap_type'];
		$swap_rate = $cimy_hir_curr_options['configs'][$id]['swap_rate'];

		switch ($swap_type) {
			// seconds
			case "s":
				$seconds = $swap_rate;
				break;
			// minutes
			case "i":
				$seconds = 60*$swap_rate;
				break;
			// hours
			case "G":
				$seconds = 60*60*$swap_rate;
				break;
			// days
			case "d":
				$seconds = 60*60*24*$swap_rate;
				break;
			// weeks
			case "W":
				$seconds = 60*60*24*7*$swap_rate;
				break;
			// months
			case "n":
				$seconds = 60*60*24*7*30*$swap_rate;
				break;
		}

		$speed = $cimy_hir_curr_options['configs'][$id]["speed"];
		$img_key = 0;
		if ($cimy_hir_curr_options['configs'][$id]['start_from'] == "image_in_slot")
			$img_key = get_image_key($img_array, $id);
		$move_options = array();
		if ($cimy_hir_curr_options['configs'][$id]["move_effect"] == "slide") {
			if ($cimy_hir_curr_options['configs'][$id]["down"])
				$move_options[] = ", dir: 'down'";

			if ($cimy_hir_curr_options['configs'][$id]["up"])
				$move_options[] = ", dir: 'up'";

			if ($cimy_hir_curr_options['configs'][$id]["left"])
				$move_options[] = ", dir: 'left'";

			if ($cimy_hir_curr_options['configs'][$id]["right"])
				$move_options[] = ", dir: 'right'";
		}
		else if ($cimy_hir_curr_options['configs'][$id]["move_effect"] == "kenburns") {
			if ($cimy_hir_curr_options['configs'][$id]["bottomright"]) {
				$str = ", from: 'top left ".$cimy_hir_curr_options['configs'][$id]["from_topleft_zoom"]."x'";
				$str.= ", to: 'bottom right ".$cimy_hir_curr_options['configs'][$id]["to_bottomright_zoom"]."x'";
				$move_options[] = $str;
			}

			if ($cimy_hir_curr_options['configs'][$id]["topright"]) {
				$str = ", from: 'bottom left ".$cimy_hir_curr_options['configs'][$id]["from_bottomleft_zoom"]."x'";
				$str.= ", to: 'top right ".$cimy_hir_curr_options['configs'][$id]["to_topright_zoom"]."x'";
				$move_options[] = $str;
			}

			if ($cimy_hir_curr_options['configs'][$id]["bottomleft"]) {
				$str = ", from: 'top right ".$cimy_hir_curr_options['configs'][$id]["from_topright_zoom"]."x'";
				$str.= ", to: 'bottom left ".$cimy_hir_curr_options['configs'][$id]["to_bottomleft_zoom"]."x'";
				$move_options[] = $str;
			}

			if ($cimy_hir_curr_options['configs'][$id]["topleft"]) {
				$str = ", from: 'bottom right ".$cimy_hir_curr_options['configs'][$id]["from_bottomright_zoom"]."x'";
				$str.= ", to: 'top left ".$cimy_hir_curr_options['configs'][$id]["to_topleft_zoom"]."x'";
				$move_options[] = $str;
			}
		}

		echo $start_cimy_hir_comment."
<script type=\"text/javascript\" language=\"javascript\">
jQuery(document).ready(function($) {
  $(function() {
   var myid = $('#$div_id');
   if (myid[0]) {
    $('#$div_id').crossSlide({\n";
		if ($cimy_hir_curr_options['configs'][$id]['move_effect'] == "none")
			echo "      sleep: $seconds,\n";
		else if ($cimy_hir_curr_options['configs'][$id]['move_effect'] == "slide")
			echo "      speed: $speed,\n";
		echo "      fade: ".$cimy_hir_curr_options['configs'][$id]['fade'];
		echo $cimy_hir_curr_options['configs'][$id]['shuffle'] ? ",\n      shuffle: true" : "";
		echo $cimy_hir_curr_options['configs'][$id]['double_fade'] ? ",\n      doubleFade: true" : "";
		echo $cimy_hir_curr_options['configs'][$id]['div_size_adaptable'] ? ",\n      adaptSize: true" : "";
		echo "\n    }, [";
		// super-silly Internet Explorer can't handle comma at the end of last parameter
		$flag = false;
		$i = 0;
		$has_captions = false;

		while ($i != $tot_images) {
			if ($flag)
				echo ",";
			else
				$flag = true;

			if ($cimy_hir_curr_options['configs'][$id]['images_source'] == 'plugin')
				$filepath = IMAGE_URI.$id.'/'.basename($img_array[$img_key]);
			else
				$filepath = $img_array[$img_key];
			$filename = basename($filepath);
			echo "\n\t{ src: '".esc_js($filepath)."'";

			if (($cimy_hir_curr_options['configs'][$id]['move_effect'] == "slide") && (!empty($move_options)))
				echo $move_options[$i % count($move_options)];

			if ($cimy_hir_curr_options['configs'][$id]['move_effect'] == "kenburns") {
				echo $move_options[$i % count($move_options)];
				echo ", time: $seconds";
			}

			if (!empty($cimy_hir_curr_options['configs'][$id][$filename]["link"]))
				echo "\n\t, href: '".esc_js($cimy_hir_curr_options['configs'][$id][$filename]["link"])."', target: '".$cimy_hir_curr_options['configs'][$id]['link_target']."'";
			else if (!empty($cimy_hir_curr_options['configs'][$id]["file_link"]))
				echo "\n\t, href: '".esc_js($cimy_hir_curr_options['configs'][$id]["file_link"])."', target: '".$cimy_hir_curr_options['configs'][$id]['link_target']."'";

			if (!empty($cimy_hir_curr_options['configs'][$id][$filename]["text"])) {
				echo "\n\t, alt: '".esc_js($cimy_hir_curr_options['configs'][$id][$filename]["text"])."'";
				$has_captions = true;
			}
			else if (!empty($cimy_hir_curr_options['configs'][$id]["file_text"])) {
				echo "\n\t, alt: '".esc_js($cimy_hir_curr_options['configs'][$id]["file_text"])."'";
				$has_captions = true;
			}

			echo "}";
			$img_key = ($img_key + 1) % $tot_images;
			$i++;
		}
		echo "
       ]";
		if ($has_captions)
			echo ", function(idx, img, idxOut, imgOut) {
			var caption = $('div.".$div_id."_caption');
			caption.show().css({ opacity: 0 })
			if (idxOut == undefined)
			{
				// starting single image phase, put up caption
				caption.text(img.alt).animate({ opacity: .7 })
			}
			else
			{
				// starting cross-fade phase, take out caption
				caption.hide()
			}
			var img_alt = $(img).attr('alt');
			if (img_alt === undefined || img_alt === false || img_alt == '')
				caption.hide()
		}";
		echo ");
   }
  });
});
</script>".$end_cimy_hir_comment;
	}
}

function hir_add_js() {
	print <<<EOT
	<script type="text/javascript">
		<!--
		function open_win(url, name, args) {
			newWindow = window.open(url, name, args);
			newWindow.screenX = window.screenX;
			newWindow.screenY = window.screenY;
			newWindow.focus();
		}
		//-->
	</script>
EOT;
}

function cimy_hir_manage_upload($input_name, $rules, $id, $mime_type_filter) {
	// file path
	$file_path = IMAGE_PATH.$id;

	// if there is no file to upload
	//	or dest dir is not writable
	// then everything else is useless
	if ((empty($_FILES[$input_name]['name'])) || (!is_writable($file_path)))
		return "";

	$file_name = $_FILES[$input_name]['name'];

	// filesize in Byte transformed in KiloByte
	$file_size = $_FILES[$input_name]['size'] / 1024;
	$file_type1 = $_FILES[$input_name]['type'];
	$file_tmp_name = $_FILES[$input_name]['tmp_name'];
	$file_error = $_FILES[$input_name]['error'];

	$allowed_mime_types = get_allowed_mime_types();
	// let's see if the image extension is correct, bad boy
	$validate = wp_check_filetype_and_ext($file_tmp_name, $file_name, $allowed_mime_types);
	if ($validate['proper_filename'] !== false)
		$file_name = $validate['proper_filename'];

	// sanitize the file name
	$file_name = wp_unique_filename($file_path, $file_name);
	$file_type2 = "";
	if (!empty($validate['type']))
		$file_type2 = $validate['type'];

	// picture filesystem path
	$file_full_path = $file_path."/".$file_name;

	// CHECK IF IT IS A REAL PICTURE
	if (stristr($file_type1, $mime_type_filter) === false || stristr($file_type2, $mime_type_filter) === false)
		$file_error = 1;

	// CHECK THAT FILE NAME WILL NOT SCREW THE PLUG-IN
	if ($file_name != esc_js($file_name))
		$file_error = 2;
	
	// MIN LENGTH
	if (isset($rules['min_length']))
		if ($file_size < (intval($rules['min_length'])))
			$file_error = 1;
	
	// EXACT LENGTH
	if (isset($rules['exact_length']))
		if ($file_size != (intval($rules['exact_length'])))
			$file_error = 1;

	// MAX LENGTH
	if (isset($rules['max_length']))
		if ($file_size > (intval($rules['max_length'])))
			$file_error = 1;

	// if there are no errors and filename is empty
	if (($file_error == 0) && (!empty($file_name))) {
		if (move_uploaded_file($file_tmp_name, $file_full_path)) {
			// change file permissions for broken servers
			if (defined("FS_CHMOD_FILE"))
				@chmod($file_full_path, FS_CHMOD_FILE);
			else
				@chmod($file_full_path, 0644);
		}
	}

	return $file_error;
}

// function to delete all files/subdirs in a path
// taken from PHP unlink's documentation comment by torch - torchsdomain dot com @ 22-Nov-2006 09:27
// modified by Marco Cimmino to delete correctly call recursion before so can also delete subdirs when empty
if (!function_exists("cimy_rfr")) {
	function cimy_rfr($path, $match) {
		static $deld = 0, $dsize = 0;

		// remember that glob returns FALSE in case of error
		$dirs = glob($path."*");
		$files = glob($path.$match);

		// call recursion before so we delete files in subdirs first!
		if (is_array($dirs)) {
			foreach ($dirs as $dir) {
				if (is_dir($dir)) {
					$dir = basename($dir) . "/";
					cimy_rfr($path.$dir, $match);
				}
			}
		}

		if (is_array($files)) {
			foreach ($files as $file) {
				if (is_file($file)) {
					$dsize += filesize($file);
					unlink($file);
					$deld++;
				}
				else if (is_dir($file)) {
					rmdir($file);
				}
			}
		}

		return "$deld files deleted with a total size of $dsize bytes";
	}
}

function rotator_options() {
	global $cimy_hir_options, $cimy_hir_curr_options, $cimy_hir_domain, $cimy_project_url, $_wp_default_headers;

	$theme_uploaded_headers = get_uploaded_header_images();

	if (!current_user_can("manage_cimy_image_rotator"))
		return;

	if (isset($_REQUEST["id"]))
		$id = intval($_REQUEST["id"]);
	else
		$id = 0;

	$upload_res = 0;
	if (isset($_POST["upload"])) {
		$upload_res = cimy_hir_manage_upload("userfile", false, $id, "image/");
	}

	if (isset($_REQUEST["action"])) {
		$action = $_REQUEST["action"];
		if ($action == "add") {
			$id = max(array_keys($cimy_hir_curr_options['configs'])) + 1;
			$cimy_hir_curr_options = cimy_hir_set_default_config($cimy_hir_curr_options, $id);
			$new_dir = IMAGE_PATH.$id."/";
			if (defined("FS_CHMOD_DIR"))
				@mkdir($new_dir, FS_CHMOD_DIR);
			else
				wp_mkdir_p($new_dir);
			update_option($cimy_hir_options, $cimy_hir_curr_options);
		} else if (($action == "delete") && ($id != 0)) {
			unset($cimy_hir_curr_options['configs'][$id]);
			update_option($cimy_hir_options, $cimy_hir_curr_options);
			cimy_rfr(IMAGE_PATH.$id."/", "*");
			// delete also the subdir
			if (is_dir(IMAGE_PATH.$id))
				rmdir(IMAGE_PATH.$id);
			// defaults to id = 0
			$id = 0;
		}
	}

	if (isset($_POST["cimy_hir_post"])) {
		$cimy_hir_curr_options['configs'][$id]['images_source'] = $_POST['hir_images_source'];
		$cimy_hir_curr_options['configs'][$id]['move_effect'] = $_POST['hir_move_effect'];

		$cimy_hir_curr_options['configs'][$id]['from_topleft_zoom'] = floatval($_POST['hir_from_topleft_zoom']);
		$cimy_hir_curr_options['configs'][$id]['to_topleft_zoom'] = floatval($_POST['hir_to_topleft_zoom']);

		$cimy_hir_curr_options['configs'][$id]['from_bottomleft_zoom'] = floatval($_POST['hir_from_bottomleft_zoom']);
		$cimy_hir_curr_options['configs'][$id]['to_bottomleft_zoom'] = floatval($_POST['hir_to_bottomleft_zoom']);



		$cimy_hir_curr_options['configs'][$id]['from_topright_zoom'] = floatval($_POST['hir_from_topright_zoom']);
		$cimy_hir_curr_options['configs'][$id]['to_topright_zoom'] = floatval($_POST['hir_to_topright_zoom']);


		$cimy_hir_curr_options['configs'][$id]['from_bottomright_zoom'] = floatval($_POST['hir_from_bottomright_zoom']);
		$cimy_hir_curr_options['configs'][$id]['to_bottomright_zoom'] = floatval($_POST['hir_to_bottomright_zoom']);

		$cimy_hir_curr_options['configs'][$id]['speed'] = intval($_POST['hir_speed']);
		$cimy_hir_curr_options['configs'][$id]['start_from'] = $_POST['hir_start_from'];


		if (isset($_POST['hir_down']))
			$cimy_hir_curr_options['configs'][$id]['down'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['down'] = false;

		if (isset($_POST['hir_up']))
			$cimy_hir_curr_options['configs'][$id]['up'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['up'] = false;

		if (isset($_POST['hir_left']))
			$cimy_hir_curr_options['configs'][$id]['left'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['left'] = false;

		if (isset($_POST['hir_right']))
			$cimy_hir_curr_options['configs'][$id]['right'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['right'] = false;

		if (isset($_POST['hir_bottomright']))
			$cimy_hir_curr_options['configs'][$id]['bottomright'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['bottomright'] = false;

		if (isset($_POST['hir_topright']))
			$cimy_hir_curr_options['configs'][$id]['topright'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['topright'] = false;

		if (isset($_POST['hir_bottomleft']))
			$cimy_hir_curr_options['configs'][$id]['bottomleft'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['bottomleft'] = false;

		if (isset($_POST['hir_topleft']))
			$cimy_hir_curr_options['configs'][$id]['topleft'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['topleft'] = false;

		if ($cimy_hir_curr_options['configs'][$id]['move_effect'] == "slide") {
			if (!$cimy_hir_curr_options['configs'][$id]['down']
			 && !$cimy_hir_curr_options['configs'][$id]['up']
			 && !$cimy_hir_curr_options['configs'][$id]['left']
			 && !$cimy_hir_curr_options['configs'][$id]['right'])
				$cimy_hir_curr_options['configs'][$id]['down'] = true;
		} else if ($cimy_hir_curr_options['configs'][$id]['move_effect'] == "kenburns") {
			if (!$cimy_hir_curr_options['configs'][$id]['bottomright']
			 && !$cimy_hir_curr_options['configs'][$id]['topright']
			 && !$cimy_hir_curr_options['configs'][$id]['bottomleft']
			 && !$cimy_hir_curr_options['configs'][$id]['topleft'])
				$cimy_hir_curr_options['configs'][$id]['bottomright'] = true;
		}

		if (isset($_POST['hir_swap_rate'])) {
			$rate = intval($_POST["hir_swap_rate"]);

			if ($rate == 0)
				$rate = 1;

			$cimy_hir_curr_options['configs'][$id]['swap_rate'] = $rate;
		}

		if (isset($_POST['hir_swap_type'])) {
			$cimy_hir_curr_options['configs'][$id]['swap_type'] = $_POST["hir_swap_type"];
		}

		if (isset($_POST['hir_name'])) {
			$cimy_hir_curr_options['configs'][$id]['name'] = stripslashes($_POST["hir_name"]);
			if (empty($cimy_hir_curr_options['configs'][$id]['name'])) {
				if ($id == 0)
					$cimy_hir_curr_options['configs'][$id]['name'] = $options["configs"][$id]['name'] = __("Main", $cimy_hir_domain);
				else
					$cimy_hir_curr_options['configs'][$id]['name'] = sprintf(__("Config #%d", $cimy_hir_domain), $id);
			}
		}

		if (isset($_POST['hir_div_id'])) {
			$cimy_hir_curr_options['configs'][$id]['div_id'] = stripslashes($_POST["hir_div_id"]);
		}

		if (isset($_POST['hir_div_width'])) {
			$value = intval($_POST["hir_div_width"]);
			if ($value < 0)
				$value = 0;
			$cimy_hir_curr_options['configs'][$id]['div_width'] = $value;
		}

		if (isset($_POST['hir_div_height'])) {
			$value = intval($_POST["hir_div_height"]);
			if ($value < 0)
				$value = 0;
			$cimy_hir_curr_options['configs'][$id]['div_height'] = $value;
		}

		if (isset($_POST['hir_border'])) {
			$value = intval($_POST["hir_border"]);
			if ($value < 0)
				$value = 0;
			$cimy_hir_curr_options['configs'][$id]['border'] = $value;
		}

		if (isset($_POST['hir_fade'])) {
			$value = intval($_POST["hir_fade"]);
			if ($value < 0)
				$value = 0;
			$cimy_hir_curr_options['configs'][$id]['fade'] = $value;
		}

		if (isset($_POST['hir_shuffle']))
			$cimy_hir_curr_options['configs'][$id]['shuffle'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['shuffle'] = false;

		if (isset($_POST['hir_double_fade']))
			$cimy_hir_curr_options['configs'][$id]['double_fade'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['double_fade'] = false;

		if (isset($_POST['hir_div_size_adaptable']))
			$cimy_hir_curr_options['configs'][$id]['div_size_adaptable'] = true;
		else
			$cimy_hir_curr_options['configs'][$id]['div_size_adaptable'] = false;

		if (isset($_POST['hir_file_link'])) {
			$value = stripslashes($_POST["hir_file_link"]);
			$cimy_hir_curr_options['configs'][$id]['file_link'] = $value;
		}

		$cimy_hir_curr_options['configs'][$id]['link_target'] = $_POST['hir_link_target'];

		if (isset($_POST['hir_file_text'])) {
			$value = stripslashes($_POST["hir_file_text"]);
			$cimy_hir_curr_options['configs'][$id]['file_text'] = $value;
		}

		update_option($cimy_hir_options, $cimy_hir_curr_options);
	}

	if (isset($_GET['to_del'])) {
		$file_name = $_GET['to_del'];

		// protect from site traversing
		$file_name = sanitize_file_name($file_name);

		@unlink(IMAGE_PATH.$id.'/'.$file_name);
		unset($cimy_hir_curr_options['configs'][$id][$_GET['to_del']]);
		update_option($cimy_hir_options, $cimy_hir_curr_options);
	}

	if (isset($_POST['filelinks'])) {
		foreach ($_POST['filelinks'] as $key=>$link)
			$cimy_hir_curr_options['configs'][$id][$key]["link"] = stripslashes($link);

		foreach ($_POST['filetext'] as $key=>$text)
			$cimy_hir_curr_options['configs'][$id][$key]["text"] = stripslashes($text);


		update_option($cimy_hir_options, $cimy_hir_curr_options);
	}

	$image_array = get_files(IMAGE_PATH, "*", $id);
	$pics = count($image_array);
?>
<div class="wrap">
<?php
	if (function_exists("screen_icon"))
		screen_icon("options-general");
?>
<h2>Cimy Header Image Rotator</h2>
<table class="form-table">
	<tr>
		<th scope="row" width="40%">
			<strong><a href="<?php echo $cimy_project_url; ?>"><?php _e("Support the Cimy Project", $cimy_hir_domain); ?></a></strong>
		</th>
		<td width="60%">
			<form style="text-align: left;" action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input name="cmd" type="hidden" value="_s-xclick" />
			<input name="hosted_button_id" type="hidden" value="8774924" />
			<input alt="PayPal - The safer, easier way to pay online." name="submit" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" type="image" />
			<img src="https://www.paypal.com/it_IT/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
			</form>
			<?php _e("This plug-in is the results of hours of development to add new features, support new WordPress versions and fix bugs, please donate money if saved you from spending all these hours!", $cimy_hir_domain); ?>
		</td>
	</tr>
</table>
<form method="post" id="cimy_hir_admin" name="cimy_hir_admin" action="">
<input type="hidden" name="cimy_hir_post" value="1" />
<?php foreach ($cimy_hir_curr_options['configs'] as $list_id=>$value) {
if ($id == $list_id)
	echo "<strong>";
if ($list_id > 0)
	echo " | "; ?>
<a href="<?php echo admin_url("options-general.php?page=cimy_header_image_rotator&amp;id=".$list_id); ?>"><?php echo esc_html($cimy_hir_curr_options['configs'][$list_id]['name']); ?></a>
<?php if ($id == $list_id)
	echo "</strong>";


} ?>
<br /><a href="<?php echo admin_url("options-general.php?page=cimy_header_image_rotator&amp;action=add"); ?>"><?php _e("Add one configuration", $cimy_hir_domain); ?></a>
<?php if ($id > 0) { ?>
 | <a href="<?php echo admin_url("options-general.php?page=cimy_header_image_rotator&amp;id=".$id."&amp;action=delete"); ?>"><?php _e("Delete current configuration", $cimy_hir_domain); ?></a>
<?php } ?>
<table border="0" cellspacing="2" cellpadding="0">
<!-- 	ThemeFuse link doesn't pay enough, disabling -->
<!--	<tr><td></td><td></td><td></td><td></td>
		<td colspan="10">
			<div style="width: 450px; border: 1px solid #dddddd; background: none repeat scroll 0% 0% #ffffff; padding: 20px;">
			<h3 style="margin: 0; padding: 0;">ThemeFuse Original WP Themes</h3>
			<p>If you are interested in buying an original WP theme I would recommend <a href="https://www.e-junkie.com/ecom/gb.php?cl=136641&amp;c=ib&amp;aff=155563" target="ejejcsingle">ThemeFuse</a>. They make some amazing WP themes, that have a cool 1 click auto install feature and excellent after care support services. Check out some of their themes!</p>
			<p><a href="https://www.e-junkie.com/ecom/gb.php?cl=136641&amp;c=ib&amp;aff=155563" target="ejejcsingle"><img src="http://themefuse.com/banners/468x60.png" border="0" alt="Original WP by ThemeFuse" width="468" height="60" /></a></p>
			</div>
		</td>
	</tr>-->
	<tr>
		<td width="20"></td>
		<td><strong><?php _e("Name:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_name" value="<?php echo esc_attr($cimy_hir_curr_options['configs'][$id]['name']); ?>" size="30" />
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td colspan="5"><strong><?php _e("Load the pictures from:", $cimy_hir_domain); ?></strong></td>
	</tr>
	<tr>
		<td><input type="radio" name="hir_images_source" value="theme_default"<?php checked("theme_default", $cimy_hir_curr_options['configs'][$id]['images_source']); ?> /></td>
		<td colspan="5"><?php printf("<a href='%s' target='_blank'>".__("Theme's default [%d pictures]", $cimy_hir_domain).'</a>', admin_url("themes.php?page=custom-header"), count($_wp_default_headers)); ?></td>
	</tr>
	<tr>
		<td><input type="radio" name="hir_images_source" value="theme_uploaded"<?php checked("theme_uploaded", $cimy_hir_curr_options['configs'][$id]['images_source']); ?> />
		<td colspan="5"><?php printf("<a href='%s' target='_blank'>".__("Theme's uploaded [%d pictures]", $cimy_hir_domain).'</a>', admin_url("themes.php?page=custom-header"), count($theme_uploaded_headers)); ?></td>
	</tr>
	<tr>
		<td><input type="radio" name="hir_images_source" value="plugin"<?php checked("plugin", $cimy_hir_curr_options['configs'][$id]['images_source']); ?> /></td>
		<td colspan="5"><?php printf(__("Plug-in's uploaded [%d pictures]", $cimy_hir_domain), $pics); ?></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td width="20"><img src="<?php echo PLUGIN_URI.'img/ok.gif'; ?>" alt="" /></td>
		<td><strong><?php _e("Swap rate:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_swap_rate" value="<?php echo $cimy_hir_curr_options['configs'][$id]['swap_rate']; ?>" size="6" />
		<select name="hir_swap_type">
			<option value="s" <?php selected("s", $cimy_hir_curr_options['configs'][$id]['swap_type']); ?>><?php _e("Seconds", $cimy_hir_domain); ?></option>
			<option value="i" <?php selected("i", $cimy_hir_curr_options['configs'][$id]['swap_type']); ?>><?php _e("Minutes", $cimy_hir_domain); ?></option>
			<option value="G" <?php selected("G", $cimy_hir_curr_options['configs'][$id]['swap_type']); ?>><?php _e("Hours", $cimy_hir_domain); ?></option>
			<option value="d" <?php selected("d", $cimy_hir_curr_options['configs'][$id]['swap_type']); ?>><?php _e("Days", $cimy_hir_domain); ?></option>
			<option value="W" <?php selected("W", $cimy_hir_curr_options['configs'][$id]['swap_type']); ?>><?php _e("Weeks", $cimy_hir_domain); ?></option>
			<option value="n" <?php selected("n", $cimy_hir_curr_options['configs'][$id]['swap_type']); ?>><?php _e("Months", $cimy_hir_domain); ?></option>
		</select></td>
	</tr>
</table>
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td width="20"></td>
		<td><?php _e("This setting determines the interval at which your header images rotate.", $cimy_hir_domain); ?></td>
	</tr>
</table>
<p></p>
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td width="20"><img src="<?php header_img_check($id); ?>" alt="" /></td>
		<td><strong><?php _e("DIV ID:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_div_id" size="24" value="<?php echo esc_attr($cimy_hir_curr_options['configs'][$id]['div_id']); ?>" /></td>
		<td><strong><?php _e("Border:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_border" size="24" value="<?php echo $cimy_hir_curr_options['configs'][$id]['border']; ?>" /></td>
	</tr>
	<tr>
		<td></td>
		<td><strong><?php _e("Width:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_div_width" size="24" value="<?php echo $cimy_hir_curr_options['configs'][$id]['div_width']; ?>" /></td>
		<td><strong><?php _e("Height:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_div_height" size="24" value="<?php echo $cimy_hir_curr_options['configs'][$id]['div_height']; ?>" /></td>
	</tr>
	<tr>
		<td></td>
		<td><strong><?php _e("Fade effect:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_fade" size="24" value="<?php echo $cimy_hir_curr_options['configs'][$id]['fade']; ?>" /></td>
		<td colspan="2"><?php _e("smaller value means faster fade (0=disabled)", $cimy_hir_domain); ?></td>
	</tr>
	<tr>
		<td colspan="5"><input type="checkbox" name="hir_double_fade" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['double_fade']); ?> /> <?php _e("Double fade (Better experience with pictures that have transparent background)", $cimy_hir_domain); ?></td>
	</tr>
	<tr>
		<td colspan="5"><input type="checkbox" name="hir_div_size_adaptable" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['div_size_adaptable']); ?> /> <?php _e("Adapt the container's size to the window's size (Requires browser page refresh)", $cimy_hir_domain); ?></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td><input name="hir_move_effect" type="radio" value="none"<?php checked("none", $cimy_hir_curr_options['configs'][$id]['move_effect']); ?> /></td>
		<td><strong><?php _e("Static", $cimy_hir_domain); ?></strong></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td><input name="hir_move_effect" type="radio" value="slide"<?php checked("slide", $cimy_hir_curr_options['configs'][$id]['move_effect']); ?> /></td>
		<td colspan="5"><strong><?php _e("Slide effect:", $cimy_hir_domain); ?></strong></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="5"><?php _e("Note that swap rate is ignored in this case and if you get no images then you have to reduce the size of the div and/or lower the speed value", $cimy_hir_domain); ?></td>
	</tr>
	<tr>
		<td></td>
		<td><strong><?php _e("Speed:", $cimy_hir_domain); ?></strong></td>
		<td><input type="text" name="hir_speed" size="24" value="<?php echo $cimy_hir_curr_options['configs'][$id]['speed']; ?>" /></td>
		<td colspan="2"><?php _e("pixels/second", $cimy_hir_domain); ?></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="3"><input type="checkbox" name="hir_down" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['down']); ?> /> <?php _e("From UP to DOWN", $cimy_hir_domain); ?></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="3"><input type="checkbox" name="hir_up" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['up']); ?> /> <?php _e("From DOWN to UP", $cimy_hir_domain); ?></td>
	</tr>

	<tr>
		<td></td>
		<td colspan="3"><input type="checkbox" name="hir_right" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['right']); ?> /> <?php _e("From LEFT to RIGHT", $cimy_hir_domain); ?></td>
	</tr>

	<tr>
		<td></td>
		<td colspan="3"><input type="checkbox" name="hir_left" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['left']); ?> /> <?php _e("From RIGHT to LEFT", $cimy_hir_domain); ?></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td><input name="hir_move_effect" type="radio" value="kenburns"<?php checked("kenburns", $cimy_hir_curr_options['configs'][$id]['move_effect']); ?> /></td>
		<td><strong><?php _e("Ken Burns effect:", $cimy_hir_domain); ?></strong></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="hir_bottomright" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['bottomright']); ?> /> <?php _e("From TOP LEFT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_from_topleft_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['from_topleft_zoom']; ?>" size="3" />x</td>
		<td><?php _e("to BOTTOM RIGHT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_to_bottomright_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['to_bottomright_zoom']; ?>" size="3" />x</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="hir_topleft" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['topleft']); ?> /> <?php _e("From BOTTOM RIGHT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_from_bottomright_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['from_bottomright_zoom']; ?>" size="3" />x</td>
		<td><?php _e("to TOP LEFT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_to_topleft_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['to_topleft_zoom']; ?>" size="3" />x</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="hir_bottomleft" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['bottomleft']); ?> /> <?php _e("From TOP RIGHT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_from_topright_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['from_topright_zoom']; ?>" size="3" />x</td>
		<td><?php _e("to BOTTOM LEFT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_to_bottomleft_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['to_bottomleft_zoom']; ?>" size="3" />x</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="hir_topright" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['topright']); ?> /> <?php _e("From BOTTOM LEFT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_from_bottomleft_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['from_bottomleft_zoom']; ?>" size="3" />x</td>
		<td><?php _e("to TOP RIGHT", $cimy_hir_domain); ?></td>
		<td><strong><?php _e("Zoom:", $cimy_hir_domain); ?></strong> <input type="text" name="hir_to_topright_zoom" value="<?php echo $cimy_hir_curr_options['configs'][$id]['to_topright_zoom']; ?>" size="3" />x</td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td colspan="5"><input type="radio" name="hir_start_from" size="24" value="image_zero"<?php checked("image_zero", $cimy_hir_curr_options['configs'][$id]['start_from']); ?> /> <?php _e("Start always from the first picture", $cimy_hir_domain); ?></td>
	</tr>
	<tr>
		<td colspan="5"><input type="radio" name="hir_start_from" size="24" value="image_in_slot"<?php checked("image_in_slot", $cimy_hir_curr_options['configs'][$id]['start_from']); ?> /> <?php _e("Start from the picture in the time frame assigned, ie: if you want a 'one picture per day'", $cimy_hir_domain); ?></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td colspan="2"><input type="checkbox" name="hir_shuffle" size="24" value="1"<?php checked(1, $cimy_hir_curr_options['configs'][$id]['shuffle']); ?> /> <?php _e("Shuffle images", $cimy_hir_domain); ?></td>
	</tr>
	<tr><td><br /></td></tr>
	<tr>
		<td></td>
		<td><strong><?php _e("Images link:", $cimy_hir_domain); ?></strong></td>
		<td colspan="3"><input type="text" name="hir_file_link" size="79" value="<?php echo esc_attr($cimy_hir_curr_options['configs'][$id]['file_link']); ?>" /></td>
	</tr>
	<tr>
		<td></td>
		<td><strong><?php _e("Clicking on links open new tab/window:", $cimy_hir_domain); ?></strong></td>
		<td colspan="3">
			<select name="hir_link_target">
				<option value="_self" <?php selected($cimy_hir_curr_options['configs'][$id]['link_target'], "_self"); ?>><?php _e("No"); ?></option>
				<option value="_blank" <?php selected($cimy_hir_curr_options['configs'][$id]['link_target'], "_blank"); ?>><?php _e("Yes"); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><strong><?php _e("Images caption:", $cimy_hir_domain); ?></strong></td>
		<td colspan="3"><input type="text" name="hir_file_text" size="79" value="<?php echo esc_attr($cimy_hir_curr_options['configs'][$id]['file_text']); ?>" /></td>
	</tr>
</table>
<p><input class="button-primary" type="submit" name="submitButtonName" value="<?php _e('Save Changes') ?>" /></p>
</form>
<?php
if (isset($image_array[0]))
	if (is_file($image_array[0]))
		$filename = IMAGE_URI.$id."/".basename($image_array[0]);
?>
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td width="20"></td>
		<td width="700">
		<?php
			echo "<br />"."<strong>".__("Copy following code in your theme where you want image rotation:", $cimy_hir_domain)."</strong><br />";
			echo "<p><textarea rows=\"15\" cols=\"200\" class=\"large-text readonly\" name=\"rules\" id=\"rules\" readonly=\"readonly\">";
			echo esc_attr("<div id=\"".$cimy_hir_curr_options['configs'][$id]['div_id']."\">".__("Loading images...", $cimy_hir_domain)."</div>")."\n";
			echo esc_attr("<div class=\"".$cimy_hir_curr_options['configs'][$id]['div_id']."_caption\"></div>\n");
			echo esc_attr("<style type=\"text/css\">\n");
			echo esc_attr("\t#".$cimy_hir_curr_options['configs'][$id]['div_id']." {\n");
			echo esc_attr("\t\tfloat: left;\n");
			echo esc_attr("\t\tmargin: 1em auto;\n");
			echo esc_attr("\t\tborder: ".$cimy_hir_curr_options['configs'][$id]['border']."px solid #000000;\n");
			echo esc_attr("\t\twidth: ".$cimy_hir_curr_options['configs'][$id]['div_width']."px;\n");
			echo esc_attr("\t\theight: ".$cimy_hir_curr_options['configs'][$id]['div_height']."px;\n");
			if ($cimy_hir_curr_options['configs'][$id]['div_size_adaptable']) {
				echo esc_attr("\t\tmax-width: 100%;\n");
				echo esc_attr("\t\tmax-height: 100%;\n");
			}
			echo esc_attr("\t}\n");
			echo esc_attr("\tdiv.".$cimy_hir_curr_options['configs'][$id]['div_id']."_caption {\n");
			echo esc_attr("\t\tposition: absolute;\n");
			echo esc_attr("\t\tmargin-top: 175px;\n");
			echo esc_attr("\t\tmargin-left: -75px;\n");
			echo esc_attr("\t\twidth: 150px;\n");
			echo esc_attr("\t\ttext-align: center;\n");
			echo esc_attr("\t\tleft: 50%;\n");
			echo esc_attr("\t\tpadding: 5px 10px;\n");
			echo esc_attr("\t\tbackground: black;\n");
			echo esc_attr("\t\tcolor: white;\n");
			echo esc_attr("\t\tfont-family: sans-serif;\n");
			echo esc_attr("\t\tborder-radius: 10px;\n");
			echo esc_attr("\t\tdisplay: none;\n");
			echo esc_attr("\t\tz-index: 2;\n");
			echo esc_attr("\t}\n");
			echo esc_attr("</style>\n");
			echo esc_attr("<noscript>")."\n";
			echo "\t".esc_attr("<div id=\"".$cimy_hir_curr_options['configs'][$id]['div_id']."_nojs\">")."\n";
			echo "\t\t".esc_attr("<img id=\"cimy_img_id\" src=\"".$filename."\" alt=\"\" />")."\n";
			echo "\t".esc_attr("</div>")."\n";
			echo esc_attr("</noscript>");
			echo "</textarea></p>";
			echo "<br /><br />";

			_e("<strong>Note 1:</strong> image rotation will be available only for users with JavaScript enabled on their browsers", $cimy_hir_domain);
// 			echo "<br />";
// 			_e("<strong>Note 2:</strong> you can easily avoid real-time rotation just passing <strong>false</strong> to the JavaScript call", $cimy_hir_domain);
		?>
		</td>
	</tr>
</table>
<p></p>
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td width="20"><img src="<?php image_path_check($image_array); ?>" alt="" /></td>
		<td><strong><?php _e("Images path:", $cimy_hir_domain); ?>&nbsp;</strong></td>
		<td><?php echo IMAGE_PATH.$id."/"; ?></td>
	</tr>
</table>
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td width="20"></td>
		<td><?php image_path_message($image_array); ?></td>
	</tr>
</table>
<p></p>
<h2><?php _e("Images"); ?></h2>
<form method="post" id="cimy_hir_upload" name="cimy_hir_upload" action="#cimy_hir_upload">
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<?php $allowed_exts = "'".implode("','", cimy_hir_get_allowed_image_extensions())."'"; ?>
		<td><input id="userfile" name="userfile" size="45" type="file" onchange="uploadFile('cimy_hir_upload', 'userfile', '<?php _e("Please upload an image with one of the following extensions", $cimy_hir_domain); ?>', Array(<?php echo $allowed_exts; ?>));" /></td>
		<td><input class="button" name="upload" type="submit" value="<?php _e("Upload image", $cimy_hir_domain); ?>" /></td>
	</tr>
	<?php if (isset($_POST["upload"])) {
		if ($upload_res == 1)
			echo '<tr><td colspan="2"><font color="red">'.__("There have been some problems uploading the image", $cimy_hir_domain).'</font></td></tr>';
		else if ($upload_res == 2)
			echo '<tr><td colspan="2"><font color="red">'.__("The file name contains some illegal characters, please rename it and upload it again", $cimy_hir_domain).'</font></td></tr>';
	} ?>
	<tr>
		<td colspan="2"><?php
			if (!is_writable(IMAGE_PATH.$id."/")) {
				echo '<img src="'.PLUGIN_URI.'img/error.gif'.'" alt="" />&nbsp;';
				echo "<font color=\"red\">".__("Web server cannot write on images directory, check permissions", $cimy_hir_domain)."</font>";
			}
			else {
				echo '<img src="'.PLUGIN_URI.'img/ok.gif'.'" alt="" />&nbsp;';
				_e("Web server can write on images directory", $cimy_hir_domain);
			}
		?></td>
		<td></td>
	</tr>
</table>
</form>
<br />
<form method="post" id="cimy_hir_file_options" name="cimy_hir_file_options" action="#cimy_hir_file_options">
<table border="0" cellspacing="2" cellpadding="0">
<tr>
<td>
<?php

if ($image_array === false) {
	echo "<font color=\"red\">".__("Cannot open images directory. Please make sure that you create it and web server has read permission over it.", $cimy_hir_domain)."</font>";
} else {
	if ($pics == 0) {
		echo "<font color=\"red\">".__("Could not find any pictures.", $cimy_hir_domain)."</font>";
	}
	else {
		_e("Click on pictures to view...", $cimy_hir_domain);
		?>
		<table class="widefat" cellpadding="10">
		<?php
		$thead_tfoot = '<tr>
			<th style="text-align: center;"><h3>'.__("Delete").'</h3></th>
			<th style="text-align: center;"><h3>'.__("File name", $cimy_hir_domain).'</h3></th>
			<th style="text-align: center;"><h3>'.__("Link").'</h3></th>
			<th style="text-align: center;"><h3>'.__("Caption").'</h3></th>
		</tr>';
		?>
		<thead align="center">
			<?php echo $thead_tfoot; ?>
		</thead>
		<tfoot align="center">
			<?php echo $thead_tfoot; ?>
		</tfoot>
		<tbody>
		<?php
		$style = "";
		foreach ($image_array as $entry) {
			if (is_file($entry)) {
				$style = (' class="alternate"' == $style) ? '' : ' class="alternate"';
				$filename = basename($entry);
				echo '<tr'.$style.'>';
				echo '<td><a href="'.esc_attr(admin_url('options-general.php?page=cimy_header_image_rotator&id='.$id.'&to_del='.$filename.'#cimy_hir_upload')).'" title="'.__("Delete file", $cimy_hir_domain).'" >[X]</a></td>';
				echo '<td><a href="javascript:void(0)" onclick="open_win(\''.esc_attr(IMAGE_URI.$id."/".$filename).'\', \'header\', \'width=500, height=250, status=no, toolbar=no, menubar=no, scrollbars=yes, resizable=yes\')">'.esc_html($filename).'</a>';
				if ($filename != esc_js($filename))
					echo '<br /><font color="red">'.__("The file name contains some illegal characters, please rename it and upload it again", $cimy_hir_domain).'</font>';

				echo '</td>';
				echo "\n<td><input name=\"filelinks[".esc_attr($filename)."]\" type=\"text\" size=\"60\" value=\"".esc_attr($cimy_hir_curr_options['configs'][$id][$filename]["link"])."\" /></td>\n";
				echo "\n<td><input name=\"filetext[".esc_attr($filename)."]\" type=\"text\" size=\"60\" value=\"".esc_attr($cimy_hir_curr_options['configs'][$id][$filename]["text"])."\" /></td>\n";
				echo '</tr>';
			}
		}

		echo "</tbody></table>";
	}
}
?>
<p><input class="button-primary" type="submit" name="submitButtonName" value="<?php _e('Save Changes') ?>" /></p>
</td>
</tr>
</table>
</form>
</div>
<?php
}

function cimy_hir_get_allowed_image_extensions() {
	$all_ext = get_allowed_mime_types();
	$image_ext = array();
	if (empty($all_ext))
		return $image_ext;
	foreach ($all_ext as $key=>$value)
		if (stristr($value, "image/") !== false)
			$image_ext = array_merge($image_ext, explode('|', $key));
	return $image_ext;
}
