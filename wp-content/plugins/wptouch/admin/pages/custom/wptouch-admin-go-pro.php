<?php $settings = wptouch_get_settings( 'bncid' ); ?>
<div id="upgrade-area">
	<!-- ajaxed -->
</div>

<?php  if ( wptouch_is_path_writable( WP_PLUGIN_DIR ) && wptouch_is_path_writable( WPTOUCH_DIR ) ) { ?>
	<p>If you have a licence for WPtouch Pro you can enter it here and unlock all of WPtouch Pro's features. <br />To purchase a license, please visit <a href="http://www.wptouch.com/?utm_source=wptouch&utm_medium=web&utm_campaign=upgrade-to-pro-top">wptouch.com</a>.</p>
	<div id="license-settings-area" class="wptouch-clearfix">
		<input class="license-inputs" type="text" placeholder="<?php _e( 'Account E-Mail Address', 'wptouch-pro'  ); ?>" id="license_email" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings->bncid ) echo $settings->bncid; else ''; ?>" />

		<input class="license-inputs" type="text" placeholder="<?php _e( 'Product License Key', 'wptouch-pro' ); ?>" id="license_key" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings->wptouch_license_key ) echo $settings->wptouch_license_key; else ''; ?>" />

		<div id="activate-license">
			<a href="#" class="activate button"><?php _e( 'Activate WPtouch Pro', 'wptouch-pro' ); ?></a>
		</div>
	</div>
<?php } ?>


<?php include_once( WPTOUCH_ADMIN_DIR . '/html/license-modals.php' ); ?>