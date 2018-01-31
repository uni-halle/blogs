<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if (function_exists('parabola_init_fn')):
	add_action('admin_init', 'parabola_init_fn');
	add_action('parabola_before_righty', 'parabola_extra');
endif;

function parabola_theme_settings_restore($class='') { 
	global $cryout_theme_settings;
?>
		<form name="parabola_form" action="options.php" method="post" enctype="multipart/form-data">
			<div id="accordion">
				<?php settings_fields('parabola_settings'); ?>
				<?php do_settings_sections('parabola-page'); ?>
			</div>
			<div id="submitDiv">
			    <br>
				<input class="button" name="parabola_settings[parabola_submit]" type="submit" style="float:right;"   value="<?php _e('Save Changes','parabola'); ?>" />
				<input class="button" name="parabola_settings[parabola_defaults]" id="parabola_defaults" type="submit" style="float:left;" value="<?php _e('Reset to Defaults','parabola'); ?>" />
				</div>
		</form>
<?php
} // parabola_theme_settings_buttons()

function parabola_extra() { 
	$url = plugin_dir_url( dirname(__FILE__) ) . '/media';
	include_once( plugin_dir_path( __FILE__ ) . 'extra.php' );
} // parabola_extra()