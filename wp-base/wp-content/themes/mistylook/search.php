<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

		<h2 class="pagetitle"><?php _e('Search Results for','ml');?> <?php echo "'".$s."'";?></h2>
		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','ml');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post-info">
						<?php _e('Posted in','ml');?> <?php the_category(', ') ?>  <?php _e('on','ml');?> <?php the_time(__('M jS, Y','ml')) ?></p>
				</div>

				<div class="entry">
					<?php the_excerpt(); ?>
					<p><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to','ml');?> <?php the_title(); ?>"><?php _e('Read Full Post &#187;','ml');?></a></p>
				</div>
			</div>

		<?php endwhile; ?>

		<p align="center"><?php posts_nav_link(' - ',__('&#171; Newer Posts','ml'),__('Older Posts &#187;','ml')) ?></p>

	<?php else : include_once(TEMPLATEPATH.'/notfound.php');?>
	<?php endif; ?>
</div><!-- end id:content-main -->
<?php get_sidebar();?>
<?php get_footer();?>