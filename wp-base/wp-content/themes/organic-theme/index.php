<?php
/**
 * @package WordPress
 * @subpackage Organic_Theme
 */
get_header();
?>
<div id="page-wrap">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
	<p class="comment-count"><?php comments_popup_link(__('<span class="count">0</span> comments'), __('<span class="count">1</span> comment'), __('<span class="count">%</span> comments')); ?></p>
	<p class="meta">Posted on <?php the_time('jS F Y') ?> by <?php the_author() ?> <?php if (!is_page()) { ?> in <?php the_category(' |'); } ?></p>
	<p class="the-tags"><?php the_tags(__(''), ', ', ''); ?></p>
	<div class="storycontent">
		<?php the_content(__('(more...)')); ?>
		<div class="clr"></div>
	</div>
    <?php if (!is_page()) { include("includes/social.php"); } ?>
	<div class="feedback">
		<?php wp_link_pages(); ?>
	</div>
</div>

<?php comments_template(); ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
      <div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
<?php } ?>
</div>
<?php get_footer(); ?>
