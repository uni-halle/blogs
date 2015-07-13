<?php
/*
Plugin Name: Contact Form 7 Modules: Hidden Fields
Plugin URI: https://katz.co/contact-form-7-hidden-fields/
Description: Add hidden fields to the popular Contact Form 7 plugin.
Author: Katz Web Services, Inc.
Author URI: http://www.katzwebservices.com
Version: 2.0
Text Domain: cf7_modules
Domain Path: languages
*/

/*  Copyright 2015 Katz Web Services, Inc. (email: info at katzwebservices.com)

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
	if( class_exists('WPCF7_Shortcode') ) {
		wpcf7_add_shortcode( array( 'hidden', 'hidden*' ), 'wpcf7_hidden_shortcode_handler', true );
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

/* Shortcode handler */

add_filter('wpcf7_form_elements', 'wpcf7_form_elements_strip_paragraphs_and_brs');

/**
 * Strip paragraph tags being wrapped around the field
 * @param $form
 *
 * @return mixed
 */
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

	$tag = new WPCF7_Shortcode( $tag );

	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-hidden' );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$class .= ' wpcf7-hidden';

	if ( 'hidden*' === $tag->type ) {
		$class .= ' wpcf7-validates-as-required';
	}

	$value = (string) reset( $tag->values );

	$placeholder = '';
	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$placeholder = $value;
		$value       = '';
	}

	$default_value = $tag->get_default_option( $value );

	$value = contact_form_7_hidden_fields_fill_post_data( $value, $tag );

	// Post data hasn't filled yet. No arrays.
	if( $default_value === $value ) {
		$value = contact_form_7_hidden_fields_fill_user_data( $value );
	}

	// Arrays get imploded.
	$value = is_array( $value ) ? implode( apply_filters( 'wpcf7_hidden_field_implode_glue', ', ' ), $value ) : $value;

	// Make sure we're using a string. Objects get JSON-encoded.
	if ( ! is_string( $value ) ) {
		$value = json_encode( $value );
	}

	$value = apply_filters( 'wpcf7_hidden_field_value', apply_filters( 'wpcf7_hidden_field_value_' . $tag->get_id_option(), $value ) );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts = array(
		'type'        => 'hidden',
		'class'       => $tag->get_class_option( $class ),
		'id'          => $tag->get_id_option(),
		'name'        => $tag->name,
		'tabindex'    => $tag->get_option( 'tabindex', 'int', true ),
		'placeholder' => $placeholder,
		'value'       => $value,
	);

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf( '<input %1$s />%2$s', $atts, $validation_error );

	return $html;
}

/**
 * Fill data based on user information.
 *
 * @param string $value Existing value, if any
 *
 * @return mixed
 */
function contact_form_7_hidden_fields_fill_user_data( $value ) {

	$return = $value;

	// Process user stuff
	if ( preg_match( '/user/ism', strtolower( trim( $value ) ) ) && is_user_logged_in() ) {
		global $current_user;
		get_currentuserinfo();

		switch ( $value ) {
			case 'user_name':
				$return = $current_user->user_login;
				break;
			case 'user_id':
				$return = $current_user->ID;
				break;
			case 'caps':
				$return = $current_user->caps;
				break;
			case 'allcaps':
				$return = $current_user->allcaps;
				break;
			case 'user_roles':
				$return = $current_user->roles;
				break;
			default:
				// Gets the values for `user_email`, others that have `user_` prefix.
				if ( $current_user->has_prop( $value ) ) {
					$return = $current_user->get( $value );
				} else {
					// Define some other item in the WP_User object using the `user_[what you want to get]` format
					// This works for the `user_display_name` setting
					$user_key = preg_replace( '/user[_-](.+)/ism', '$1', $value );
					if ( $current_user->has_prop( $user_key ) ) {
						$return = $current_user->get( $user_key );
					}
				}
				break;
		}
	}

	return $return;
}

/**
 * Fill data based on user information.
 *
 * @param string $value Existing value, if any
 * @param WPCF7_Shortcode $tag Tag
 *
 * @return mixed
 */
function contact_form_7_hidden_fields_fill_post_data( $value = '', $tag ) {
	global $post;

	$return = $value;

	if ( is_object( $post ) ) {

		switch ( strtolower( trim( $value ) ) ) {
			case 'post_title':
			case 'post-title':
				$return = $post->post_title;
				break;
			case 'page_url':
			case 'post_url':
				$return = get_permalink( $post->ID );
				if ( empty( $return ) && isset( $post->guid ) ) {
					$return = $post->guid;
				}
				$return = esc_url( $return );
				break;
			case 'post_category':
				$categories = get_the_category( $post->ID );
				$catnames   = array();
				// Get the category names
				foreach ( $categories as $cat ) {
					$catnames[] = $cat->cat_name;
				}

				$return = implode( ', ', $catnames );
				break;
			case 'post_author_id':
				$return = $post->post_author;
				break;
			case 'post_author':
				$user   = get_userdata( $post->post_author );
				$return = $user->display_name;
				break;
			default:
				// You want post_modified? just use [hidden hidden-123 "post_modified"]
				if ( isset( $post->{ $value } ) ) {
					$return = $post->{ $value };
				}
				break;
		}

		if ( preg_match( '/^custom_field\-(.*?)$/ism', $value ) ) {
			$custom_field = preg_replace( '/custom_field\-(.*?)/ism', '$1', $value );
			$return       = get_post_meta( $post->ID, $custom_field, false ) ? get_post_meta( $post->ID, $custom_field, false ) : '';
		}
	}

	return $return;
}

add_filter('wpcf7_hidden_field_value_example', 'wpcf7_hidden_field_add_query_arg');
function wpcf7_hidden_field_add_query_arg($value = '') {
	if(isset($_GET['category'])) {
		return $_GET['category'];
	}
	return $value;
}


/* Tag generator */

if ( is_admin() ) {
	add_action( 'admin_init', 'wpcf7_add_tag_generator_hidden', 30 );
}

function wpcf7_add_tag_generator_hidden() {

	if( class_exists('WPCF7_TagGenerator') ) {

		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add( 'hidden', __( 'hidden', 'cf7_modules' ), 'wpcf7_tg_pane_hidden' );

	}
}

function wpcf7_tg_pane_hidden( $contact_form, $args = '' ) {

	$args = wp_parse_args( $args, array() );

	$description = __( "Generate a form tag for a hidden field. For more details, see %s.", 'contact-form-7' );
	$desc_link = wpcf7_link( __( 'https://wordpress.org/plugins/contact-form-7-modules/', 'contact-form-7' ), __( 'the plugin page on WordPress.org', 'contact-form-7' ), array('target' => '_blank' ) );
?>
<div class="control-box">
	<fieldset>
		<legend><?php printf( esc_html( $description ), $desc_link ); ?></legend>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
					<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
				</tr>

				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'ID attribute', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'cf7_modules' ) ); ?>)</label></th>
					<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Value', 'cf7_modules'); ?>
					</th>
					<td>
						<input type="text" name="values" class="oneline" />
						<div>
							<input type="checkbox" name="watermark" class="option" />&nbsp;<?php echo esc_html( __( 'Use this text as watermark?', 'cf7_modules' ) ); ?>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Dynamic Values', 'cf7_modules'); ?>
					</th>
					<td>
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
			</tbody>
		</table>
	</fieldset>
</div>
	<div class="insert-box">
		<input type="text" name="hidden" class="tag code" readonly="readonly" onfocus="this.select()" />

		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
		</div>

		<br class="clear" />

		<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
	</div>
<?php
}
