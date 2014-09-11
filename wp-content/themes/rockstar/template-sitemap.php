<?php
/*
Template Name: Sitemap Page
*/
?>

<?php get_header(); ?>
	
	<div id="content">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			                
            <h2><?php bloginfo('title'); ?> <?php _e('Sitemap',woothemes); ?></h2>
				
				<div class="entry">
						<h3><?php _e('Pages',woothemes); ?></h3>
								
							<ul><?php wp_list_pages('sort_column=menu_order&depth=0&title_li='); ?></ul>
								
						<h3><?php _e('Blog / News Categories',woothemes); ?></h3>
								
							<ul><?php wp_list_categories('depth=0&title_li=&show_count=1'); ?></ul>
								
						<h3><?php _e('Blog / News Monthly Archives',woothemes); ?></h3>
							
							<ul><?php wp_get_archives('type=monthly&limit=12'); ?> </ul>
				</div><!--entry-->  				
				
			</div><!--post-->				
		
		<?php endwhile; endif; ?>
	
	</div><!--content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>