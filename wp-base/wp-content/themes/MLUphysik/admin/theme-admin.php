<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 */


/*-----------------------------------------------------------------------------------*/
/* REGISTER Admin */
/*-----------------------------------------------------------------------------------*/
function MLUphysik_theme_settings_init(){
	register_setting( 'MLUphysik_theme_settings', 'MLUphysik_theme_settings' );
}


// add js for admin
function MLUphysik_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}
//add css for admin
function MLUphysik_style() {
	wp_enqueue_style('thickbox');
}
function MLUphysik_echo_scripts()
{
?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {

// Media Uploader
window.formfield = '';

jQuery('.upload_image_button').live('click', function() {
	window.formfield = jQuery('.upload_field',jQuery(this).parent());
	tb_show('', 'media-upload.php?type=image&TB_iframe=true');
	return false;
});

window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {
	if (window.formfield) {
		imgurl = jQuery('img',html).attr('src');
		window.formfield.val(imgurl);
		tb_remove();
	}
	else {
		window.original_send_to_editor(html);
	}
	window.formfield = '';
	window.imagefield = false;
}

});
//]]> 
</script>
<?php
}

if (isset($_GET['page']) && $_GET['page'] == 'MLUphysik-settings') {
	add_action('admin_print_scripts', 'MLUphysik_scripts'); 
	add_action('admin_print_styles', 'MLUphysik_style');
	add_action('admin_head', 'MLUphysik_echo_scripts');
}


function MLUphysik_add_settings_page() {
add_theme_page( __( 'MLUphysik' ), __( 'Theme Options' ), 'manage_options', 'MLUphysik-settings', 'MLUphysik_theme_settings_page');
}

add_action( 'admin_init', 'MLUphysik_theme_settings_init' );
add_action( 'admin_menu', 'MLUphysik_add_settings_page' );

function MLUphysik_theme_settings_page() {
	
global $slider_effects;
?>


<?php 
/*-----------------------------------------------------------------------------------*/
/* START Admin */
/*-----------------------------------------------------------------------------------*/
?>

<div class="wrap">

<?php
// If the form has just been submitted, this shows the notification
if ( $_GET['settings-updated'] ) { ?>
<div id="message" class="updated fade MLUphysik-message"><p><?php _e('Options Saved','MLUphysik'); ?></p></div>
<?php } ?>

<div id="icon-options-general" class="icon32"></div>
<h2><?php _e( 'Theme Options' ) ?></h2>

<form method="post" action="options.php">

<?php settings_fields( 'MLUphysik_theme_settings' ); ?>
<?php $options = get_option( 'MLUphysik_theme_settings' ); ?>

<table class="form-table">  

<tr valign="top">
<th scope="row"><?php _e( 'Favicon', 'MLUphysik' ); ?></th>
<td>
<input id="MLUphysik_theme_settings[favicon]" class="regular-text" type="text" size="36" name="MLUphysik_theme_settings[favicon]" value="<?php esc_attr_e( $options['favicon'] ); ?>" />
<br />
<label class="description abouttxtdescription" for="MLUphysik_theme_settings[favicon]"><?php _e( 'Upload or type in the URL for the site favicon.' ); ?></label>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'Logo', 'MLUphysik' ); ?></th>
<td>
<input id="MLUphysik_theme_settings[upload_mainlogo]" class="regular-text upload_field" type="text" size="36" name="MLUphysik_theme_settings[upload_mainlogo]" value="<?php esc_attr_e( $options['upload_mainlogo'] ); ?>" />
<input class="upload_image_button button-secondary" type="button" value="Upload Image" />
<br />
<label class="description abouttxtdescription" for="MLUphysik_theme_settings[logo]"><?php _e( 'Upload or type in the URL for the site logo.' ); ?></label>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'Notifications Bar Code', 'MLUphysik' ); ?></th>
<td>
<textarea id="MLUphysik_theme_settings[notification]" class="regular-text" name="MLUphysik_theme_settings[notification]" rows="5" cols="45"><?php esc_attr_e( $options['notification'] ); ?></textarea>
<br />
<label class="description abouttxtdescription" for="MLUphysik_theme_settings[notification]"><?php _e( 'Enter your custom homepage tagline text here. HTML allowed.' ); ?></label>
</td>

<tr valign="top">
<th scope="row">Theme Credits</th>
<td><p>This Theme was created by <a href="http://www.oligoform.com">OLIGOFORM GbR</a>.<br /><em>inspired by AJ Clarke.</em></p>
</td>
</tr>

</table>
<p class="submit-changes">
<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
</p>
</form>
</div><!-- END wrap -->

<?php
}
//sanitize and validate
function MLUphysik_options_validate( $input ) {
	global $select_options, $radio_options;
	if ( ! isset( $input['option1'] ) )
		$input['option1'] = null;
	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
	$input['sometext'] = wp_filter_nohtml_kses( $input['sometext'] );
	if ( ! isset( $input['radioinput'] ) )
		$input['radioinput'] = null;
	if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
		$input['radioinput'] = null;
	$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );

	return $input;
}

?>