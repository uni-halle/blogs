<?php

/*
Plugin Name: AHP Sitewide Recent Posts for WordPress MU
Plugin URI: http://www.metablog.us/blogging/ahp-recent-posts-plugin-for-wordpress-mu/
Description: Retrieves a list of the most recent posts sitewide in a WordPress MU installation.. Automatically excludes blog ID 1 (main blog), and post ID 1 (first "Hello World" posts of new blogs).  Flexible display output.
Author: Aziz Poonawalla
Author URI: http://metablog.us

Copyright 2010  Aziz Poonawalla  (email : apoonawa-blog@yahoo.com)

Version 1.0
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- Simple widget added with default values, easily edited

*/

function ahp_recent_posts_widget($args) {
        extract($args);
        echo $before_widget;
        echo "\n$before_title Recent Journals $after_title";
		echo '<ul class="ahp_recent-posts">' . "\n";
        ahp_recent_posts(5,60,255);
/*
$how_many: how many recent posts are being displayed (default: 5)
$how_long: time frame to choose recent posts from (in days) (default: 60)
$optmask: bitmask for various display options (default: 255)
	DISPLAY OPTIONS BITMASK
	1;  // gravatar
	2;  // date
	4;  // author name
	8;  // comment count
	16; // blog name	
	32; // post title	
	64; // post excerpt
	128; // excerpt capitalization
$exc_size: size of excerpt in words (default: 30)
$begin_wrap: start html code (default: <li class="ahp_recent-posts">)
$end_wrap: end html code to adapt to different themes (default: </li>)
*/
	echo "</ul>";
        echo $after_widget;
}

function ahp_recent_posts_init() {
        register_sidebar_widget(__("AHP Recent Posts"),"ahp_recent_posts_widget");
}

add_action("plugins_loaded","ahp_recent_posts_init");

/*
class ahp_recent_posts_Widget extends WP_Widget {  
    function ahp_recent_posts_Widget() {  
        parent::WP_Widget(false, 'AHP Recent Posts');  
    }  
function form($instance) {  
        // outputs the options form on admin  
    }  
function update($new_instance, $old_instance) {  
        // processes widget options to be saved  
        return $new_instance;  
    }  
function widget($args, $instance) {  
        // outputs the content of the widget  
    }  
}  
register_widget('ahp_recent_posts_Widget');  

*/


?>