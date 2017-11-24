<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require_once( plugin_dir_path( __FILE__ ).'../includes/opinionstage-client-session.php' );

add_action( 'admin_menu', 'opinionstage_register_menu_page' );
add_action( 'wp_ajax_opinionstage_ajax_toggle_flyout', 'opinionstage_ajax_toggle_flyout' );
add_action( 'wp_ajax_opinionstage_ajax_toggle_article_placement', 'opinionstage_ajax_toggle_article_placement' );
add_action( 'wp_ajax_opinionstage_ajax_toggle_sidebar_placement', 'opinionstage_ajax_toggle_sidebar_placement' );

function opinionstage_register_menu_page() {
	if (function_exists('add_menu_page')) {

		add_menu_page(
			__('Poll, Survey, Quiz, Slider & Form', OPINIONSTAGE_TEXT_DOMAIN),
			__('Poll, Survey, Quiz, Slider & Form', OPINIONSTAGE_TEXT_DOMAIN),
			'edit_posts',
			OPINIONSTAGE_MENU_SLUG,
			'opinionstage_menu_page',
			plugins_url('admin/images/os.png', plugin_dir_path( __FILE__ )),
			'25.234323221'
		);
	}
}

function opinionstage_menu_page() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);

	$os_client_logged_in = opinionstage_user_logged_in();

	opinionstage_register_css_asset( 'menu-page', 'menu-page.css' );
	opinionstage_register_css_asset( 'icon-font', 'icon-font.css' );
	opinionstage_register_javascript_asset( 'menu-page', 'menu-page.js', array('jquery') );

	require( plugin_dir_path( __FILE__ ).'menu-page-template.php' );
}

// Toggle the flyout placement activation flag
function opinionstage_ajax_toggle_flyout() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['fly_out_active'] = $_POST['activate'];

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
}
// Toggle the article placement activation flag
function opinionstage_ajax_toggle_article_placement() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['article_placement_active'] = $_POST['activate'];

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
}
// Toggle the sidebar placement activation flag
function opinionstage_ajax_toggle_sidebar_placement() {
	$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
	$os_options['sidebar_placement_active'] = $_POST['activate'];

	update_option(OPINIONSTAGE_OPTIONS_KEY, $os_options);
}
?>
