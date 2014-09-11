<?php get_header(); ?>

	
	<div id="main" class="clearfloat">
	<div id="content" class="clearfloat">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php include (TEMPLATEPATH . "/loop.php"); ?>
		
		


	<div id="commentarea">
	<?php comments_template(); ?>
	</div>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
</div>

<?php get_sidebar(); ?>
</div><!--END MAIN-->



</div><!--END WRAPPER-->
</div><!--END TOP-->


<?php get_footer(); ?>
