<?php
if( !defined('ABSPATH') ){ exit();}
function smap_free_network_destroy($networkwide) {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
			$old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				smap_free_destroy();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	smap_free_destroy();
}

function smap_free_destroy()
{
	global $wpdb;
	
	if(get_option('xyz_credit_link')=="smap")
	{
		update_option("xyz_credit_link", '0');
	}
	delete_option('xyz_smap_application_name');
	delete_option('xyz_smap_application_id');
	delete_option('xyz_smap_application_secret');
	//delete_option('xyz_smap_fb_id');
	delete_option('xyz_smap_message');
	delete_option('xyz_smap_po_method');
	delete_option('xyz_smap_post_permission');
	delete_option('xyz_smap_current_appln_token');
	delete_option('xyz_smap_af');
	delete_option('xyz_smap_pages_ids');
	
		
	delete_option('xyz_smap_twconsumer_secret');
	delete_option('xyz_smap_twconsumer_id');
	delete_option('xyz_smap_tw_id');
	delete_option('xyz_smap_current_twappln_token');
	delete_option('xyz_smap_twpost_permission');
	delete_option('xyz_smap_twpost_image_permission');
	delete_option('xyz_smap_twaccestok_secret');
	delete_option('xyz_smap_twmessage');
	delete_option('xyz_smap_twtr_char_limit');
	
	delete_option('xyz_smap_application_lnarray');
	delete_option('xyz_smap_ln_shareprivate');
	delete_option('xyz_smap_ln_sharingmethod');
	delete_option('xyz_smap_lnapikey');
	delete_option('xyz_smap_lnapisecret');
// 	delete_option('xyz_smap_lnoauth_verifier');
// 	delete_option('xyz_smap_lnoauth_token');
// 	delete_option('xyz_smap_lnoauth_secret');
	delete_option('xyz_smap_lnpost_permission');
	delete_option('xyz_smap_lnaf');
	delete_option('xyz_smap_lnmessage');
	delete_option('xyz_smap_std_future_to_publish');
	delete_option('xyz_smap_std_apply_filters');
	delete_option('xyz_smap_free_version');
	
	delete_option('xyz_smap_include_pages');
	delete_option('xyz_smap_include_posts');
	delete_option('xyz_smap_include_categories');
	delete_option('xyz_smap_include_customposttypes');
	delete_option('xyz_smap_peer_verification');
	delete_option('xyz_smap_fbap_post_logs');
	delete_option('xyz_smap_lnap_post_logs');
	delete_option('xyz_smap_twap_post_logs');
	delete_option('xyz_smap_premium_version_ads');
	delete_option('xyz_smap_default_selection_edit');
// 	delete_option('xyz_smap_utf_decode_enable');
	delete_option('xyz_smap_dnt_shw_notice');
	delete_option('smap_installed_date');
	delete_option('xyz_smap_credit_dismiss');
}

register_uninstall_hook(XYZ_SMAP_PLUGIN_FILE,'smap_free_network_destroy');

?>