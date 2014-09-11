<?php
/*
Plugin Name: My Foursquare
Plugin URI: http://myfoursquare.net/wordpress
Description: My Foursquare makes it easy to show off your Foursquare badges and mayorships on your WordPress blog.
Version: 1.0.6
Author: My Foursquare
Author URI: http://myfoursquare.net
License: GPL2

Copyright 2010  David McKinney  (email : dave@myfoursquare.net)

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
include "lib/foursquareapi.php";
include "lib/xml2array.class.php";
class WPFoursquareBadges {
	function WPFoursquareBadges() {
		add_action('init',array(&$this,'init'));
		add_action("plugins_loaded",array(&$this,"update_foursquare_option"));
		add_action("widgets_init", array(&$this,'register'));
	}
	
	function init() {
		$ch = isset($_REQUEST['choice']) ? $_REQUEST['choice'] : "";
		if($ch == "save_foursquare_option") {
			if($_REQUEST['foursquare']['fs_confirm']) {
				update_option('foursquare_option',$_REQUEST['foursquare']);
				echo '<p><font color=green>Successfully saved!</font></p>';
			} else
				echo '<p <font color=red>Please agree to the Terms of Use (checkbox)</font></p>';
		}
	}
	
	//Add foursquare options
	function update_foursquare_option () {
		$fsoption = get_option('foursquare_option');
		if(!$fsoption) {
			$data['badge'] = 1;	
			$data['mayor'] = 1;	
			$data['location'] = 1;	
			$data['title'] = "My Foursquare";	
			add_option('foursquare_option',$data);
		}
	}
		
	function control(){
		include "includes/widget-form.php";
	}

	function widget($args){
		include "includes/widget-display.php";
	}

	function register(){
		register_sidebar_widget('My Foursquare', array('WPFoursquareBadges', 'widget'));
		register_widget_control('My Foursquare', array('WPFoursquareBadges', 'control'));
	}
	
	// Insert Foursquare badges,mayors and location
	function insert_4square () {
		$option = get_option("foursquare_option");
		$url 	= 'http://api.foursquare.com/v1/user?badges=1&mayor=1';
		
		$arrayData = get_foursquare_data($option['user'],$option['pass'],$url);
		if(isset($arrayData['user'])){
			$badges 	= $arrayData['user']['badges'][0]['badge'];
			$mayors 	= $arrayData['user']['mayor'][0]['venue'];
			$locationdata =get_foursquare_data($option['user'],$option['pass'],"http://api.foursquare.com/v1/history?l=1");
			$location = $locationdata['checkins']['checkin'];

			update_option('foursquare_badges',$badges);
			update_option('foursquare_mayors',$mayors);
			update_option('foursquare_location',$location);
		}else{
			echo 'Problem in connect to Foursquare.com at this time ,Please try again';
			exit;
		}
	}
}
$wpfb = new WPFoursquareBadges();

//Custom CRON 
add_action("template_redirect","call_custom_cron");
function call_custom_cron() {
	global $wpfb;
	$option = get_option("foursquare_option");
	if($option['user'] &&  $option['pass']) {
		//echo "user pass";
		$cn_time = get_option("custom_cron_time");
		if(!$cn_time) {
			$wpfb->insert_4square();
			update_option("custom_cron_time",date("Y-m-d H:i:s"));
		} else {
			$ctime = 30*60;
			$vdate = date('Y-m-d H:i:s',time()-($ctime));
			if($cn_time <= $vdate){
				//echo "\r $cn_time <=  $vdate \r";
				update_option("custom_cron_time",date("Y-m-d H:i:s"));
				$wpfb->insert_4square();
			}
		}
	} else {
		//echo "else";
	}
}

function timeAgo($timestamp, $granularity=2, $format='Y-m-d H:i:s'){

	$difference = time() - $timestamp;

	if($difference < 0) return '0 seconds ago';             // if difference is lower than zero check server offset
	elseif($difference < 864000){                                   // if difference is over 10 days show normal time form

		 $periods = array('week' => 604800,'day' => 86400,'hr' => 3600,'min' => 60,'sec' => 1);
		 $output = '';
		 foreach($periods as $key => $value){
		 
			    if($difference >= $value){
			    
			            $time = round($difference / $value);
			            $difference %= $value;
			            
			            $output .= ($output ? ' ' : '').$time.' ';
			            $output .= (($time > 1 && $key == 'day') ? $key.'s' : $key);
			            
			            $granularity--;
			    }
			    if($granularity == 0) break;
		 }
		 return ($output ? $output : '0 seconds').' ago';
	}
	else return date($format, $timestamp);
}
?>
