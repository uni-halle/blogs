<?php 
/**
 * 
 * @author alexhermann
 *
 */

interface AWD_facebook_plugin_interface{
	public function __construct($file,$AWD_facebook);
	public function init();
	public function get_version();
	public function old_parent();
	public function missing_parent();
	public function missing_facebook_connect();
	public function default_options($options);
	public function register_widgets();

	public function admin_init();
	public function admin_menu();
	public function admin_form();
	
	public function plugin_settings_menu($list);
	public function plugin_settings_form($fields);
	
	public function front_enqueue_js();
	public function front_enqueue_css();
	public function admin_enqueue_js();
	public function admin_enqueue_css();
	public function global_enqueue_js();
	public function global_enqueue_css();
	public function hook_post_from_custom_options();
	public function js_vars($vars);
	
}
?>