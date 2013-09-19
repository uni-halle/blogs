<?php
/*
Plugin Name: WP Tweet Button
Version: 2.1.3
Plugin URI: http://0xtc.com/plugins/wp-tweet-button
Description: The WordPress implementation of the official Twitter Tweet Button.
Contributors: 0xtc
Author: Tanin Ehrami
Author URI: http://0xtc.com
Stable tag: trunk
Text Domain: wp-tweet-button
Domain Path: /lang
*/

/*
	Hooks:
#	wp_tweet_button_long_url 
	(string:url)
	Use this hook to manipulate the value of the permalink that has about to be used for shortening and for counturl

#	wp_tweet_button_url
	(string:url)
	Use this hook to manipulate the value of the shorturl that has about to be used to tweet with.

#	wp_tweet_button
	(string:html)
	Use this hook to manipulate the generated button html code.

#	wp_tweet_button_options
	(Array)
	Use this hook to register new options at runtime.

#	wp_tweet_button_shortenerdata
	(Array)
	Use this hook together with the wp_tweet_button_options hook to add new shorteners to the configuration.
*/

class wpTweetButton {
	var $pluginlabel 	= 'WP Tweet Button';
	var $pluginslug 	= 'wptweetbutton';
	var $optionsname 	= 'wp_tweet_button';
	var $txtdom 		= 'wp-tweet-button';
	var $readyState 	= false;
	var $ismanual		= false;
	var $excerptState 	= false;
	var $hasbutton 		= array();
	var $postid;
	var $wpt_pluginpath;	// passive usage
	/**
	* The following are default settings for the plugin's configuration.
	* Most of these settings can be accessed from the plugin's settings page.
	*/
	var $options = array(
		'tw_hook_prio'=> '75',
		'tw_debug'=> '',
		'tw_where'=> 'before',
		'tw_text'=>'entry_title',
		'tw_text_format'=>'VIA',
		'tw_text_custom'=>'',
		'tw_no_wporg'=>'',
		'tw_strip_www'=>'',
		'tw_flush_cache'=>'',
		'tw_url_shortener'=>'none',
		'tw_url_shortener_awesm_key'=>'',
		'tw_url_shortener_snip_user'=>'',
		'tw_url_shortener_snip_key'=>'',
		'tw_url_shortener_cligs_key'=>'',
		'tw_url_shortener_super_user'=>'',
		'tw_url_shortener_super_key'=>'',
		'tw_url_shortener_bitly_user'=>'',
		'tw_url_shortener_bitly_key'=>'',
		'tw_url_shortener_jmp_user'=>'',
		'tw_url_shortener_jmp_key'=>'',
		'tw_url_shortener_tinyarrow_host'=>'',
		'tw_url_shortener_tinyarrow_user'=>'',
		'tw_url_shortener_google_key'=>'',
		'tw_url_shortener_google_token'=>'',
		'tw_url_samecount'=>'1',
		'tw_rel_meta'=>'',
		'tw_btn_text'=>'Tweet',
		'tw_via'=>'',
		'tw_via_authors'=>'',
		'tw_ga_code'=>'',
		'tw_ga_setallowanchor'=>'',
		'tw_enable_ga_code'=>'1',
		'tw_align'=>'none',
		'tw_rec'=>'',
		'tw_rec_desc'=>'',
		'tw_rec_authors'=>'',
		'tw_lang'=>'en',
		'tw_style'=> '',
		'tw_style_c'=> '',
		'tw_count'=> 'horizontal',
		'tw_nostyle_feed'=> '',
		'tw_force_manual'=> '',
		'tw_display_single'=> '1',
		'tw_display_archive'=> '1',
		'tw_display_search'=> '1',
		'tw_display_excerpt'=> '1',
		'tw_display_feed'=> '1',
		'tw_display_page'=> '1',
		'tw_display_front'=> '1',
		'tw_display_mobile'=>'',
		'tw_ex_after_home'=>'',
		'tw_ex_after_archive'=>'',
		'tw_bwdata_attr'=> '',
		'tw_use_rel_me'=> '',
		'tw_add_rel_shortlink'=> '',
		'tw_no_https_shortlinks'=> '1',
		'tw_post_type_exclude'=>'',
		'tw_config_ver'=>'',
		'tw_auto_tweet_via'=>'',
		'tw_auto_tweet_token_secret'=>'',
		'tw_auto_tweet_token'=>'',
		'tw_auto_tweet_pages'=>'0',
		'tw_auto_tweet_posts'=>'0',
		'tw_auto_tweet_posts_onsave'=>'',
		'tw_auto_tweet_pages_onsave'=>'',
		'tw_auto_tweet_strip'=>'stext',
		'tw_auto_long_tweet_mode'=>'',
		'tw_script_infooter'=>'0',
		'tw_urlencode'=>'0',
		'tw_prev_content_dup'=>'1'
		);
	/**
	* These are settings from the list above that have boolean values (true or false / 1 or 0) 
	* It is used for cleanup and validation actions.
	*/
	var $boolops = array(
		'tw_debug',
		'tw_script_infooter',
		'tw_force_manual',
		'tw_display_single',
		'tw_display_search',
		'tw_ex_after_home',
		'tw_ex_after_archive',
		'tw_display_archive',
		'tw_display_page',
		'tw_display_front',
		'tw_display_excerpt',
		'tw_display_feed',
		'tw_display_mobile',
		'tw_enable_ga_code',
		'tw_ga_setallowanchor',
		'tw_use_rel_me',
		'tw_via_authors',
		'tw_rec_authors',
		'tw_add_rel_shortlink',
		'tw_bwdata_attr',
		'tw_url_samecount',
		'tw_auto_tweet_pages',
		'tw_auto_tweet_posts',
		'tw_auto_tweet_posts_onsave'=>'',
		'tw_auto_tweet_pages_onsave'=>'',
		'tw_auto_long_tweet_mode'=>'',
		'tw_no_https_shortlinks',
		'tw_prev_content_dup',
		'tw_urlencode',
		'tw_nostyle_feed'
		);
	/**
	* These are TinyArro.ws domains. 
	* Due to javascript ristrictions there is currently no method to automatically add
	* TinyArro.ws domains to a tweet button dialog. 
	* Although TinyArro.ws is not supported we're declaring them here in the hope of one day
	* being able to use them.
	*/
	var $tinyarrow_hosts = array(
		'xn--ogi.ws'=>'&#x27A8;.ws',
		'xn--vgi.ws'=>'&#x27AF;.ws',
		'xn--3fi.ws'=>'&#x2794;.ws',
		'xn--egi.ws'=>'&#x279E;.ws',
		'xn--9gi.ws'=>'&#x27BD;.ws',
		'xn--5gi.ws'=>'&#x27B9;.ws',
		'xn--1ci.ws'=>'&#x2729;.ws',
		'xn--odi.ws'=>'&#x273F;.ws',
		'xn--rei.ws'=>'&#x2765;.ws',
		'xn--cwg.ws'=>'&#x203A;.ws',
		'xn--bih.ws'=>'&#x2318;.ws',
		'xn--fwg.ws'=>'&#x203D;.ws',
		'xn--l3h.ws'=>'&#x2601;.ws',
		'ta.gd '=>'ta.gd'
		);
	/**
	* The following array contains information on shorteners and 
	* their required configuration.
	*/
	var $shortenerdata = array(
		'none' => array(
			'label'=>'None',
		),
		'google' => array(
			'label'=>'goo.gl',
			'params'=>array(
				"key" => array('value'=>'tw_url_shortener_google_key','name'=>'API Key')
			)
		),
		'awesm' => array(
			'label'=>'awe.sm',
			'params'=>array(
				"awesmapikey" => array('value'=>'tw_url_shortener_awesm_key','name'=>'API Key')
			)
		),
		'tiny' => array(
			'label'=>'tinyurl.com',
			'method'=>'GET',
			'url'=>'http://tinyurl.com/api-create.php',
			'params'=> array(
				'url'=>'%URL%'
			)
		),
		'b2l' => array(
			'label'=>'b2l.me',
			'method'=>'GET',
			'url'=>'http://b2l.me/api.php?alias',
			'params'=> array(
				'alias'=>'',
				'url'=>'%URL%'
			)
		),
		'snipr.com' => array(
			'label'=>'snipr.com',
			'method'=>'POST',
			'url'=>'http://snipr.com/site/getsnip',
			'params'=> array(
				"snipformat" => 'simple',
				"sniplink" => '%RAWURL%',
				"snipuser" => array('value'=>'tw_url_shortener_snip_user','name'=>'Username','important'=>true),
				"snipapi" => array('value'=>'tw_url_shortener_snip_key','name'=>'API Key','important'=>true)
			)
		),
		'cligs'=> array(
			'label'=>'cli.gs',
			'method'=>'GET',
			'url'=>'http://cli.gs/api/v1/cligs/create',
			'params'=>array(
				'url'=>'%URL%',
				'appid'=>'wp-tweet-button',
				'key'=>array('value'=>'tw_url_shortener_cligs_key','name'=>'API Key')
			)
		),
		'supr'=>array(
			'label'=>'su.pr',
			'method'=>'GET',
			'url'=>'http://su.pr/api/simpleshorten',
			'params'=>array(
				'url'=>'%URL%',
				'login'=>array('value'=>'tw_url_shortener_super_user','name'=>'Username'),
				'apiKey'=>array('value'=>'tw_url_shortener_super_key','name'=>'API Key'),
				'version'=>'1.0'
			)
		),
		'jmp'=>array(
			'label'=>'j.mp',
			'method'=>'GET',
			'url'=>'http://api.j.mp/v3/shorten',
			'params'=>array(
				'longUrl'=>'%URL%',
				'history'=>'1',
				'login'=>array('value'=>'tw_url_shortener_jmp_user','name'=>'Username','important'=>true),
				'apiKey'=>array('value'=>'tw_url_shortener_jmp_key','name'=>'API Key','important'=>true),
				'format'=>'txt'
			)
		),
		'bitly'=>array(
			'label'=>'bit.ly',
			'method'=>'GET',
			'url'=>'http://api.bit.ly/v3/shorten',
			'params'=>array(
				'version'=>'3',
				'longUrl'=>'%URL%',
				'history'=>'1',
				'login'=>array('value'=>'tw_url_shortener_bitly_user','name'=>'Username','important'=>true),
				'apiKey'=>array('value'=>'tw_url_shortener_bitly_key','name'=>'API Key','important'=>true),
				'format'=>'txt'
			)
		),
		'tinyarrow'=>array(
			'label'=>'tinyarro.ws',
			'method'=>'GET',
			'url'=>'http://tinyarro.ws/api-create.php',
			'enabled'=>'false',
			'params'=>array(
				'suggest'=>'_nounicode',
				'host'=>array('value'=>'tw_url_shortener_tinyarrow_host'),
				'url'=>'%RAWURL%'
			)
		),
		'tanin'=>array(
			'label'=>'tanin.nl',
			'method'=>'GET',
			'url'=>'http://tanin.nl/s/api-create.php',
			'enabled'=>'false',
			'params'=>array(
				'u'=>'%URL%'
			)
		),
		'slly'=>array(
			'label'=>'sl.ly',
			'method'=>'GET',
			'url'=>'http://sl.ly/',
			'enabled'=>'false',
			'params'=>array(
				'module'=>'ShortURL',
				'file'=>'Add',
				'mode'=>'API',
				'url'=>'%RAWURL%'
			)
		)
	);

