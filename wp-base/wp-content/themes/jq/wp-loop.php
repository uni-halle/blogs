<!-- WP Loop -->
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="headline clearfix">
<div class="date">
<?php the_time('M/y'); ?>
<p class="date-month"><?php the_time('j'); ?></p>
</div>
<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
<div class="preview"><a href="#" class="view-excerpt"></a></div>
<div class="clearfix"></div>
<p class="post_info">         
<?php comments_popup_link('No comments','1 Comment','% Comments','','Comments off'); ?> &middot; 
Posted by <i><?php the_author(); ?></i> in <?php the_category(', '); ?><?php if ( is_user_logged_in() ) : ?><?php edit_post_link('Edit', ' &middot; ', ''); ?><?php endif; ?>
</p>
</div>
<?php
/* This code retrieves all our admin options. */
global $options;
foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<div class="excerpt">
<?php /* Post options */
if ($jq_post_display == "false") {
the_content();
wp_link_pages();
} else { 
the_excerpt();
} ?>	
<div class="clearfix"></div>
<p class="tag_info">
<?php $tag = get_the_tags(); if (! $tag) {echo "No tags";} else {the_tags('', ' &middot; ', '');} ?>
</p>
</div>
<?php endwhile; ?>
<p class="previous-posts"><?php previous_posts_link('<< Latest posts'); ?></p>
<p class="next-posts"><?php next_posts_link('Older posts >>'); ?></p>
<?php else : ?>
<p class="not-found"><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php endif; ?>
