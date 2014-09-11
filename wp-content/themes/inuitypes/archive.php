<?php get_header(); ?>

    <div class="browsing-archive">
    
	    <?php if (have_posts()) : ?>
				
		    <?php if (is_category()) { ?>
			<h2><span class="fl"><?php echo get_option('bizzthemes_browsing_category'); ?> &raquo;<?php echo single_cat_title(); ?>&laquo;</span></h2>  

			<?php } elseif (is_day()) { ?>
			<h2><span class="fl"><?php echo get_option('bizzthemes_browsing_day'); ?> &raquo;<?php the_time('F jS, Y'); ?>&laquo;</span></h2>

			<?php } elseif (is_month()) { ?>
			<h2><span class="fl"><?php echo get_option('bizzthemes_browsing_month'); ?> &raquo;<?php the_time('F, Y'); ?>&laquo;</span></h2>

			<?php } elseif (is_year()) { ?>
			<h2><span class="fl"><?php echo get_option('bizzthemes_browsing_year'); ?> &raquo;<?php the_time('Y'); ?>&laquo;</span></h2>
							
			<?php } elseif (is_tag()) { ?>
			<h2><span class="fl"><?php echo get_option('bizzthemes_browsing_tag'); ?> &raquo;<?php echo single_tag_title('', true); ?>&laquo;</span></h2>
			
			<?php } ?>
				
	</div>
		
	<div class="blog">
		
		<?php while (have_posts()) : the_post(); ?>	
            
            <div class="post">
            
			    <h2><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				
			    <div class="date-comments">
                    					
					<?php if ( get_option('bizzthemes_relative_date') ) { ?>
						
					    <p class="fl"><em><?php relativeDate(get_the_time('YmdHis')) ?></em></p>
						
					<?php } else { ?>
						
					    <p class="fl"><em><?php the_time('F j, Y'); ?></em></p>
						
					<?php } ?>
					
					<?php if ( get_option( 'bizzthemes_commentcount' )) { ?> 
					
					    <p class="fr"><span class="comments"><?php comments_popup_link('0', '1', '%'); ?></span></p>
						
					<?php } ?>
                
			    </div>
				
			    <div class="fix"></div>
                
			    <p><?php echo strip_tags(get_the_excerpt(), '<a><strong>'); ?></p>
				
            </div>

		<?php endwhile; ?>
		
		<div class="fix"></div>
	
	    <div class="pagination">
			
            <?php if (function_exists('wp_pagenavi')) { ?><?php wp_pagenavi(); ?><?php } ?>
						
        </div>
					
    </div>		
	
	<?php endif; ?>	

</div>

<?php get_sidebar(); ?>
   	    
<?php get_footer(); ?>
