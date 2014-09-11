<?php
/* This code retrieves all our admin options. */
global $options;
foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<?php get_header(); ?>
<div id="content" class="clearfix">
<?php get_sidebar(); ?>
<div id="left">
<?php /* Slideshow HTML */
if ($jq_slide_display == "false") { ?>
<div id="slideshow">
    <div id="slidesContainer">
<?php
 $rand_posts = get_posts('numberposts=7&orderby=rand');
 foreach( $rand_posts as $post ) : ?>
		<div class="slide">
			<h1 class="slide_header"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
			<div class="clearfix"></div>
			<p class="post_info_slide">         
			<?php comments_popup_link('No comments','1 Comment','% Comments','','Comments off'); ?> &middot; 
			Posted by <i><?php the_author(); ?></i> in <?php the_category(', '); ?>
			</p>
		</div>
 <?php endforeach; ?>
    </div>
</div>
<?php } ?>
<!-- Slideshow HTML -->
<?php include (TEMPLATEPATH . "/wp-loop.php"); ?>
</div>
</div>
<?php get_footer(); ?>
