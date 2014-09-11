<?php get_header(); ?>

<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

<div class="post">

<?php the_title('<h1 class="post-title entry-title">', '</h1>'); ?>

	<div class="info">
	<?php edit_post_link(__('Edit', 'philna'), '<span class="editpost">', '</span>'); ?>	
	<span class="comments-link addcomment"><a href="#respond" title="<?php _e('Add a comment', 'philna') ?>"><?php _e('Add a comment', 'philna') ?></a></span>	
	<span class="published"><?php the_time(__('F jS, Y', 'philna')) ?></span>
	<span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_ID());?>"><?php the_author();?></a></span>
	</div>

	<div class="entry-content">
	<?php the_content(__('Continue reading','philna') . ' ' . the_title('"', '"', false)); ?>
	<?php wp_link_pages("before=<p class='pages'>".__('Pages:','philna')."&after=</p>"); ?>
	</div>

	<div class="entry-meta">
	<span class="cat-links"><?php the_category(', '); ?></span>
	<?php the_tags('<span class="tag-links">', ', ', '</span>');?>
	</div>

</div>

	<?php endwhile; ?>
<?php
if(function_exists('wp23_related_posts')) {
echo '<div id="related_posts">';
wp23_related_posts();
echo '</div>';
}
?>

<?php comments_template('', true); ?>
	
<?php else: ?>

<p class="no-data"><?php _e('Sorry, no posts matched your criteria.','philna'); ?></p>

<?php endif; ?>
<?php if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera')):?>
<div id="postnavi">
<span class="prev"><?php next_post_link('%link') ?></span>
<span class="next"><?php previous_post_link('%link') ?></span>
<div class="fixed"></div>
</div>
<?php endif?>
<?php get_footer(); ?>