	/**
	* Auto-tweet provider URL.
	*/
	var $wptbsrv = 'http://wptbsrv.0xtc.com/a/';
	var $settingsuri = 'options-general.php?page=wp-tweet-button.php';

	/**
	* This function is used during development to output debug data.
	*/
	function decho ($var_name,$thingie){
		if ($this->tw_get_option('tw_debug') != '1') return false;
		echo '<!-- '.$var_name.' = ';
		if (is_array($thingie)){
			print_r ($thingie);
		} else {
			echo $thingie;
		}
		echo ' -->'."\r\n";
	}

	function tw_get_activepost(){
		$activepost = null;
		if(is_int($this->postid)) {
			$activepost = get_post($this->postid);
		} else {
			global $post;
			$activepost = $post;
		}

		return $activepost;
	}
	
	/**
	* This function isn't actually used but is left here for historical reasons. Yeah, that's it...historical reasons.
	*/
	function init() {
		$this->tw_readoptions();
	}
	
	/**
	* Validates and reads configuration into class.
	*/
	function tw_readoptions() {
		$values = $this->options;
		$twOptions = get_option($this->optionsname);
		if (!empty($twOptions)) {
			foreach ($twOptions as $key => $option) $values[$key] = $option;	
		} elseif ($values['tw_config_ver']!='1') {
			$bool_options = $this->boolops;
			if (get_option('tw_count')){
				foreach( $values as $key => $value ) {
					if($tmpvar = (string)get_option($key)){
						$values[$key] = get_option($key);
					}
				}
				foreach($bool_options as $key) {
					if (get_option($key)!='1'){
						update_option($bool_options[$key],'0');
						$values[$key] = '0';
					}
				}
				foreach( $values as $key => $value ) {
					delete_option($key);
				}
				$values['tw_config_ver']='1';
			}
		}
		$this->options=$values;
		$this->tw_writeoptions();
		return $values;
	}

	/**
	* Commits options to database.
	*/
	function tw_writeoptions(){
		update_option($this->optionsname, $this->options);
	}
	
	/**
	* This function returns individual settings during runtime
	*/
	function tw_get_option($optionname){
		return $this->options[$optionname];
	}

	/**
	* This function sets individual settings during runtime 
	*/
	function tw_set_option($optionname=null, $value=null){
		$this->options[$optionname]=$value;
	}

	/**
	* Validate and write settings.
	*/
	function tw_updateoptions() {
		if( isset($_POST['tw_where']) ) {
			$new_options = $_POST;
			$bool_options = $this->boolops;
			foreach($bool_options as $key) {
				$new_options[$key] = $new_options[$key] ? '1' : '';
			}
			$new_options['tw_via'] = $this->tw_sanitize_username($new_options['tw_via']);
			$new_options['tw_rec'] = $this->tw_sanitize_username($new_options['tw_rec']);
			$new_options['tw_ga_code'] = trim($new_options['tw_ga_code']);
			foreach($this->options as $key => $option) {
				$this->options[$key] = $new_options[$key];
			}			
			$this->tw_writeoptions();
			return true;
		}
		return false;
	}
	
	/**
	* Clear cached shortURLs if requested. 
	*/
	function tw_flush_cached_shortlinks(){
		if ($_POST['tw_flush_cached_shortlinks']=='1'){
			$allposts = get_posts('numberposts=-1&post_type=post&post_status=any');
			foreach( $allposts as $postinfo) {
				delete_post_meta($postinfo->ID, '_twitterrelated_short_url');
				delete_post_meta($postinfo->ID, '_twitterrelated_short_urlHash');
				delete_post_meta($postinfo->ID, '_activeshortener');
			}
			return 'fs';
		}
	}
	
	/**
	* Clear page cache if requested 
	*/
	function tw_flush_cache(){
		$msg = '';
		if ($_POST['tw_flush_cache']=='1'){
			if (function_exists('w3tc_pgcache_flush')) {
				w3tc_pgcache_flush();
				return 'w3';
			} else if (function_exists('wp_cache_clear_cache')) {
				wp_cache_clear_cache();
				return 'sc';
			}
		}
		return false;
	}

	/**
	* Find the author's twitter name from various settings. 
	*/
	function tw_get_twitter_name(){
		$activepost = $this->tw_get_activepost();
		$viauser=false;
		if (function_exists('get_user_meta')){
			if (get_user_meta($activepost->post_author, 'twitter',true) !='' && $this->tw_get_option('tw_via_authors')!='1'){
				$viauser = get_user_meta($activepost->post_author, 'twitter',true);
			} else {
				$viauser = $this->tw_get_option('tw_via');
			}
		} else {
			$viauser = $this->tw_get_option('tw_via');
		}
		return $viauser;
	}

