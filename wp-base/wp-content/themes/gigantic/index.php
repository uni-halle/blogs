<?php get_header(); ?>

<div id="content">

<?php query_posts('showposts=3'); ?>

<?php while (have_posts()) : the_post(); ?>
		
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	
	<p class="post_meta"><?php the_author(); ?>, <?php the_time('F jS, Y'); ?>. <?php comments_number('No comments','One comment','% comments'); ?>.</p>
	
	<?php the_excerpt(); ?>
	
<?php endwhile; ?>
		
</div> <!-- end content -->

</div> <!-- end main -->

<?php get_footer(); ?>
