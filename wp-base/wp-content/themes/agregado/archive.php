<?php get_header(); ?>
	<div id="main" class="clearfloat">
	
	<div id="content">
	<?php is_tag(); ?>
		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h3 class="pagetitle">Post archive for &#8216;<?php single_cat_title(); ?>&#8217;</h3>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h3 class="pagetitle">Tag archive for &#8216;<?php single_tag_title(); ?>&#8217;</h3>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h3 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h3>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h3 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h3>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h3 class="pagetitle">Archive for <?php the_time('Y'); ?></h3>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h3 class="pagetitle">Author Archives</h3>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h3 class="pagetitle">News Archives</h3>
 	  <?php } ?>

		<?php while (have_posts()) : the_post(); ?>
		<?php include (TEMPLATEPATH . "/loop.php"); ?>
		<?php endwhile; ?>

			<?php paged_menu(); ?>
		

	<?php else : ?>

		<h3 class="center">Not Found</h3>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

	</div><!--END CONTENT-->

<?php get_sidebar(); ?>
</div><!--END MAIN-->



</div><!--END WRAPPER-->
</div><!--END TOP-->


<?php get_footer(); ?>
