<?php
/**
 * 
 * @author alexhermann
 *
 */

//NEEDED VARS
$plugin_directory = array_pop(explode(DIRECTORY_SEPARATOR, dirname(dirname(__FILE__))));
$this->wpdb = $wpdb;
$this->plugin_url = plugins_url($plugin_directory);
$this->plugin_url_images = $this->plugin_url."/assets/img/";
$this->set_admin_roles();
//TRANSLATION
load_plugin_textdomain($this->ptd,false,dirname( plugin_basename( __FILE__ ) ) . '/langs/');

//init
add_action("init",array(&$this,'wp_init'));
add_action('admin_init', array(&$this,'admin_initialisation'));

//DISPLAY ADMIN
add_action('admin_bar_init', array(&$this,'admin_bar_init'));


add_action('AWD_facebook_admin_notices',array(&$this,'display_messages'));
add_action('AWD_facebook_admin_notices',array(&$this,'missing_config'));
add_action('AWD_facebook_admin_notices',array(&$this,'display_all_errors'));

add_action('admin_menu', array(&$this,'admin_menu'));
add_action('network_admin_menu', array(&$this,'add_js_options'));

add_filter("admin_footer_text", array($this,'admin_footer_text'),10,1);
add_action('edit_user_profile', array(&$this,'user_profile_edit'));
add_action('show_user_profile', array(&$this,'user_profile_edit'));
add_action('personal_options_update', array(&$this,'user_profile_save'));
add_action('edit_user_profile_update', array(&$this,'user_profile_save'));
add_action("AWD_facebook_save_settings",array(&$this,'hook_post_from_plugin_options'));
add_action('admin_footer',array(&$this,'debug_content'));

//DISPLAY FRONT
add_filter('language_attributes',  array($this,'ogp_language_attributes'),10,1);
add_action('after_setup_theme',array(&$this,'add_theme_support'));
add_action('wp_head',array(&$this,'display_ogp_objects'));
add_filter('the_content', array(&$this,'the_content'));
add_filter('comments_template', array(&$this,'the_comments_form'));
add_action('wp_enqueue_scripts',array(&$this,'front_enqueue_js'));
add_action('wp_footer',array(&$this,'debug_content'));

//INTERNAL
add_action('wp_ajax_get_media_field', array(&$this,'ajax_get_media_field'));
add_action('wp_ajax_get_app_infos_content', array(&$this,'get_app_infos_content'));
add_action('wp_ajax_delete_ogp_object', array(&$this,'delete_ogp_object'));
add_action('wp_ajax_save_ogp_object', array(&$this,'save_ogp_object'));
add_action('wp_ajax_save_ogp_object_links', array(&$this,'save_ogp_object_links'));
add_action('wp_ajax_get_open_graph_object_form', array(&$this,'ajax_get_open_graph_object_form'));
add_filter('rewrite_rules_array',array(&$this,'insert_rewrite_rules' ));
add_filter('query_vars',array(&$this,'insert_query_vars' ));
add_action('wp_loaded',array(&$this,'flush_rules' ));
add_action('parse_query',array(&$this,'parse_request' ));
add_filter('logout_url',array(&$this,'logout_url'));
add_action('save_post', array(&$this, 'save_options_post_editor'));

//remove frame security header to allow the website admin to work in iframe
remove_action( 'login_init', 'send_frame_options_header');
remove_action( 'admin_init', 'send_frame_options_header');

//SHORTCODES
add_shortcode('AWD_likebutton', array(&$this,'shortcode_like_button'));
add_shortcode('AWD_likebox', array(&$this,'shortcode_like_box'));
add_shortcode('AWD_activitybox', array(&$this,'shortcode_activity_box'));
add_shortcode('AWD_shared_activitybox', array(&$this,'shortcode_shared_activity_box'));
add_shortcode('AWD_loginbutton', array(&$this,'shortcode_login_button'));
add_shortcode('AWD_comments', array(&$this,'shortcode_comments_box'));


//OPTIONS
$this->optionsManager = new AWD_facebook_options($this->plugin_option_pref,$this->wpdb);
$this->optionsManager->load();
$this->options = $this->optionsManager->getOptions();


//Init the SDK PHP
if(!empty($this->options['app_id'])  && !empty($this->options['app_secret_key'])){
	$this->php_sdk_init();	
}

//UPDATES OPTIONS
do_action("AWD_facebook_save_settings");
/****************************************************
* load subplugins AWD
* save settings
* refresh options from bdd.
/****************************************************/
do_action("AWD_facebook_plugins_init");
//UPDATES OPTIONS FOR PLUGINS
do_action("AWD_facebook_save_settings");
/****************************************************/
$this->optionsManager->load();
$this->options = $this->optionsManager->getOptions();

//load the facebook lib
//and load the sdk if connect if activated.
add_action('wp_print_footer_scripts',array(&$this,'js_sdk_init'), 11);
add_action('admin_print_footer_scripts',array(&$this,'js_sdk_init'));

//init the FB connect
if($this->options['app_id'] !='' && $this->options['app_secret_key'] !=''){
	if($this->options['connect_enable'] == 1){
		$on_register = $this->options['login_button']['display_on_register_page'];
		$on_login = $this->options['login_button']['display_on_login_page'];
		
		//add action to add the login button on the wp-login.php page...
		if($on_register == 1 || $on_login == 1){
			add_action('login_enqueue_scripts',array(&$this,'login_enqueue_scripts'));
		}
		if($on_login == 1){
			add_action('login_form',array(&$this,'the_login_button_wp_login'));
		}
		if($on_register == 1){
			add_action('register_form',array(&$this,'the_login_button_wp_login'));
		}
		//Add avatar functions
		if($this->options['connect_fbavatar'] == 1){
			add_filter('get_avatar', array($this, 'fb_get_avatar'), 100, 5);//modify in last... 
		}
	}
}
?>