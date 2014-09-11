<?php get_header(); ?>
	
	<div id="content">
	
		<?php if (have_posts()) : ?>
		
			<?php $post = $posts[0]; ?>

			<?php if (is_category()) { ?><h3><?php _e('Browsing archives for',woothemes); ?> '<?php echo single_cat_title(); ?>'</h3>
			<?php } elseif (is_day()) { ?><h3><?php _e('Browsing archives for',woothemes); ?> <?php the_time('F jS, Y'); ?></h3>
			<?php } elseif (is_month()) { ?><h3><?php _e('Browsing archives for',woothemes); ?> <?php the_time('F, Y'); ?></h3>
			<?php } elseif (is_year()) { ?><h3><?php _e('Browsing archives for',woothemes); ?> <?php the_time('Y'); ?></h3>
			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?><h3>Archives</h3>

			<?php } ?>
		
		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			                
            <h2><a title="<?php _e('Permanent Link to',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
           	<p class="meta">
           		<span class="label"><?php the_category(','); ?></span><!-- label -->
           		<?php the_time('j F Y'); ?> | <?php comments_popup_link(__('0 Comments',woothemes), __('1 Comment',woothemes), __('% Comments',woothemes)); ?>
           	</p>
				
				<div class="entry">
					<?php woo_get_image('image',get_option('woo_thumb_width'),get_option('woo_thumb_height'),'thumb alignleft'); ?>
						
					<?php
					if ( get_option('woo_content_archives') == "true" ) 
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