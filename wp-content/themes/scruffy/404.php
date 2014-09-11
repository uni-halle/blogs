<?php get_header(); ?>

	<div id="content">
	
		<div class="post first" id="post-<?php the_ID(); ?>">
			
			<h2 class="title">404 Error</h2>
			
			<div class="entry">
			
				<h3>Sorry, looks like you followed a bad link! <a href="<?php bloginfo('home'); ?>" title="Home">Go back home</a></h3>
				
			</div><!-- /entry -->
			
		</div><!-- /post -->
	
	</div><!-- /content -->
		
<?php get_sidebar() ?>

<?php get_footer(); ?>