<?php

require_once( 'list.php' );

function wptouch_admin_is_checklist_option_selected() {
	global $_primed_setting;

	$settings = wptouch_get_settings( $_primed_setting->domain, false );
	$setting_name = $_primed_setting->name;

	return ( isset( $settings->$setting_name ) && ( in_array( wptouch_admin_get_list_option_key() , $settings->$setting_name ) ) );
}