<?php get_header(); ?>

		
	<div id="main" class="clearfloat">
	<div id="content" class="clearfloat">
	<?php if (have_posts()) : ?>

		<h3 class="pagetitle">Search Results</h3>

		<?php while (have_posts()) : the_post(); ?>
		<?php include (TEMPLATEPATH . "/loop.php"); ?>
		<?php endwhile; ?>

		<div class="clearfloat pagination">
			<?php paged_menu(); ?>
		</div>

	<?php else : ?>

		<h3>No results found. Try again?</h3>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
	<?php endif; ?>

	
</div>

<?php get_sidebar(); ?>
</div><!--END MAIN-->



</div><!--END WRAPPER-->
</div><!--END TOP-->


<?php get_footer(); ?>