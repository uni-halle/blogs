<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

add_action('admin_footer', 'add_deactivate_feedback_form');

function add_deactivate_feedback_form() {
	$current_screen = get_current_screen();

	if ( 'plugins' !== $current_screen->id && 'plugins-network' !== $current_screen->id ) {
		return;
	}

	if(opinionstage_user_access_token() == null){
		return; 
	}

	include 'views/deactivate-feedback-form.php';
}

?>