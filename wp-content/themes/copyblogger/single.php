<?php get_header(); ?>

	<div id="content_box">
		
		<div id="content" class="posts single">
			
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<?php include (TEMPLATEPATH . '/navigation.php'); ?>
			
			<h1><?php the_title(); ?></h1>
			<p class="post_date"><?php the_time('F jS, Y') ?> | <?php the_category(', ') ?></p>
			<div class="entry">
				<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
			</div>
			
			<?php comments_template(); ?>
			
		<?php endwhile; else: ?>
		
			<h1>Uh oh.</h1>
			<p class="post_date">You better <em>never</em> see this text.</p>
			<div class="entry">
				<p>Sorry, no posts matched your criteria. Wanna search instead?</p>
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</div>
			
		<?php endif; ?>
		
		</div>
		
		<?php get_sidebar(); ?>
			
	</div>

<?php get_footer(); ?>