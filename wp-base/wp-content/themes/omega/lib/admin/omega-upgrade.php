<?php
/**
 * Upgrade routine for Portfolio Press.
 * Sets $options['upgrade-0-9-6'] to true if user is updating
 */
function omega_upgrade_routine() {

	$options = get_option( 'omega_framework', false );

	// If version is set, upgrade routine has already run
	if ( !empty( $options['version'] ) ) {
		return;
	}

	// If $options exist, user is upgrading
	if ( $options ) {
		$options['upgrade-0-9-6'] = true;
	}

	// New version number
	$options['version'] = '0.9.6';

	update_option( 'omega_framework', $options );
}
add_action( 'admin_init', 'omega_upgrade_routine' );

/**
 * Displays notice if user has upgraded theme
 */
function omega_upgrade_notice() {

	if ( current_user_can( 'edit_theme_options' ) ) {
		$options = get_option( 'omega_framework', false );

		if ( !empty( $options['upgrade-0-9-6'] ) && $options['upgrade-0-9-6'] ) {
			echo '<div class="updated"><p>';
				printf( __(
					'Thanks for updating Omega Theme.  Please <a href="%1$s">read about important changes</a> in this version and give your site a quick check. <a href="%2$s">Dismiss notice</a>' ),
					'http://themehall.com/forums/topic/omega-0-9-6-updates',
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
		$options['upgrade-0-9-6'] = false;
		update_option( 'omega_framework', $options );
	}

}
add_action( 'admin_init', 'omega_notice_ignores' );
?>