<?php
if( !defined('ABSPATH') ){ exit();}
add_action('wp_ajax_xyz_smap_ajax_backlink', 'xyz_smap_ajax_backlink_call');
function xyz_smap_ajax_backlink_call() {

	global $wpdb;
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'backlink' ))
		 {
					echo 1;die;
		 }
		 if(current_user_can('administrator')){
		 	global $wpdb;
		 	if(isset($_POST)){
		 		if(intval($_POST['enable'])==1){
		 			update_option('xyz_credit_link','smap');
		 			echo "smap";
		 		}
		 		if(intval($_POST['enable'])==-1){
		 			update_option('xyz_smap_credit_dismiss', "hide");
		 			echo -1;
		 		}
		 	}
		 }
	}
	die();
}

add_action('wp_ajax_xyz_smap_selected_pages_auto_update', 'xyz_smap_free_selected_pages_auto_update_fn');

function xyz_smap_free_selected_pages_auto_update_fn() {
	global $wpdb;
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_selected_pages_nonce' ))
		{
			echo 1;die;
		}
		global $wpdb;
		if(isset($_POST)){
			$pages=stripslashes($_POST['pages']);
			$smap_sec_key=$_POST['smap_secretkey'];
			$xyz_smap_fb_numericid=$_POST['xyz_fb_numericid'];
			$xyz_smap_smapsoln_userid=$_POST['smapsoln_userid'];
			update_option('xyz_smap_page_names',$pages);
			update_option('xyz_smap_af', 0);
			update_option('xyz_smap_secret_key', $smap_sec_key);
			update_option('xyz_smap_fb_numericid', $xyz_smap_fb_numericid);
			update_option('xyz_smap_smapsoln_userid', $xyz_smap_smapsoln_userid);
		}
	}
	die();
}
add_action('wp_ajax_xyz_smap_xyzscripts_accinfo_auto_update', 'xyz_smap_xyzscripts_accinfo_auto_update_fn');
function xyz_smap_xyzscripts_accinfo_auto_update_fn() {
	global $wpdb;
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_xyzscripts_accinfo_nonce' ))
		{
			echo 1;die;
		}
		global $wpdb;
		if(isset($_POST)){
			$xyzscripts_hash_val=stripslashes($_POST['xyz_user_hash']);
			$xyzscripts_user_id=$_POST['xyz_userid'];
			update_option('xyz_smap_xyzscripts_user_id', $xyzscripts_user_id);
			update_option('xyz_smap_xyzscripts_hash_val', $xyzscripts_hash_val);
		}
	}
	die();
}
?>