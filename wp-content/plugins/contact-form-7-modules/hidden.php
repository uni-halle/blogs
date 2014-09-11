<?php
/*
Plugin Name: Contact Form 7 Modules: Hidden Fields
Plugin URI: https://katz.co/contact-form-7-hidden-fields/
Description: Add hidden fields to the popular Contact Form 7 plugin.
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

add_action('init', 'contact_form_7_hidden_fields_textdomain');

function contact_form_7_hidden_fields_textdomain() {
	// Load the default language files
	load_plugin_textdomain( 'cf7_modules', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


add_action('plugins_loaded', 'contact_form_7_hidden_fields', 11);

function contact_form_7_hidden_fields() {
	global $pagenow;
	if(function_exists('wpcf7_add_shortcode')) {
		wpcf7_add_shortcode( 'hidden', 'wpcf7_hidden_shortcode_handler', true );
		wpcf7_add_shortcode( 'hidden*', 'wpcf7_hidden_shortcode_handler', true );
	} else {
		if($pagenow != 'plugins.php') { return; }
		add_action('admin_notices', 'cfhiddenfieldserror');
		add_action('admin_enqueue_scripts', 'contact_form_7_hidden_fields_scripts');

		function cfhiddenfieldserror() {
			$out = '<div class="error" id="messages"><p>';
			if(file_exists(WP_PLUGIN_DIR.'/contact-form-7/wp-contact-form-7.php')) {
				$out .= 'The Contact Form 7 is installed, but <strong>you must activate Contact Form 7</strong> below for the Hidden Fields Module to work.';
			} else {
				$out .= 'The Contact Form 7 plugin must be installed for the Hidden Fields Module to work. <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=contact-form-7&from=plugins&TB_iframe=true&width=600&height=550').'" class="thickbox" title="Contact Form 7">Install Now.</a>';
			}
			$out .= '</p></div>';
			echo $out;
		}
	}
}

function contact_form_7_hidden_fields_scripts() {
	wp_enqueue_script('thickbox');
}

/**
** A base module for [hidden] and [hidden*]
**/

add_action('admin_init', 'load_contact_form_7_modules_functions');

if(!function_exists('load_contact_form_7_modules_functions')) {
	function load_contact_form_7_modules_functions() {
		include_once(plugin_dir_path( __FILE__ ).'functions.php');
	}
}

/* Shortcode handler */

add_filter('wpcf7_form_elements', 'wpcf7_form_elements_strip_paragraphs_and_brs');
function wpcf7_form_elements_strip_paragraphs_and_brs($form) {
	return preg_replace_callback('/<p>(<input\stype="hidden"(?:.*?))<\/p>/ism', 'wpcf7_form_elements_strip_paragraphs_and_brs_callback', $form);
}

function wpcf7_form_elements_strip_paragraphs_and_brs_callback($matches = array()) {
	return "\n".'<!-- CF7 Modules -->'."\n".'<div style=\'display:none;\'>'.str_replace('<br>', '', str_replace('<br />', '', stripslashes_deep($matches[1]))).'</div>'."\n".'<!-- End CF7 Modules -->'."\n";
}

/**
** A base module for [hidden], [hidden*]
**/

/* Shortcode handler */

