<?php
/*
Plugin Name: Contact Form 7 Modules: Send All Fields
Plugin URI: https://katz.co/contact-form-7-hidden-fields/
Description: Send all submitted fields in the message body using one simple tag: <code>[all-fields]</code>
Author: Katz Web Services, Inc.
Author URI: http://www.katzwebservices.com
Version: 1.4.2
Text Domain: cf7_modules
Domain Path: languages
*/

/*  Copyright 2014 Katz Web Services, Inc. (email: info at katzwebservices.com)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_action('init', 'contact_form_7_all_fields_textdomain');

function contact_form_7_all_fields_textdomain() {
	// Load the default language files
	load_plugin_textdomain( 'cf7_modules', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * A base module for [all-fields] and [all-fields*]
 */
add_action('init', 'load_contact_form_7_modules_functions');

if(!function_exists('load_contact_form_7_modules_functions')) {
	function load_contact_form_7_modules_functions() {
		include_once(plugin_dir_path( __FILE__ ).'functions.php');
	}
}

add_filter('wpcf7_mail_components', 'all_fields_wpcf7_before_send_mail');

function all_fields_wpcf7_before_send_mail($array) {
	$debug = false;  global $wpdb;

	if($debug) { print_r($array); }
	if($debug) { print_r($_POST); }

	$post = $_POST;

	$html = false;
	if(wpautop($array['body']) == $array['body']) { $html = true; }

	foreach($post as $k => $v) {
		if(substr($k, 0, 6) == '_wpcf7' || strpos($k, 'all-fields') || $k === '_wpnonce') {
			unset($post["{$k}"]);
		}
	}
	if($debug) { print_r($post); }

	$postbody = '';

    if($html) {
    	$postbody = apply_filters( 'wpcf7_send_all_fields_format_before' , '<dl>', 'html');
    } else {
    	$postbody = apply_filters( 'wpcf7_send_all_fields_format_before' , '', 'text');
    }

	foreach($post as $k => $v) {

        // Remove dupe content. The Hidden and Values are both sent.
        if(preg_match('/hidden\-/', $k)) { continue; }

        // If there's no value for the field, don't send it.
        if(empty($v) && false === apply_filters( 'wpcf7_send_all_fields_send_empty_fields' , false )) {
        	continue;
        }

        if(is_array($v)) {
			$v = implode(', ', $v);
		}

        // Make the fields easier to read. Thanks, @hitolonen
        $k = apply_filters( 'wpcf7_send_all_fields_format_key', true ) ? ucwords(str_replace("-", " ", str_replace("_", " ", $k))) : $k;

        // Sanitize!
        $k = esc_attr($k);
        $v = esc_attr($v);

		if($html) {
			$postbody .= apply_filters( 'wpcf7_send_all_fields_format_item', "<dt style='font-size:1.2em;'><font size='3'><strong style='font-weight:bold;'>{$k}</strong>:</font></dt><dd style='padding:0 0 .5em 1.5em; margin:0;'>{$v}</dd>", $k, $v, 'html');
		} else {
			$postbody .= apply_filters( 'wpcf7_send_all_fields_format_item', "{$k}: {$v}\n", $k, $v, 'text');
		}
	}
	if($html) {
		$postbody .= apply_filters( 'wpcf7_send_all_fields_format_after' , '</dl>', 'html');
	} else {
		$postbody .= apply_filters( 'wpcf7_send_all_fields_format_after' , '', 'text');
	}

	if($debug) { print_r($postbody); }

	$array['body'] = str_replace('<p>[all-fields]</p>', $postbody, str_replace('[all-fields]', $postbody, $array['body']));

    if($debug) { die(); } else { return $array; }
}


/* Tag generator */

add_action( 'admin_init', 'wpcf7_add_tag_generator_all_fields', 30 );

function wpcf7_add_tag_generator_all_fields() {
	if(function_exists('wpcf7_add_tag_generator')) {
		wpcf7_add_tag_generator( 'all-fields', __( 'All Fields', 'cf7_modules' ), 'wpcf7-tg-pane-all-fields', 'wpcf7_tg_pane_all_fields' );
	}
}

function wpcf7_tg_pane_all_fields() {
?>
<div id="wpcf7-tg-pane-all-fields" class="hidden">
	<form action="">
	<h3><?php printf(__('Add all fields to your email with %s[all-fields]%s', 'cf7_modules'), '<code>', '</code>'); ?></h3>
	<div class="tg-mail-tag" style="text-align:left; margin-top:.5em;">
		<?php echo esc_html( __( "Put this code into the Mail fields below to output all submitted fields in the email.", 'cf7_modules' ) ); ?><br />&nbsp;<input type="text" value="[all-fields]" readonly="readonly" onfocus="this.select()" />
	</div>
	</form>
</div>
<?php
}