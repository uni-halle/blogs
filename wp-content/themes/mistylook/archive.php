<?php get_header();?>
<div id="content">
<div id="content-main">
<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle"><?php _e('Category Archive for','ml');?> '<?php echo single_cat_title(); ?>'</h2>

		<?php /* If this is a Tag archive */ } elseif (function_exists('is_tag')&& is_tag()) { ?>
		<h2 class="pagetitle"><?php _e('Tag Archive','ml');?> '<?php echo single_tag_title(); ?>'</h2>

 		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php _e('Daily Archive for','ml');?> <?php the_time(__('F jS, Y','ml')); ?></h2>

		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php _e('Monthly Archive for','ml');?> <?php the_time(__('F, Y','ml')); ?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle"><?php _e('Yearly Archive for','ml');?> <?php the_time(__('Y','ml')); ?></h2>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php _e('Blog Archives','ml');?></h2>

		<?php } ?>
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