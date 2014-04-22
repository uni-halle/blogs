<?php
/**
 * Sets $options['upgrade-0-9-9'] to true if user is updating
 */
function omega_upgrade_routine() {

	$options = get_option( 'omega_framework', false );
	
	// If version is set, upgrade routine has already run
	if ( $options['version'] == '0.9.9' ) {
		return;
	}
	
	// If $options exist, user is upgrading
	if ( empty( $options['upgrade-0-9-9']) && get_option( 'omega_theme_settings', false ) ) {
		$options['upgrade-0-9-9'] = true;
	}

	// New version number
	$options['version'] = '0.9.9';

	update_option( 'omega_framework', $options );
}
add_action( 'admin_init', 'omega_upgrade_routine' );

/**
 * Displays notice if user has upgraded theme
 */
function omega_upgrade_notice() {

	if ( current_user_can( 'edit_theme_options' ) ) {
		$options = get_option( 'omega_framework', false );

		if ( !empty( $options['upgrade-0-9-9'] ) && $options['upgrade-0-9-9'] ) {
			echo '<div class="updated"><p>';
				printf( __(
					'Thanks for updating Omega Theme.  Please <a href="%1$s" target="_blank">read about important changes</a> in this version and give your site a quick check. <a href="%2$s">Dismiss notice</a>' ),
					'http://themehall.com/forums/topic/omega-0-9-9-updates',
					'?omega_upgrade_notice_ignore=1' );
			echo '</p></div>';
		}
	}

}
add_action( 'admin_notices', 'omega_upgrade_notice', 100 );

/**
 * Hides notices if user chooses to dismiss it
 */
function omega_notice_ignores() {

	$options = get_option( 'omega_framework' );

	if ( isset( $_GET['omega_upgrade_notice_ignore'] ) && '1' == $_GET['omega_upgrade_notice_ignore'] ) {
		$options['upgrade-0-9-9'] = false;
		update_option( 'omega_framework', $options );
	}

}
add_action( 'admin_init', 'omega_notice_ignores' );
?>