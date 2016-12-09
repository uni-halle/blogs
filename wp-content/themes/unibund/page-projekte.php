<?php

/*
Template Name: Projektseite
*/


get_header(); ?>

<div id="primary" class="content-area">
	
	
	
	
	<main id="main" class="site-main" role="main">
		
		
		
		
		
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
		
		
		
		
		<?php
		
		
		/***************
		 * 
		 * 		Projekte
		 * 
		 * *************/
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'category_name' => 'projekte',
			'paged' => $paged,
			'posts_per_page' => 2
		);
 
		query_posts($args); 
		
		// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content-projekte', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );
		
		
		
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
