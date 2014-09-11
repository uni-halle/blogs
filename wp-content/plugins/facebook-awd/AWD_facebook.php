<?php
/*
Plugin Name: Facebook AWD All in One
Plugin URI: http://facebook-awd.ahwebdev.fr
Description: Facebook AWD will adds required extensions from facebook to your site.
Version: 1.6
Author: AHWEBDEV
Author URI: http://www.ahwebdev.fr
License: Copywrite AHWEBDEV
Text Domain: AWD_facebook
Last modification: 23/01/2013
 */

/**
 * Facebook AWD All in One
 *
 * @package facebook-awd
 * @author alexhermann
 */
Class AWD_facebook
{

	/**
	 * The name of the plugin
	 * 
	 * @var unknown_type
	 */
	public $plugin_name = 'Facebook AWD';

	/**
	 * The slug of the plugin
	 * 
	 * @var string
	 */
	public $plugin_slug = 'awd_fcbk';

	/**
	 * private
	 * preffix blog option
	 */
	public $plugin_option_pref = 'awd_fcbk_option_';

	/**
	 * public
	 * preffix blog option
	 */
	public $plugin_page_admin_name = 'Facebook Admin';

	/**
	 * public
	 * preffix blog option
	 */
	public $ptd = 'AWD_facebook';

	/**
	 * private
	 * position of the menu in admin
	 */
	public $blog_admin_hook_position;

	/**
	 * private
	 * hook admin
	 */
	public $blog_admin_page_hook;

	/**
	 * public
	 * current_user
	 */
	public $current_user;

	/**
	 * public
	 * Name of the file of the plugin
	 */
	public $file_name = "AWD_facebook.php";

	/**
	 * public
	 * Options of the plugin
	 */
	public $options = array();

	/**
	 * global message admin
	 */
	public $messages;

	/**
	 * global message admin
	 */
	public $errors;

	/**
	 * global message admin
	 */
	public $warnings;

	/**
	 * me represent the facebook user datas
	 */
	public $me = null;

	/**
	 * fcbk represent the facebook php SDK instance
	 */
	public $fcbk = null;

	/**
	 * the ID of the current facebook user.
	 */
	public $uid = null;

	/**
	 * the internal url to login
	 * @var string
	 */
	public $_login_url;

	/**
	 * the internal url to logout
	 * @var string
	 */
	public $_logout_url;

	/**
	 * the internal url to unsyc account
	 * @var string
	 */
	public $_unsync_url;

	/**
	 * Contains the list of Plugins AWD_facebook_plugin_interface
	 * @var array
	 */
	public $plugins = array();

	//****************************************************************************************
	//	GLOBALS FUNCTIONS
	//****************************************************************************************    
	/**
	 * Setter $this->current_user
	 */
	public function get_current_user()
	{
		$this->current_user = wp_get_current_user();
		return $this->current_user;
	}

	/**
	 * Getter Version
	 * @param array $plugin_folder_var
	 * @return array
	 */
	public function get_version(array $plugin_folder_var = array())
	{
		if (count($plugin_folder_var) == 0) {
			include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			$plugin_folder = get_plugins();
		}
		return $plugin_folder['facebook-awd/AWD_facebook.php']['Version'];
	}

	/**
	 *
	 * Getter
	 * the first image displayed in a post.
	 * @param string $post_content
	 * @return the image found.
	 */ 
	public function catch_that_image($post_content = "")
	{
		global $post;
		$first_img = '';
		if ($post_content == "" && is_object($post))
			$post_content = $post->post_content;
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
		if (isset($matches[1][0]))
			$first_img = $matches[1][0];
		return $first_img;
	}

	/**
	 * Debug a var
	 * @param var $var
	 * @param boolean $detail default = false
	 * @echo string
	 */
	public function Debug($var, $detail = 0)
	{
		echo "<pre>";
		if ($detail != 0) {
			var_dump($var);
		} else {
			print_r($var);
		}
		echo "</pre>";
	}

	/**
	 * Get the plugin path 
	 * required for Facebook AWD plugins
	 * @return string $path
	 */
	public function get_plugins_model_path()
	{
		return realpath(dirname(__FILE__)) . '/inc/classes/plugins/class.AWD_facebook_plugin_abstract.php';
	}

	//****************************************************************************************
	//	INIT
	//****************************************************************************************
	public function __autoload($class_name)
	{
		$dependencies = array(
		//AWD dependencies and plugins
		'awd_fcbk_api' => dirname(__FILE__) . '/inc/classes/facebook/class.AWD_facebook_api.php', 'awd_fcbk_likebutton' => dirname(__FILE__) . '/inc/classes/model/class.AWD_facebook_likebutton.php', 'awd_fcbk_activity' => dirname(__FILE__) . '/inc/classes/model/class.AWD_facebook_activity.php', 'awd_fcbk_shared_activity' => dirname(__FILE__) . '/inc/classes/model/class.AWD_facebook_shared_activity.php', 'awd_fcbk_likebox' => dirname(__FILE__) . '/inc/classes/model/class.AWD_facebook_likebox.php', 'awd_fcbk_comments' => dirname(__FILE__) . '/inc/classes/model/class.AWD_facebook_comments.php', 'awd_fcbk_options' => dirname(__FILE__) . '/inc/classes/model/class.AWD_facebook_options.php', 'awd_fcbk_widgets' => dirname(__FILE__) . '/inc/classes/tools/class.AWD_facebook_widget.php', 'awd_fcbk_forms' => dirname(__FILE__) . '/inc/classes/tools/class.AWD_facebook_form.php', 
		//Opengraph protocol by ogp.me
		'opengraph_media' => dirname(__FILE__) . '/inc/classes/tools/opengraph_protocol_tools/media.php', 'opengraph_objects' => dirname(__FILE__) . '/inc/classes/tools/opengraph_protocol_tools/objects.php', 'opengraph_protocol' => dirname(__FILE__) . '/inc/classes/tools/opengraph_protocol_tools/opengraph-protocol.php', 
		//getid 3 tools for Opengraph medias.
		'getid3' => dirname(__FILE__) . '/inc/classes/tools/getid3/getid3.php');
		$dependencies = apply_filters("AWD_facebook_deps", $dependencies);
		foreach ($dependencies as $dep => $path) {
			if (file_exists($path)) {
				require_once($path);
			} else {
				throw new RuntimeException("Facebook AWD loader Class: " . $dep . " => " . $path . " was not found", 404);
			}
		}
	}

	/**
	 * plugin construct
	 */
	public function __construct()
	{
		spl_autoload_register(array(&$this, '__autoload'));
		//init the plugin and action
		add_action('plugins_loaded', array(&$this, 'initial'));
		//like box widget register
		add_action('widgets_init', array(&$this, 'register_AWD_facebook_widgets'));
		$ps = get_option('permalink_structure');
		//Base vars
		$this->_login_url = $ps != '' ? home_url('facebook-awd/login') : home_url('?facebook_awd[action]=login');
		$this->_logout_url = $ps != '' ? home_url('facebook-awd/logout') : home_url('?facebook_awd[action]=logout');
		$this->_unsync_url = $ps != '' ? home_url('facebook-awd/unsync') : home_url('?facebook_awd[action]=unsync');
		$this->_channel_url = $ps != '' ? home_url('facebook-awd/channel.html') : home_url('?facebook_awd[action]=channel.html');
		$this->_deauthorize_url = $ps != '' ? home_url('facebook-awd/deauthorize') : home_url('?facebook_awd[action]=deauthorize');
		$this->_realtime_api_url = $ps != '' ? home_url('facebook-awd/realtime-update-api') : home_url('?facebook_awd[action]=realtime-update-api');
	}

	/**
	 * hook action added to init
	 */
	public function wp_init()
	{
		//Js
		wp_register_script($this->plugin_slug . '-bootstrap-js', $this->plugin_url . '/assets/js/bootstrap.min.js', array('jquery'), $this->get_version(), true);
		wp_register_script($this->plugin_slug . '-google-code-prettify', $this->plugin_url . '/assets/js/google-code-prettify/prettify.js', array('jquery'));
		wp_register_script($this->plugin_slug . '-admin-js', $this->plugin_url . '/assets/js/facebook_awd_admin.js', array('jquery', $this->plugin_slug . '-google-code-prettify'));
		wp_register_script($this->plugin_slug, $this->plugin_url . '/assets/js/facebook_awd.js', array('jquery'), $this->get_version(), true);

		//Css
		wp_register_style($this->plugin_slug . '-ui-bootstrap', $this->plugin_url . '/assets/css/bootstrap.css');
		//wp_register_style($this->plugin_slug.'-ui-bootstrap-responsive', $this->plugin_url.'/assets/css/bootstrap-responsive.min.css');
		wp_register_style($this->plugin_slug . '-google-code-prettify-css', $this->plugin_url . '/assets/js/google-code-prettify/prettify.css');
	}

	/**
	 * plugin init
	 */
	public function initial()
	{
		global $wpdb;
		include_once(dirname(__FILE__) . '/inc/init.php');
	}

	/**
	 * add support for ogimage opengraph
	 */
	public function add_theme_support()
	{
		//add fetured image menu to get FB image in open Graph set image 50x50
		if (function_exists('add_theme_support')) {
			add_theme_support('post-thumbnails');
			add_image_size('AWD_facebook_ogimage', 200, 200, true);
		}
		//add featured image + post excerpt in post type too.
		if (function_exists('add_post_type_support')) {
			$post_types = get_post_types();
			foreach ($post_types as $type) {
				add_post_type_support($type, array('thumbnails', 'excerpt'));
			}
		}
	}

	//****************************************************************************************
	//	MESSAGE ADMIN
	//****************************************************************************************
	/**
	 * missing config notices
	 * 
	 */
	public function missing_config()
	{
		if ($this->options['app_id'] == '') {
			$this->errors[] = new WP_Error('AWD_facebook_not_ready', __('Facebook AWD is almost ready... Go to settings and set your FB Application ID.', $this->ptd));
		}
		if ($this->options['app_secret_key'] == '') {
			$this->errors[] = new WP_Error('AWD_facebook_not_ready', __('Facebook AWD is almost ready... Go to settings and set your FB Secret Key.', $this->ptd));
		}
	}

	/**
	 * Display Error in admin Facebook AWD area
	 * 
	 */
	public function display_all_errors()
	{
		$html = '';
		if (isset($this->errors) && count($this->errors) > 0 AND is_array($this->errors)) {
			foreach ($this->errors as $error) {
				if (is_wp_error($error)) {
					$html .= $error->get_error_message();
				}
			}
			$this->display_messages($html, 'error');
		}
		$html = '';
		if (isset($this->warnings) && count($this->warnings) > 0 AND is_array($this->warnings)) {
			foreach ($this->warnings as $warning) {
				if (is_wp_error($warning))
					$html .= $warning->get_error_message();

			}
			$this->display_messages($html, 'warning', true);
		}
	}

	/**
	 * Display Message in admin Facebook AWD area
	 * 
	 */
	public function display_messages($message = null, $type = 'info', $echo = true)
	{
		$html = '';
		if (!empty($message)) {
			$html = '<div class="alert alert-' . $type . '">' . $message . '</div>';
		} else if (isset($this->messages) && count($this->messages) > 0 AND is_array($this->messages)) {
			foreach ($this->messages as $key => $message) {
				if (is_string($type))
					$type = $key;
				$html .= '<div class="alert alert-' . $type . '">' . $message . '</div>';
			}
		}
		if (!$echo)
			return $html;

		echo $html;
	}

	/**
	 * Getter
	 * Help in the plugin tooltip
	 * 
	 * @param string $elem
	 * @param string $class
	 * @param string $image
	 * @return string a link to open lightbox with linked content
	 */
	public function get_the_help($elem, $class = "help awd_tooltip", $image = 'info.png')
	{
		return '<a href="#" class="' . $class . '" id="help_' . $elem . '"><i class="icon-info-sign"></i></a>';
	}

	//****************************************************************************************
	//	ADMIN
	//****************************************************************************************

	/**
	 * Checks if we should add links to the bar.
	 * 
	 */
	public function admin_bar_init()
	{
		// Is the user sufficiently leveled, or has the bar been disabled?
		if (!is_super_admin() || !is_admin_bar_showing())
			return;
		add_action('admin_bar_menu', array(&$this, 'admin_bar_links'), 500);
	}

	/**
	 * Add links to the Admin bar.
	 * 
	 */
	public function admin_bar_links()
	{
		global $wp_admin_bar;
		$links = array();

		if ($this->is_user_logged_in_facebook() && isset($this->me['link'])) {
			$links[] = array(__('My Profile', $this->ptd), $this->me['link']);
		}
		if ($this->is_user_logged_in_facebook()) {
			$links[] = array(__('Refresh Facebook Data', $this->ptd), $this->_login_url);
			$links[] = array(__('Unsync FB Account', $this->ptd), $this->_unsync_url);
		}

		if (current_user_can('manage_options')) {
			$links[] = array(__('Settings', $this->ptd), admin_url('admin.php?page=' . $this->plugin_slug));
			$links[] = array(__('Documentation', $this->ptd), 'http://facebook-awd.ahwebdev.fr/documentation/');
			$links[] = array(__('Support', $this->ptd), 'http://facebook-awd.ahwebdev.fr/support/');
			if (!is_admin())
				$links[] = array(__('Debugger', $this->ptd), 'http://developers.facebook.com/tools/debug/og/object?q=' . urlencode($this->get_current_url()));
		}
		$links = apply_filters('AWD_facebook_admin_bar_links', $links);

		if (count($links)) {
			$wp_admin_bar->add_menu(array('title' => '<img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'facebook-mini.png" alt="facebook logo"/> ' . $this->plugin_name, 'href' => false, 'id' => $this->plugin_slug, 'href' => false));
			foreach ($links as $link => $infos) {
				$wp_admin_bar->add_menu(array('id' => $this->plugin_slug . '_submenu' . $link, 'title' => $infos[0], 'href' => $infos[1], 'parent' => $this->plugin_slug, 'meta' => array('target' => '_blank')));
			}
		}
	}

	/**
	 * Save customs fields during post edition
	 * This will be called on sheduled post too
	 * WARNING Sheduled posts published event is only fired when someone hit the website. 
	 * The publish to facebook hook is anonimous as we store the pages's access token.
	 * 
	 * @param int $post_id
	 */
	public function save_options_post_editor($post_id)
	{
		if (!wp_is_post_revision($post_id)) {
			$fb_publish_to_pages = false;
			$fb_publish_to_user = false;
			$post = get_post($post_id);
			$options = get_post_meta($post_id, $this->plugin_slug, true);
			$narray = array();
			foreach ($_POST as $__post => $val) {
				if (preg_match('@' . $this->plugin_option_pref . '@', $__post)) {
					$name = str_ireplace($this->plugin_option_pref, '', $__post);
					$options[$name] = $val;
				}
			}
			//$options = array_merge($options, $narray);			
			update_post_meta($post->ID, $this->plugin_slug, $options);
			$this->publish_post_hook($post);
		}
	}

	/**
	 * Add footer text ads Facebook AWD version
	 * 
	 * @param string $footer_text
	 * @return string
	 */
	public function admin_footer_text($footer_text)
	{
		return $footer_text . "  " . __('| With:', $this->ptd) . " <a href='http://facebook-awd.ahwebdev.fr/'>" . $this->plugin_name . " v" . $this->get_version() . "</a>";
	}

	/**
	 * Admin plugin init menu
	 * call form init.php
	 */
	public function admin_menu()
	{
		//admin hook
		$this->blog_admin_page_hook = add_menu_page($this->plugin_page_admin_name, __($this->plugin_name, $this->ptd), 'manage_facebook_awd_publish_to_pages', $this->plugin_slug, array($this, 'admin_content'), $this->plugin_url_images . 'facebook-mini.png', $this->blog_admin_hook_position);
		$this->blog_admin_settings_hook = add_submenu_page($this->plugin_slug, __('Settings', $this->ptd), '<img src="' . $this->plugin_url_images . 'settings.png" /> ' . __('Settings', $this->ptd), 'manage_facebook_awd_publish_to_pages', $this->plugin_slug);
		$this->blog_admin_plugins_hook = add_submenu_page($this->plugin_slug, __('Plugins', $this->ptd), '<img src="' . $this->plugin_url_images . 'plugins.png" /> ' . __('Plugins', $this->ptd), 'manage_facebook_awd_plugins', $this->plugin_slug . '_plugins', array($this, 'admin_content'));
		if ($this->options['open_graph_enable'] == 1) {
			$this->blog_admin_opengraph_hook = add_submenu_page($this->plugin_slug, __('Open Graph', $this->ptd), '<img src="' . $this->plugin_url_images . 'ogp-logo.png" /> ' . __('Open Graph', $this->ptd), 'manage_facebook_awd_opengraph', $this->plugin_slug . '_open_graph', array($this, 'admin_content'));
			add_action("load-" . $this->blog_admin_opengraph_hook, array(&$this, 'admin_initialisation'));
			add_action('admin_print_styles-' . $this->blog_admin_opengraph_hook, array(&$this, 'admin_enqueue_css'));
			add_action('admin_print_scripts-' . $this->blog_admin_opengraph_hook, array(&$this, 'admin_enqueue_js'));
		}
		add_action("load-" . $this->blog_admin_page_hook, array(&$this, 'admin_initialisation'));
		add_action("load-" . $this->blog_admin_plugins_hook, array(&$this, 'admin_initialisation'));

		add_action('admin_print_styles-' . $this->blog_admin_page_hook, array(&$this, 'admin_enqueue_css'));
		add_action('admin_print_styles-' . $this->blog_admin_plugins_hook, array(&$this, 'admin_enqueue_css'));
		add_action('admin_print_styles-post-new.php', array(&$this, 'admin_enqueue_css'));
		add_action('admin_print_styles-post.php', array(&$this, 'admin_enqueue_css'));
		add_action('admin_print_styles-link-add.php', array(&$this, 'admin_enqueue_css'));
		add_action('admin_print_styles-link.php', array(&$this, 'admin_enqueue_css'));
		add_action('admin_print_styles-widgets.php', array(&$this, 'admin_enqueue_css'));

		add_action('admin_print_scripts-' . $this->blog_admin_page_hook, array(&$this, 'admin_enqueue_js'));
		add_action('admin_print_scripts-' . $this->blog_admin_plugins_hook, array(&$this, 'admin_enqueue_js'));
		add_action('admin_print_scripts-post-new.php', array(&$this, 'admin_enqueue_js'));
		add_action('admin_print_scripts-post.php', array(&$this, 'admin_enqueue_js'));
		add_action('admin_print_scripts-link-add.php', array(&$this, 'admin_enqueue_js'));
		add_action('admin_print_scripts-link.php', array(&$this, 'admin_enqueue_js'));
		add_action('admin_print_scripts-widgets.php', array(&$this, 'admin_enqueue_js'));

		//enqueue here the library facebook connect
		$this->add_js_options();
		//Add meta box
		$this->add_meta_boxes();
	}

	/**
	 * Admin initialisation
	 */
	public function admin_initialisation()
	{
		//add 2 column screen
		add_screen_option('layout_columns', array('max' => 2, 'default' => 2));
	}

	/**
	 * Add meta boxes for admin
	 */
	public function add_meta_boxes()
	{
		$icon = isset($this->options['app_infos']['icon_url']) ? '<img style="vertical-align:middle;" src="' . $this->options['app_infos']['icon_url'] . '" alt=""/>' : '';

		//Settings page
		if ($this->blog_admin_page_hook != '') {
			add_meta_box($this->plugin_slug . "_settings_metabox", __('Settings', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'settings.png" />', array(&$this, 'settings_content'), $this->blog_admin_page_hook, 'normal', 'core');
			add_meta_box($this->plugin_slug . "_meta_metabox", __('My Facebook', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'facebook-mini.png" alt="facebook logo"/>', array(&$this, 'fcbk_content'), $this->blog_admin_page_hook, 'side', 'core');
			add_meta_box($this->plugin_slug . "_app_infos_metabox", __('Application Infos', $this->ptd) . ' ' . $icon, array(&$this, 'app_infos_content'), $this->blog_admin_page_hook, 'side', 'core');
			add_meta_box($this->plugin_slug . "_info_metabox", __('Informations', $this->ptd), array(&$this, 'general_content'), $this->blog_admin_page_hook, 'side', 'core');
			if (current_user_can('manage_facebook_awd_settings')) {
				add_meta_box($this->plugin_slug . "_activity_metabox", __('Activity on your site', $this->ptd), array(&$this, 'activity_content'), $this->blog_admin_page_hook, 'side', 'core');
			}
		}
		//Plugins page
		if ($this->blog_admin_plugins_hook != '') {
			add_meta_box($this->plugin_slug . "_plugins_metabox", __('Plugins Settings', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'plugins.png" />', array(&$this, 'plugins_content'), $this->blog_admin_plugins_hook, 'normal', 'core');
			add_meta_box($this->plugin_slug . "_meta_metabox", __('My Facebook', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'facebook-mini.png" alt="facebook logo"/>', array(&$this, 'fcbk_content'), $this->blog_admin_plugins_hook, 'side', 'core');
			add_meta_box($this->plugin_slug . "_app_infos_metabox", __('Application Infos', $this->ptd) . ' ' . $icon, array(&$this, 'app_infos_content'), $this->blog_admin_plugins_hook, 'side', 'core');
			add_meta_box($this->plugin_slug . "_info_metabox", __('Informations', $this->ptd), array(&$this, 'general_content'), $this->blog_admin_plugins_hook, 'side', 'core');
			if (current_user_can('manage_facebook_awd_settings')) {
				add_meta_box($this->plugin_slug . "_activity_metabox", __('Activity on your site', $this->ptd), array(&$this, 'activity_content'), $this->blog_admin_plugins_hook, 'side', 'core');
			}
		}
		$post_types = get_post_types();
		foreach ($post_types as $type) {
			//Like button manager on post page type
			add_meta_box($this->plugin_slug . "_awd_mini_form_metabox", __('Facebook AWD Manager', $this->ptd) . ' <img style="vertical-align:middle;" style="vertical-align:middle;" src="' . $this->plugin_url_images . 'facebook-mini.png" alt="facebook logo"/>', array(&$this, 'post_manager_content'), $type, 'side', 'core');
		}
		//add_meta_box($this->plugin_slug."_awd_mini_form_metabox", __('Facebook AWD Manager',$this->ptd).' <img style="vertical-align:middle;" style="vertical-align:middle;" src="'.$this->plugin_url_images.'facebook-mini.png" alt="facebook logo"/>', array(&$this,'post_manager_content'),  'link' , 'side', 'core');

		if ($this->blog_admin_opengraph_hook != '') {
			if ($this->options['open_graph_enable'] == 1) {
				add_meta_box($this->plugin_slug . "_open_graph_metabox", __('Open Graph', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'ogp-logo.png" />', array(&$this, 'open_graph_content'), $this->blog_admin_opengraph_hook, 'normal', 'core');
				add_meta_box($this->plugin_slug . "_meta_metabox", __('My Facebook', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'facebook-mini.png" alt="facebook logo"/>', array(&$this, 'fcbk_content'), $this->blog_admin_opengraph_hook, 'side', 'core');
				add_meta_box($this->plugin_slug . "_app_infos_metabox", __('Application Infos', $this->ptd) . ' ' . $icon, array(&$this, 'app_infos_content'), $this->blog_admin_opengraph_hook, 'side', 'core');
				add_meta_box($this->plugin_slug . "_info_metabox", __('Informations', $this->ptd), array(&$this, 'general_content'), $this->blog_admin_opengraph_hook, 'side', 'core');
				if (current_user_can('manage_facebook_awd_settings')) {
					add_meta_box($this->plugin_slug . "_activity_metabox", __('Activity on your site', $this->ptd), array(&$this, 'activity_content'), $this->blog_admin_opengraph_hook, 'side', 'core');
				}
			}
		}

		//Call the menu init to get page hook for each menu
		do_action('AWD_facebook_admin_menu');
		//For each page hook declared in plugins add side meta box
		$plugins = $this->plugins;
		if (is_array($plugins)) {
			foreach ($plugins as $plugin) {
				if (isset($plugin->plugin_admin_hook) && $plugin->plugin_admin_hook != '') {
					$page_hook = $plugin->plugin_admin_hook;
					add_meta_box($this->plugin_slug . "_meta_metabox", __('My Facebook', $this->ptd) . ' <img style="vertical-align:middle;" src="' . $this->plugin_url_images . 'facebook-mini.png" alt="facebook logo"/>', array(&$this, 'fcbk_content'), $page_hook, 'side', 'core');
					add_meta_box($this->plugin_slug . "_app_infos_metabox", __('Application Infos', $this->ptd) . ' ' . $icon, array(&$this, 'app_infos_content'), $page_hook, 'side', 'core');
					add_meta_box($this->plugin_slug . "_info_metabox", __('Informations', $this->ptd), array(&$this, 'general_content'), $page_hook, 'side', 'core');
					if (current_user_can('manage_facebook_awd_settings')) {
						add_meta_box($this->plugin_slug . "_activity_metabox", __('Activity on your site', $this->ptd), array(&$this, 'activity_content'), $page_hook, 'side', 'core');
					}
				}
			}
		}
	}

	/**
	 * Admin css enqueue Stylesheet
	 */
	public function admin_enqueue_css()
	{
		wp_enqueue_style($this->plugin_slug . '-ui-bootstrap');
		wp_enqueue_style($this->plugin_slug . '-google-code-prettify-css');
		wp_enqueue_style('thickbox');
	}

	/**
	 * Admin js enqueue Javascript
	 */
	public function admin_enqueue_js()
	{
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('common');
		wp_enqueue_script('wp-list');
		wp_enqueue_script('postbox');
		wp_enqueue_script($this->plugin_slug . '-admin-js');
		wp_enqueue_script($this->plugin_slug . '-bootstrap-js');
		wp_enqueue_script($this->plugin_slug . '-google-code-prettify');
	}

	/**
	 * Add required javascript vars 
	 * to work with the plugin
	 */
	public function add_js_options()
	{
		$AWD_facebook_vars = array('ajaxurl' => admin_url('admin-ajax.php'), 'homeUrl' => home_url(), 'loginUrl' => $this->_login_url, 'logoutUrl' => $this->_logout_url, 'scope' => current_user_can("manage_options") ? $this->options["perms_admin"] : $this->options["perms"], 'app_id' => $this->options['app_id'], 'FBEventHandler' => array('callbacks' => array()));
		$AWD_facebook_vars = apply_filters('AWD_facebook_js_vars', $AWD_facebook_vars);
		wp_localize_script($this->plugin_slug, $this->plugin_slug, $AWD_facebook_vars);
		wp_enqueue_script($this->plugin_slug);
	}

	/**
	 * Add javascript resources to front
	 */
	public function front_enqueue_js()
	{
		wp_enqueue_style($this->plugin_slug . '-ui-bootstrap');
		$this->add_js_options();
	}

	/**
	 * Admin Infos
	 */
	public function general_content()
	{
		if (current_user_can('manage_facebook_awd_settings')) {
			echo '<h2>' . __('Plugins installed', $this->ptd) . '</h2>';
			if (is_array($this->plugins) && count($this->plugins)) {
				foreach ($this->plugins as $plugin) {
					echo '
					<p><span class="label label-success">
						' . $plugin->plugin_name . '
						<small>v' . $plugin->get_version() . '</small>
					</span></p>';
				}
			} else {
				echo '
				<p><span class="label label-inverse">' . __('No plugin found', $this->ptd) . '</span></p>';
			}
			echo '
			<p><a href="http://facebook-awd.ahwebdev.fr/plugins/" class="btn btn-important" target="blank">' . __('Find plugins', $this->ptd) . '</a></p>';
		}

		echo '<h4>' . __('Follow me on Facebook', $this->ptd) . '</h4>';
		echo do_shortcode('[AWD_likebox href="https://www.facebook.com/Ahwebdev" colorscheme="light" stream="0" show_faces="0" xfbml="0" header="0" width="257" height="60"]');
		echo '<h4>' . __('Follow me on Twitter', $this->ptd) . '</h4>
		<a href="https://twitter.com/ah_webdev" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="true">Follow @ah_webdev</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		';

	}

	/**
	 * Get app infos content model
	 */
	public function get_app_infos_content()
	{
		echo $this->get_app_info();
		//call the app_content function
		echo $this->app_infos_content();
		exit();
	}

	/**
	 * Get App infos form api and store it in options
	 * @return string $errors
	 */
	public function get_app_info()
	{
		if (is_object($this->fcbk)) {
			try {
				$app_info = $this->fcbk->api('/' . $this->options['app_id']);
				$this->options['app_infos'] = $this->optionsManager->updateOption('app_infos', $app_info, true);
			} catch (Exception $e) {
				$this->options['app_infos'] = $this->optionsManager->updateOption('app_infos', array(), true);
				$error = new WP_Error($e->getCode(), $e->getMessage());
				$this->display_messages($error->get_error_message(), 'error', false);
			}
		}
		return false;
	}

	/**
	 * Application infos content
	 */
	public function app_infos_content()
	{

		$infos = $this->options['app_infos'];
		if (empty($infos)) {
			$error = new WP_Error('AWD_facebook_not_ready', __('You must set a valid Facebook Application ID and Secret Key and your Facebook User ID in settings.', $this->ptd));
			echo $error->get_error_message();
			echo '<br /><a href="#" id="reload_app_infos" class="btn btn-danger" data-loading-text="<i class=\'icon-time icon-white\'></i> Testing... "><i class="icon-refresh icon-white"></i> ' . __('Reload', $this->ptd) . '</a>';
		} else {
			echo '
			<div id="awd_app">
				<table class="table table-condensed">
					<thead>
						<th>' . __('Info', $this->ptd) . '</th>
						<th>' . __('Value', $this->ptd) . '</th>
					</thead>
					<tbody>
						<tr>
							<th>' . __('Name', $this->ptd) . ':</th>
							<td>' . $infos['name'] . '</td>
						</tr>
						<tr>
							<th>ID:</th>
							<td>' . $infos['id'] . '</td>
						</tr>
						<tr>
							<th>' . __('Link', $this->ptd) . ':</th>
							<td><a href="' . $infos['link'] . '" target="_blank">View App</a></td>
						</tr>
						<tr>
							<th>' . __('Namespace', $this->ptd) . ':</th>
							<td>' . $infos['namespace'] . '</td>
						</tr>
						<tr>
							<th>' . __('Daily active users', $this->ptd) . ':</th>
							<td class="app_active_users">' . (isset($infos['daily_active_users']) ? $infos['daily_active_users'] : 0) . '</td>
						</tr>
						<tr>
							<th>' . __('Weekly active users', $this->ptd) . ':</th>
							<td class="app_active_users">' . (isset($infos['weekly_active_users']) ? $infos['weekly_active_users'] : 0) . '</td>
						</tr>
						<tr>
							<th>' . __('Monthly active users', $this->ptd) . ':</th>
							<td class="app_active_users">' . (isset($infos['monthly_active_users']) ? $infos['monthly_active_users'] : 0) . '</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th><img src="' . $infos['logo_url'] . '" class="thumbnail"/></th>
							';
			if (current_user_can('manage_facebook_awd_settings')) {
				echo '
								<td>
									<a href="#" id="reload_app_infos" class="btn btnNormal" data-loading-text="<i class=\'icon-time\'></i> Loading...">
										<i class="icon-wrench"></i> ' . __('Test', $this->ptd) . '
									</a>
								</td>';
			} else {
				echo '<td></td>';
			}
			echo '</tr>
					</tfoot>
				</table>
			</div>';
		}
	}

	/**
	 * Admin content
	 */
	public function admin_content()
	{
		include_once(dirname(__FILE__) . '/inc/admin/views/admin.php');
	}

	/**
	 * Get a html field for media by ajax post
	 * @echo string $html
	 */
	public function ajax_get_media_field()
	{
		$label = $_POST['label'];
		$label2 = $_POST['label2'];
		$type = $_POST['type'];
		$name = $_POST['name'];
		$form = new AWD_facebook_form('form_media_field', 'POST', '', $this->plugin_option_pref);
		echo $form->addMediaButton($label, $name, '', 'span8', array('class' => 'span6'), array('data-title' => $label2, 'data-type' => $type), true);
		exit();
	}

	/**
	 * Activity contents
	 */
	public function activity_content()
	{
		$url = parse_url(home_url());
		echo do_shortcode('[AWD_activitybox domain=' . $url['host'] . '" width="258" height="200" header="false" font="lucida grande" border_color="#F9F9F9" recommendations="1" ref="Facebook AWD Plugin"]');
	}

	/**
	 * plugin Options
	 */
	public function plugins_content()
	{
		include_once(dirname(__FILE__) . '/inc/admin/views/admin_plugins.php');
		include_once(dirname(__FILE__) . '/inc/admin/views/help/settings.php');
		include_once(dirname(__FILE__) . '/inc/admin/views/help/plugins.php');
	}

	/**
	 * Settings Options
	 */
	public function settings_content()
	{
		include_once(dirname(__FILE__) . '/inc/admin/views/admin_settings.php');
		include_once(dirname(__FILE__) . '/inc/admin/views/help/settings.php');
		include_once(dirname(__FILE__) . '/inc/admin/views/help/plugins.php');
	}

	/**
	 * Admin fcbk info content
	 */
	public function fcbk_content()
	{
		$options = array('width' => 200, 'logout_label' => '<i class="icon-off icon-white"></i> ' . __("Logout", $this->ptd));
		if ($this->is_user_logged_in_facebook()) {
			echo $this->get_the_login_button($options);
			$this->display_messages(sprintf(__("%s Facebook ID: %s", $this->ptd), '<i class="icon-user"></i> ', $this->uid));
		} else if ($this->options['connect_enable']) {
			echo '<a href="#" class="AWD_facebook_connect_button btn btn-info" data-redirect="' . $this->get_current_url() . '"><i class="icon-user icon-white"></i> ' . __("Login with Facebook", $this->ptd) . '</a>';
		} else {
			$this->display_messages(sprintf(__('You should enable FB connect in %sApp settings%s', $this->ptd), '<a href="admin.php?page=' . $this->plugin_slug . '">', '</a>'), 'warning');
		}
	}

	//****************************************************************************************
	//	OPENGRAPH
	//****************************************************************************************

	public function ogp_language_attributes($language_attributes)
	{
		$namespace_url = '';
		if (isset($this->options['app_infos']['namespace'])) {
			$namespace_url = $this->options['app_infos']['namespace'] . ': http://ogp.me/ns/fb/' . $this->options['app_infos']['namespace'];
		}
		$ogp = new OpenGraphProtocol();
		$language_attributes .= ' prefix="' . OpenGraphProtocol::PREFIX . ': ' . OpenGraphProtocol::NS . ' fb:http://ogp.me/ns/fb# ' . $namespace_url . '"';
		return $language_attributes;
	}

	/**
	 * Admin page for opengraph settings
	 * @echo string $html
	 */
	public function open_graph_content()
	{
		include_once(dirname(__FILE__) . '/inc/admin/views/admin_open_graph.php');
	}

	/**
	 * Admin form crete/copy/edit (ajax post)
	 * @echo string $html
	 */
	public function get_open_graph_object_form($object_id = '', $copy = false)
	{
		include_once(dirname(__FILE__) . '/inc/admin/views/admin_open_graph_form.php');
	}

	/**
	 * Admin form crete/copy/edit (by ajax post)
	 * @echo form object's templte
	 */
	public function ajax_get_open_graph_object_form()
	{

		$object_id = $_POST['object_id'];
		$copy = isset($_POST['copy']) ? $_POST['copy'] : false;
		echo $this->get_open_graph_object_form($object_id, $copy);
		exit();
	}

	/**
	 * Admin return an item for object's template list in admin.
	 * @param array $object
	 * @return string
	 */
	public function get_open_graph_object_list_item($object)
	{
		return '
		<tr class="awd_object_item_' . $object['id'] . '">
		<td><strong>' . $object['object_title'] . '</strong></td>
		<td>
			<div class="btn-group pull-right" data-object-id="' . $object['id'] . '">
				<button class="btn btn-mini awd_edit_opengraph_object"><i class="icon-edit"></i> ' . __('Edit', $this->ptd) . '</button>
				<button class="btn btn-mini awd_edit_opengraph_object copy"><i class="icon-share"></i> ' . __('Copy', $this->ptd) . '</button>
				<button class="btn btn-mini awd_delete_opengraph_object btn-warning"><i class="icon-remove icon-white"></i> ' . __('Delete', $this->ptd) . '</button>
			</div>
		</td>
		</tr>';
	}

	/**
	 * Admin save/update object template
	 * @return json array
	 */
	public function save_ogp_object()
	{
		if (isset($_POST[$this->plugin_option_pref . '_nonce_options_save_ogp_object']) && wp_verify_nonce($_POST[$this->plugin_option_pref . '_nonce_options_save_ogp_object'], $this->plugin_slug . '_save_ogp_object')) {
			$opengraph_object = array();
			foreach ($_POST[$this->plugin_option_pref . 'awd_ogp'] as $option => $value) {
				$option_name = str_ireplace($this->plugin_option_pref, "", $option);
				//clean empty value.
				if (is_array($value)) {
					$value = array_filter($value);
				}
				$opengraph_object[$option_name] = $value;
			}

			//verification submitted value
			if ($opengraph_object['object_title'] == '')
				$opengraph_object['object_title'] = __('Default Opengraph Object', $this->ptd);

			//Check if the id  of the object was supplied
			if ($opengraph_object['id'] == '')
				$opengraph_object['id'] = rand(0, 9999) . '_' . time();

			if (isset($this->options['opengraph_objects'][$opengraph_object['id']])) {
				$this->options['opengraph_objects'][$opengraph_object['id']] = $opengraph_object;
				//if no object existing, create a new object reference and save it.
			} else {
				$this->options['opengraph_objects'][$opengraph_object['id']] = $opengraph_object;
			}
			//save with option manager
			$this->options['opengraph_objects'] = $this->optionsManager->updateOption('opengraph_objects', $this->options['opengraph_objects'], true);
			echo json_encode(array('success' => 1, 'item' => $this->get_open_graph_object_list_item($opengraph_object), 'item_id' => $opengraph_object['id'], 'links_form' => $this->get_open_graph_object_links_form()));
			exit();
		}
		return false;
	}

	/**
	 * This function delete an opengraph object template (ajax post)
	 * @param integer $_POST['object_id']
	 * @echo string (json array)
	 */
	public function delete_ogp_object()
	{
		$object_id = $_POST['object_id'];
		unset($this->options['opengraph_objects'][$object_id]);

		$this->options['opengraph_objects'] = $this->optionsManager->updateOption('opengraph_objects', $this->options['opengraph_objects'], true);
		echo json_encode(array('success' => 1, 'count' => count($this->options['opengraph_objects']), 'links_form' => $this->get_open_graph_object_links_form()));
		exit();
	}

	/**
	 * Admin Save object relation (ajax post)
	 * @echo json array
	 */
	public function save_ogp_object_links()
	{
		if (isset($_POST[$this->plugin_option_pref . '_nonce_options_object_links']) && wp_verify_nonce($_POST[$this->plugin_option_pref . '_nonce_options_object_links'], $this->plugin_slug . '_update_object_links')) {
			if ($_POST) {
				$opengraph_object_links = array();
				foreach ($_POST[$this->plugin_option_pref . 'opengraph_object_link'] as $context => $object_id) {
					$opengraph_object_links[$context] = $object_id;
				}
				//save with option manager
				$this->options['opengraph_object_links'] = $this->optionsManager->updateOption('opengraph_object_links', $opengraph_object_links, true);
				echo json_encode(array('success' => 1));
				exit();
			}
		}
	}

	/**
	 * This function transform an array into an OpenGraphProtocol object
	 * @param array $object
	 * @return OpenGraphProtocol
	 */
	public function opengraph_array_to_object($object)
	{
		$ogp = new OpenGraphProtocol();
		if (isset($object['locale']))
			$ogp->setLocale($object['locale']);
		else
			$ogp->setLocale($this->options['locale']);
		if (isset($object['site_name']))
			$ogp->setSiteName($object['site_name']);
		if (isset($object['title']))
			$ogp->setTitle($object['title']);
		if (isset($object['description']))
			$ogp->setDescription($object['description']);
		if (isset($object['type'])) {
			if ($object['type'] == 'custom' && isset($object['custom_type']))
				$ogp->setType($object['custom_type']);
			else
				$ogp->setType($object['type']);
		}
		if (isset($object['url']))
			$ogp->setURL($object['url']);
		if (isset($object['determiner']))
			$ogp->setDeterminer($object['determiner']);
		if (isset($object['images'])) {
			if (is_array($object['images']) && count($object['images'])) {
				foreach ($object['images'] as $image_url) {
					if ($image_url != '') {
						$ogp_img = $this->create_OpenGraphProtocolImage($image_url);
						$ogp->addImage($ogp_img);
					}
				}
			}
		}
		if (isset($object['videos'])) {
			if (is_array($object['videos']) && count($object['videos'])) {
				foreach ($object['videos'] as $video_url) {
					if ($video_url != '') {
						$ogp_video = $this->create_OpenGraphProtocolVideo($video_url);
						$ogp->addVideo($ogp_video);
					}
				}
			}
		}
		if (isset($object['audios'])) {
			if (is_array($object['audios']) && count($object['audios'])) {
				foreach ($object['audios'] as $audio_url) {
					if ($audio_url != '') {
						$ogp_audio = $this->create_OpenGraphProtocolAudio($audio_url);
						$ogp->addAudio($ogp_audio);
					}
				}
			}
		}
		return $ogp;
	}

	/**
	 * @param unknown $image_url
	 * @return OpenGraphProtocolImage
	 */
	public function create_OpenGraphProtocolImage($image_url)
	{
		$ogp_img = new OpenGraphProtocolImage();
		$ogp_img->setURL($image_url);

		if ($this->is_valid_url($image_url)) {
			//add infos to image
			$image_size = @getimagesize($image_url);
			if (is_array($image_size)) {
				if (isset($image_size['mime']))
					$ogp_img->setType($image_size['mime']);
				if (isset($image_size[0]))
					$ogp_img->setWidth($image_size[0]);
				if (isset($image_size[1]))
					$ogp_img->setHeight($image_size[1]);
			}
		}
		//check if we want file under ssl
		$url_info = parse_url($image_url);
		if ($url_info) {
			if ($url_info['scheme'] == 'https') {
				$secure_url = $image_url;
				$image_url = str_replace('https://', 'http://', $image_url);
				$ogp_img->setSecureURL($secure_url);
				$ogp_img->setURL($image_url);
			}
		}
		return $ogp_img;
	}

	/**
	 * @param unknown $video_url
	 * @return OpenGraphProtocolVideo
	 */
	public function create_OpenGraphProtocolVideo($video_url)
	{
		$ogp_video = new OpenGraphProtocolVideo();
		$ogp_video->setURL($video_url);
		//add video infos
		$upload_dir = wp_upload_dir();
		$video_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $video_url);
		$getID3 = new getID3();
		$video_infos = $getID3->analyze($video_path);
		if (!isset($video_infos['error'])) {
			if (isset($video_infos['mime_type']))
				$ogp_video->setType($video_infos['mime_type']);

			if (isset($video_infos['video']['resolution_x']))
				$ogp_video->setWidth(intval($video_infos['video']['resolution_x']));

			if (isset($video_infos['video']['resolution_y'])) {
				$ogp_video->setHeight(intval($video_infos['video']['resolution_y']));
			}
		} else {
			//return new WP_Error('AWD_facebook_opengraphvideo_parser', __('Facebook AWD cannot parse this video file', $this->ptd));
		}

		//check if we want file under ssl
		$url_info = parse_url($video_url);
		if ($url_info) {
			if ($url_info['scheme'] == 'https') {
				$secure_url = $video_url;
				$audio_url = str_replace('https://', 'http://', $video_url);
				$ogp_video->setSecureURL($secure_url);
				$ogp_video->setURL($video_url);
			}
		}

		return $ogp_video;
	}

	/**
	 * @param unknown $audio_url
	 * @return OpenGraphProtocolAudio
	 */
	public function create_OpenGraphProtocolAudio($audio_url)
	{
		$ogp_audio = new OpenGraphProtocolAudio();
		$ogp_audio->setURL($audio_url);
		//add video infos
		$upload_dir = wp_upload_dir();
		$audio_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $audio_url);
		$getID3 = new getID3();
		$audio_infos = $getID3->analyze($audio_path);
		if (!isset($audio_infos['error'])) {
			if (isset($audio_infos['mime_type']))
				$ogp_audio->setType($audio_infos['mime_type']);
		} else {
			//return new WP_Error('AWD_facebook_opengraphvideo_parser', __('Facebook AWD cannot parse this video file', $this->ptd));
		}
		//check if we want file under ssl
		$url_info = parse_url($audio_url);
		if ($url_info) {
			if ($url_info['scheme'] == 'https') {
				$secure_url = $audio_url;
				$audio_url = str_replace('https://', 'http://', $audio_url);
				$ogp_audio->setSecureURL($secure_url);
				$ogp_audio->setURL($audio_url);
			}
		}
		return $ogp_audio;
	}

	/**
	 * Test if url return 200
	 * @param string $url
	 * @return boolean
	 */
	public function is_valid_url($url)
	{
		//test image if image exist
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($retcode == '200' OR $retcode == '302')
			return true;

		return false;
	}

	/**
	 * Admin Form to link object to post type.
	 * @return string $html
	 */
	public function get_open_graph_object_links_form()
	{
		$html = '';
		$form = new AWD_facebook_form('form_create_opengraph_object_links', 'POST', '', $this->plugin_option_pref);
		$ogp_objects = apply_filters('AWD_facebook_ogp_objects', $this->options['opengraph_objects']);
		$page_contexts = $this->options['opengraph_contexts'];
		$taxonomies = get_taxonomies(array('public' => true, 'show_ui' => true), 'objects');
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomie_name => $tax_values) {
				$page_contexts[$tax_values->name] = $tax_values->label;
			}
		}
		$postypes_media = get_post_types(array('name' => 'attachment'), 'objects');
		$postypes = get_post_types(array('show_ui' => true), 'objects');
		if (is_object($postypes_media['attachment']))
			$postypes['attachment'] = $postypes_media['attachment'];
		unset($postypes['post']);
		unset($postypes['page']);
		if (!empty($postypes)) {
			foreach ($postypes as $postype_name => $posttype_values) {
				$page_contexts[$posttype_values->name] = $posttype_values->label;
			}
		}

		$html .= $form->start();
		if (is_array($ogp_objects) && count($ogp_objects)) {
			foreach ($page_contexts as $key => $context) {
				$options = array();
				$options[] = array('value' => '', 'label' => __('Disabled', $this->ptd));
				$linked_object = isset($this->options['opengraph_object_links'][$key]) ? $this->options['opengraph_object_links'][$key] : '';
				foreach ($ogp_objects as $value => $ogp_object) {
					$options[] = array('value' => $value, 'label' => $ogp_object['object_title']);
				}
				$html .= $form->addSelect(__('Choose Opengraph object for', $this->ptd) . ' ' . $context, 'opengraph_object_link[' . $key . ']', $options, $linked_object, 'span4', array('class' => 'span4'));
			}
		} else {
			$html .= $this->display_messages(__('No Object found', $this->ptd), 'warning', false);
		}
		$html .= wp_nonce_field($this->plugin_slug . '_update_object_links', $this->plugin_option_pref . '_nonce_options_object_links', null, false);
		$html .= $form->end();
		return $html;
	}

	/**
	 * Helper to get html opengraph tags by post id.
	 * @param integer $post_id
	 * @return string $html
	 */
	public function get_ogp_tags($post_id)
	{
		$post = get_post($post_id);
		$the_query = new WP_Query($args);
		$html = '';
		while ($the_query->have_posts()) :
			$the_query->the_post();
			$html = $this->define_ogp_objects();
		endwhile;
		wp_reset_postdata();
		return $html;
	}

	/**
	 * Helper to display html opengraph tags depending on the current WP_query.
	 * This function is called by hook wp_head.
	 * @echo string
	 */
	public function display_ogp_objects()
	{
		echo $this->define_ogp_objects();
	}

	/**
	 * Helper to get a descrtion from the post.
	 * @param WP_Post
	 * @return string
	 */
	public function get_post_description($post)
	{
		if (!empty($post->post_excerpt)) {
			$description = esc_attr(str_replace("\r\n", ' ', substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, 160)));
		} else {
			$description = esc_attr(str_replace("\r\n", ' ', substr(strip_tags(strip_shortcodes($post->post_content)), 0, 160)));
		}
		return $description;
	}

	/**
	 * Helper to get a post thumbnail
	 * @param WP_Post
	 * @return string
	 */
	public function get_post_thumbnail($post)
	{
		$img = '';
		if (current_theme_supports('post-thumbnails')) {
			if (has_post_thumbnail($post->ID)) {
				$img = $this->catch_that_image(get_the_post_thumbnail($post->ID, 'AWD_facebook_ogimage'));
			}
		}
		if (empty($img))
			$img = $this->catch_that_image($post->post_content);

		if (empty($img)) {
			if (isset($this->options['app_infos']['logo_url']))
				$img = $this->options['app_infos']['logo_url'];
		}
		return $img;
	}

	/**
	 * Define the relating tags on the current page/post/...
	 * @return string $html
	 */
	public function define_ogp_objects()
	{
		global $wp_query, $post;
		$html = '';
		$current_post_type = get_post_type();
		$blog_name = get_bloginfo('name');
		$blog_description = str_replace(array("\n", "\r"), "", get_bloginfo('description'));
		$home_url = home_url();
		$linked_object = null;
		$array_replace = array();
		switch (1) {
			case is_front_page():
			case is_home():
				$array_replace = array($blog_name, $blog_description, $home_url);
				//if home page is a single page add some value in pattern
				if (is_page($post->ID)) {
					$array_replace = array($blog_name, $blog_description, $home_url, $post->post_title, $this->get_post_description($post), $this->get_post_thumbnail($post), get_permalink($post->ID));
				}
				$linked_object = isset($this->options['opengraph_object_links']['frontpage']) ? $this->options['opengraph_object_links']['frontpage'] : null;
				break;

			case is_author():
				$linked_object = isset($this->options['opengraph_object_links']['author']) ? $this->options['opengraph_object_links']['author'] : null;
				$current_author = get_user_by('slug', $wp_query->query_vars['author_name']);
				$avatar = get_avatar($current_author->ID, '200');
				if ($avatar)
					$gravatar_attributes = simplexml_load_string($avatar);
				if (!empty($gravatar_attributes['src']))
					$gravatar_url = $gravatar_attributes['src'];
				$array_replace = array($blog_name, $blog_description, $home_url, trim(wp_title('', false)), $current_author->description, $gravatar_url, $this->get_current_url());
				break;
			case is_archive():
				switch (1) {
					case is_tag():
						$linked_object = isset($this->options['opengraph_object_links']['post_tag']) ? $this->options['opengraph_object_links']['post_tag'] : null;
						$array_replace = array($blog_name, $blog_description, $home_url, trim(wp_title('', false)), '', '', $this->get_current_url());
						break;
					case is_tax():
						$taxonomy_slug = $wp_query->query_vars['taxonomy'];
						$linked_object = isset($this->options['opengraph_object_links'][$taxonomy_slug]) ? $this->options['opengraph_object_links'][$taxonomy_slug] : null;
						$array_replace = array($blog_name, $blog_description, $home_url, trim(wp_title('', false)), term_description(), '', $this->get_current_url());
						break;
					case is_category():
						$linked_object = isset($this->options['opengraph_object_links']['category']) ? $this->options['opengraph_object_links']['category'] : null;
						$array_replace = array($blog_name, $blog_description, $home_url, trim(wp_title('', false)), category_description(), '', $this->get_current_url());
						break;
					default:
						$linked_object = isset($this->options['opengraph_object_links']['archive']) ? $this->options['opengraph_object_links']['archive'] : null;
						$array_replace = array($blog_name, $blog_description, $home_url, trim(wp_title('', false)), '', '', $this->get_current_url());
						break;
				}
				break;
			case is_attachment():
				$linked_object = isset($this->options['opengraph_object_links']['attachment']) ? $this->options['opengraph_object_links']['attachment'] : null;
				$array_replace = array($blog_name, $blog_description, $home_url, trim(wp_title('', false)), '', '', $this->get_current_url());
				break;
			case is_page():
			case is_single():
				$linked_object = isset($this->options['opengraph_object_links'][(is_single() ? 'post' : 'page')]) ? $this->options['opengraph_object_links'][(is_single() ? 'post' : 'page')] : null;
				$array_replace = array($blog_name, $blog_description, $home_url, $post->post_title, $this->get_post_description($post), $this->get_post_thumbnail($post), get_permalink($post->ID));
				break;
		}

		
		
		//redefine object template from post if value is set
		$from_post = 0;
		$object_template = null;
		
		if (is_object($post)) {
			$custom = get_post_meta($post->ID, $this->plugin_slug, true);
			if (!is_string($custom) AND isset($custom['opengraph']['object_link'])) {
				if($custom['opengraph']['object_link'] == 'custom'){
					$from_post = 1;
					$object_template = $custom['awd_ogp'];
				}else if ($custom['opengraph']['object_link'] != '') {
					$from_post = 1;
					$linked_object = $custom['opengraph']['object_link'];
				}
			}
		}
		//define object template depending on object values
		if($object_template === null)
			$object_template = isset($this->options['opengraph_objects'][$linked_object]) ? $this->options['opengraph_objects'][$linked_object] : null;
				
		//Process all pattern.
		$object_template = $this->process_opengraph_pattern($array_replace, $object_template);
		
		if ($object_template != null) {
			if (is_object($post)) {
				//auto load images attachment
				if (!empty($object_template['auto_load_images_attachment'])) {
					$images = array();
					if ($object_template['auto_load_images_attachment'] == 1) {
						$attachments_images = get_posts(array('post_type' => 'attachment', 'posts_per_page' => -1, 'post_parent' => $post->ID, 'post_mime_type' => 'image/*'));
						if ($attachments_images) {
							foreach ($attachments_images as $attachments_image) {
								$images[] = wp_get_attachment_url($attachments_image->ID);
							}
						}
					}
					$object_template['images'] = array_merge($images, $object_template['images']);
				}

				//auto load videos attachment
				if (!empty($object_template['auto_load_videos_attachment'])) {
					$videos = array();
					if ($object_template['auto_load_videos_attachment'] == 1) {
						$attachments_videos = get_posts(array('post_type' => 'attachment', 'posts_per_page' => -1, 'post_parent' => $post->ID, 'post_mime_type' => array('video/*')));
						if ($attachments_videos) {
							foreach ($attachments_videos as $attachments_video) {
								$videos[] = wp_get_attachment_url($attachments_video->ID);
							}
						}
						$object_template['videos'] = array_merge($videos, $object_template['videos']);
					}
				}

				//auto load audios attachment
				if (!empty($object_template['auto_load_audios_attachment'])) {
					$audios = array();
					if ($object_template['auto_load_audios_attachment'] == 1) {
						$attachments_audios = get_posts(array('post_type' => 'attachment', 'posts_per_page' => -1, 'post_parent' => $post->ID, 'post_mime_type' => array('audio/*')));
						if ($attachments_audios) {
							foreach ($attachments_audios as $attachments_audio) {
								$audios[] = wp_get_attachment_url($attachments_audio->ID);
							}
						}
						$object_template['audios'] = array_merge($audios, $object_template['audios']);
					}
				}
			}
			return $this->render_ogp_tags($object_template, $from_post);
		}
		return false;
	}

	/**
	 * Replace all the pattern by related content
	 * 
	 * @param unknown $array_replace
	 * @param unknown $object_template
	 * @return mixed
	 */
	public function process_opengraph_pattern($array_replace, $object_template)
	{
		$array_pattern = array("%BLOG_TITLE%", "%BLOG_DESCRIPTION%", "%BLOG_URL%", "%TITLE%", "%DESCRIPTION%", "%IMAGE%", "%URL%");
		if (is_array($object_template)) {
			foreach ($object_template as $field => $value) {
				$value = str_replace($array_pattern, $array_replace, $value);
				$object_template[$field] = $value;
			}
		}
		return $object_template;
	}

	/**
	 * Render opengraph tags, replace pattern by value depending on linked_object
	 * @param array $array_replace
	 * @param array $linked_object
	 * @return string $html
	 */
	public function render_ogp_tags($object_template, $from_post = 0)
	{
		//construct related ogp object
		$ogp = $this->opengraph_array_to_object($object_template);
		$html = '<!-- ' . $this->plugin_name . ' Opengraph [v' . $this->get_version() . '] (object reference: "' . $object_template['object_title'] . '" ' . ($from_post == 1 ? 'Defined from post' : '') . ') -->' . "\n";
		if ($this->options['app_id'] != '')
			$html .= '<meta property="fb:app_id" content="' . $this->options['app_id'] . '" />' . "\n";
		if ($this->options['admins'] != '')
			$html .= '<meta property="fb:admins" content="' . $this->options['admins'] . '" />' . "\n";
		$html .= $ogp->toHTML();
		$html .= "\n" . '<!-- ' . $this->plugin_name . ' END Opengraph -->' . "\n";
		return $html;
	}

	//****************************************************************************************
	//	FRONT AND CONTENT
	//****************************************************************************************

	/**
	 * The Filter on the content to add like button
	 * @param string $content
	 * @return string $content
	 */
	public function the_content($content)
	{
		global $post;
		$exclude_post_type = explode(",", $this->options['like_button']['exclude_post_type']);
		$exclude_post_page_id = explode(",", $this->options['like_button']['exclude_post_id']);
		$exclude_terms_slug = explode(",", $this->options['like_button']['exclude_terms_slug']);

		//get all terms for the post
		$args = array();
		$taxonomies = get_taxonomies($args, 'objects');
		$terms = array();
		if ($taxonomies) {
			foreach ($taxonomies as $taxonomy) {
				$temp_terms = get_the_terms($post->ID, $taxonomy->name);
				if ($temp_terms)
					foreach ($temp_terms as $temp_term)
						if ($temp_term) {
							$terms[] = $temp_term->slug;
							$terms[] = $temp_term->term_id;
						}
			}
		}
		//say if we need to exclude this post for terms
		$is_term_to_exclude = false;
		if ($terms)
			foreach ($terms as $term) {
				if (in_array($term, $exclude_terms_slug))
					$is_term_to_exclude = true;
			}

		$custom = get_post_meta($post->ID, $this->plugin_slug, true);
		if (!is_array($custom)) {
			$custom = array();
		}
		$options = array_merge($this->options['content_manager'], $custom);

		//enable by default like button
		if (isset($options['like_button']['redefine']) && $options['like_button']['redefine'] == 1) {
			$like_button = $this->get_the_like_button($post);
			if ($options['like_button']['enabled'] == 1) {
				if ($options['like_button']['place'] == 'bottom')
					return $content . $like_button;
				elseif ($options['like_button']['place'] == 'both')
					return $like_button . $content . $like_button;
				elseif ($options['like_button']['place'] == 'top')
					return $like_button . $content;
			} else {
				return $content;
			}
		} elseif (
		//if
		//no in posts to exclude
		!in_array($post->post_type, $exclude_post_type)
		//no in pages to exclude
 && !in_array($post->ID, $exclude_post_page_id)
		//no in terms to exclude
 && !$is_term_to_exclude) {
			$like_button = $this->get_the_like_button($post);
			if ($post->post_type == 'page' && $this->options['like_button']['on_pages']) {
				if ($this->options['like_button']['place_on_pages'] == 'bottom')
					return $content . $like_button;
				elseif ($this->options['like_button']['place_on_pages'] == 'both')
					return $like_button . $content . $like_button;
				elseif ($this->options['like_button']['place_on_pages'] == 'top')
					return $like_button . $content;
			} elseif ($post->post_type == 'post' && $this->options['like_button']['on_posts']) {
				if ($this->options['like_button']['place_on_posts'] == 'bottom')
					return $content . $like_button;
				elseif ($this->options['like_button']['place_on_posts'] == 'both')
					return $like_button . $content . $like_button;
				elseif ($this->options['like_button']['place_on_posts'] == 'top')
					return $like_button . $content;
			} elseif (in_array($post->post_type, get_post_types(array('public' => true, '_builtin' => false))) && $this->options['like_button']['on_custom_post_types']) {
				//for other custom post type
				if ($this->options['like_button']['place_on_custom_post_types'] == 'bottom')
					return $content . $like_button;
				elseif ($this->options['like_button']['place_on_custom_post_types'] == 'both')
					return $like_button . $content . $like_button;
				elseif ($this->options['like_button']['place_on_custom_post_types'] == 'top')
					return $like_button . $content;
			}
		}
		return $content;

	}

	//****************************************************************************************
	//	PUBLISH TO FACEBOOK
	//****************************************************************************************

	public function publish_post_hook($post)
	{
		//check if the post is published
		if ($post->post_status == 'publish') {
			//Publish to Graph api
			$options = get_post_meta($post->ID, "awd_fcbk", true);
			$options_publish = $options["fbpublish"];
			$message = $options_publish['message_text'];
			$read_more_text = $options_publish['read_more_text'];
			//Check if we want to publish on facebook pages and profile as anonimous, access token  stored inside options.
			if ($options_publish['to_pages'] == 1) {
				$fb_publish_to_pages = $this->get_pages_to_publish($post->post_author);
				if (count($fb_publish_to_pages) > 0) {
					$this->publish_post_to_facebook($message, $read_more_text, $fb_publish_to_pages, $post->ID);
				}
			}
			//Check if we want to publish on profile
			if ($this->is_user_logged_in_facebook()) {
				if ($options_publish['to_profile'] == 1 && $this->current_facebook_user_can('publish_stream')) {
					$this->publish_post_to_facebook($message, $read_more_text, $this->uid, $post->ID);
				}
			}
		}
	}

	/**
	 * All pages the user authorize to publish on.
	 * @return array All Facebook pages linked by user
	 */
	public function get_pages_to_publish($user_id = null)
	{
		//First try to get the pages of the current user.
		if ($user_id == null) {
			$me = $this->me;
		} else {
			$me = get_user_meta($user_id, "fb_user_infos", true);
		}
		$pages = array();
		if (isset($me['pages'])) {
			$pages = $me['pages'];
		}
		$publish_to_pages = array();
		foreach ($pages as $fb_page) {
			//if pages are in the array of option to publish on,
			if (isset($this->options['fb_publish_to_pages'][$fb_page['id']])) {
				if ($this->options['fb_publish_to_pages'][$fb_page['id']] == 1) {
					$new_page = array();
					$new_page['id'] = $fb_page['id'];
					$new_page['access_token'] = $fb_page['access_token'];
					$publish_to_pages[] = $new_page;
				}
			}
		}
		return $publish_to_pages;
	}

	/**
	 * Publish the WP_Post to facebook
	 * @param string $message
	 * @param string $read_more_text
	 * @param array $to_pages
	 * @param int $post_id
	 * @return string The result of the query
	 */
	public function publish_post_to_facebook($message = null, $read_more_text = null, $to_pages, $post_id)
	{
		$fb_queries = array();
		$permalink = get_permalink($post_id);
		if (is_array($to_pages) && count($to_pages) > 0) {
			foreach ($to_pages as $fbpage) {
				$feed_dir = '/' . $fbpage['id'] . '/feed/';
				$params = array('access_token' => $fbpage['access_token'], 'message' => stripcslashes($message), 'link' => $permalink, 'actions' => array(array('name' => stripcslashes($read_more_text), 'link' => $permalink)));
				try {
					//try to post batch request to publish on all pages asked + profile at one time
					$post_id = $this->fcbk->api($feed_dir, 'POST', $params);
					return $post_id;
				} catch (FacebookApiException $e) {
					$error = new WP_Error($e->getCode(), $e->getMessage());
					return $error;
				}
			}
		} else if (is_int(absint($to_pages))) {
			$feed_dir = '/' . $to_pages . '/feed/';
			$params = array('message' => $message, 'link' => $permalink, 'actions' => array(array('name' => $read_more_text, 'link' => $permalink)));
			try {
				//try to post batch request to publish on all pages asked + profile at one time
				$post_id = $this->fcbk->api($feed_dir, 'POST', $params);
				return $post_id;
			} catch (FacebookApiException $e) {
				$error = new WP_Error($e->getCode(), $e->getMessage());
				return $error;
			}
		}
		return $result;
	}

	/**
	 * Update options when settings are updated.
	 * @return boolean
	 */
	public function update_options_from_post()
	{
		if ($_POST) {
			$new_options = array();
			foreach ($_POST as $option => $value) {
				$option_name = str_ireplace($this->plugin_option_pref, "", $option);
				$new_options[$option_name] = $value;
			}
			$this->optionsManager->setOptions($new_options);
			$this->optionsManager->save();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Event
	 * Called when the options are updated in plugins.
	 */
	public function hook_post_from_plugin_options()
	{
		if (isset($_POST[$this->plugin_option_pref . '_nonce_options_update_field']) && wp_verify_nonce($_POST[$this->plugin_option_pref . '_nonce_options_update_field'], $this->plugin_slug . '_update_options')) {
			//do custom action for sub plugins or other exec.
			do_action('AWD_facebook_save_custom_settings');

			//unset submit to not be stored
			unset($_POST[$this->plugin_option_pref . '_nonce_options_update_field']);
			unset($_POST['_wp_http_referer']);
			if ($this->update_options_from_post()) {
				//$this->get_facebook_user_data();
				//TOTO update user infos here ?
				//$this->save_facebook_user_data($this->get_current_user()->ID);
				$this->get_app_info();
				$this->messages['success'] = __('Options updated', $this->ptd);
			} else {
				$this->errors[] = new WP_Error('AWD_facebook_save_option', __('Options not updated there is an error...', $this->ptd));
			}

		} else if (isset($_POST[$this->plugin_option_pref . '_nonce_reset_options']) && wp_verify_nonce($_POST[$this->plugin_option_pref . '_nonce_reset_options'], $this->plugin_slug . '_reset_options')) {
			$this->optionsManager->reset();
			$this->messages['success'] = __('Options were reseted', $this->ptd);
		}
	}

	//****************************************************************************************
	//	USER PROFILE
	//****************************************************************************************
	/**
	 * Set Admin Roles
	 * Add FB capabalities to default WP roles
	 */
	public function set_admin_roles()
	{
		$roles = array('administrator' => array('manage_facebook_awd_settings', 'manage_facebook_awd_plugins', 'manage_facebook_awd_opengraph', 'manage_facebook_awd_publish_to_pages'), 'editor' => array('manage_facebook_awd_publish_to_pages', 'manage_facebook_awd_opengraph'), 'author' => array('manage_facebook_awd_publish_to_pages'));
		$roles = apply_filters('AWD_facebook_admin_roles', $roles);
		foreach ($roles as $role => $caps) {
			$wp_role = get_role($role);
			foreach ($caps as $cap) {
				$wp_role->add_cap($cap);
			}
		}
	}

	/**
	 * Get current URL
	 * @return string current url
	 */
	public function get_current_url()
	{
		return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	/**
	 * The action to add special field in user profile
	 * @param object $WP_User
	 * @return string $content
	 */
	public function user_profile_edit($user)
	{

		if (current_user_can('read')) {
			echo '
			<h3>' . __('Facebook infos', $this->ptd) . '</h3>
			<table class="form-table">
				<tr>
					<th><label for="fb_email">' . __('Facebook Email', $this->ptd) . '</label></th>
					<td>
						<input type="text" name="fb_email" id="fb_email" value="' . esc_attr(get_user_meta($user->ID, 'fb_email', true)) . '" class="regular-text" /><br />
						<span class="description">' . __('Enter your Facebook Email', $this->ptd) . '</span>
					</td>
				</tr>
				<tr>
					<th><label for="fb_uid">' . __('Facebook ID', $this->ptd) . '</label></th>
					<td>
						<input type="text" name="fb_uid" id="fb_uid" value="' . esc_attr(get_user_meta($user->ID, 'fb_uid', true)) . '" class="regular-text" /><br />
						<span class="description">' . __('Enter your Facebook ID', $this->ptd) . '</span>
					</td>
				</tr>
				<tr>
					<th><label for="fb_reset">' . __('Unsync Facebook Account ?', $this->ptd) . '</label></th>
					<td>
						<input type="checkbox" name="fb_reset" id="fb_reset" value="1" /><br />
						<span class="description">' . __('Note: This will clear all your facebook data linked with this account.', $this->ptd) . '</span>
					</td>
				</tr>
			</table>';
		}
	}

	/**
	 * The action to save special field in user profile
	 * @param int $WP_User ID
	 */
	public function user_profile_save($user_id)
	{
		if (!current_user_can('read', $user_id))
			return false;
		if (isset($_POST['fb_reset'])) {
			wp_redirect($this->_unsync_url);
			exit();
		}
		if (isset($_POST['fb_email'])) {
			update_user_meta($user_id, 'fb_email', $_POST['fb_email']);
		}
		if (isset($_POST['fb_uid'])) {
			update_user_meta($user_id, 'fb_uid', $_POST['fb_uid']);
		}

	}

	//****************************************************************************************
	//	Facebook CONNECT
	//****************************************************************************************
	/**
	 * Getter
	 * WP User infos form FB uid
	 * @param int $fb_uid
	 * @return array|boolean
	 */
	public function get_user_from_fbuid($fb_uid)
	{
		$existing_user = $this->wpdb->get_var('SELECT DISTINCT `u`.`ID` FROM `' . $this->wpdb->users . '` `u` JOIN `' . $this->wpdb->usermeta . '` `m` ON `u`.`ID` = `m`.`user_id`  WHERE (`m`.`meta_key` = "fb_uid" AND `m`.`meta_value` = "' . $fb_uid . '" )  LIMIT 1 ');
		if ($existing_user) {
			$user = get_userdata($existing_user);
			return $user;
		} else {
			return false;
		}
	}

	public function get_all_facebook_users()
	{
		$existings_users = $this->wpdb->get_results('SELECT DISTINCT `u`.`ID`,`u`.`display_name`,`m`.`meta_value`   FROM `' . $this->wpdb->users . '` `u` JOIN `' . $this->wpdb->usermeta . '` `m` ON `u`.`ID` = `m`.`user_id`  WHERE (`m`.`meta_key` = "fb_uid" AND `m`.`meta_value` !="" )');
		return $existings_users;
	}

	/**
	 * Add avatar Facebook As Default
	 * @param array $avatar_defaults
	 * @return string
	 */
	public function fb_addgravatar($avatar_defaults)
	{
		$avatar_defaults[$this->plugin_slug] = 'Facebook Profile Picture';
		return $avatar_defaults;
	}

	/**
	 * Get avatar from facebook
	 * Replace it where we need it.
	 * @param string $avatar
	 * @param object $comments_objects
	 * @param int $size
	 * @param string $default
	 * @param string $alt
	 * @return string
	 */
	public function fb_get_avatar($avatar, $comments_objects, $size, $default, $alt)
	{
		$fbuid = 0;
		$default_avatar = get_option('avatar_default');
		if (is_object($comments_objects)) {
			$fbuid = get_user_meta($comments_objects->user_id, 'fb_uid', true);
			if ($fbuid == '') {
				$fbuid = $comments_objects->user_id;//try if we directly get fbuid
			}
		} elseif (is_numeric($comments_objects)) {
			$fbuid = get_user_meta($comments_objects, 'fb_uid', true);
		} elseif ($comments_objects != '') {
			if ($default == 'awd_fcbk') {
				$user = get_user_by('email', $comments_objects);
				$fbuid = get_user_meta($user->ID, 'fb_uid', true);
			}
		}
		if ($fbuid != '' && $fbuid != 0) {
			if ($size <= 70) {
				$type = 'square';
			} else if ($size > 70) {
				$type = 'normal';
			} else {
				$type = 'large';
			}
			$fb_avatar_url = 'http://graph.facebook.com/' . $fbuid . '/picture' . ($type != '' ? '?type=' . $type : '');
			$my_avatar = "<img src='" . $fb_avatar_url . "' class='avatar AWD_fbavatar' alt='" . $alt . "' height='" . $size . "' />";
			return $my_avatar;
		}
		return $avatar;
	}

	/**
	 * @return true if the user has this perm.
	 */
	public function current_facebook_user_can($perm)
	{
		if ($this->is_user_logged_in_facebook()) {
			if (isset($this->me['permissions']) && is_array($this->me['permissions'])) {
				if (isset($this->me['permissions'][$perm]) && $this->me['permissions'][$perm] == 1)
					return true;
			}
		}
		return false;
	}

	public function get_me()
	{
		try {
			$this->me = $this->fcbk->api('/me', 'GET');
			return $this->me;
		} catch (FacebookApiException $e) {
			$fb_error = $e->getResult();
			$error = new WP_Error(403, $this->plugin_name . ' Error: ' . $fb_error['error']['type'] . ' ' . $fb_error['error']['message']);
			return $error;
		}
	}

	public function get_fb_user($fb_uid)
	{
		try {
			$this->me = $this->fcbk->api('/' . $fb_uid, 'GET');
			return $this->me;
		} catch (FacebookApiException $e) {
			$fb_error = $e->getResult();
			$error = new WP_Error(403, $this->plugin_name . ' Error: ' . $fb_error['error']['type'] . ' ' . $fb_error['error']['message']);
			return $error;
		}
	}

	public function get_my_permissions()
	{
		try {
			$this->me['permissions'] = $this->fcbk->api('/me/permissions');
			$this->me['permissions'] = $this->me['permissions']['data'][0];
			return $this->me['permissions'];
		} catch (FacebookApiException $e) {
			$fb_error = $e->getResult();
			$error = new WP_Error(403, $this->plugin_name . ' Error: ' . $fb_error['error']['type'] . ' ' . $fb_error['error']['message']);
			return $error;
		}
	}

	public function get_permissions($fb_uid)
	{
		$perms = array();
		try {
			$perms = $this->fcbk->api('/' . $fb_uid . '/permissions');
			$perms = isset($perms['data'][0]) ? $perms['data'][0] : array();
			return $perms;
		} catch (FacebookApiException $e) {
			$fb_error = $e->getResult();
			$error = new WP_Error(403, $this->plugin_name . ' Error: ' . $fb_error['error']['type'] . ' ' . $fb_error['error']['message']);
			return $error;
		}
	}

	public function get_realtime_subscriptions()
	{
		$sub = array();
		try {
			if (is_object($this->fcbk)) {
				$sub = $this->fcbk->api('/' . $this->options['app_id'] . '/subscriptions', 'GET', array("access_token" => $this->fcbk->getApplicationAccessToken()));
			} else {
				$error = new WP_Error(500, $this->plugin_name . " Api not configured");
				return $error;
			}
			$sub = isset($sub['data']) ? $sub['data'] : array();
			return $sub;
		} catch (FacebookApiException $e) {
			$fb_error = $e->getResult();
			$error = new WP_Error(403, $this->plugin_name . ' Error: ' . $fb_error['error']['type'] . ' ' . $fb_error['error']['message']);
			return $error;
		}
	}

	public function fb_get_pages()
	{
		try {
			$fb_pages = $this->fcbk->api('/me/accounts');
			if (isset($fb_pages['data'])) {
				foreach ($fb_pages['data'] as $fb_page) {
					$this->me['pages'][$fb_page['id']] = $fb_page;
				}
			}
			return $this->me['pages'];
		} catch (FacebookApiException $e) {
			$fb_error = $e->getResult();
			$error = new WP_Error(403, $this->plugin_name . ' Error: ' . $fb_error['error']['type'] . ' ' . $fb_error['error']['message']);
			return $error;
		}
	}

	/**
	 * Get all facebook Data only when. Then store them.
	 * @throws FacebookApiException
	 * @return string
	 */
	public function get_facebook_user_data()
	{
		if ($this->uid) {
			$return = $this->get_me();
			if (is_wp_error($return)) {
				return $return;
			}
			$return = $this->get_my_permissions();
			if (is_wp_error($return))
				return $return;

			if (isset($return['manage_pages'])) {
				if ($return['manage_pages'] == 1) {
					$return = $this->fb_get_pages();
					if (is_wp_error($return))
						return $return;
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * Set all facebook Data
	 */
	public function init_facebook_user_data($user_id)
	{
		$this->me = get_user_meta($user_id, 'fb_user_infos', true);
	}

	public function save_facebook_user_data($user_id, $data)
	{
		update_user_meta($user_id, 'fb_email', $data['email']);
		update_user_meta($user_id, 'fb_uid', $data['id']);
		update_user_meta($user_id, 'fb_user_infos', $data);
	}

	public function clear_facebook_user_data($user_id)
	{
		update_user_meta($user_id, 'fb_email', '');
		update_user_meta($user_id, 'fb_user_infos', array());
		update_user_meta($user_id, 'fb_uid', '');
	}

	/**
	 * Get the WP_User ID from current Facebook User
	 * @return int
	 */
	public function get_existing_user_from_facebook()
	{
		$existing_user = $this->wpdb->get_var('SELECT DISTINCT `u`.`ID` FROM `' . $this->wpdb->users . '` `u` JOIN `' . $this->wpdb->usermeta . '` `m` ON `u`.`ID` = `m`.`user_id`
		WHERE (`m`.`meta_key` = "fb_uid" AND `m`.`meta_value` ="' . $this->uid . '")
		OR (`m`.`meta_key` = "fb_email" AND `m`.`meta_value`="' . $this->me['email'] . '") OR (`u`.`user_email` = "' . $this->me['email'] . '")  LIMIT 1 ');
		if (empty($existing_user))
			$existing_user = false;
		return $existing_user;
	}

	/**
	 * Know if a user is logged in facebook.
	 * @return boolean
	 */
	public function is_user_logged_in_facebook()
	{
		if (isset($this->uid) && $this->uid != 0 && count($this->me)) {
			return true;
		}
		return false;
	}

	public function get_facebook_page_url()
	{
		$facebook_page_url = null;
		if (is_object($this->fcbk)) {
			$signedrequest = $this->fcbk->getSignedRequest();
			if (is_array($signedrequest) && array_key_exists("page", $signedrequest)) {
				$facebook_page_url = json_decode(file_get_contents("https://graph.facebook.com/" . $signedrequest['page']['id']))->{"link"} . "?sk=app_" . $this->fcbk->getAppId();
			}
		}
		return $facebook_page_url;
	}

	/**
	 * INIT PHP SDK 3.1.1 version Modified to Change TimeOut
	 * Connect the user here
	 */
	public function php_sdk_init()
	{
		$this->fcbk = new AWD_facebook_api($this->options);
		$this->uid = $this->fcbk->getUser();
		$this->init_facebook_user_data($this->get_current_user()->ID);
		//helpers vars.
		$login_options = array('scope' => current_user_can("manage_options") ? $this->options["perms_admin"] : $this->options["perms"], 'redirect_uri' => $this->_login_url . (get_option('permalink_structure') != '' ? '?' : '&') . 'redirect_to=' . $this->get_current_url());
		$this->_oauth_url = $this->fcbk->getLoginUrl($login_options);
		$this->facebook_page_url = $this->get_facebook_page_url();
	}

	/**
	 * Add Js init fcbk to footer  ADMIN AND FRONT 
	 * Print debug if active here
	 */
	public function js_sdk_init()
	{
		$html = "\n" . '
		<!-- ' . $this->plugin_name . ' Facebook Library Library-->
		<div id="fb-root"></div>
		<script type="text/javascript">
			(function(d){
				var js, id = "facebook-jssdk",
				ref = d.getElementsByTagName("script")[0];
				if (d.getElementById(id)){
					return;
				}
				js = d.createElement("script"); 
				js.id = id; js.async = true;
				js.src = "//connect.facebook.net/' . $this->options['locale'] . '/all.js";
				ref.parentNode.insertBefore(js, ref);
			}(document));';

		if ($this->options['connect_enable'] == 1) {
			$html .= '					
			jQuery(document).ready(function(){
				window.fbAsyncInit = function(){
					FB.init({
						appId : awd_fcbk.app_id,
						channelUrl : "' . $this->_channel_url . '",
	  					status     : true,
	  					cookie     : true,
	  					xfbml      : true,
	  					oauth      : true
					});
					AWD_facebook.FbEventHandler();
				};
			});
		';
		}
		$html .= '</script>';
		echo $html;
	}

	/**
	 * Core connect the user to wordpress.
	 * @param WP_User $user_object
	 */
	public function authenticate_cookie($user)
	{
		wp_authenticate_cookie($user, '', '');
	}

	public function register_user()
	{
		$username = sanitize_user($this->me['first_name'], true);
		$i = '';
		while (username_exists($username . $i)) {
			$i = absint($i);
			$i++;
		}
		$username = $username . $i;
		$userdata = array('user_pass' => wp_generate_password(), 'user_login' => $username, 'user_nicename' => $username, 'user_email' => $this->me['email'], 'display_name' => $this->me['name'], 'nickname' => $username, 'first_name' => $this->me['first_name'], 'last_name' => $this->me['last_name'], 'role' => get_option('default_role'));
		$userdata = apply_filters('AWD_facebook_register_userdata', $userdata);
		$new_user = wp_insert_user($userdata);
		//Test the creation							
		if (isset($new_user->errors)) {
			wp_die($this->Debug($new_user->errors));
		}
		if (is_int($new_user)) {
			//send email new registration
			wp_new_user_notification($new_user, $userdata['user_pass']);
			return $new_user;
		}

		return false;
	}

	public function get_user_from_provider()
	{
		if ($this->uid) {
			$return = $this->get_facebook_user_data();
			if (is_wp_error($return)) {
				return $return;
			}
			//If user is already logged in and lauch a connect with facebook, try to change info about user account
			if (is_user_logged_in()) {
				$wp_user_id = $this->get_current_user()->ID;
			} else {
				//Found existing user in WP
				$wp_user_id = $this->get_existing_user_from_facebook();
			}
			return $wp_user_id;
		}
		return false;
	}

	public function authenticate($user, $username = '', $password = '')
	{
		$wp_user_id = $this->get_user_from_provider();
		//No user was found we create a new one
		if ($wp_user_id == false && $this->uid) {
			$wp_user_id = $this->register_user();
		}
		if (is_wp_error($wp_user_id)) {
			wp_die($wp_user_id);
		} else if (false === $wp_user_id) {
			wp_redirect($this->_oauth_url);
			exit();
		}

		$this->save_facebook_user_data($wp_user_id, $this->me);
		$this->init_facebook_user_data($wp_user_id);
		$user = new WP_User($wp_user_id);
		//Will create the cookie authentification of the user.
		$this->authenticate_cookie($user);

		return $user;
	}

	/**
	 * Change logout url for users connected with Facebook
	 * @param string $url
	 * @return string
	 */
	public function logout_url($url)
	{
		if ($this->is_user_logged_in_facebook()) {
			$parsing = parse_url($url);
			if (get_option('permalink_structure') != '')
				$redirect_url = str_replace('action=logout&amp;', '', $this->_logout_url . '?' . $parsing['query']);
			else
				$redirect_url = str_replace('action=logout&amp;', '', $this->_logout_url . '&' . $parsing['query']);

			$logout_url = $this->fcbk->getLogoutUrl(array('access_token' => $this->fcbk->getAccessToken(), 'next' => $redirect_url . '/'));

			return $logout_url;
		}
		return $url;
	}

	/**
	 * Logout handler
	 * 
	 * If a user is logged out on Facebook using wp logout function,
	 * The user will be redirected here, and this method will perform the logout on the WP side.	 * 
	 * 
	 */
	public function logout($redirect_url = '')
	{
		$referer = wp_get_referer();
		$this->fcbk->destroySession();
		wp_logout();
		do_action('wp_logout');
		//if we are in an iframe or a canvas page, redirect to
		if (!empty($redirect_url)) {
			wp_redirect($redirect_url);
		} elseif (!empty($referer)) {
			wp_redirect($referer);
		} else {
			wp_redirect(home_url());
		}
		exit();
	}

	/**
	 * Login handler
	 * 
	 * This method will listen for a facebook session.
	 * Once the user was logged in on facebook side. he should be redirected here.
	 * This method will try to loggin or register a user found via a facebook session.
	 * 
	 */
	public function login($redirect_url = '')
	{
		//This filter will add the authentification process
		add_filter('authenticate', array(&$this, 'authenticate'), 10, 3);
		//This will call the filter
		$user = wp_signon('', is_ssl());
		//then redirect where we need.
		if (!empty($redirect_url)) {
			wp_redirect($redirect_url);
		} elseif (!empty($referer)) {
			wp_redirect($referer);
		} else {
			wp_redirect(home_url());
		}
		exit();
	}

	/**
	 * Facebook AWD internal request parser
	 *
	 * If a user is logged out on Facebook using wp logout function,
	 * The user will be redirected here, and this method will perform the logout on the WP side.	 *
	 *
	 */
	public function parse_request()
	{
		global $wp_query;
		$query = get_query_var('facebook_awd');
		$redirect_url = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
		//Parse the query for internal process
		if (!empty($query)) {
			$action = $query['action'];
			switch ($action) {
				//LOGIN
				case 'login':
					$this->login($redirect_url);
					break;

				//LOGOUT
				case 'logout':
					$this->logout($redirect_url);
					break;

				//UNSYNC
				case 'unsync':
					if ($this->is_user_logged_in_facebook()) {
						$this->clear_facebook_user_data($this->get_current_user()->ID);
						wp_redirect(wp_logout_url());
					} else {
						wp_redirect(wp_get_referer());
					}
					exit();
					break;

				case 'channel.html':
					$cache_expire = 60 * 60 * 24 * 365;
					header("Pragma: public");
					header("Cache-Control: max-age=" . $cache_expire);
					header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expire) . ' GMT');
					echo '<script src="//connect.facebook.net/' . $this->options['locale'] . '/all.js"></script>';
					exit();
					break;

				case 'deauthorize':
					$sr = $this->fcbk->getSignedRequest();
					if (isset($sr['user_id'])) {
						$wp_user = $this->get_user_from_fbuid($sr['user_id']);
						if ($wp_user) {
							//require_once(ABSPATH.'wp-admin/includes/user.php');
							//echo wp_delete_user($wp_user->ID);
							//clean facebook user data
							$this->clear_facebook_user_data($wp_user->ID);
							exit();
						}
					}
					wp_die($this->display_messages("Facebook AWD Deauthorize Error. Access denied", null, false));
					break;
				case 'realtime-update-api':
					$token = md5($this->options['app_id']);
					//subscribe mode
					if ($_SERVER['REQUEST_METHOD'] == 'GET') {
						if (!isset($_REQUEST['hub_mode']))
							wp_die($this->display_messages("Facebook AWD Realtime Error hub_mode not set. Access denied", null, false));
						if ($_REQUEST['hub_mode'] != 'subscribe')
							wp_die($this->display_messages("Facebook AWD Realtime Error hub_mode not match current path. Access denied", null, false));
						if (!isset($_REQUEST['hub_challenge']))
							wp_die($this->display_messages("Facebook AWD Realtime Error hub_challenge not set. Access denied", null, false));
						if (!isset($_REQUEST['hub_challenge']))
							wp_die($this->display_messages("Facebook AWD Realtime Error hub_challenge not set. Access denied", null, false));
						if (!isset($_REQUEST['hub_verify_token']))
							wp_die($this->display_messages("Facebook AWD Realtime Error hub_verify_token not set. Access denied", null, false));
						if ($_REQUEST['hub_verify_token'] != $token)
							wp_die($this->display_messages("Facebook AWD Realtime Error hub_verify_token not match. Access denied", null, false));

						echo $_REQUEST['hub_challenge'];
						exit();
					}
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$json_results = file_get_contents("php://input");
						$sha_token = 'sha1=' . hash_hmac('sha1', $json_results, $this->options['app_secret_key']);
						error_log('Facebook AWD realtime Action connected with ' . $sha_token);
						if ($_SERVER['HTTP_X_HUB_SIGNATURE'] == $sha_token) {
							$updates = json_decode($json_results, true);

							if ($updates['object'] == 'permissions') {
								foreach ($updates['entry'] as $user_changeset) {
									$wp_user = $this->get_user_from_fbuid($user_changeset['id']);
									$return = $this->get_permissions($user_changeset['id']);
									if (!is_wp_error($return)) {
										//update the perms of the user
										$old_fb_data = $wp_user->fb_user_infos;
										if (is_array($old_fb_data)) {
											//replace the old array of perms by the new one
											$new_fb_data = array_replace($old_fb_data, array('permissions' => $return));
											$this->save_facebook_user_data($wp_user->ID, $new_fb_data);
										}
									} else {
										error_log("Facebook AWD " . print_r($return, true) . " error perms");
									}
								}
							}

							if ($updates['object'] == 'user') {
								foreach ($updates['entry'] as $user_changeset) {
									$wp_user = $this->get_user_from_fbuid($user_changeset['id']);
									if ($wp_user) {
										$fb_user = $this->get_fb_user($user_changeset['id']);
										if (!is_wp_error($fb_user)) {
											//update the user
											$old_fb_data = $wp_user->fb_user_infos;
											if (is_array($fb_user)) {
												//replace the old array of perms by the new one
												$new_fb_data = array_replace($old_fb_data, $fb_user);
												$this->save_facebook_user_data($wp_user->ID, $new_fb_data);
												wp_update_user(array('ID' => $wp_user->ID, 'user_nicename' => $new_fb_data['name'], 'display_name ' => $new_fb_data['name'], 'user_email' => $new_fb_data['email'], 'user_firstname' => $new_fb_data['first_name'], 'user_lastname' => $new_fb_data['last_name']));
											}
										} else {
											error_log("Facebook AWD " . print_r($return, true) . " error perms");
										}
									}
								}
							}
							exit();
						}
					}
					wp_die($this->display_messages("Facebook AWD Realtime Error. Access denied", null, false));
					break;
			}
		}
	}

	/**
	 * Flush rules WP
	 */
	public function flush_rules()
	{
		$rules = get_option('rewrite_rules');
		if (!isset($rules['facebook-awd/(login|logout|unsync|channel.html|deauthorize|realtime-update-api)$'])) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}

	/**
	 * insert rules WP
	 */
	public function insert_rewrite_rules($rules)
	{
		$newrules = array();
		$newrules['facebook-awd/(login|logout|unsync|channel.html|deauthorize|realtime-update-api)$'] = 'index.php?facebook_awd[action]=$matches[1]';
		return $newrules + $rules;
	}

	/**
	 * Isert query vars
	 * @return $vars
	 */
	public function insert_query_vars($vars)
	{
		$vars[] = 'facebook_awd';
		return $vars;
	}

	//****************************************************************************************
	//	LOGIN BUTTON
	//****************************************************************************************
	/**
	 * @return the loggin button  shortcode
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_login_button($atts = array())
	{
		return $this->get_the_login_button($atts);
	}

	/**
	 * @return the html for login button
	 * @param array $options
	 * @return string
	 */
	public function get_the_login_button($options = array())
	{
		$options = wp_parse_args($options, $this->options['login_button']);

		//search and replace pattern for redirect url
		$options['login_redirect_url'] = str_replace(array("%CURRENT_URL%"), array($this->get_current_url()), $options['login_redirect_url']);
		$options['logout_redirect_url'] = str_replace(array("%CURRENT_URL%"), array($this->get_current_url()), $options['logout_redirect_url']);

		$html = '';
		switch (1) {
			case ($this->is_user_logged_in_facebook() && $this->options['connect_enable'] && is_user_logged_in()):
				$html .= '<div class="AWD_profile AWD_facebook_wrap">' . "\n";
				if ($options['show_profile_picture'] == 1 && $options['show_faces'] == 0) {
					$html .= '<div class="AWD_profile_image pull-left"><a href="' . $this->me['link'] . '" target="_blank" class="thumbnail"> ' . get_avatar($this->get_current_user()->ID, '50') . '</a></div>' . "\n";
				}
				$html .= '<div class="AWD_right">' . "\n";
				if ($options['show_faces'] == 1) {
					$login_button = '<fb:login-button show-faces="1" width="' . $options['width'] . '" max-rows="' . $options['max_row'] . '" size="medium"></fb:login-button>';
					$html .= '<div class="AWD_faces">' . $login_button . '</div>' . "\n";
				} else {
					$html .= '<div class="AWD_name"><a href="' . $this->me['link'] . '" target="_blank">' . $this->me['name'] . '</a></div>' . "\n";
				}
				$html .= '<div class="AWD_logout"><a href="' . wp_logout_url($options['logout_redirect_url']) . '" class="btn btn-mini btn-danger">' . $options['logout_label'] . '</a></div>' . "\n";
				$html .= '</div>' . "\n";
				$html .= '<div class="clear"></div>' . "\n";
				$html .= '</div>' . "\n";
				return $html;
				break;
			case $this->options['connect_enable']:
				return '
					<div class="AWD_facebook_login">
						<a href="#" class="AWD_facebook_connect_button" data-redirect="' . (isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : urlencode($options['login_redirect_url'])) . '"><img src="' . $options['image'] . '" border="0" alt="Login"/></a>
					</div>' . "\n";
				break;
			default:
				if (is_admin())
					return $this->display_messages(sprintf(__('You should enable FB connect in %sApp settings%s', $this->ptd), '<a href="admin.php?page=' . $this->plugin_slug . '">', '</a>'), 'warning', false);
				break;
		}
	}

	public function login_enqueue_scripts()
	{
		$this->add_js_options();
	}

	/**
	 * Print the login button for the wp-login.php page
	 */
	public function the_login_button_wp_login()
	{
		echo '
		<div class="AWD_facebook_connect_wplogin" style="text-align:right;">
		<label>' . __('Connect with Facebook', $this->ptd) . '</label>
		' . $this->get_the_login_button() . '
		</div>
		<br />
		';
	}

	//****************************************************************************************
	//	LIKE BUTTON
	//****************************************************************************************
	/**
	 * @return the like button shortcode
	 * @return html code
	 */
	public function shortcode_like_button($atts = array())
	{
		global $post;
		return $this->get_the_like_button($post, $atts);
	}

	/**
	 * @return the like button
	 * @return string
	 */
	public function get_the_like_button($post = "", $options = array())
	{

		if (!isset($options['href']) OR empty($options['href']))
			if (is_object($post))
				$options['href'] = get_permalink($post->ID);

		$options = wp_parse_args($options, $this->options['like_button']);
		try {
			$AWD_facebook_likebutton = new AWD_facebook_likebutton($options);
			return '<div class="AWD_facebook_likebutton">' . $AWD_facebook_likebutton->get() . '</div>';
		} catch (Exception $e) {
			return $this->display_messages($e->getMessage(), 'error', false);
		}
	}

	/**
	 * Add manager to post editor
	 * @param WP_Post object $post
	 */
	public function post_manager_content($post)
	{
		//Prepare manager for link publish or post.
		$id = $post->ID;
		$url = get_permalink($post->ID);
		$custom = get_post_meta($id, $this->plugin_slug, true);
		$options = array();
		if (isset($custom)) {
			$options = $custom;
		}
		$options = wp_parse_args($options, $this->options['content_manager']);
		$form = new AWD_facebook_form('form_posts_settings', 'POST', '', $this->plugin_option_pref);
		echo '
	 	<div class="AWD_facebook_wrap">
			' . do_action('AWD_facebook_admin_notices') . '
			<h2>' . __('Like Button', $this->ptd) . '</h2>';
		if ($url != '') {
			echo '
				<div class="alert alert-info">
					' . do_shortcode('[AWD_likebutton width="250" href="' . $url . '"]');
			if (isset($this->plugins["awd_fcbk_post_to_feed"]))
				echo do_shortcode('[AWD_facebook_post_to_feed_button width="250" href="' . $url . '"]');
			echo '
				</div>';
		}
		echo '
			<div class="row">
				' . $form->addSelect(__('Redefine globals settings ?', $this->ptd), 'like_button[redefine]', array(array('value' => 0, 'label' => __('No', $this->ptd)), array('value' => 1, 'label' => __('Yes', $this->ptd))), $options['like_button']['redefine'], 'span3', array('class' => 'span3')) . '
				' . $form->addSelect(__('Activate ?', $this->ptd), 'like_button[enabled]', array(array('value' => 0, 'label' => __('No', $this->ptd)), array('value' => 1, 'label' => __('Yes', $this->ptd))), $options['like_button']['enabled'], 'span3', array('class' => 'span3')) . '
				' . $form->addSelect(__('Where ?', $this->ptd), 'like_button[place]', array(array('value' => 'top', 'label' => __('Top', $this->ptd)), array('value' => 'bottom', 'label' => __('Bottom', $this->ptd)), array('value' => 'both', 'label' => __('Both', $this->ptd))), $options['like_button']['place'], 'span3', array('class' => 'span3')) . '
			</div>';

		if (current_user_can('manage_facebook_awd_publish_to_pages')) {
			echo '<h2>' . __('Publish to Facebook', $this->ptd) . '</h2>';
			if ($this->is_user_logged_in_facebook()) {
				if ($this->current_facebook_user_can('publish_stream')) {
					if ($this->current_facebook_user_can('manage_pages')) {
						echo '<div class="row">';
						echo $form->addSelect(__('Publish to pages ?', $this->ptd), 'fbpublish[to_pages]', array(array('value' => 0, 'label' => __('No', $this->ptd)), array('value' => 1, 'label' => __('Yes', $this->ptd))), $options['fbpublish']['to_pages'], 'span3', array('class' => 'span3'));
						echo $form->addSelect(__('Publish to profile ?', $this->ptd), 'fbpublish[to_profile]', array(array('value' => 0, 'label' => __('No', $this->ptd)), array('value' => 1, 'label' => __('Yes', $this->ptd))), $options['fbpublish']['to_profile'], 'span3', array('class' => 'span3'));
						echo $form->addInputText(__('Custom Action Label', $this->ptd), 'fbpublish[read_more_text]', $options['fbpublish']['read_more_text'], 'span3', array('class' => 'span3'));
						echo $form->addInputTextArea(__('Add a message to the post ?', $this->ptd), 'fbpublish[message_text]', $options['fbpublish']['message_text'], 'span3', array('class' => 'span3'));
						echo '</div>';
					} else {
						$this->warnings[] = new WP_Error('AWD_facebook_pages_auth', __('You must authorize manage_pages permission in the settings of the plugin', $this->ptd));
						$this->display_all_errors();
					}
				} else {
					$this->warnings[] = new WP_Error('AWD_facebook_pages_auth_publish_stream', __('You must authorize publish_stream permission in the settings of the plugin', $this->ptd));
					$this->display_all_errors();
				}
			} else {
				echo '<p>' . do_shortcode('[AWD_loginbutton]') . '</p>';
			}
		}
		if (current_user_can('manage_facebook_awd_opengraph')) {
			if ($this->options['open_graph_enable'] == 1) {
				echo '<h2>' . __('Opengraph', $this->ptd) . '</h2>';
				$add_link = '<a class="btn btn btn-mini" href="' . admin_url('admin.php?page=' . $this->plugin_slug . '_open_graph') . '" target="_blank"><i class="icon-plus"></i> ' . __('Create an object', $this->ptd) . '</a>';

				$ogp_objects = apply_filters('AWD_facebook_ogp_objects', $this->options['opengraph_objects']);
				if (is_array($ogp_objects) && count($ogp_objects)) {
					$linked_object = '';
					$select_objects_options = array(
						array('value' => '', 'label' => __('Default', $this->ptd)),
						array('value' => 'custom', 'label' => __('Custom', $this->ptd))
					);
					foreach ($ogp_objects as $key => $ogp_object) {
						$select_objects_options[] = array('value' => $key, 'label' => $ogp_object['object_title']);
					}
					echo '
						<div class="row">
							' . $form->addSelect(__('Redefine Opengraph object for this post', $this->ptd), 'opengraph[object_link]', $select_objects_options, $options['opengraph']['object_link'], 'span3', array('class' => 'span3')) .$add_link.'
						</div>';
				} else {
					$this->display_messages(sprintf(__('No Object found.', $this->ptd) . ' ' . $add_link, '<a href="' . admin_url('admin.php?page=' . $this->plugin_slug . '_open_graph') . '" target="_blank">', '</a>'), 'warning');
				}
				
				$opengraph_array = isset($options['awd_ogp']) ? $options['awd_ogp'] : null;
				echo '<div class="hidden opengraph_object_form">';
				$this->get_open_graph_object_form($opengraph_array);
				echo '</div>';
			}
		}

		echo '
		</div>';
	}

	//****************************************************************************************
	//	Shared ACTIVITY BOX
	//****************************************************************************************
	/**
	 * @return the like button shortcode
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_shared_activity_box($atts = array())
	{
		return $this->get_the_shared_activity_box($atts);
	}

	/**
	 * @return the shared activity box
	 * @param array $options
	 * @return string
	 */
	public function get_the_shared_activity_box($options = array())
	{
		$options = wp_parse_args($options, $this->options['shared_activity_box']);
		try {
			$AWD_facebook_shared_activity = new AWD_facebook_shared_activity($options);
			return '<div class="AWD_facebook_shared_activity">' . $AWD_facebook_shared_activity->get() . '</div>';
		} catch (Exception $e) {
			return $this->display_messages($e->getMessage(), 'error', false);
		}
	}

	//****************************************************************************************
	//	COMMENT BOX
	//****************************************************************************************
	/**
	 * @return the comment box shortcode
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_comments_box($atts = array())
	{
		global $post;
		return $this->get_the_comments_box($post, $atts);
	}

	/**
	 * @return the comment box
	 * @param WP_Post object $post
	 * @param array $options
	 * @return string
	 */
	public function get_the_comments_box($post = null, $options = array())
	{
		if (!isset($options['href']) OR empty($options['href']))
			if (is_object($post))
				$options['href'] = get_permalink($post->ID);

		$options = wp_parse_args($options, $this->options['comments_box']);
		try {
			$AWD_facebook_comments = new AWD_facebook_comments($options);
			return '<div class="AWD_facebook_comments">' . $AWD_facebook_comments->get() . '</div>';
		} catch (Exception $e) {
			return $this->display_messages($e->getMessage(), 'error', false);
		}
	}

	/**
	 * Display the comment form
	 */
	public function display_the_comment_form()
	{
		global $post;
		echo $this->get_the_comments_box($post);
	}

	/**
	 * Filter the comment form to add fbcomments
	 */
	public function the_comments_form($stylesheet_path)
	{
		global $post;
		$exclude_post_page_id = explode(",", $this->options['comments_box']['exclude_post_id']);
		if (!in_array($post->ID, $exclude_post_page_id)) {
			if ($this->options['comments_box']['place'] == 'replace') {
				//replace the form with a template.
				$stylesheet_path = $this->options['comments_box']['comments_template_path'];
			} else {
				add_action('comment_form_' . $this->options['comments_box']['place'], array(&$this, 'display_the_comment_form'));
			}
		}
		return $stylesheet_path;
	}

	//****************************************************************************************
	//	LIKE BOX 
	//****************************************************************************************
	/**
	 * @return the like box shortcode
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_like_box($atts = array())
	{
		return $this->get_the_like_box($atts);
	}

	/**
	 * @return the Like Box
	 * @param array $options
	 * @return string
	 */
	public function get_the_like_box($options = array())
	{
		$options = wp_parse_args($options, $this->options['like_box']);
		try {
			$AWD_facebook_likebox = new AWD_facebook_likebox($options);
			return '<div class="AWD_facebook_likebox">' . $AWD_facebook_likebox->get() . '</div>';
		} catch (Exception $e) {
			return $this->display_messages($e->getMessage(), 'error', false);
		}
	}

	//****************************************************************************************
	//	ACTIVITY BOX 
	//****************************************************************************************
	/**
	 * @return the Activity Box shortcode
	 * @param array $atts
	 * @return string
	 */
	public function shortcode_activity_box($atts = array())
	{
		return $this->get_the_activity_box($atts);
	}

	/**
	 * @return the Activity Button
	 * @param array $options
	 * @return string
	 */
	public function get_the_activity_box($options = array())
	{
		$options = wp_parse_args($options, $this->options['activity_box']);
		try {
			$AWD_facebook_activity = new AWD_facebook_activity($options);
			return '<div class="AWD_facebook_activity">' . $AWD_facebook_activity->get() . '</div>';
		} catch (Exception $e) {
			return $this->display_messages($e->getMessage(), 'error', false);
		}
	}

	//****************************************************************************************
	//	REGISTER WIDGET
	//****************************************************************************************
	/**
	 * Like box register widgets
	 */
	public function register_AWD_facebook_widgets()
	{
		global $wp_widget_factory;
		require(dirname(__FILE__) . '/inc/admin/forms/like_box.php');
		$wp_widget_factory->widgets['AWD_facebook_widget_likebox'] = new AWD_facebook_widget(array('id_base' => 'like_box', 'name' => $this->plugin_name . ' ' . __('Like Box', $this->ptd), 'description' => __('Add a Facebook Like Box', $this->ptd), 'model' => $fields['like_box'], 'self_callback' => array($this, 'shortcode_like_box'), 'text_domain' => $this->ptd, 'preview' => true));

		require(dirname(__FILE__) . '/inc/admin/forms/like_button.php');
		$wp_widget_factory->widgets['AWD_facebook_widget_like_button'] = new AWD_facebook_widget(array('id_base' => 'like_button', 'name' => $this->plugin_name . ' ' . __('Like Button', $this->ptd), 'description' => __('Add a Facebook Like Button', $this->ptd), 'model' => $fields['like_button'], 'self_callback' => array($this, 'shortcode_like_button'), 'text_domain' => $this->ptd, 'preview' => true));

		require(dirname(__FILE__) . '/inc/admin/forms/login_button.php');
		$wp_widget_factory->widgets['AWD_facebook_widget_login_button'] = new AWD_facebook_widget(array('id_base' => 'login_button', 'name' => $this->plugin_name . ' ' . __('Login Button', $this->ptd), 'description' => __('Add a Facebook Login Button', $this->ptd), 'model' => $fields['login_button'], 'self_callback' => array($this, 'shortcode_login_button'), 'text_domain' => $this->ptd));

		require(dirname(__FILE__) . '/inc/admin/forms/activity_box.php');
		$wp_widget_factory->widgets['AWD_facebook_widget_activity_box'] = new AWD_facebook_widget(array('id_base' => 'activity_box', 'name' => $this->plugin_name . ' ' . __('Activity Box', $this->ptd), 'description' => __('Add a Facebook Activity Box', $this->ptd), 'model' => $fields['activity_box'], 'self_callback' => array($this, 'shortcode_activity_box'), 'text_domain' => $this->ptd, 'preview' => true));

		require(dirname(__FILE__) . '/inc/admin/forms/comments_box.php');
		$wp_widget_factory->widgets['AWD_facebook_widget_comments_box'] = new AWD_facebook_widget(array('id_base' => 'comments_box', 'name' => $this->plugin_name . ' ' . __('Comments Box', $this->ptd), 'description' => __('Add a Facebook Comments Box', $this->ptd), 'model' => $fields['comments_box'], 'self_callback' => array($this, 'shortcode_comments_box'), 'text_domain' => $this->ptd));

		require(dirname(__FILE__) . '/inc/admin/forms/shared_activity_box.php');
		$wp_widget_factory->widgets['AWD_facebook_shared_activity_box'] = new AWD_facebook_widget(array('id_base' => 'shared_activity_box', 'name' => $this->plugin_name . ' ' . __('Shared Activity Box', $this->ptd), 'description' => __('Add a Facebook Shared Activity Box', $this->ptd), 'model' => $fields['shared_activity_box'], 'self_callback' => array($this, 'shortcode_shared_activity_box'), 'text_domain' => $this->ptd));

		do_action('AWD_facebook_register_widgets');
	}

	//****************************************************************************************
	//	DEBUG AND DEV
	//****************************************************************************************
	/**
	 * Debug
	 */
	public function debug_content()
	{
		if ($this->options['debug_enable'] == 1) {
			$_this = clone $this;
			$_this = (array) $_this;
			unset($_this['current_user']);
			unset($_this['wpdb']);
			unset($_this['optionsManager']);
			echo '
			<div class="AWD_facebook_wrap">
				<div class="container-fluid">
					<div class="awd_debug well">
						<div class="page-header">
							<h2>' . __('Facebook AWD API', $this->ptd) . '</h2>
						</div>
						';
			$this->Debug($_this['fcbk']);
			echo '
						<div class="page-header">
							<h2>' . __('Facebook AWD APPLICATIONS INFOS', $this->ptd) . '</h2>
						</div>';
			$this->Debug($_this['options']['app_infos']);
			echo '
						<div class="page-header">
							<h2>' . __('Facebook AWD CURRENT USER', $this->ptd) . '</h2>
						</div>';
			$this->Debug($_this['me']);
			echo '
						<div class="page-header">
							<h2>' . __('Facebook AWD Options', $this->ptd) . '</h2>
						</div>';
			$this->Debug($_this['options']);
			echo '
						<div class="page-header">
							<h2>' . __('Facebook AWD FULL', $this->ptd) . '</h2>
						</div>';
			$this->Debug($_this);
			echo '
					</div>
				</div>
			</div>
			<br />';
		}
	}
}

//Object Plugin.
$AWD_facebook = new AWD_facebook();
?>