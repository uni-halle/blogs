<?php get_header(); ?>

	<div id="content_box">
	
		<div id="left_box">
	
			<div id="content" class="page">

				<div id="content_inner">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
					<h1><?php the_title(); ?></h1>	
					<div class="format_text">		
<?php the_content('<p>Read the rest of this page &rarr;</p>'); ?>
<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
					</div>
				
				</div>
				
				<?php endwhile; endif; ?>

			</div>

			<?php include (TEMPLATEPATH . '/left_bar.php'); ?>

		</div>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>