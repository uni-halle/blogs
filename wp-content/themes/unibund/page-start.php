<?php

/*
Template Name: Startseite
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
	        <div class="start-text">
	            <?php the_content(); ?> 
	        </div>
	
	    <?php
	    	endwhile; 
	    	wp_reset_query(); 
	    ?>
		
	
		<section class="project-container">
		<?php
		
		
		
		/***************
		 * 
		 * 		Projekte
		 * 
		 **************/
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'category_name' => 'projekte',
			'paged' => $paged,
			'posts_per_page' => 3
		);
 
		query_posts($args); 
		
		// Start the Loop.
			
			$i=0;
			while ( have_posts() ) : the_post();
				$i++;

				
				print ('<div class="project-list project-'.$i.'">');
				
				get_template_part( 'template-parts/start-projekte', get_post_format() );
				
				print ('</div>');

			// End the loop.
			endwhile;

		
		?>
		
		
		
		</section>
		
		<section class="news-container">
			
			<div class="box-50">
				
				<h3 class="start-h3 side-text"><i class="material-icons">radio_button_unchecked</i><span><?php echo __('[:de]News[:en]News'); ?></span></h3>
				
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
					'posts_per_page' => 2,
					'post_status' => array('publish', 'future'),
				);
				query_posts($args); 
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/start-news', 'page' );
				endwhile;
				?>
				
				<div class="start-link"><a href="<?php echo get_page_link(29); ?>"><span class="link-text"><?php echo __('[:de]alle News[:en]all news'); ?></span><span class="text-icons"><i class="material-icons">arrow_forward</i></span></a></div>
				
			</div>
		
			<div class="box-50">
				
				<h3 class="start-h3 side-text"><i class="material-icons">radio_button_unchecked</i><span><?php echo __('[:de]Termine[:en]Events'); ?></span></h3>
				
				<?php
				/***************
				 * 
				 * 		Termine
				 * 
				 * *************/
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
					'category_name' => 'events',
					'paged' => $paged,
					'posts_per_page' => 3,
					'post_status' => array('future'),
				);
				query_posts($args); 
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/start-termine', 'page' );
				endwhile;
				?>
				
				<div class="start-link"><a href="<?php echo get_page_link(21); ?>"><span class="link-text"><?php echo __('[:de]alle Termine[:en]all events'); ?></span><span class="text-icons"><i class="material-icons">arrow_forward</i></span></a></div>
				
			</div>
			
			
		
		
		</section>


	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->


<?php get_footer(); ?>
