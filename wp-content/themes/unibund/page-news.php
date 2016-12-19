<?php

/*
Template Name: Newsseite
*/


get_header(); ?>

<div id="primary" class="content-area">
	
	
	
	
	<main id="main" class="site-main-wide" role="main">
		
		<?php
		
		
		/***************
		 * 
		 * 		Content
		 * 
		 * *************/
		 
		
	    while ( have_posts() ) : the_post(); ?> 
	        <div class="entry-content-page">
	            <?php the_content(); ?> 
	        </div>
	
	    <?php
	    	endwhile; 
	    	wp_reset_query(); 
	    ?>
		

		
		
			<h3 class="start-h3"><i class="material-icons">radio_button_unchecked</i><span><?php echo __('[:de]News[:en]News'); ?></span></h3>
				
			<?php
				/***************
				 * 
				 * 		News
				 * 
				 * *************/
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
					'category_name' => 'news',
					'paged' => $paged,
					'posts_per_page' => 200
				);
				query_posts($args);
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/start-news', 'page' );
				endwhile;
			?>
				
				
				
		<div class="clearfix"></div>
	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
