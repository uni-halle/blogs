<?php

/*
Plugin Name: AHP Sitewide Recent Posts for WordPress MU
Plugin URI: http://www.metablog.us/blogging/ahp-recent-posts-plugin-for-wordpress-mu/
Description: Retrieves a list of the most recent posts sitewide in a WordPress MU installation.. Automatically excludes blog ID 1 (main blog), and post ID 1 (first "Hello World" posts of new blogs).  Flexible display output.
Author: Aziz Poonawalla
Author URI: http://metablog.us

Copyright 2010  Aziz Poonawalla  (email : apoonawa-blog@yahoo.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Function arguments:

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

Sample call: to show 10 posts from recent 30 days, with  20 word excerpt

	<?php ahp_recent_posts(5, $how_long = 60, 255, 20) ;  ?>

Version 1.0
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- Author links fixed to point to their blog
- all input parameters now have defaults
- changed "span class=" to "span style=" (to fix validation error)
- strip whitespace or image captions at start of post (fixes display error)
- order by blog post date instead of last updated
- strip slahes on blog name
- fixed comment count query
- gravatar CSS class now in Edit These section

(skipped a few...)

Version 0.6
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- added comment count display option
- added enable/disable excerpt capitalization
- consolidated title/name of post display options into bitmask
- reduced number of required arguments
- added class name ahp-recent-posts to default start html LI tags
- added class ahp-excerpt to excerpt block

Version 0.5
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- changed gravatar link to point to all posts by author on main blog (ID = 1). 
- added date string, author output
- implemented bitmask to control gravatar, date, author output
- consolidated numwords argument with display argument

Version 0.4.1
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- added gravatar support, icon size 24px
- gravatar can be styled by img.avatar-24 in your css file
- gravatar image links to author's blog
- capitalization of first five words of the excerpt

Version 0.4.0 
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- added exclusions for first blog, first post, enabled post excerpt

Version: 0.32
Update Author: G. Morehouse
Update Author URI: http://wiki.evernex.com/index.php?title=Wordpress_MU_sitewide_recent_posts_plugin

Version: 0.31
Update Author: Sven Laqua
Update Author URI: http://www.sl-works.de/

Version: 0.3
Author: Ron Rennick
Author URI: http://atypicalhomeschool.net/
*/

