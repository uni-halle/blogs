<?php if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
		));

		// have a look for additional widgets and activate them
		$widgets_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_template() . '/widgets');
		if ($widgets_dir) {while(($widgetFile = $widgets_dir->read()) !== false)
		{if (!preg_match('|^\.+$|', $widgetFile) && preg_match('|\.php$|', $widgetFile))
		include(ABSPATH . '/wp-content/themes/' . get_template() . '/widgets/' . $widgetFile);
		}}

		function unregister_problem_widgets() {
		unregister_sidebar_widget('Search');
		unregister_sidebar_widget('Recent Comments');
		unregister_sidebar_widget('Recent Posts');
		unregister_sidebar_widget('tag_cloud');
		unregister_sidebar_widget('meta');
		unregister_sidebar_widget('links');
		unregister_sidebar_widget('calendar');
		unregister_sidebar_widget('pages');
		unregister_sidebar_widget('archives');
		}
		add_action('widgets_init','unregister_problem_widgets');
		}


/* Function Change Header */

define('HEADER_TEXTCOLOR', 'f1ffcc');
define('HEADER_IMAGE', '%s/images/header.jpg'); // %s is theme dir url
define('HEADER_IMAGE_WIDTH', 540);
define('HEADER_IMAGE_HEIGHT', 200);

function theme_admin_header_style() { ?>

<style type="text/css">

#headimg {
	margin: 0px 0px 20px 0px;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	background: url(<?php header_image(); ?>) no-repeat top center transparent;}

	#headimg * {color: #<?php header_textcolor();?>;}

#headimg #desc {
	border: none;
	font-size: 12px;
	color: #f1ffcc; 
	text-align: center;
	font-variant: normal;
	letter-spacing: 1px;
	margin: 0px 0px 0px 0px; 
	padding: 0px 0px 0px 0px;
	font-family: Verdana, Arial, Helvetica, sans-serif;}

#headimg h1 {
	text-align: center;
	border: none;
	color: #f1ffcc;
	font-size: 20px;
	font-weight: normal;
	letter-spacing: 1px;
	font-variant: normal;
	margin: 0px 0px 0px 0px; 
	padding: 40px 0px 0px 0px;
	font-family: Verdana, Arial, Helvetica, sans-serif;}

	#headimg h1 a {border: none; color: #f1ffcc;}

</style>


<?php } function theme_header_style() { ?>

<style type="text/css">

#header {
	margin: 0px 0px 0px 40px;
	text-align: right;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	background: url(<?php header_image(); ?>) no-repeat top center transparent;}

	#header * {color: #<?php header_textcolor();?>;}

</style>

<?php } if ( function_exists('add_custom_image_header') ) {
add_custom_image_header('theme_header_style', 'theme_admin_header_style');}


/*
Plugin Name: Recent Comments Edit
Plugin URI: http://mtdewvirus.com/code/wordpress-plugins/
Description: Retrieves a list of the most recent comments.
Version: 1.18
Author: Nick Momrik
Author URI: http://mtdewvirus.com/
*/

function mdv_recent_comments_edit($no_comments = 5, $comment_lenth = 5, $before = '<li>', $after = '</li>', $show_pass_post = false, $comment_style = 0) {
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
				$output .= $before . '<span class="last">' . $comment_author . ':</span> <a href="' . $permalink;
				$output .= '" title="Den ganzen Kommentar lesen ' . $comment_author.'">' . $comment_excerpt.' ...</a>' . $after;
			}
		}
		$output = convert_smilies($output);
	} else {
		$output .= $before . "Nichts gefunden" . $after;
	}
    echo $output;
}


/*
Plugin Name: Most Commented Edit
Plugin URI: http://mtdewvirus.com/code/wordpress-plugins/
Description: Retrieves a list of the posts with the most comments. Modified for Last X days -- by DJ Chuang www.djchuang.com 
Version: 1.4
Author: Nick Momrik
Author URI: http://mtdewvirus.com/
*/

function mdv_most_commented_edit($no_posts = 5, $before = '<li>', $after = '</li>', $show_pass_post = false, $duration='') {
    global $wpdb;
	$request = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'comment_count' FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish'";
	if(!$show_pass_post) $request .= " AND post_password =''";

        if($duration !="") { $request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
}

	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
	if ($posts) {
		foreach ($posts as $post) {
			$post_title = stripslashes($post->post_title);
			$comment_count = $post->comment_count;
			$permalink = get_permalink($post->ID);
			$output .= $before . '<a href="' . $permalink . '" title="' . $post_title.'">' . $post_title . '</a> (' . $comment_count.'x)' . $after;
		}
	} else {
		$output .= $before . "Nichts gefunden" . $after;
	}
    echo $output;
}

// helper functions
	$numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type='post' and post_status = 'publish'");
		if (0 < $numposts) $numposts = number_format($numposts); 

	$numcmnts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
		if (0 < $numcmnts) $numcmnts = number_format($numcmnts);
// ----------------


/*
Plugin Name: Post Word Count Edit
Plugin URI: http://mtdewvirus.com/code/wordpress-plugins/
Description: Outputs the total number of words in all posts.
Version: 1.02
Author: Nick Momrik
Author URI: http://mtdewvirus.com/
*/

function mdv_post_word_count_edit() {
    global $wpdb;
	$now = gmdate("Y-m-d H:i:s",time());
	$words = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_date < '$now'");
	if ($words) {
		foreach ($words as $word) {
			$post = strip_tags($word->post_content);
			$post = explode(' ', $post);
			$count = count($post);
			$totalcount = $count + $oldcount;
			$oldcount = $totalcount;
		}
	} else {
		$totalcount=0;
	}
	echo number_format($totalcount);
}

?>