<?php
/*
	Plugin Name: * mluBlogs Blog Fetch Functions
	Plugin URI: http://blogs.urz.uni-halle.de
	Description: Diverse Helfer-Funktionen fuer die Anzeige der Blogs an verschiednen Stellen (Neue Blogs, Aktivste Blogs, Blogliste etc.)
	Version: 1.0
	Author: Matthias Kretschmann
	Author URI: http://matthiaskretschmann.com
	
	Instructions:
    Kommt in mu-plugins Ordner
	
	2011, Matthias Kretschmann

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//Fetch most recently created blogs
function get_recent_blogs($number_blogs=5) {
  global $wpdb;
  $blog_table=$wpdb->blogs;
  //fetch blog_id,domain,path from wp_blogs table ,where the blog is not spam,deleted or archived order by the date and time of registration
  $query="select blog_id,domain,path from $blog_table where public='1' and archived='0' and spam='0' and deleted='0' order by registered desc limit 0,$number_blogs";
 
  $recent_blogs=$wpdb->get_results($query);
 
return $recent_blogs;
}

// Get total post count
function mlublogs_post_count() {
	//get current blog id
	global $blog_id;
	global $wpdb;
	
	//set a temp var for current blog
	$tempBlogId = $blog_id;
	
	//get all blog ids in the system as array
	$res = $wpdb->get_results('select blog_id from wp_blogs', ARRAY_A);
	
	$total = 0;
	
	//loop through each blog, get the post counts
	foreach ($res as $result) {
		$wpdb->set_blog_id($result['blog_id']);
		$val = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_date_gmt < '" . gmdate("Y-m-d H:i:s",time()) . "'");
		$total += $val;
	}
	
	//reset just in case
	$wpdb->set_blog_id($tempBlogId);
	
	//return the total count of all posts
	echo $total;
}

?>
