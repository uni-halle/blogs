<div id="sidebar" class="g33">
<hr/>
<div class="clear"></div>
<div id="sidebar1" class="g10 alpha">
<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
<li><h3>Recent entries</h3>
<ul><?php get_archives('postbypost', 5); ?></ul>
<br /></li>
<li><h3>Meta</h3>
<ul><?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<?php wp_meta(); ?>
<li><a href="http://validator.w3.org/check?uri=referer">XHTML</a></li></ul>
<br /></li>
<?php endif; ?>
</ul>
</div>
<div id="sidebar2" class="g10">
<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
<li><h3>Recent comments</h3>
<?php 
global $wpdb;

$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, 
comment_post_ID, comment_author, comment_date_gmt, comment_approved, 
comment_type,comment_author_url, 
SUBSTRING(comment_content,1,40) AS com_excerpt 
FROM $wpdb->comments 
LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = 
$wpdb->posts.ID) 
WHERE comment_approved = '1' AND comment_type = '' AND 
post_password = '' 
ORDER BY comment_date_gmt DESC 
LIMIT 3"; 
$comments = $wpdb->get_results($sql);

$output = $pre_HTML; 
$output .= "\n<ul>"; 
foreach ($comments as $comment) { 

$output .= "\n<li>".strip_tags($comment->comment_author) 
.":&nbsp;" . "<a href=\"" . get_permalink($comment->ID) . 
"#comment-" . $comment->comment_ID . "\" title=\"la " . 
$comment->post_title . "\">" . strip_tags($comment->com_excerpt) 
."</a></li>";
 
} 
$output .= "\n</ul>"; 
$output .= $post_HTML;
 
echo $output;?>
<br /></li>
<li><h3>Blogroll</h3>
<ul><?php wp_list_bookmarks('title_li=&categorize=0'); ?></ul>
<br /></li>
<?php endif; ?>
</ul>
</div>
<div id="sidebar3" class="g10 omega">
<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(3) ) : else : ?>
<li>
<form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<p><input type="text" value="Search and press Enter..." name="s" id="s" style="width:90%;color:#999" onfocus="if (this.value == 'Search and press Enter...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search and press Enter...';}" />
</p></form>
</li>
<li><h3>Pages</h3>
<ul><?php wp_list_pages('title_li='); ?></ul>
<br /></li>
<li><h3>Cats</h3>
<ul><?php wp_list_categories('title_li=&show_count=1'); ?></ul>
<br />
</li>
<li><h3>Archives</h3>
<ul><li><select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
<option value="Choose"><?php echo attribute_escape(__('Select month')); ?></option>
<?php wp_get_archives('type=monthly&format=option&show_post_count=1'); ?></select></li></ul>
</li>
<?php endif; ?>
</ul>
</div>
<div class="clear"></div>
<h3>Tag Cloud</h3>
<p><?php wp_tag_cloud('smallest=8&largest=8&number=100') ?></p>
</div>
<div class="clear"></div>