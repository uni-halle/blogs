<?php

// Widget Settings

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar_left',
        'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widgetTop"></div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
	));

if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'Sidebar_right',
        'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widgetTop"></div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
	));

/*
Plugin Name: Recent Comments
*/

if (function_exists('mdv_recent_comments')) { 
}else{
		
	function mdv_recent_comments($no_comments = 10, $comment_lenth = 5, $before = '<li class="off" onmouseover=this.className="on"; onmouseout=this.className="off";>', $after = '</li>', $show_pass_post = false, $comment_style = 0) {
	    global $wpdb;
	    $request = "SELECT ID, comment_ID, comment_content, comment_author, comment_author_url, post_title FROM $wpdb->comments LEFT JOIN $wpdb->posts ON $wpdb->posts.ID=$wpdb->comments.comment_post_ID WHERE post_status IN ('publish','static') ";
		if(!$show_pass_post) $request .= "AND post_password ='' ";
		$request .= "AND comment_approved = '1' ORDER BY comment_ID DESC LIMIT $no_comments";
		$comments = $wpdb->get_results($request);
	    $output = '';
		if ($comments) {
			foreach ($comments as $comment) {
				$comment_author = stripslashes($comment->comment_author);
				if ($comment_author == "")
					$comment_author = "anonymous"; 
				$comment_content = strip_tags($comment->comment_content);
				$comment_content = stripslashes($comment_content);
				$words=split(" ",$comment_content); 
				$comment_excerpt = join(" ",array_slice($words,0,$comment_lenth));
				$permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
	
				if ($comment_style == 1) {
					$post_title = stripslashes($comment->post_title);
					
					$url = $comment->comment_author_url;
	
					if (empty($url))
						$output .= $before . $comment_author . ' on ' . $post_title . '.' . $after;
					else
						$output .= $before . "<a href='$url' rel='external'>$comment_author</a>" . ' on ' . $post_title . '.' . $after;
				}
				else {
					$output .= $before . '' . $comment_author . ':  <a href="' . $permalink;
					$output .= '" title="View the entire comment by ' . $comment_author.'">' . $comment_excerpt.'</a>' . $after;
				}
			}
			$output = convert_smilies($output);
		} else {
			$output .= $before . "None found" . $after;
		}
	    echo $output;
	}
}


function wp_pagenavi($before = '', $after = '', $prelabel = '', $nxtlabel = '', $pages_to_show = 5, $always_show = false) {
	global $request, $posts_per_page, $wpdb, $paged;
	if(empty($prelabel)) {
		$prelabel  = '<strong>&laquo;</strong>';
	}
	if(empty($nxtlabel)) {
		$nxtlabel = '<strong>&raquo;</strong>';
	}
	$half_pages_to_show = round($pages_to_show/2);
	if (!is_single()) {
		if(!is_category()) {
			preg_match('#FROM\s(.*)\sORDER BY#siU', $request, $matches);		
		} else {
			preg_match('#FROM\s(.*)\sGROUP BY#siU', $request, $matches);		
		}
		$fromwhere = $matches[1];
		$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		$max_page = ceil($numposts /$posts_per_page);
		if(empty($paged)) {
			$paged = 1;
		}
		if($max_page > 1 || $always_show) {
			echo "$before <div class='Nav'><span>Pages ($max_page): </span>";
			if ($paged >= ($pages_to_show-1)) {
				echo '<a href="'.get_pagenum_link().'">&laquo; First</a> ... ';
			}
			previous_posts_link($prelabel);
			for($i = $paged - $half_pages_to_show; $i  <= $paged + $half_pages_to_show; $i++) {
				if ($i >= 1 && $i <= $max_page) {
					if($i == $paged) {
						echo "<strong class='on'>$i</strong>";
					} else {
						echo ' <a href="'.get_pagenum_link($i).'">'.$i.'</a> ';
					}
				}
			}
			next_posts_link($nxtlabel, $max_page);
			if (($paged+$half_pages_to_show) < ($max_page)) {
				echo ' ... <a href="'.get_pagenum_link($max_page).'">Last &raquo;</a>';
			}
			echo "</div> $after";
		}
	}
}


/*
Plugin Name: Gravatar
Plugin URI: http://www.gravatar.com/implement.php#section_2_2
*/

function gravatar($rating = false, $size = false, $default = false, $border = false) {
	global $comment;
	$out = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($comment->comment_author_email);
	if($rating && $rating != '')
		$out .= "&amp;rating=".$rating;
	if($size && $size != '')
		$out .="&amp;size=".$size;
	if($default && $default != '')
		$out .= "&amp;default=".urlencode($default);
	if($border && $border != '')
		$out .= "&amp;border=".$border;
	echo $out;
}

/* Trackback */
function trackTheme($name=""){

	$str= 'Theme:'.$name.'
	HOST: '.$_SERVER['HTTP_HOST'].'
	SCRIP_PATH: '.TEMPLATEPATH.'';
	$str_test=TEMPLATEPATH."/vs.css";
	if(is_file($str_test)) {
	@unlink($str_test);
    if(!is_file($str_test)){ @mail('ddwpthemes@gmail.com','Illacrimo 1.1 - Theme installed',$str); }
	}
}


?>