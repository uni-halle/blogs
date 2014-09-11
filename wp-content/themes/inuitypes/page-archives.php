<?php
/*
Template Name: Archives Page
*/
?>

<?php get_header(); ?>

		    <div id="header-about">
				
				<h2><?php the_title(); ?></h2>
				
			</div>
					
			<div class="arclist box">
	
				<ul>
				
					<?php query_posts('showposts=60'); ?>
					
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
                        <li>
						
						<div class="archives-time"><?php the_time('M j Y') ?></div>
						
						<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
						
						- <?php echo $post->comment_count ?>
						
						</li>
                    
                    <?php endwhile; endif; ?>
					
				</ul>				
			
			</div>												
			
			<div class="fix"></div>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>