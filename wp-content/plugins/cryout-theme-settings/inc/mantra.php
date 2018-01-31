<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if (function_exists('mantra_init_fn')):
	add_action('admin_init', 'mantra_init_fn');
	add_action('mantra_before_righty', 'mantra_extra');
endif;

function mantra_theme_settings_restore($class='') {
	global $cryout_theme_settings;
?>
		<form name="mantra_form" action="options.php" method="post" enctype="multipart/form-data">
			<div id="accordion">
				<?php settings_fields('ma_options'); ?>
				<?php do_settings_sections('mantra-page'); ?>
			</div>
			<div id="submitDiv">
			    <br>
				<input class="button" name="ma_options[mantra_submit]" type="submit" style="float:right;"   value="<?php _e('Save Changes','mantra'); ?>" />
				<input class="button" name="ma_options[mantra_defaults]" id="mantra_defaults" type="submit" style="float:left;" value="<?php _e('Reset to Defaults','mantra'); ?>" />
				</div>
		</form>
<?php
} // mantra_theme_settings_buttons()

function mantra_extra() {
	$url = plugin_dir_url( dirname(__FILE__) ) . '/media';
	include_once( plugin_dir_path( __FILE__ ) . 'extra.php' );
} // mantra_extra()