<?php

/*
http://codex.wordpress.org/Function_Reference/register_uninstall_hook#uninstall.php

Please note: Due to the uninstall procedure of WordPress you have to delete left-over database entries in multisite environments manually for each blog.
*/

/*
security check
*/

if (!defined( 'WP_UNINSTALL_PLUGIN'))
	wp_die(__('You are not allowed to uninstall plugins on this site.'), '', array('response' => 403));

if (!current_user_can('manage_options'))
	wp_die(__('You do not have sufficient permissions to manage options for this site.'), '', array('response' => 403));

/*
delete option-array
*/

delete_option('timezonecalculator');

/*
delete widget-options
*/

delete_option('widget_timezonecalculator');

/*
delete calculator user-settings
*/

global $wpdb;

$q = "DELETE FROM $wpdb->usermeta WHERE $wpdb->usermeta.meta_key LIKE '%timezonecalculator_timezones%'";

$wpdb->query($q);

/*
delete timezones-cache transients
*/

$transient_name='timezonecalculator_js_array_'.get_locale();

delete_transient($transient_name);
delete_transient($transient_name.'_etc');
