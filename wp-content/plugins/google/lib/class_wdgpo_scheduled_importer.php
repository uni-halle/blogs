<?php
class Wdgpo_ScheduledImporter {

	private function __construct () {}

	public static function serve () {
		if (!function_exists('curl_init')) return false;
		$google = Wdgpo_GoogleAuth::get_instance();
		$google->import_gplus_data();
	}
}
/**
 * Schedule cron jobs for comments import.
 */
function wdgpo_comment_import () {
	Wdgpo_ScheduledImporter::serve();
}
if (function_exists('curl_init')) add_action('wdgpo_import_comments', 'wdgpo_comment_import');
if (!wp_next_scheduled('wdgpo_import_comments')) wp_schedule_event(time(), 'hourly', 'wdgpo_import_comments');