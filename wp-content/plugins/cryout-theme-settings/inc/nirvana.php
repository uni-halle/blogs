<?php
if (function_exists('nirvana_init_fn')):
	add_action('admin_init', 'nirvana_init_fn');
	add_action('nirvana_before_righty', 'nirvana_extra');
endif;

function nirvana_theme_settings_restore($class='') { 
	global $cryout_theme_settings;
?>
		<form name="nirvana_form" id="nirvana_form" action="options.php" method="post" enctype="multipart/form-data">
			<div id="accordion" class="<?php echo $class; ?>">
				<?php settings_fields('nirvana_settings'); ?>
				<?php do_settings_sections('nirvana-page'); ?>
			</div>
			<div id="submitDiv">
			    <br>
				<input class="button" name="nirvana_settings[nirvana_submit]" type="submit" id="nirvana_sumbit" style="float:right;"   value="<?php _e('Save Changes','nirvana'); ?>" />
				<input class="button" name="nirvana_settings[nirvana_defaults]" id="nirvana_defaults" type="submit" style="float:left;" value="<?php _e('Reset to Defaults','nirvana'); ?>" />
				</div>
		</form>
<?php
} // nirvana_theme_settings_buttons()

function nirvana_extra() { 
	$url = plugin_dir_url( dirname(__FILE__) ) . '/media';
	include_once( plugin_dir_path( __FILE__ ) . 'extra.php' );
} // nirvana_extra()
