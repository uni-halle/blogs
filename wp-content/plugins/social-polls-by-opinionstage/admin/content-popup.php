<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

add_action( 'media_buttons', 'opinionstage_content_popup_add_editor_button');
add_action( 'admin_enqueue_scripts', 'opinionstage_content_popup_js');
add_action( 'admin_footer-post-new.php', 'opinionstage_content_popup_html' );
add_action( 'admin_footer-post.php',     'opinionstage_content_popup_html' );

function opinionstage_content_popup_add_editor_button() {
	require( plugin_dir_path( __FILE__ ).'content-popup-button.php' );
}

function opinionstage_content_popup_js() {
	opinionstage_register_javascript_asset(
		'content-popup',
		'content-popup.js',
		array('jquery')
	);

	opinionstage_enqueue_js_asset('content-popup');
}

function opinionstage_content_popup_html() {
	require( plugin_dir_path( __FILE__ ).'content-popup-template.html.php' );
}
?>
