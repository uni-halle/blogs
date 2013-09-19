<?php get_header(); ?>

	<div id="content">

	  <?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; ?>
 	  
 	  <?php if (is_category()) { ?>
		
		<h2 class="pagetitle"><?php _e('Category archive for','Mimbo'); ?> &#8216;<?php single_cat_title(); ?>&#8217; <a href="<?php echo get_category_feed_link(); ?>feed/"><img src="<?php bloginfo('template_url'); ?>/images/rss.gif" alt="rss" /></a> </h2>
 	  
 	  <?php } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><?php _e('Tag archive for','Mimbo'); ?> &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  
 	  <?php } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php _e('Date archive for','Mimbo'); ?> <?php the_time('F jS, Y'); ?></h2>
 	  
 	  <?php } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php _e('Date archive for','Mimbo'); ?> <?php the_time('F, Y'); ?></h2>
 	  
 	  <?php } elseif (is_year()) { ?>
		<h2 class="pagetitle"><?php _e('Date archive for','Mimbo'); ?> <?php the_time('Y'); ?></h2>
	  
 	  <?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php _e('Blog Archives','Mimbo'); ?></h2>
 	  <?php } ?>

   <ul class="archive-list clearfloat">
		<?php while (have_posts()) : the_post(); ?>
		
			<?php include (TEMPLATEPATH . '/archivelist.php'); ?>

		<?php endwhile; ?>
	</ul>

		<?php include (TEMPLATEPATH . '/pagination.php'); ?>
		
	<?php else : ?>

		<h2><?php _e('Not Found','Mimbo'); ?></h2>
		
	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
