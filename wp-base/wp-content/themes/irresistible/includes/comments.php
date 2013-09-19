<?php

$comment_posts = get_option('woo_comment_posts');
if (empty($comment_posts) || $comment_posts < 1) $comment_posts = 6;

global $wpdb;
 
$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved,
comment_type,comment_author_url,
SUBSTRING(comment_content,1,50) AS com_excerpt
FROM $wpdb->comments
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
$wpdb->posts.ID)
WHERE comment_approved = '1' AND comment_type = '' AND
post_password = ''
ORDER BY comment_date_gmt DESC LIMIT ".$comment_posts;

$comments = $wpdb->get_results($sql);
$output = $pre_HTML;

foreach ($comments as $comment) {


?>
<li>
	<?php echo strip_tags($comment->comment_author); ?> said:&nbsp;
	<a href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="on <?php echo $comment->post_title; ?>">
		<?php echo strip_tags($comment->com_excerpt); ?>...
   </a>
   <div style="clear:both"></div>
</li>
<?php 
}
?>