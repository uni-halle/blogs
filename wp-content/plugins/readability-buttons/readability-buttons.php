<?php
/*
Plugin Name: Readability Buttons (readability.com)
Plugin URI: http://sparanoid.com/work/readability/
Description: Adds readability.com widget for your site.
Version: 2.1.0
Author: Tunghsiao Liu
Author URI: http://sparanoid.com/
Author Email: t@sparanoid.com
License: GPLv2 or later

  Copyright 2014 Tunghsiao Liu, aka. Sparanoid (t@sparanoid.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

class Readability_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * The widget constructor. Specifies the classname and description, instantiates
	 * the widget, loads localization files, and includes necessary scripts and
	 * styles.
	 */
	public function __construct() {

    // TODO be sure to change 'widget-name' to the name of *your* plugin
		load_plugin_textdomain( 'readability_buttons', false, plugin_dir_path( __FILE__ ) . '/lang/' );

		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );

		// TODO: update classname and description
  	// TODO: replace 'widget-name-locale' to be named more plugin specific. other instances exist throughout the code, too.
		parent::__construct(
			'widget-readability-buttons-id',
			'Readability Buttons',
			array(
				'classname'		=>	'widget-readability-buttons',
				'description'	=>	__( 'Short description.', 'widget-name-locale' )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( &$this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_widget_scripts' ) );

	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @args			The array of form elements
	 * @instance		The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
    $title 		           = apply_filters('widget_title', $instance['title']);
    $show_read_now       = $instance['show_read_now'];
    $show_read_later     = $instance['show_read_later'];
    $show_print          = $instance['show_print'];
    $show_email          = $instance['show_email'];
    $show_send_to_kindle = $instance['show_send_to_kindle'];
    $show_vertical       = $instance['show_vertical'];
    $text_color          = $instance['text_color'];
    $bg_color            = $instance['bg_color'];

		echo $before_widget;

    	// TODO: This is where you retrieve the widget values

		// Display the widget
		include( plugin_dir_path(__FILE__) . '/views/widget.php' );

		echo $after_widget;

	} // end widget

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @new_instance	The previous instance of values before the update.
	 * @old_instance	The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

    	// TODO Update the widget with the new values
  		$instance['title']               = strip_tags($new_instance['title']);
  		$instance['show_read_now']       = strip_tags($new_instance['show_read_now']);
  		$instance['show_read_later']     = strip_tags($new_instance['show_read_later']);
  		$instance['show_print']          = strip_tags($new_instance['show_print']);
  		$instance['show_email']          = strip_tags($new_instance['show_email']);
  		$instance['show_send_to_kindle'] = strip_tags($new_instance['show_send_to_kindle']);
  		$instance['show_vertical']       = strip_tags($new_instance['show_vertical']);
  		$instance['text_color']          = strip_tags($new_instance['text_color']);
  		$instance['bg_color']            = strip_tags($new_instance['bg_color']);

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {

    	// TODO define default values for your variables
		//$instance = wp_parse_args(
		//	(array) $instance,
		//	array(
		//		'$title'	=>	'wwwwwww'
		//		'$$message'	=>	'aaaaaaa'
		//	)
		//);

    $title                = esc_attr($instance['title']);
    $show_read_now        = esc_attr($instance['show_read_now']);
    $show_read_later      = esc_attr($instance['show_read_later']);
    $show_print           = esc_attr($instance['show_print']);
    $show_email           = esc_attr($instance['show_email']);
    $show_send_to_kindle  = esc_attr($instance['show_send_to_kindle']);
    $show_vertical        = esc_attr($instance['show_vertical']);
    $text_color           = esc_attr($instance['text_color']);
    $bg_color             = esc_attr($instance['bg_color']);


    	// TODO store the values of widget in a variable

		// Display the admin form
    	include( plugin_dir_path(__FILE__) . '/views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Fired when the plugin is activated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @params	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		// TODO change 'widget-name' to the name of your plugin
    // wp_register_script( 'readability-buttons-admin-styles', plugins_url( 'readability-buttons/css/admin.css' ) );
    // wp_enqueue_script( 'readability-buttons-admin-styles' );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		// TODO change 'widget-name' to the name of your plugin
    // wp_register_script( 'widget-name-admin-script', plugins_url( 'readability-buttons/js/admin.js' ) );
    // wp_enqueue_script( 'widget-name-admin-script' );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		// TODO change 'widget-name' to the name of your plugin
    // wp_register_script( 'widget-name-widget-styles', plugins_url( 'readability-buttons/css/widget.css' ) );
    // wp_enqueue_script( 'widget-name-widget-styles' );

	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		// TODO change 'widget-name' to the name of your plugin
    // wp_register_script( 'widget-name-widget-script', plugins_url( 'readability-buttons/js/widget.js' ) );
    // wp_enqueue_script( 'widget-name-widget-script' );

	} // end register_widget_scripts

} // end class
// TODO remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("Readability_Widget");' ) );
?>