<?php get_header(); ?>

	<div id="content_box">
	
		<div id="left_box">
	
			<div id="content">
		
				<div id="content_inner">
<?php 
				if (have_posts()) : 
					while (have_posts()) : the_post(); ?>
		
					<h1><?php the_title(); ?></h1>
					<p class="post_author"><em>by</em> <?php the_author(); ?></p>
					<div class="format_text">
<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>
<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
					</div>
		
				</div>
		
				<?php comments_template(); ?>
<?php 
					endwhile;
				else: ?>
	
					<h1>Uh oh.</h1>
					<div class="format_text">
						<p>Sorry, no posts matched your criteria. Wanna search instead?</p>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
					</div>
				
				</div>
<?php 
				endif; ?>
		
			</div>
		
			<?php include (TEMPLATEPATH . '/left_bar.php')?>
	
		</div>
	
		<?php get_sidebar(); ?>
		
	</div>

<?php get_footer(); ?>