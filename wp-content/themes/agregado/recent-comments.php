<div class="widget">
<h3>Recent Comments</h3>
<?php
	//This grabs recent comments
	global $wpdb;
 
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
		comment_post_ID, comment_author, comment_date_gmt, comment_approved,
		comment_type,comment_author_url,
		SUBSTRING(comment_content,1,68) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
		$wpdb->posts.ID )
		WHERE comment_approved = '1' AND comment_type = '' AND
		post_password = ''
		ORDER BY comment_date_gmt DESC
		LIMIT 3";

	$comments = $wpdb->get_results($sql);
	$output = $pre_HTML;
	$output .= "\n<ul id=\"recentcomments\">";
	
	foreach ($comments as $comment) {
	
		$output .= "\n<li><a href=\"" . get_permalink($comment->ID) .
		"#comment-" . $comment->comment_ID . "\" title=\"on " . 
		$comment->post_title . "\"><em>".strip_tags($comment->comment_author) ."</em>" . 
		"" . strip_tags($comment->com_excerpt)
		."...</a></li>";
		
	}
	
	$output .= "\n</ul>";
	$output .= $post_HTML;
	
	echo $output;
	?>
    
   </div>