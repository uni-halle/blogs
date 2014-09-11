<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
		<div class="postnav">
			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
		</div>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php if (function_exists('post_class')){ post_class(); } else { echo 'class="post"' ;} ?>>
				<div class="posttitle">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info"><?php the_time(__('M jS, Y','ml')) ?> <?php _e('by','ml');?> <?php the_author_posts_link() ?> <?php edit_post_link(__('Edit','ml'), '', ' | '); ?> </p>
				</div>
				<div class="entry">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
					<p class="postmetadata">
						<?php if (function_exists('the_tags')) the_tags(__('Tags: ','ml'), ', ', '<br/>'); ?> </p>
				</div>
				<p class="postmetadata"><?php _e('Posted in','ml');?> <?php the_category(', ') ?></p>
			</div>
			<?php comments_template(); ?>
		<?php endwhile; ?>
	<?php else : include_once(TEMPLATEPATH.'/notfound.php');?>
	<?php endif; ?>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer();?>