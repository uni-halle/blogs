<?php get_header(); ?>
	
	<div id="content">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			                
            <h2><a title="<?php _e('Permanent Link to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
           	<p class="meta">
           		<span class="label"><?php the_category(','); ?></span><!-- label -->
           		<?php the_time('j F Y'); ?> | <?php comments_popup_link(__('0 Comments',woothemes), __('1 Comment',woothemes), __('% Comments',woothemes)); ?>
           	</p>
           		
           		<?php echo woo_get_embed('embed','550','309'); ?>
				
				<div class="entry">
					<?php woo_get_image('image',get_option('woo_thumb_width'),get_option('woo_thumb_height'),'thumb alignleft'); ?>
						
					<?php
					if ( get_option('woo_content_home') == "true" ) 
						the_content('[...]'); 
					else 
						the_excerpt(); 
					?>
				</div><!--entry-->	
                
            <p class="tags"><?php the_tags(__('Tagged in ', woothemes), ', ', ''); ?> </p>   				
				
			</div><!--post-->		
		
		<?php endwhile; endif; ?>

		<div id="navigation">
			<div id="prev"><?php next_posts_link(__('Older Posts &raquo;',woothemes)) ?></div>
			<div id="next"><?php previous_posts_link(__('&laquo; Newer Posts',woothemes)) ?></div>
		</div>				
		
		<div class="fix"></div>
	
	</div><!--content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>