function ahp_recent_posts($how_many = 5, $how_long = 60, $optmask = 255, $exc_size = 30, $begin_wrap = '<li class="ahp_recent-posts">', $end_wrap = '</li>') {
	global $wpdb;
	$counter = 0;
	
	// EDIT THESE TO CUSTOMIZE THE OUTPUT
	$debug = 0;
	$blog_prefix = " at ";
	$post_prefix = "";
	$date_prefix = " on ";
	$auth_prefix = ' by ';
	$com_prefix = ' | ';
	$date_format = 'D M jS, Y';
	$grav_size = 40;
	$gravatar_css = "float: left; margin-right: 5px";
	
	// DISPLAY OPTIONS BITMASK
	$grav_flag = 1;  // gravatar
	$date_flag = 2;  // date
	$auth_flag = 4;  // author name
	$com_flag  = 8;  // comment count
	$name_flag = 16; // blog name	
	$post_flag = 32; // post name	
	$exc_flag  = 64; // post excerpt
	$cap_flag  = 128; // excerpt capitalization
	
	// set the various option flags
	if ($optmask & $grav_flag) { $use_grav = 1; } else { $use_grav = 0; } 
	if ($optmask & $date_flag) { $use_date = 1; } else { $use_date = 0; } 
	if ($optmask & $auth_flag) { $use_auth = 1; } else { $use_auth = 0; } 
	if ($optmask & $com_flag)  { $use_com  = 1; } else { $use_com = 0;  } 
	if ($optmask & $name_flag) { $use_name = 1; } else { $use_name = 0; } 
	if ($optmask & $post_flag) { $use_post = 1; } else { $use_post = 0; } 
	if ($optmask & $exc_flag)  { $use_exc  = 1; } else { $use_exc = 0;  } 
	if ($optmask & $cap_flag)  { $use_cap  = 1; } else { $use_cap = 0;  } 
	
	// debug block
	if ($debug) { 
		echo '<li>'.'opt = '.$optmask.': grav = '.$use_grav.', date = '.$use_date
		.', auth = '.$use_auth.', use_com = '.$use_com.', use_name = '.$use_name
		.', use_post = '.$use_post.', use_exc = '.$use_exc.', use_cap = '.$use_cap .'</li>';
	}
	
	// get a list of blogs in order of most recent update. show only public and nonarchived/spam/mature/deleted
	$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE
		public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND blog_id != '1' AND
		last_updated >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
		ORDER BY last_updated DESC");
		
	if ($blogs) {
			
		foreach ($blogs as $blog) {

			// we need _posts, _comments, and _options tables for this to work
			$blogOptionsTable = "wp_".$blog."_options";
		    $blogPostsTable = "wp_".$blog."_posts";
		    $blogCommentsTable = "wp_".$blog."_comments";
							
			// debug block
			if ($debug) { echo '<li>processing blog '.$blog.'</li>'; }

			// fetch the ID, post title, post content, post date, and user's email for the latest post
			$thispost = $wpdb->get_results("SELECT $blogPostsTable.ID, $blogPostsTable.post_title, 
				$blogPostsTable.post_content, $blogPostsTable.post_date, wp_users.display_name, 
				wp_users.user_email, wp_users.user_login
				FROM $blogPostsTable, wp_users
				WHERE wp_users.ID = $blogPostsTable.post_author
				AND post_status = 'publish' AND post_type = 'post' 
				AND post_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
				AND $blogPostsTable.id > 1 
				ORDER BY $blogPostsTable.id DESC limit 0,1");

			// if it is found put it to the output
			if($thispost) {
			
				// debug block
				if ($debug) {
					echo '<li>processing thispost ID = '.$thispost[0]->ID.'<br>';
					echo 'post_title = '.$thispost[0]->post_title.'<br>';
					//echo 'post_content = '.$thispost[0]->post_content.'<br>';
					echo 'post_date = '.$thispost[0]->post_date.'<br>';
					echo 'display_name = '.$thispost[0]->display_name.'<br>';
					echo 'user_email = '.$thispost[0]->user_email.'<br>';
					echo 'user_login = '.$thispost[0]->user_login.'</li>';
				}
			
				// get post ID
				$thispostID = $thispost[0]->ID;
				
				// get permalink by ID.  check wp-includes/wpmu-functions.php
				$thispermalink = get_blog_permalink($blog, $thispostID);
				
				// We need this for both the blog link and the link to the author's page
				$options = $wpdb->get_results("SELECT option_value FROM
				$blogOptionsTable WHERE option_name IN ('siteurl','blogname') 
				ORDER BY option_name DESC");

				$blog_link = $options[0]->option_value;

				// get blog name,  URL
				if ($use_name) { 
					$blog_name = stripslashes($options[1]->option_value);
					$this_blogname = $blog_prefix.'<a href="'.$blog_link.'">'.$blog_name.'</a>';

				} else { $this_blogname = ''; }

				// get comments
				if ($use_com) {

					// sql query for all comments on the current post
 					$num_comments = $wpdb->get_var("SELECT comment_count
					FROM $blogPostsTable
					WHERE ID = $thispostID");
										
					// pretty text
					if ($num_comments == 0) { $thiscomment = $com_prefix.'no comments'; }
					elseif ($num_comments == 1) { $thiscomment = $com_prefix.'one comment'; }
					else { $thiscomment = $com_prefix.$num_comments.' comments'; } 
					
				} else { $thiscomment = ''; }
									
				// get author
				if ($use_auth) { 
					$thisauthor = $auth_prefix.$thispost[0]->display_name;
				} else { $thisauthor = ''; } 
				
				// get author's posts link 
				$thisuser = $thispost[0]->user_login;
				$thisuser_url = $blog_link."author/".$thisuser;
				
				// get gravatar
				if ($use_grav) { 
					$grav_img = get_avatar( $thispost[0]->user_email , $grav_size ); 
					$thisgravatar = '<span style="'.$gravatar_css.'">'.
					                 '<a href="'.$thisuser_url.'">'.$grav_img.'</a></span>';
				} else { $thisgravatar = ''; }
				
				// get post date (nicely formatted)
				if ($use_date) { 
					$thisdate = $date_prefix.date($date_format, strtotime( $thispost[0]->post_date )) ; 					
				} else { $thisdate = ''; }
				
				// get post name 
				if ($use_post) { 
					$this_postname = $post_prefix.'<a href="'.$thispermalink.'">'.$thispost[0]->post_title.'</a>';
				} else { $this_postname = ''; }
				
				if ($use_exc) { 

					if ($exc_size == 0) { 

						$thisexcerpt = ''; 
			
					} else { 
					
						// get post content and truncate to (numwords) words
						$thiscontent = strip_tags( $thispost[0]->post_content );
						$regex_str = "/\[caption.*\[\/caption\]/";
						$thiscontent = preg_replace($regex_str, "", $thiscontent);
						$thiscontent = trim($thiscontent);
						preg_match("/([\S]+\s*){0,$exc_size}/", $thiscontent, $regs);						
						
						if ($use_cap) {
							// build the excerpt html block, capitalize first five words
							$trunc_content = explode( ' ', trim($regs[0]) , 6 );
							$exc_str = strtoupper($trunc_content[0]).' '
							.strtoupper($trunc_content[1]).' '
							.strtoupper($trunc_content[2]).' '
							.strtoupper($trunc_content[3]).' '
							.strtoupper($trunc_content[4]).' '
							.$trunc_content[5].'... ';
					
						} else { 
							$exc_str = trim($regs[0]); 
						} 
							
						$thisexcerpt = '<span class="ahp-excerpt">'.$exc_str
						.'<a href="'.$thispermalink.'">'					
						.'&raquo;&raquo;MORE'.'</a></span>';
					}
				
				} else { 
					$thisexcerpt = ''; 
				}
					
					echo $begin_wrap.'<p>'.$thisgravatar.'<small>'.$thisauthor.$this_blogname.$thisdate.'</small></p>'
					.'<p>'.$this_postname.'&nbsp'
					.'<small>'.$thisexcerpt.$thiscomment.'</small></p>'
					.$end_wrap;
					
					$counter++;
			
			} else { if ($debug) { echo "no recent post found."; } }
						
			// don't go over the limit - break out of foreach loop
			if($counter >= $how_many) { break; }
		}
		
	} else { if ($debug) { echo "no recently-updated blog found."; } }

// function end	
}

?>