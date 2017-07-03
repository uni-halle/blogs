<?php
if (function_exists('tempera_init_fn')):
	add_action('admin_init', 'tempera_init_fn');
	add_action('tempera_before_righty', 'tempera_extra');
endif;

function tempera_theme_settings_restore($class='') { 
	global $cryout_theme_settings;
?>
		<form name="tempera_form" id="tempera_form" action="options.php" method="post" enctype="multipart/form-data">
			<div id="accordion">
				<?php settings_fields('tempera_settings'); ?>
				<?php do_settings_sections('tempera-page'); ?>
			</div>
			<div id="submitDiv">
			    <br>
				<input class="button" name="tempera_settings[tempera_submit]" type="submit" id="tempera_sumbit" style="float:right;"   value="<?php _e('Save Changes','tempera'); ?>" />
				<input class="button" name="tempera_settings[tempera_defaults]" id="tempera_defaults" type="submit" style="float:left;" value="<?php _e('Reset to Defaults','tempera'); ?>" />
				</div>
		</form>
<?php
} // tempera_theme_settings_buttons()

function tempera_extra() { 
	$url = plugin_dir_url( dirname(__FILE__) ) . '/media';
	include_once( plugin_dir_path( __FILE__ ) . 'extra.php' );
} // tempera_extra()