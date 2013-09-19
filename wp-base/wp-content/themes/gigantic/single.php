<?php get_header(); ?>

<div id="content">

<?php while (have_posts()) : the_post(); ?>

	<h2><?php the_title(); ?></h2>
	
	<p class="post_meta post_details"><?php the_author(); ?>, <?php the_time('F jS, Y'); ?>. <a href="#comments"><?php comments_number('No comments','One comment','% comments'); ?></a>. Filed under <?php the_category(', '); ?>.</p>
	
	<?php the_content(); ?>
	
	<div id="pagination">
	
		<p class="previous"><?php previous_post_link('Previous post:  %link') ?></p>
		
		<p class="next"><?php next_post_link('Next post: %link') ?></p>
		
	</div>
	
	<?php comments_template(); ?>
		
<?php endwhile; ?>

</div> <!-- end content -->

</div> <!-- end main -->

<?php get_footer(); ?>
