<?php get_header() ?>

	<div id="content">

		<?php if ( have_posts() ) : ?>

			<h2>Search results for &#8220;<?php the_search_query() ?>&#8221;.</h2>
			
			<?php while ( have_posts() ) : the_post() ?>
			
				<h3 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
						
				<p class="post_meta"><?php the_author(); ?>, <?php the_time('F jS, Y'); ?>. <?php comments_number('No comments','One comment','% comments'); ?>.</p>
					
				<?php the_excerpt(); ?>
			
			<?php endwhile; ?>

		<?php else : ?>

		<h2>Unfortunately your search for &#8220;<?php the_search_query() ?>&#8221; returned no results.</h2>
		
		<p>You could always try another search.</p>			

		<?php endif; ?>

	</div>
	
<?php get_footer() ?>
