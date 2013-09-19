<?php
/*
Template Name: Aktionen
 */
get_header();
?>

<?php query_posts("cat=3&orderby=modified&order=DESC&posts_per_page=10"); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	 <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
	<div class="meta"><?php edit_post_link(__('Edit This')); ?></div>

	<div class="storycontent">
		<?php
                  the_excerpt();
                ?>
<a id="weiter" href="<?php the_permalink(); ?>" >(Gesamten Beitrag lesen...)</a>
        <hr />
	</div>

</div>

<?php comments_template(); // Get wp-comments.php template ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

<?php posts_nav_link(' &#8212; ', __('&laquo; Newer Posts'), __('Older Posts &raquo;')); ?>

<?php get_footer(); ?>
