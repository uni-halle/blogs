<?php get_header(); ?>

	<?php while(have_posts()) : the_post(); ?>
	
	<div class="post clearfix" id="post-<?php the_ID(); ?>">
		<p class="byline">
			<span class="date"><?php the_time('F jS, Y'); ?></span><br /><em>by</em> <?php the_author_posts_link(); ?>
		</p>		
		<h2 class="title"><?php the_title(); ?></h2>
		<p class="dots clear">.........................................................</p>
				
		<span class="para">&#182;</span>
		<div class="entry">
			<?php the_content(); ?>
		</div>
			
	</div> <!-- .post -->
	
	<?php endwhile; ?>
	
	<div class="comments">
		<?php comments_template(); ?>
	</div>		

<?php get_footer(); ?>