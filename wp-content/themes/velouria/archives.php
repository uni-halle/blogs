<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

	<div class="page">
	
		<h2><?php wp_title('',true,'right'); ?></h2>
		
		<h3>Essential reading:</h3>
		
		<?php query_posts('tag=lead&showposts=100'); ?>
		
		<?php while (have_posts()) : the_post(); ?>
		
			<h4><a href="<?php the_permalink(); ?> " title="Read this post in full"><?php the_title(); ?></a></h4>
									
			<?php the_excerpt(); ?>
									
		<?php endwhile; ?>
		
		<h3>Everything:</h3>
		
		<?php query_posts('showposts=999'); ?>
		
		<?php while (have_posts()) : the_post(); ?>
		
			<h5><a href="<?php the_permalink(); ?> " title="Read this post in full"><?php the_title(); ?></a></h5>
									
		<?php endwhile; ?>

	</div>

<?php get_footer(); ?>