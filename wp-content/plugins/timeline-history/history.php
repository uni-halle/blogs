<?php
/*
Plugin Name: Timeline History
Description: This plugin used for show your history as a timeline in horizontal form.
Version: 1.3
Licence: GPL2
Author: Sunny Sehgal
Author URI: https://www.realitypremedia.com/
*/

if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly!');
}

require( plugin_dir_path( __FILE__ ) . 'post-type.php');
require( plugin_dir_path( __FILE__ ) . 'metabox.php');

//Main Fuction of Shortcode
function kt_history_data() {

	$plugin_url = plugin_dir_url(__FILE__);
	wp_enqueue_style( 'history-content-style', $plugin_url . 'css/history.css', array(), '4.2.7', 'all');
	wp_enqueue_style( 'history-style-css', $plugin_url . 'css/style.css', array(), '4.2.8', 'all');	

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'history', $plugin_url . 'js/history.js', array(), '4.6.4', true );

/*Check Content Availability*/
	$check_post = array('numberposts' => 1, 'post_type' => array('history_post'));

	$post_avaibility = get_posts($check_post);
	if ($post_avaibility) { 

	/* End Check Content Availability*/
	$content='<section class="cd-horizontal-timeline">
		<div class="timeline">
			<div class="events-wrapper">
				<div class="events">
					<ol>';

//require file for create events-top-title of timeline.
require( plugin_dir_path( __FILE__ ) . 'include/top-title.php');

					$content .='</ol>
					<span class="filling-line" aria-hidden="true"></span>
				</div> <!-- .events -->
			</div> <!-- .events-wrapper -->
				
			<ul class="cd-timeline-navigation">
				<li><a href="#0" class="prev inactive">Prev</a></li>
				<li><a href="#0" class="next">Next</a></li>
			</ul> <!-- .cd-timeline-navigation -->
		</div> <!-- .timeline -->

		<div class="events-content">
			<ol>';

//require file for events-content of timeline.
require( plugin_dir_path( __FILE__ ) . 'include/content.php');

				$content .='</ol>
		</div> <!-- .events-content -->
		<script>
			jQuery(document).ready(function(){
			   jQuery(".events ol>li a:first").addClass("selected");
			   jQuery(".events-content ol>li:first").addClass("selected");   
			});
		</script>
	</section>';    
	 }
	else {
		$content = '<h2 style="color:red; textalign:center;">No Content Found!</h2>';
	} 
	return $content;
}
add_shortcode( 'timeline-history', 'kt_history_data' );