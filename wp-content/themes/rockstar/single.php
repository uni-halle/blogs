<?php get_header(); ?>
	
	<div id="content">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			                
            <h2><a title="<?php _e('Permanent Link to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
           	<p class="meta">
           		<span class="label"><?php the_category(','); ?></span><!-- label -->
           		<?php the_time('j F Y'); ?> | <?php comments_popup_link(__('0 Comments',woothemes), __('1 Comment',woothemes), __('% Comments',woothemes)); ?>
           	</p>
				
				<div class="entry">
					<?php the_content(); ?>
				</div><!--entry-->	
                
            <p class="tags"><?php the_tags(__('Tagged in ', woothemes), ', ', ''); ?> </p>   				
				
			</div><!--post-->				

			<div id="comments">
				<?php comments_template('',true); ?>
			</div><!--comments-->			
		
		<?php endwhile; endif; ?>
	
	</div><!--content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>