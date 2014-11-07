<?php
/*
Plugin Name: Autolink URI
Plugin URI: http://www.semiologic.com/software/autolink-uri/
Description: RETIRED - Automatically wraps unhyperlinked uri with html anchors.
Version: 2.7.1
Author: Denis de Bernardy & Mike Koepke
Author URI: http://www.getsemiologic.com
Text Domain: sem-autolink-uri
Domain Path: /lang
License: Dual licensed under the MIT and GPLv2 licenses
*/

/*
Terms of use
------------

This software is copyright Denis de Bernardy & Mike Koepke, and is distributed under the terms of the MIT and GPLv2 licenses.
**/


/**
 * autolink_uri
 *
 * @package Autolink URI
 **/

class autolink_uri {
	/**
	 * Plugin instance.
	 *
	 * @see get_instance()
	 * @type object
	 */
	protected static $instance = NULL;

	/**
	 * URL to this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_url = '';

	/**
	 * Path to this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_path = '';

	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook plugins_loaded
	 * @return  object of this class
	 */
	public static function get_instance()
	{
		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 *
	 */

	public function __construct() {
		$this->plugin_url    = plugins_url( '/', __FILE__ );
		$this->plugin_path   = plugin_dir_path( __FILE__ );

		add_action( 'plugins_loaded', array ( $this, 'init' ) );
    }


	/**
	 * init()
	 *
	 * @return void
	 **/

	function init() {
		// more stuff: register actions and filters
        // after shortcodes
        add_filter('the_content', array($this, 'filter'), 12);
        add_filter('the_excerpt', array($this, 'filter'), 12);
	    add_filter('widget_text', array($this, 'filter'), 12);
	}

    /**
	 * filter()
	 *
	 * @param string $text
	 * @return string $text
	 **/

	function filter($text) {
		global $escape_autolink_uri;
		
		$escape_autolink_uri = array();
		
		$text = autolink_uri::escape($text);
		
		$text = preg_replace_callback("/
			((?<![\"'])                                     # don't look inside quotes
            (\b
            (						    # protocol or www.
				[a-z]{3,}:\/\/
			|
				www\.
			)
			(?:						    # domain
				[a-zA-Z0-9_\-]+
				(?:\.[a-zA-Z0-9_\-]+)*
			|
				localhost
			)
			(?:	                        # port
				 \:[0-9]+
			)?
			(?:						    # path (optional)
				[\/|\?][\w#!:\.\?\+=&%@!'~\*,;\/\(\)\[\]\-]*
			)?
            )
            (?![\"']))
			/ix", array($this, 'url_callback'), $text);
		
		$text = preg_replace_callback("/
			\b
			(?:mailto:)?
			(
				[a-z0-9%_|~-]+
				(?:\.[a-z0-9%_|~-]+)*
				@
				[a-z0-9%_|~-]+
				(?:\.[a-z0-9%_|~-]+)+
			)
			/ix", array($this, 'email_callback'), $text);
		
		$text = autolink_uri::unescape($text);
		
		return $text;
	} # filter()
	
	
	/**
	 * url_callback()
	 *
	 * @param array $match
	 * @return string $text
	 **/

	function url_callback($match) {
		$url = $match[0];
		$href = $url;
		
		if ( strtolower($match[1]) === 'www.' )
			$href = 'http://' . $href;
		
		$href = esc_url($href);
		
		return '<a href="' . $href . '">' . $url . '</a>';
	} # url_callback()
	
	
	/**
	 * email_callback()
	 *
	 * @param array $match
	 * @return string $text
	 **/

	function email_callback($match) {
		$email = end($match);
		return '<a href="' . esc_url('mailto:' . $email) . '">' . $email . '</a>';
	} # email_callback()
	
	
	/**
	 * escape()
	 *
	 * @param string $text
	 * @return string $text
	 **/

	function escape($text) {
		global $escape_autolink_uri;
		
		if ( !isset($escape_autolink_uri) )
			$escape_autolink_uri = array();
		
		foreach ( array(
			'head' => "/
				.*?
				<\s*\/\s*head\s*>
				/isx",
			'blocks' => "/
				<\s*(script|style|object|textarea|code|pre)(?:\s.*?)?>
				.*?
				<\s*\/\s*\\1\s*>
				/isx",
			'smart_links' => "/
				\[.+?\]
				/x",
			'anchors' => "/
				<\s*a\s.+?>.+?<\s*\/\s*a\s*>
				/isx",
			'tags' => "/
				<[^<>]+?(?:src|href|codebase|archive|usemap|data|value|action|background|placeholder)=[^<>]+?>
				/ix",
			) as $regex ) {
			$text = preg_replace_callback($regex, array($this, 'escape_callback'), $text);
		}
		
		return $text;
	} # escape()
	
	
	/**
	 * escape_callback()
	 *
	 * @param array $match
	 * @return string $tag_id
	 **/

	function escape_callback($match) {
		global $escape_autolink_uri;
		
		$tag_id = "----escape_autolink_uri:" . md5($match[0]) . "----";
		$escape_autolink_uri[$tag_id] = $match[0];
		
		return $tag_id;
	} # escape_callback()
	
	
	/**
	 * unescape()
	 *
	 * @param string $text
	 * @return string $text
	 **/

	function unescape($text) {
		global $escape_autolink_uri;
		
		if ( !$escape_autolink_uri )
			return $text;
		
		$unescape = array_reverse($escape_autolink_uri);
		
		return str_replace(array_keys($unescape), array_values($unescape), $text);
	} # unescape()
} # autolink_uri

$autolink_uri = autolink_uri::get_instance();