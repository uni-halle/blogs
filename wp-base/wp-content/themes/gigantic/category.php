<?php get_header() ?>

	<div id="content">

		<h2 class="page-title">Posts filed under &#8220;<?php single_cat_title() ?>&#8221;.</h2>
			
		<div class="description"><?php echo category_description(); ?></div> <!-- wrapped in a div to validate - wp wraps in p tags -->

		<?php while ( have_posts() ) : the_post() ?>
		
			<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
			
			<p class="post_meta"><?php the_author(); ?>, <?php the_time('F jS, Y'); ?>. <?php comments_number('No comments','One comment','% comments'); ?>.</p>
			
			<?php the_excerpt(); ?>

		<?php endwhile; ?>

		</div><!-- #content -->
		
	</div><!-- #main -->

<?php get_footer() ?>
