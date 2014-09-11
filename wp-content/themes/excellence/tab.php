<br />
<br />

<div class="tabber">

<div class="tabbertab">
<h2>Recent Posts</h2>
<ul>
<?php
$myposts = get_posts('numberposts=10&offset=1');
foreach($myposts as $post) :
?>
<li><a href="<?php the_permalink(); ?>"><?php the_title();
?></a></li>
<?php endforeach; ?>
</ul>
</div>

<div class="tabbertab">
<h2>Recent Comments</h2>
<?php
global $wpdb;

$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
comment_post_ID, comment_author, comment_date_gmt, comment_approved,
comment_type,comment_author_url,
SUBSTRING(comment_content,1,60) AS com_excerpt
FROM $wpdb->comments
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
$wpdb->posts.ID)
WHERE comment_approved = '1' AND comment_type = '' AND
post_password = ''
ORDER BY comment_date_gmt DESC
LIMIT 10";
$comments = $wpdb->get_results($sql);

$output = $pre_HTML;
$output .= "\n<ul>";
foreach ($comments as $comment) {
$output .= "\n<li><a href=\"" . get_permalink($comment->ID) .
"#comment-" . $comment->comment_ID . "\" title=\"on " .
$comment->post_title . "\">" .strip_tags($comment->comment_author)
." : ". strip_tags($comment->com_excerpt)
."</a></li>";

}
$output .= "\n</ul>";
$output .= $post_HTML;
echo $output;?>
</div>

<div class="tabbertab">
<h2>Archives</h2>
<ul>
<?php wp_get_archives('type=monthly'); ?>
</ul>
</div>

</div>