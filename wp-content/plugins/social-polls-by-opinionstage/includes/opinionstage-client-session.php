<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

function opinionstage_user_logged_in() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);

	return isset($os_options['uid']) && isset($os_options['email']);
}

function opinionstage_user_access_token() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);

	if ( isset($os_options['token']) ) {
		return $os_options['token'];
	} else {
		return null;
	}
}
?>