	/**
	* This function decides the format of the tweet text based on preferences and limitations.
	*/
	function tw_get_text($entitydecode=false,$dots=true){	
		$activepost = $this->tw_get_activepost();
		if (get_post_meta($activepost->ID, '_twitterrelated_custom_text', true)){
			if ($entitydecode){
				$button_data_text =  html_entity_decode($this->tw_preptext(get_post_meta($activepost->ID, '_twitterrelated_custom_text', true)));
			} else {
				$button_data_text =  $this->tw_preptext(get_post_meta($activepost->ID, '_twitterrelated_custom_text', true));
			}
		} else {
			$tw_text = $this->tw_get_option('tw_text');
			
			if ($this->tw_get_option('tw_urlencode')=='1'){
				if ($tw_text=='entry_title') { $button_data_text =$activepost->post_title;}
				if ($tw_text=='page_title') { $button_data_text = $activepost->post_title .' - '. get_bloginfo('name');}
			} else {
				if ($tw_text=='entry_title') { $button_data_text = get_the_title($activepost->ID);}
				if ($tw_text=='page_title') { $button_data_text = get_the_title($activepost->ID) .' - '. get_bloginfo('name');}
			}
			if ($tw_text=='blog_title')	 { $button_data_text = get_bloginfo('name');}
			if ($tw_text=='custom_title') { $button_data_text = $this->tw_preptext(stripslashes($this->tw_get_option('tw_text_custom')));}
			$button_data_text = trim ($button_data_text);
			$maxlen = 140;
			$maxlen = $maxlen - strlen($this->tw_get_option('tw_via'));		// username
			$maxlen = $maxlen - 1;											// @
			$maxlen = $maxlen - 1;											// [space]			
			if ($this->tw_get_option('tw_text_format')=='VIA') $maxlen = $maxlen - 3;
			if ($this->tw_get_option('tw_text_format')=='RT') $maxlen = $maxlen - 2;
			$maxlen = $maxlen - 1;											// [space]
			if ($dots) $maxlen = $maxlen - 19;								// link
			if ($dots) $maxlen = $maxlen - 1;								// [space]
			$n_button_data_text = $this->tw_clean_trim ($button_data_text,$maxlen, $dots);
			$button_data_text = $n_button_data_text;
		}
		return $button_data_text;
	}
	
	
	
	/**
	* Applies formatting and transformations to texts. 
	*/	
	function tw_clean_trim($text,$len,$dots=true){
			if (strlen($text)<=$len){return $text;}
			if ($dots) {$spacing = 4;}else{$spacing=0;}
			$string = wordwrap(trim($text), $len - $spacing);
			$string = substr($string, 0, strpos($string, "\n"));			
			if ($dots && (strlen($string)<=(strlen($text)+$spacing))){
				$string .= '... ';
			}
			return $string;

	}
	/**
	* Applies formatting and transformations to texts. 
	*/	
	function tw_preptext($text){
		$activepost = $this->tw_get_activepost();
		$tmptxt = null;
		$tmptxt= str_replace('%POSTCONTENT%', strip_tags($activepost->post_content), $text);
		if ($this->tw_get_option('tw_urlencode')=='1'){
			$tmptxt= str_replace('%POSTTITLE%', $activepost->post_title, $tmptxt);
		} else {
			$tmptxt= str_replace('%POSTTITLE%', get_the_title($activepost->ID), $tmptxt);
		}
		$tmptxt= str_replace('%BLOGTITLE%', get_bloginfo('name'), $tmptxt);
		$tmptxt= str_replace('%BLOGHASHTAGS%', $this->tw_get_hash_tags(), $tmptxt);
		$tmptxt= str_replace('%BLOGHASHCATS%', $this->tw_get_hash_cats(), $tmptxt);
		return $tmptxt;
	}

	/**
	* Adds relational tag "me" to header. 
	*/
	function tw_add_rel_me(){
		echo '<link rel="me" href="http://twitter.com/'.$this->tw_get_twitter_name().'" />';
	}
	
	/**
	* Adds relational tag "shortlink" to header. 
	*/
	function tw_add_rel_shortlink(){
		if (is_single() ==false && is_page() == false){return false;}
		if (is_home()){
			$url =  home_url();
		} else {
			$url = $this->tw_get_short_url();
		}
		echo '<link rel="shortlink" href="'.$url.'" />';
	}
	
