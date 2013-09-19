<?php
function yinheli_recentcomments($lim_comments = 6, $before = '<li> ', $after = '</li>', $show_pass_post = false) {
global $wpdb, $tablecomments, $tableposts;
	$request = "SELECT ID, comment_ID, comment_content, post_title, comment_author_email, comment_author FROM $tableposts, $tablecomments WHERE 
$tableposts.ID=$tablecomments.comment_post_ID AND (post_status = 'publish' AND comment_author !='popdo' OR post_status = 'static') AND comment_type = ''";
 
if(!$show_pass_post) { $request .= "AND post_password ='' "; }
 
    $request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT 
 
$lim_comments";
    $comments = $wpdb->get_results($request);
    $output = '';
    foreach ($comments as $comment) {
       $comment_author = stripslashes($comment->comment_author);

       $comment_excerpt = mb_strimwidth(strip_tags(apply_filters('the_comment', $comment->comment_content)), 0, 36,"...");
       $permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;

       $output .= $before . '<div class="yrc_avatar">' . get_avatar($comment, 32) . '<span class="yrc_info"><a href="' . $permalink . '" title="Comment on: ' . $comment->post_title  . '">' . $comment_author . ':</a></span></div><p> ' . $comment_excerpt . '...</p>' . $after;
       }
       echo $output;
 
}
?>