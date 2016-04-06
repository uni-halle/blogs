<?php

add_action('wp_ajax_xyz_smap_ajax_backlink', 'xyz_smap_ajax_backlink_call');

function xyz_smap_ajax_backlink_call() {


	global $wpdb;

	if($_POST){

		update_option('xyz_credit_link','smap');
	}
	die();
}


?>