function wpcf7_hidden_shortcode_handler( $tag ) {

	if ( ! is_array( $tag ) )
		return '';

	$type = $tag['type'];
	$name = $raw_name = $tag['name'];
	$options = (array) $tag['options'];
	$values = (array) $tag['values'];

	if ( empty( $name ) ) {
		return '';
	}

	$atts = '';
	$id_att = '';
	$class_att = '';
	$size_att = '';
	$maxlength_att = '';
	$tabindex_att = '';
	$title_att = '';

	$class_att .= ' wpcf7-hidden';

	if ( 'hidden*' == $type )
		$class_att .= ' wpcf7-validates-as-required';

	foreach ( $options as $option ) {
		if ( preg_match( '%^id:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
			$id_att = $matches[1];

		} elseif ( preg_match( '%^class:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
			$class_att .= ' ' . $matches[1];
		}
	}

	$value = (string) reset( $values );

	if ( wpcf7_script_is() && $value && preg_grep( '%^watermark$%', $options ) ) {
		$class_att .= ' wpcf7-use-title-as-watermark';
		$title_att .= sprintf( ' %s', $value );
		$value = '';
	}

	if ( wpcf7_is_posted() )
		$value = stripslashes_deep( $_POST[$name] );

	if ( $id_att ) {
		$id_att = trim( $id_att );
		$atts .= ' id="' . trim( $id_att ) . '"';
	}
	if ( $class_att )
		$atts .= ' class="' . trim( $class_att ) . '"';

	// Add support for new CF7 format
	if(preg_match('/hidden/ism', $name)) {
		$name = isset($values[0]) ? $values[0] : $name;
	}

	$sanitized_name = strtolower(trim($name));

	global $post;

	if(is_object($post)) {

		switch($sanitized_name) {
			case 'post_title':
			case 'post-title':
				$value = $post->post_title;
				break;
			case 'page_url':
			case 'post_url':
				$value =  get_permalink($post->ID);
				if(empty($value) && isset($post->guid)) {
					$value = $post->guid;
				}
				$value = esc_url( $value );
				break;
			case 'post_category':
				$categories = get_the_category($post->ID); $catnames = array();
				// Get the category names
				foreach($categories as $cat) { $catnames[] = $cat->cat_name; }

				$value = implode(', ', $catnames);
				break;
			case 'post_author_id':
				$value = $post->post_author;
				break;
			case 'post_author':
				$user = get_userdata($post->post_author);
				$value = $user->display_name;
				break;
			default:
				// You want post_modified? just use [hidden hidden-123 "post_modified"]
				if(isset($post->{$name})) { $value = $post->{$name}; }
				break;
		}

		if (preg_match('/^custom_field\-(.*?)$/ism', $sanitized_name)) {
			$custom_field = preg_replace('/custom_field\-(.*?)/ism', '$1', $name);
			$value = get_post_meta($post->ID, $custom_field, false) ? get_post_meta($post->ID, $custom_field, false) : '';
		}
	}


	// Process user stuff
	if (preg_match('/user/ism', $sanitized_name) && is_user_logged_in()) {
		global $current_user;
      	get_currentuserinfo();

      	switch ($sanitized_name) {
      		case 'user_name':
      			$value = $current_user->user_login;
      			break;
      		case 'user_id':
      			$value = $current_user->ID;
      			break;
      		case 'caps':
      			$value = $current_user->caps;
      			break;
      		case 'allcaps':
      			$value = $current_user->allcaps;
      			break;
      		case 'user_roles':
      			$value = $current_user->roles;
      			break;
      		default:
      			// Gets the values for `user_email`, others that have `user_` prefix.
      			if($current_user->has_prop($sanitized_name)) {
      				$value = $current_user->get($sanitized_name);
      			} else {
	      			// Define some other item in the WP_User object using the `user_[what you want to get]` format
	      			// This works for the `user_display_name` setting
    	  			$user_key = preg_replace('/user[_-](.+)/ism', '$1', $sanitized_name);
    	  			if($current_user->has_prop($user_key)) {
      					$value = $current_user->get($user_key);
      				}
      			}
      			break;
      	}
	}

	// Arrays get imploded.
	$value = is_array($value) ? implode(apply_filters('wpcf7_hidden_field_implode_glue', ', '), $value) : $value;

	// Make sure we're using a string
	if(!is_string($value)) { $value = json_encode($value); }

	$value = apply_filters('wpcf7_hidden_field_value', apply_filters('wpcf7_hidden_field_value_'.$id_att, $value));

	$html = '<input type="hidden" name="' . $raw_name . '" value="' . esc_attr( $value ) . '"' . $atts . ' />'."\n";
	if($name !== $raw_name) { $html .= '<input type="hidden" name="' . $name . '" value="' . esc_attr( $value ) . '"' . $atts . ' />'; }

	return $html;
}

add_filter('wpcf7_hidden_field_value_example', 'wpcf7_hidden_field_add_query_arg');
function wpcf7_hidden_field_add_query_arg($value = '') {
	if(isset($_GET['category'])) {
		return $_GET['category'];
	}
	return $value;
}


/* Tag generator */

add_action( 'admin_init', 'wpcf7_add_tag_generator_hidden', 30 );

function wpcf7_add_tag_generator_hidden() {
	if(function_exists('wpcf7_add_tag_generator')) {
		wpcf7_add_tag_generator( 'hidden', __( 'Hidden field', 'cf7_modules' ), 'wpcf7-tg-pane-hidden', 'wpcf7_tg_pane_hidden' );
	}
}

function wpcf7_tg_pane_hidden() {
?>
<div id="wpcf7-tg-pane-hidden" class="hidden">
<form action="">

<table>
<tr><td><?php echo esc_html( __( 'Name', 'cf7_modules' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>

<tr>
<td><code>id</code> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)<br />
<input type="text" name="id" class="idvalue oneline option" /></td>
</tr>

<tr>
<td>
	<?php echo esc_html( __( 'Default value', 'cf7_modules' ) ); ?> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)<br /><input type="text" name="values" class="oneline" />
	<br /><input type="checkbox" name="watermark" class="option" />&nbsp;<?php echo esc_html( __( 'Use this text as watermark?', 'cf7_modules' ) ); ?>

</td>

<td>
	<?php _e('Dynamic Values', 'cf7_modules'); ?><br />
	<span class="howto" style="font-size:1em;"><?php _e('To use dynamic data from the post or page the form is embedded on, you can use the following values:', 'cf7_modules'); ?></span>
	<ul>
		<li><?php _e('<code>post_title</code>: The title of the post/page', 'cf7_modules'); ?></li>
		<li><?php _e('<code>post_url</code>: The URL of the post/page', 'cf7_modules'); ?></li>
		<li><?php _e('<code>post_category</code>: The categories the post is in, comma-separated', 'cf7_modules'); ?></li>
		<li><?php _e('<code>post_date</code>: The date the post/page was created', 'cf7_modules'); ?></li>
		<li><?php _e('<code>post_author</code>: The name of the author of the post/page', 'cf7_modules'); ?></li>
	</ul>
	<span class="howto"><?php _e('The following values will be replaced if an user is logged in:', 'cf7_modules'); ?></span>
	<ul>
		<li><?php _e('<code>user_name</code>: User Login', 'cf7_modules'); ?></li>
		<li><?php _e('<code>user_id</code>: User ID', 'cf7_modules'); ?></li>
		<li><?php _e('<code>user_email</code>: User Email Address', 'cf7_modules'); ?></li>
		<li><?php _e('<code>user_display_name</code>: Display Name (Generally the first and last name of the user)', 'cf7_modules'); ?></li>
	</ul>
</td>
</tr>
</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'cf7_modules' ) ); ?><br /><input type="text" name="hidden" class="tag" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'cf7_modules' ) ); ?><br /><input type="text" class="mail-tag" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}
