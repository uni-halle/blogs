<?php $settings = wptouch_get_settings( 'bncid' ); ?>
<?php if ( wptouch_is_path_writable( WP_PLUGIN_DIR ) && wptouch_is_path_writable( WPTOUCH_DIR ) ) { ?>
	<h3>Upgrade to WPtouch Pro</h3>
	<p>If you have a licence for WPtouch Pro you can enter it here and automatically upgrade.</p>
	<div id="license-settings-area" class="wptouch-clearfix">
		<input class="license-inputs" type="text" placeholder="<?php _e( 'Account E-Mail Address', 'wptouch-pro'  ); ?>" id="license_email" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings->bncid ) echo $settings->bncid; else ''; ?>" />

		<input class="license-inputs" type="text" placeholder="<?php _e( 'Product License Key', 'wptouch-pro' ); ?>" id="license_key" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings->wptouch_license_key ) echo $settings->wptouch_license_key; else ''; ?>" />

		<div id="activate-license">
			<a href="#" class="activate button"><?php _e( 'Activate', 'wptouch-pro' ); ?></a>
		</div>
	</div>
<?php } ?>

<div id="upgrade-area">
	<!-- ajaxed -->
</div>

<?php include_once( WPTOUCH_ADMIN_DIR . '/html/license-modals.php' ); ?>