	/**
	* Used once to hook up actions. 
	*/
	function tw_hook_up_actions (){
			add_action('admin_menu', 			array(&$this, 'tw_options'));
			add_action('admin_init', 			array(&$this, 'tw_init'));
			add_action('admin_menu', 			array(&$this, 'tw_tweet_button_post_options_box'));

			add_action('new_to_publish', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('new_to_future', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('new_to_draft',			array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('future_to_draft', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('future_to_future', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('draft_to_future', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('draft_to_draft', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('draft_to_publish', 		array(&$this, 'tw_post_tweet_button_box_process'));
			add_action('pending_to_publish', 	array(&$this, 'tw_post_tweet_button_box_process'));

			add_action('publish_post', 			array(&$this, 'tw_post_tweet_button_box_process'));			
			add_action('publish_page', 			array(&$this, 'tw_post_tweet_button_box_process'));

			if ($this->tw_get_option('tw_auto_tweet_posts_onsave')=='1' || $this->tw_get_option('tw_auto_tweet_pages_onsave')=='1'){
				add_action('publish_post',				array(&$this, 'tw_send_auto_tweet'),100,1);
				add_action('xmlrpc_publish_post',		array(&$this, 'tw_send_auto_tweet'),100,1);
				add_action('publish_page',				array(&$this, 'tw_send_auto_tweet'),100,1);
			}

			if ($this->tw_get_option('tw_auto_tweet_posts')=='1' || $this->tw_get_option('tw_auto_tweet_pages')=='1'){
				add_action('new_to_publish',	array(&$this, 'tw_send_auto_tweet'),100,1);
				add_action('draft_to_publish',	array(&$this, 'tw_send_auto_tweet'),100,1);
				add_action('pending_to_publish',array(&$this, 'tw_send_auto_tweet'),100,1);
			}

			add_action('profile_update', 		array(&$this, 'tw_account_cleanup'));
			

			
	}
	
	/**
	* The function that runs the show.
	*/
	function wpTweetButton(){
		$this->options = $this->tw_readoptions();
		$this->shortenerdata = apply_filters('wp_tweet_button_shortenerdata', $this->shortenerdata);
		$this->options = apply_filters('wp_tweet_button_options', $this->options);
		if (($this->tw_get_option('tw_auto_tweet_posts')=='1') || ($this->tw_get_option('tw_auto_tweet_pages')=='1')){
			add_action('future_to_publish',	array(&$this, 'tw_send_auto_tweet'),100,1);
		}
		if (is_admin()){
			$this->tw_hook_up_actions();
			$uri=null;
			if($this->tw_updateoptions()){
				$uri='&op1=1';
			}
			$tfcr = $this->tw_flush_cache();
			if  ($tfcr=='w3'){$uri .='&op2=w3';}
			if  ($tfcr=='sc'){$uri .='&op2=sc';}

			$tfcr = $this->tw_flush_cached_shortlinks();
			if  ($tfcr=='fs'){$uri .='&op3=fs';}

			if (function_exists('filter_input')){
				$t1 = filter_input(INPUT_GET, 't1', FILTER_SANITIZE_STRING);
				$t2 = filter_input(INPUT_GET, 't2', FILTER_SANITIZE_STRING);
				$t3 = filter_input(INPUT_GET, 't3', FILTER_SANITIZE_STRING);
				$oauth = filter_input(INPUT_GET, 'oauth', FILTER_SANITIZE_STRING);
				$goauth = filter_input(INPUT_GET, 'goauth', FILTER_SANITIZE_STRING);
				$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
			} else {
				$t1 = $_GET['t1'];
				$t2 = $_GET['t2'];
				$t3 = $_GET['t3'];
				$oauth = $_GET['oauth'];
				$goauth = $_GET['goauth'];
				$token = $_GET['token'];
			}
			if ($t1 != '' & $t2 !=''){
				$this->tw_set_option('tw_auto_tweet_token',$t1);
				$this->tw_set_option('tw_auto_tweet_token_secret',$t2);
				$this->tw_set_option('tw_auto_tweet_via',$t3);
				$this->tw_writeoptions();
				$uri .= '&op2=oauthok';
			}
			if ($oauth=='delete'){
				$this->tw_set_option('tw_auto_tweet_token','');
				$this->tw_set_option('tw_auto_tweet_token_secret','');
				$this->tw_set_option('tw_auto_tweet_pages','');
				$this->tw_set_option('tw_auto_tweet_posts','');
				$this->tw_set_option('tw_auto_tweet_via','');
				$this->tw_writeoptions();
				$uri .= '&op2=oauthdel';
			}

			if ($token !='' && function_exists('json_decode')){
					$gtone_time_token = $token;
        			$gtheaders = array( 
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Authorization' => 'AuthSub token="' . $gtone_time_token . '"'
					);
				$gtbody = '';
				$gtparams = array('method' => 'GET', 'body' => $gtbody, 'headers' => $gtheaders);
				$gthttp = new WP_Http();
				$gtresult = $gthttp->request('https://www.google.com/accounts/AuthSubSessionToken', $gtparams);
				$matches = array();
				preg_match('/Token=(.+)/', $gtresult[ 'body' ], $matches );
				if (count($matches) > 1) {
					$wpgoogl_token = $matches[ 1 ];
					$this->tw_set_option('tw_url_shortener_google_token', $wpgoogl_token);
					$this->tw_writeoptions();
				}
				$uri .= '&op2=goauthok';
			}
			
			if ($goauth=='delete' && function_exists('json_decode')){
				$gheaders = array( 
					'Content-Type' => 'application/x-www-form-urlencoded',
					'Authorization' => 'AuthSub token="' . $this->tw_get_option( 'tw_url_shortener_google_token' ) . '"'
				);
				$gbody = '';
				$gparams = array( 'method' => 'GET', 'body' => $body, 'headers' => $headers );
				$ghttp = new WP_Http();
				$gresult = $ghttp->request('https://www.google.com/accounts/AuthSubRevokeToken', $gparams );
				$this->tw_set_option('tw_url_shortener_google_token','');
				$this->tw_writeoptions();
				$uri .= '&op2=goauthdel';
			}
			
			if ($uri){
				header('location: '.$this->settingsuri.$uri);
				die;
			}
		} else {
			if ($this->tw_get_option('tw_use_rel_me') == '1' && $this->tw_get_twitter_name() !='') {
				add_action('wp_head', array(&$this, 'tw_add_rel_me'));
			}
			if ($this->tw_get_option('tw_add_rel_shortlink') == '1') {
				if (!is_archive() && !is_front_page() && !is_home() && !is_search() && !is_tag() && !is_category() && !is_author() && !is_date()){

					remove_action(	'wp_head', 					'wp_shortlink_wp_head', 10, 0 );
					add_action(		'wp_head', array(&$this, 	'tw_add_rel_shortlink'));
				}
				
				if (($this->tw_get_option('tw_url_shortener') != 'wordpress')){
					add_filter('pre_get_shortlink', array(&$this, 'tw_get_short_url_checked'),3);
				}
			}
		}
		add_filter('the_title', array(&$this,'tw_set_placement_ready'), 9);
		add_filter('the_content', array(&$this, 'tw_update'),$this->tw_get_option('tw_hook_prio'));
		add_filter('get_the_excerpt', array(&$this,'tw_enter_excerpt'), 1);
		add_filter('get_the_excerpt', array(&$this,'tw_exit_excerpt'), 9999);

		// add_filter('get_the_excerpt', array(&$this, 'tw_remove_filter'), 9);
		// ----------------------
		if ($this->tw_get_option('tw_display_excerpt') == '1') add_filter('the_excerpt', array(&$this, 'tw_update'), $this->tw_get_option('tw_hook_prio'));
		add_action('init', array(&$this, 'tw_add_script'));
		if (function_exists('get_user_meta')){
			add_filter('user_contactmethods',array(&$this, 'tw_add_twitter_contactmethod'),10,1);
		}
		if (function_exists('wp_get_shortlink') && ($this->tw_get_option('tw_url_shortener') == 'wordpress')) {
			if ($this->tw_get_option('tw_no_wporg')=='1') remove_filter('get_shortlink', 'wpme_get_shortlink_handler', 10, 4);
			if ($this->tw_get_option('tw_strip_www')=='1') add_filter('get_shortlink',array(&$this,'tw_strip_www'),9,1);
		}
	}

	function tw_get_short_url_checked (){
		if (is_page() || is_single()) {
			return $this->tw_get_short_url();
		}
	}
	
	/**
	* This is called if a post is in excerpt mode.  It sets $this->excerptState to
	* true, so we can test for it later.
	*/
	function tw_enter_excerpt($the_excerpt) {
		$this->excerptState = true;
		return $the_excerpt;
	}
	
	function tw_exit_excerpt($the_excerpt) {
		$this->excerptState = false;
		return $the_excerpt;
	}
	
	/**
	* This writes a twitter username to the user's settings.
	*/
	function tw_account_cleanup($user_id) {
		$twitter_username = $_POST['twitter'];
		$twitter_username =  $this->tw_sanitize_username($twitter_username);
		if (function_exists('update_user_meta')){
			update_user_meta($user_id, 'twitter', $twitter_username);
		}	else {
			update_usermeta($user_id, 'twitter', $twitter_username);
		}
	}

	
	/**
	* This function sets the readyState when the title is displayed.
	* It prevents that the tweet button is rendered in the header area.
	*/
	function tw_set_placement_ready($title){
		$this->readyState=true;
		return $title;
	}

	/**
	* Deprecated function that removed content filter and added kit again at a later time 
	*/
	function tw_remove_filter($content) {
		remove_action('the_content', array(&$this, 'tw_update'),$this->tw_get_option('tw_hook_prio'));
		add_filter('the_content', array(&$this, 'tw_add_content_filter'),$this->tw_get_option('tw_hook_prio'));
		return $content;
	}

	/**
	* Deprecated function that added content filter
	*/
	function tw_add_content_filter ($content){
		add_filter('the_content', array(&$this, 'tw_update'),$this->tw_get_option('tw_hook_prio'));
		return $content;
	}

	/**
	* Function returns a list of hashtags (#one #two #three) based on post tags.
	*/
	function tw_get_hash_tags(){
		$textsuff = null;
		$posttags = get_the_tags();
		if ($posttags) {
			foreach($posttags as $tag) {
					$textsuff .= htmlspecialchars(' #'.$tag->slug);
			}
		}
		return trim(str_replace('-','',$textsuff));
	}

	/**
	* Function returns a list of category hash tags (#one #two #three) based on post categories.
	*/
	function tw_get_hash_cats(){
		$textsuff = null;
		$posttags = get_the_category();
		if ($posttags) {
			foreach($posttags as $tag) {
					$textsuff .= htmlspecialchars(' #'.$tag->slug);
			}
		}
		return trim(str_replace('-','',$textsuff));
	}

	/**
	* Universal function for gettings the URL of a post.
	*/
	function tw_get_the_url($bwdata=false){
		$shortener = $this->tw_get_option('tw_url_shortener');
		if ($shortener != 'none' && $shortener != 'awesm' && $shortener != ''){
			$url=$this->tw_get_short_url();
		} else {
			$url = $this->tw_get_long_url($bwdata);
		}
		return $url;
	}

	/**
	* Function returns the post's relationship with a twitter account.
	*/
	function tw_get_related_text($urenc=false){
		$activepost = $this->tw_get_activepost();
		$text = null;
		// check for default
		if ($this->tw_get_option('tw_rec')) {
			$text = $this->tw_get_option('tw_rec');
			if ($this->tw_get_option('tw_rec_desc')) {
				$text .= ':' . (($urenc) ? urlencode($this->tw_get_option('tw_rec_desc')) : $this->tw_get_option('tw_rec_desc'));
			}
		}
		
		// check for user settings
		if ($this->tw_get_option('tw_rec_authors')=='1' && function_exists('get_user_meta')) {
			if (get_user_meta($activepost->post_author, 'twitter',true) !=''){
				$text = get_user_meta($activepost->post_author, 'twitter',true);
			}
		}

		// check for post's settings
		if  (get_post_meta($activepost->ID, '_twitterrelated', true)) {
				$text = ($urenc) ? urlencode(get_post_meta($activepost->ID, '_twitterrelated', true)) : get_post_meta($activepost->ID, '_twitterrelated', true);
				$text = str_replace('%2C','%E2%80%9A',$text);
				$text = str_replace('+','%20',$text);
				$text = str_replace('%3A',':',$text);
		} elseif 
			(get_post_meta($activepost->ID, 'twitterrelated', true)){
				$text = ($urenc) ? urlencode(get_post_meta($activepost->ID, 'twitterrelated', true)) : get_post_meta($activepost->ID, 'twitterrelated', true);
		}
		return $text;
	}
	
	/**
	* Function returns attributes for HTML5 based buttons
	*/
	function tw_build_options_data() {
		$activepost = $this->tw_get_activepost();		
		$textprefix = null;
		$button_data_via=null;
		$button_data_status_id = null;
		$theurl = $this->tw_get_the_url(true);
		$button_data_url = 'data-url="' . $theurl . '"';
		$viauser = $this->tw_get_twitter_name();
		if ($viauser){
			if ($this->tw_get_option('tw_text_format')=='VIA' || $this->tw_get_option('tw_use_rel_me')=='1') {
				$button_data_via = 'data-via="' . $viauser.'"';
			} elseif ($this->tw_get_option('tw_text_format')=='RT') {
				$textprefix = 'RT @'.$viauser.' ';
			}
		}
		$tdata = get_post_meta($activepost->ID, '_tw_autotweeted',true);
		if ($tdata !=''){
			$tdata = explode(':',$tdata );
			$tid = $tdata[0];
			$tname = $tdata[1];
// Secret future Twitter feature goes here.
//			$button_data_status_id = 'data-status-id="'.$tid.'"';
		}
		$thetext = htmlspecialchars($textprefix . $this->tw_get_text(null));
		$button_data_text = 'data-text="'.$thetext.'"';
		$button_data_related = 'data-related="'.$this->tw_get_related_text().'"';
		$button_data_count = 'data-count="'.$this->tw_get_option('tw_count').'"';
		$button_data_lang = 'data-lang="'.$this->tw_get_option('tw_lang').'"';
		if ($this->tw_get_option('tw_url_shortener')=='awesm' && $this->tw_get_option('tw_url_shortener_awesm_key') != null){
			$button_data_awesmkey = 'data-awesm-key="'.$this->tw_get_option('tw_url_shortener_awesm_key').'"';
		} else {
			$button_data_awesmkey = null;
		}
		if ($this->tw_get_option('tw_url_samecount')=='1'){
			$button_data_counturl = 'data-counturl="'.$this->tw_get_long_url(false, false). '"';
		} else {
			$button_data_counturl=null;
		}
		$anchordata = $button_data_url." ".$button_data_related." ".$button_data_via." ".$button_data_text." ".$button_data_lang." ".$button_data_count." ".$button_data_awesmkey." ".$button_data_counturl." ".$button_data_status_id;
		return $anchordata;	
	}
	
	/**
	* Function returns url parameters for legacy buttons
	*/
	function tw_build_options($bwdata=false) {
		$textprefix =null;
		if ($bwdata==true && !is_feed()){return false;}
		$viauser = $this->tw_get_twitter_name();
		$theurl = $this->tw_get_the_url(true);
		$button = '?url=' . urlencode($theurl);
		if ($viauser){
			if ($this->tw_get_option('tw_text_format')=='VIA' || $this->tw_get_option('tw_use_rel_me')=='1') {
				$button .= '&amp;via=' . $viauser;
			} elseif ($this->tw_get_option('tw_text_format')=='RT') {
				$textprefix = 'RT @'.$viauser.' ';
			}
		}
		$thetext = urlencode($textprefix . $this->tw_get_text(true));
		$button .= '&amp;text='.str_replace(array('+',' '),'%20',$thetext);
		$button .= '&amp;related='.$this->tw_get_related_text(true);
		$button .= '&amp;lang='.$this->tw_get_option('tw_lang');
		$button .= '&amp;count='.$this->tw_get_option('tw_count');
		if ($this->tw_get_option('tw_url_shortener')=='awesm' && $this->tw_get_option('tw_url_shortener_awesm_key') != null){
			$button .= '&amp;awesmapikey='.$this->tw_get_option('tw_url_shortener_awesm_key');
		}
		if ($this->tw_get_option('tw_url_samecount')=='1'){
			$button .= '&amp;counturl='.urlencode($this->tw_get_long_url(false, false));
		} 
		return $button;
	}

	/**
	* Function is an HTTP tool for requesting HTML.
	*/
	function tw_nav_browse($url, $use_POST_method = false, $POST_data = null) {
		if (!$url){return false;}
		if(function_exists('wp_remote_request') && function_exists('wp_remote_retrieve_response_code') && function_exists('wp_remote_retrieve_body') && $use_POST_method == 'GET') {
			if($use_POST_method == 'POST') {
				$request_params = array('method' => 'POST', 'body' => $POST_data,'timeout'=>15);
			} else {
				$request_params = array('method' => 'GET');
			}
			$url_request = wp_remote_request($url, $request_params);
			$url_response = wp_remote_retrieve_response_code($url_request);
			if($url_response == 200 || $url_response == '200'){
				$source = wp_remote_retrieve_body($url_request);
				if ($url_request['headers']['content-encoding']=='deflate'){
					$source = wp_remote_retrieve_body($url_request);
				} else {
					$source = wp_remote_retrieve_body($url_request);
				}
			} else {
			  $source = '';
			}
		} elseif (function_exists('curl_init') && function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			if($use_POST_method == 'POST'){
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_data);
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			$source = trim(curl_exec($ch));
			if ( curl_errno($ch) != 0 ) {
			  $source = '';
			}
			curl_close($ch);
		} else {
			$source = '';
		}
		return $source;
	}

	/**
	* Function returns the "long" url for a post.
	*/
	//	hook: wp_tweet_button_long_url
	function tw_get_long_url($addtrack=false, $addlang=true) {
		global $my_transposh_plugin;
		if (!in_the_loop() && is_home()){return home_url();}
		$activepost = $this->tw_get_activepost();
		$perms=null;
		if (empty($activepost->post_title)) {
			$perms= esc_url('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		} else {
			$perms = trim(get_permalink($activepost->ID));
		}
		
		if (is_object($my_transposh_plugin) && $addlang){
			if ($my_transposh_plugin->target_language) {
				$perms= transposh_utils::rewrite_url_lang_param($perms, $my_transposh_plugin->home_url, $my_transposh_plugin->enable_permalinks_rewrite, $my_transposh_plugin->target_language, $my_transposh_plugin->edit_mode);
			}
		}
			if ($addtrack && $this->tw_get_option('tw_ga_code') && $this->tw_get_option('tw_enable_ga_code')==1) {
			if (strstr($perms,'?')){$prestr='&';} else {$prestr='?';}
			if ($this->tw_get_option('tw_ga_setallowanchor')==1) {$prestr='#';}
			$tmptxt= str_replace('?', '', $this->tw_get_option('tw_ga_code'));
			$tmptxt= str_replace('+', '', $tmptxt);
			$tmptxt= str_replace('%POSTID%', $activepost->ID, $tmptxt);
			$tmptxt= str_replace('%POSTSLUG%', $activepost->post_name, $tmptxt);
			$tmptxt= str_replace('%2B', '', $tmptxt);
			$tmptxt= str_replace('%20', '', $tmptxt);
			$perms = $perms . $prestr . $tmptxt;
		}
		
		$perms = apply_filters('wp_tweet_button_long_url', $perms);
		if (strstr($perms,admin_url())){return false;}

		return $perms;
	}

	/**
	* Function strips www. prefix from URLs.
	*/
	function tw_strip_www($content){
		$str = str_replace('http://www.','http://',$content);
		$str = str_replace('https://www.','https://',$str);
		return $str;
	}
	
	/**
	* Function returns the "short" url for a post (or not...).
	*/
	// hook: wp_tweet_button_url
	function tw_get_short_url() {
		global $my_transposh_plugin;
		$activepost = $this->tw_get_activepost();		
		if (!in_the_loop() && is_home()){return home_url();}
		$perms = $this->tw_get_long_url(true);
		if ($perms == false) return false;
		if (strstr($_SERVER['REQUEST_URI'],'&preview=true')) return $perms;
		if (strstr($perms,'https://') && $this->tw_get_option('tw_no_https_shortlinks')=='1') return $perms;
		if (strstr($perms,'preview=true')) return $perms;
		if (is_404()) return $perms;
		$selectedshortener=$this->tw_get_option('tw_url_shortener');
		if (
			($selectedshortener == 'none')) {
			return $perms;
		}
		if ($selectedshortener == 'google' && function_exists('json_decode')){
			$googleok = true;
			$apiKey = $this->tw_get_option('tw_url_shortener_google_key');
			$apiauth = $this->tw_get_option('tw_url_shortener_google_token');
			if ($apiauth=='')$apiauth=null;
			if (!class_exists('Googl')){
				require ('inc/googl.class.php');
			}
			if (class_exists('Googl')){
				$tehgoogle = null;
				$tehgoogle = new Googl($apiKey, $apiauth);
			}
		}
		if ($selectedshortener == 'tflp' && function_exists('permalink_to_twitter_link')) {
			$fetch_url = permalink_to_twitter_link($perms);
		}
		if ($selectedshortener == 'yourls' && function_exists('wp_ozh_yourls_raw_url')) {
			$fetch_url = wp_ozh_yourls_raw_url();
		}
		if (function_exists('wp_get_shortlink') && ($selectedshortener == 'wordpress')){
			$fetch_url = wp_get_shortlink($activepost->ID);
		}
		if(!empty($fetch_url)){
			return $fetch_url;
		}
		$paramsarray = $this->shortenerdata[$selectedshortener]['params'];
		if (is_array($paramsarray)){
			$method = 'GET';
			$POST_data = array(); 
			$paramstr = '';
			foreach ($paramsarray as $name=>$value){
				if (is_array($value)){
					if (isset($value['value'])){
						$tmp1 = $this->shortenerdata[$selectedshortener]['params'][$name]; //=$this->tw_get_option($value['value']);
						$tmp1 = $this->tw_get_option($value['value']);
					}
				} else {
						$tmp = str_replace('%URL%',urlencode($perms),$this->shortenerdata[$selectedshortener]['params'][$name]);
						$tmp = str_replace('%RAWURL%',rawurlencode($perms),$tmp);
						$tmp1=$tmp;
				}
				$paramstr .= $name.'='.$tmp1.'&';
			}

			$paramstr = substr($paramstr,0, -1);
			$method = $this->shortenerdata[$selectedshortener]['method'];
			if ($method=='POST'){
				$POST_data = $this->shortenerdata[$selectedshortener]['params'];
				$request_url = $this->shortenerdata[$selectedshortener]['url'];
			} else {
				$request_url = $this->shortenerdata[$selectedshortener]['url'] . '?'.$paramstr;
			}

			if (is_object($my_transposh_plugin)){
				if ($my_transposh_plugin->target_language) {
					$langext = null;
					$langext = $my_transposh_plugin->target_language;
					$langarr = get_post_meta($activepost->ID, '_twitterrelated_short_url_lang',true);
					if (!is_array($langarr)) $langarr =  array();
					if (
					//	($selectedshortener.'_'.$langext != get_post_meta($activepost->ID, '_activeshortener',true)) || 
						(md5($perms. $selectedshortener) != $langarr[$langext]['hash'])) {
						if ($langext){
							if (!(strstr($request_url,'http://' . $_SERVER['SERVER_NAME'])) && ($request_url != '')){
								$fetch_url = trim($this->tw_nav_browse($request_url, $method, $POST_data));
							}
							$langarr[$langext]['url'] = $fetch_url;
							$langarr[$langext]['hash'] = md5($perms. $selectedshortener);
							if ( !empty( $fetch_url ) && ($fetch_url != $perms) && strstr($fetch_url,'http://') && (!strstr($perms,'preview=true'))) {
								if (!update_post_meta($activepost->ID, '_activeshortener', $selectedshortener . '_'. $langext)) {
									add_post_meta($activepost->ID, '_activeshortener', $selectedshortener . '_'. $langext);
								}
								if (!update_post_meta($activepost->ID, '_twitterrelated_short_url_lang', $langarr)) {
									add_post_meta($activepost->ID, '_twitterrelated_short_url_lang', $langarr);
								}
							}
						}
					} else {
						$fetch_url = $langarr[$langext]['url'];
						if ($fetch_url ==''){
							$fetch_url = $perms;
						}
					}
				}
			} else {
				if (($selectedshortener != get_post_meta($activepost->ID, '_activeshortener',true)) || 
					(md5(get_post_meta($activepost->ID, '_twitterrelated_short_url',true)) != get_post_meta($activepost->ID, '_twitterrelated_short_urlHash',true))) {
					if (!(strstr($request_url,'http://' . $_SERVER['SERVER_NAME'])) && ($request_url != '')){
							if ($googleok==true){
								$fetched_url = $tehgoogle->shorten($perms);
								if ($fetched_url) {$fetch_url = $fetched_url;}
							} else {
								$fetch_url = trim($this->tw_nav_browse($request_url, $method, $POST_data));
							}
					}
					if ( !empty( $fetch_url ) && ($fetch_url != $perms) && strstr($fetch_url,'http://') && (!strstr($perms,'preview=true'))) {
						if (!update_post_meta($activepost->ID, '_activeshortener', $selectedshortener )) {
							add_post_meta($activepost->ID, '_activeshortener', $selectedshortener);
						}
						if (!update_post_meta($activepost->ID, '_twitterrelated_short_url', $fetch_url)) {
							add_post_meta($activepost->ID, '_twitterrelated_short_url', $fetch_url);
						}
						if (!update_post_meta($activepost->ID, '_twitterrelated_short_urlHash', md5($fetch_url))) {
							add_post_meta($activepost->ID, '_twitterrelated_short_urlHash', md5($fetch_url));
						}
					}
				} else {
					$fetch_url = get_post_meta($activepost->ID, '_twitterrelated_short_url',true);
					if ($fetch_url ==''){
						$fetch_url = $perms;
					}
				}
			}
		}
		if ($fetch_url=='') $fetch_url=$perms;
		$fetch_url = apply_filters('wp_tweet_button_url', $fetch_url);
		return $fetch_url;
	}

	/**
	* Function generates a tweet button.
	* $bwdata can be used to return HTML5 (true) or not (false)
	*/
	// Hook : wp_tweet_button
	function tw_generate_button($bwdata=false) {
		$data=null;
		$tw_text = $this->tw_get_option('tw_btn_text');
		$alignstr = null;
		$relstr = null;
		if ($bwdata==true && !is_feed()){
			$data=$this->tw_build_options_data().' ';
		}
		if ($this->tw_get_option('tw_rel_meta')!=''){
			$relstr = 'rel="'.$this->tw_get_option('tw_rel_meta').'" ';
		}
		$alignment = $this->tw_get_option('tw_align');
		if ($alignment!='none' && $this->tw_get_option('tw_align')!=null && $alignment!=''){
			if ($alignment=='right') $alignstr = 'float:right;margin-left:10px;';
			if ($alignment=='left') $alignstr = 'float:left;margin-right:10px;';
			if ($alignment=='center') $alignstr = 'float:none;margin:0 auto;text-align:center;';
		}
		$dssep = null;
		$userstyle = $this->tw_get_option('tw_style_c');
		if ($userstyle!=''){
			if (substr($userstyle, -1)==';'){$dssep='';}else{$dssep=';';} 
		}
		if (($this->tw_get_option('tw_nostyle_feed') == '1') && (is_feed()=='1')){
			$StyleStrDiv = '';
			$StyleStrBtn = '';
		} else {
			$wpt_pluginpath = (empty($_SERVER['HTTPS'])) ? WP_PLUGIN_URL : str_replace("http://", "https://", WP_PLUGIN_URL);
			$StyleStrDiv = ' style="' . $this->tw_get_option('tw_style_c') .$dssep. $alignstr . '"';
			$StyleStrBtn = ' style="width:55px;height:22px;background:transparent url(\''. $wpt_pluginpath.'/wp-tweet-button/tweetn.png\') no-repeat  0 0;text-align:left;text-indent:-9999px;display:block;"';
		}
		if ($this->ismanual){$btnidattr='m';}else{$btnidattr='';}
		$button = 	'<div id="tweetbutton'.get_the_id().$btnidattr.'" class="tw_button"'.$StyleStrDiv.'>';
		$button .= 	'<a href="http://twitter.com/share'.$this->tw_build_options($bwdata).'" class="twitter-share-button" ' . $data . $relstr . $StyleStrBtn.'>'.$tw_text.'</a>';
		$button .= 	'</div>';
		$button = apply_filters( 'wp_tweet_button', $button );
		return $button;
	}
	
	/**
	* Function places button in content.
	*/
	function tw_update($content) {
		$this->ismanual=false;
		if ($this->hasbutton[$post->ID] == 1) return $content;
		 // because sometimes templates are written by monkeys running with knives.
		if ($this->tw_get_option('tw_prev_content_dup') == '1'){
			if (strstr($content,'id="tweetbutton'.get_the_id().'"')) return $content;
		}
		global $post;
		$this->postid = $post->ID;
		if (
			(!$this->readyState) ||
			($this->tw_get_option('tw_display_excerpt') == '' && $this->excerptState) 					||
			(get_post_meta($post->ID, '_exclude_tweet_button', true))									||
			($this->tw_get_option('tw_display_feed') == '' && is_feed())								||
			(($this->tw_get_option('tw_display_page') == '' && is_page()) && (get_post_meta($post->ID, 'forcetweetbutton', true)== ''))			||
			($this->tw_get_option('tw_display_front') == '' && (is_home() || is_front_page()))			||
			(($this->tw_get_option('tw_display_single') == '' && is_single()) && (get_post_meta($post->ID, 'forcetweetbutton', true) == ''))	||
			($this->tw_get_option('tw_display_single') == '' && (is_home() || is_front_page()))			||
			($this->tw_get_option('tw_display_archive') == '' && is_archive())							||
			($this->tw_get_option('tw_display_search') == '' && is_search())
		) {
			return $content;
		}
		$selectedPT = $this->tw_get_option('tw_post_type_exclude');
		if (is_array($selectedPT)){
			if (in_array(get_post_type($post->ID),$selectedPT)){
				return $content;
			}
		}
		if (function_exists("bnc_wptouch_is_mobile")) {		
			if (bnc_wptouch_is_mobile() && $this->tw_get_option('tw_display_mobile')==''){
					return $content;
			}
		}
		if (is_feed()){
			$button = $this->tw_generate_button();
		} else {
			$button = $this->tw_generate_button($this->tw_get_option('tw_bwdata_attr') || $this->tw_get_option('tw_url_shortener')=='tinyarrow');
		}
		$where = $this->tw_get_option('tw_where');
		if ($where == 'shortcode') {
			return str_replace('[tweetbutton]', $button, $content);
		} else if ($where == 'beforeandafter') {
			if (
					($this->tw_get_option('tw_ex_after_home') && (is_home() || is_front_page())) || 
					($this->tw_get_option('tw_ex_after_archive') && (is_archive()))
			) {
				$this->hasbutton[$post->ID] = 1;
				return $button . $content;
			}
			$this->hasbutton[$post->ID] = 1;
			return $button . $content . $button;
		} else if ($where == 'before') {
			$this->hasbutton[$post->ID] = 1;
			return $button . $content;
		} else if ($where == 'after') {
			$this->hasbutton[$post->ID] = 1;
			return $content . $button;
		} else {
			return $content;
		}
		return $content;
	}

	/**
	* Function returns a tweet button.
	*/
	function tweetbutton($post,$bwdata=false) {
		global $post;
		$this->ismanual = true;
//		if ($this->hasbutton[$post->ID.'m'] == 1) return false;
		$this->postid = $post->ID;
		if (isset($post->ID)){
			if (get_post_meta($post->ID, '_exclude_tweet_button', true)){
				return false;
			}
		}
		if ($this->tw_get_option('tw_where') == 'manual' || $this->tw_get_option('tw_force_manual') == '1') {
//			$this->hasbutton[$post->ID.'m'] = 1;
			return $this->tw_generate_button($bwdata);
		} else {
			return false;
		}
	}

	/**
	* Function places twitter script in your header or footer
	*/
	function tw_add_script() {
		if ($this->tw_get_option('tw_url_shortener')=='awesm') {
			wp_register_script('twitter-widgets','http://tools.awe.sm/tweet-button/files/widgets.js',array(),'1.1',($this->tw_get_option('tw_script_infooter') == '1'));
		} else {
			wp_register_script('twitter-widgets','http://platform.twitter.com/widgets.js',array(),'1.1',($this->tw_get_option('tw_script_infooter') == '1'));
		}
		wp_enqueue_script('twitter-widgets');
		if (is_admin()) {
			add_action('admin_head',array(&$this,'tw_drawcss_admin'));
		}
	}

	/**
	* Function outputs CSS for the settings page
	*/
	function tw_drawcss_admin(){
		echo '<style type="text/css">
			<![CDATA[ '."\r\n";
		require ('inc/admstyle.css');
		echo "\r\n".']]>'."\r\n".'</style>'."\r\n";
	}

	/**
	* Function returns settings page
	*/
	function tw_options_page(){
		require ('inc/settings.frame.inc.php');
	}

	/**
	* Function outputs the settings page contents
	*/
	function tw_options_box() {
		require ('inc/settings.inc.php');
	}

	/**
	* Function outputs the per-post settings dialog.
	*/	
	function tw_post_tweet_button_box() {
		require ('inc/perpostsettings.inc.php');
	}

	/**
	* Function saves per-post options
	*/
	function tw_post_tweet_button_box_process($post_ID) {
		$thepost = get_post($post_ID);
		$this->postid = $post_ID;
		if (!empty($_POST)){
			if ($_POST['tw_do_not_send_auto_tweet'] == '1') {
				add_post_meta($thepost->ID, '_tw_do_not_send_auto_tweet', 1, TRUE) or update_post_meta($thepost->ID, '_tw_do_not_send_auto_tweet', 1);
			} elseif ( $_POST['tw_do_not_send_auto_tweet'] == null ) {
				delete_post_meta($thepost->ID, '_tw_do_not_send_auto_tweet');
			}
			
			if ($_POST['tw_clear_short_cache_post'] == '1') {
				delete_post_meta($thepost->ID, '_twitterrelated_short_url');
				delete_post_meta($thepost->ID, '_twitterrelated_short_url_lang');
				delete_post_meta($thepost->ID, '_twitterrelated_short_urlHash');
				delete_post_meta($thepost->ID, '_activeshortener');
			}	

			if ($_POST['tw_exclude_tweet_button'] == '1') {
				add_post_meta($thepost->ID, '_exclude_tweet_button', 1, TRUE) or update_post_meta($thepost->ID, '_exclude_tweet_button', 1);
			} elseif ( $_POST['tw_exclude_tweet_button'] == null ) {
				delete_post_meta($thepost->ID, '_exclude_tweet_button');
			}	
			if ($_POST['tw_post_custom_text'] == null) {
				delete_post_meta($thepost->ID, '_twitterrelated_custom_text');
			} else {
				add_post_meta($thepost->ID, '_twitterrelated_custom_text', $_POST['tw_post_custom_text'], TRUE) or update_post_meta($thepost->ID, '_twitterrelated_custom_text', $_POST['tw_post_custom_text']);
			}
			if ($_POST['tw_twitter_related_user'] == null) {
				delete_post_meta($thepost->ID, '_twitterrelated_user');
			} else {
				add_post_meta($thepost->ID, '_twitterrelated_user', $this->tw_sanitize_username($_POST['tw_twitter_related_user']), TRUE) or update_post_meta($thepost->ID, '_twitterrelated_user', $this->tw_sanitize_username($_POST['tw_twitter_related_user']));
			}
			if ($_POST['tw_twitter_related_desc'] == null) {
				delete_post_meta($thepost->ID, '_twitterrelated_desc');
			} else {
				add_post_meta($thepost->ID, '_twitterrelated_desc', $_POST['tw_twitter_related_desc'], TRUE) or update_post_meta($thepost->ID, '_twitterrelated_desc', $_POST['tw_twitter_related_desc']);
			}
			if (($_POST['tw_twitter_related_desc'] != null) && ($_POST['tw_twitter_related_user'] != null)) {
				add_post_meta($thepost->ID, '_twitterrelated', $this->tw_sanitize_username($_POST['tw_twitter_related_user']).':'.$_POST['tw_twitter_related_desc'], TRUE) or update_post_meta($thepost->ID, '_twitterrelated', $this->tw_sanitize_username($_POST['tw_twitter_related_user']).':'.$_POST['tw_twitter_related_desc']);
			} else 	if (($_POST['tw_twitter_related_desc'] == null) && ($_POST['tw_twitter_related_user'] != null)) {
				add_post_meta($thepost->ID, '_twitterrelated', $this->tw_sanitize_username($_POST['tw_twitter_related_user']), TRUE) or update_post_meta($thepost->ID, '_twitterrelated', $this->tw_sanitize_username($_POST['tw_twitter_related_user']));
			} else {
				delete_post_meta($thepost->ID, '_twitterrelated');
			}
		}
	}

	/**
	* Function regulates and manages autotweeting
	*/
	function tw_send_auto_tweet($post_id){
		global $post_type_object;
		if(is_int($post_id)) {
			$this->postid = $post_id;
			$thepost = get_post($post_id);
		} else {
			$thepost = $post_id;
			$this->postid = $thepost->ID;
		}				
		if ($thepost->post_type == 'revision' || ($thepost->post_status != 'publish' && $thepost->post_status != 'future') || $thepost->post_password != '' ) return false;		
		if (
				(
					(($this->tw_get_option('tw_auto_tweet_posts') == '1' || $this->tw_get_option('tw_auto_tweet_posts_onsave') == '1') && $post_type_object->hierarchical=='') ||		// posts
					(($this->tw_get_option('tw_auto_tweet_pages') == '1' || $this->tw_get_option('tw_auto_tweet_pages_onsave') == '1') && $post_type_object->hierarchical=='1')			// pages
				) &&
			$this->tw_get_option('tw_auto_tweet_token') != '' &&
			$this->tw_get_option('tw_auto_tweet_token_secret') != '' &&
			(get_post_meta($thepost->ID, '_tw_do_not_send_auto_tweet',true) == '') &&
			($_POST['tw_do_not_send_auto_tweet'] == '') &&
			(get_post_meta($thepost->ID, '_tw_autotweeted',true) == '' || $_POST['tw_send_auto_tweet_again']==1)
		){
			$Post_data = array();
			$Post_data['r_oauth_token']			=	$this->tw_get_option('tw_auto_tweet_token');
			$Post_data['r_oauth_token_secret']	=	$this->tw_get_option('tw_auto_tweet_token_secret');
			if ($this->tw_get_option('tw_auto_long_tweet_mode')=='1'){
				$tweet_text							=	$this->tw_validate_tweet(trim(strip_tags($thepost->post_content)),$this->tw_get_the_url());
			} else {
				$tweet_text							=	$this->tw_validate_tweet($this->tw_get_text(null,false),$this->tw_get_the_url());
			}
			if ($tweet_text==false){return false;}

			$ctypes = array("ASCII", "UTF-8", "ISO-8859-15", "ISO-8859-1", "JIS", "EUC-JP");
			$enc = mb_detect_encoding($tweet_text,$ctypes);
			if ($enc!='UTF-8') $tweet_text = mb_convert_encoding($tweet_text,'UTF-8');
			$tweet_text_data					=	base64_encode($tweet_text);
			$Post_data['tweet_msg_data']		=	$tweet_text_data;
			$content = $this->tw_nav_browse($this->wptbsrv, 'POST', $Post_data);
			if (strstr($content,'error')==false && $content != '' && strstr($content,'<') == false) {
				add_post_meta($thepost->ID, '_tw_autotweeted', $content, TRUE) or update_post_meta($thepost->ID, '_tw_autotweeted', $content);
			} else {
				add_post_meta($thepost->ID, '_tw_autotweeted_errors', $content, TRUE) or update_post_meta($thepost->ID, '_tw_autotweeted_errors', $content);
			}
			return $content;
		}
		return false;
	}

	/**
	* Function validates, edits and optimizes tweet text for an autotweet
	*/
	function tw_validate_tweet($text, $url){
		// $tw_auto_tweet_strip = $this->tw_get_option('tw_auto_tweet_strip');
		if (strstr($url,admin_url())){return false;}
		if ($url==false){
			return false;
		}
		$max	= 140;
		$lurl 	= strlen($url);
		$ltext 	= strlen(trim($text));
		$maxtextlen = $max - 1 - $lurl;
		if (($lurl+4+$ltext) > $max){
			// The URL is longer than the maximum allowed tweet length. 
			if ($lurl>$max ){
				return false;
			}

			// The length of the URL is between 138 and 140 characters. We can't add a title, but we can use the URL.
			if (
				($lurl==$max ) || 
				( ($lurl>=$max-4) && ($lurl<=$max))
				){
				return $url;
			}

			$strmaxlen = $max - $lurl;
			$stext = $this->tw_clean_trim($text, $strmaxlen);
			
			// We shrunk the text and tries to fit it along with the URL but alas, the text disappeared in the shrinking process. We can still use the URL.
			if (strlen($stext)<=4){
				return $url;
			}

			// Yey! We can return the shrunk text AND the URL, no problem.
			if (strlen($stext.' '.$url)<=$max) {
				return $stext.' '. $url;
			}
			return false;
		} else {
			return $text.'... '.$url;
		}
	}

	/**
	* Function hooks per-post settings dialog
	*/
	function tw_tweet_button_post_options_box() {
		if ( version_compare(get_bloginfo('version'), '2.7', '>=')) {
			add_action('post_submitbox_start', array(&$this, 'tw_post_tweet_button_box'));
		} else {
			add_action('submitpost_box', array(&$this, 'tw_post_tweet_button_box'));
		}
	}

	/**
	* Function registers settings record
	*/
	function tw_init(){
		if(function_exists('register_setting')){
			register_setting('tw-options', 'wp_tweet_button');
		}
	}

	/**
	* Function cleans up returned twitter usernames
	*/
	function tw_sanitize_username($username){
		$username = str_replace(array('http://','https://','twitter.com/','twitter.com','@'),'',$username);
		return preg_replace('/[^A-Za-z0-9_]/','',$username);
	}

	/**
	* Function releases the Kraken.
	*/
	function tw_activate(){
		add_option('wp_tweet_button',array());
		$this->tw_readoptions();
	}

	/**
	* Function adds settings page to the menu.
	*/
	function tw_options() {
		add_options_page($this->pluginlabel, $this->pluginlabel, 8, basename(__FILE__), array(&$this, 'tw_options_page'));
	}

	/**
	* Function adds Twitter as a contact method in the Wordpress user settings.
	*/
	function tw_add_twitter_contactmethod($contactmethods) {
		$contactmethods['twitter'] = 'Twitter <span class="description">(username)</span>';
		return $contactmethods;
	}
}

/**
* Function manages manual tweetbutton() calls to generate a button.
*/
function tweetbutton($thepost='',$bwdata=false,$type='d'){
	global $wpTweetButton, $post;
	$thepost = is_null($post) ? $post : $thepost;
	$bwdata = is_null($bwdata) ? false : $bwdata;
	if ($type=='vertical' || $type=='horizontal' || $type=='none'){
		$wpTweetButton->tw_set_option('tw_count', $type);
	}
	return $wpTweetButton->tweetbutton($thepost,$bwdata);
}

/**
* Class, exciting and new. Create one or even a few. Bugs, a coder's reward. Ruins flow and it comes back to you. Wordpress, soon will be making another run and Wordpress promises something for everyone....
*/

$wpTweetButton = new wpTweetButton();
load_plugin_textdomain($wpTweetButton->txtdom,null,dirname( plugin_basename( __FILE__ ) ).'/lang/');
register_activation_hook( __FILE__, array(&$wpTweetButton, 'tw_activate'));
?>