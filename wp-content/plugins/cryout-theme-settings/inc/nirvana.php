<?php
if (function_exists('nirvana_init_fn')):
	add_action('admin_init', 'nirvana_init_fn');
endif;

function nirvana_theme_settings_restore($class='') { 
	global $wp_version;
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

