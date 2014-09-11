<?php get_header(); ?>

    <div class="browsing-archive">
    
	    <?php if (have_posts()) : ?>
						
			<div id="header-about">
								
            	<h2><span class="fl"><?php echo get_option('bizzthemes_search_results'); ?> &raquo;<?php printf(__('%s'), $s) ?>&laquo;</span> <span class="fr rss-archive"><a href="<?php echo get_option('home'); ?>?feed=rss2&s=<?php printf(__('%s'), $s) ?>"><?php echo get_option('bizzthemes_subscribe_follow_term'); ?></a></span></h2>          
				
			</div>
			
	</div>
	
	<div class="blog">
	
	    <!-- AdSense Search: START -->
	
	        <?php if (get_option('bizzthemes_search_adsense') <> "") { ?>
							
				<div class="adsense-468">
		
		            <?php echo stripslashes(get_option('bizzthemes_search_adsense')); ?>
		
		        </div>
							
		    <?php } ?>	
		
        <!-- AdSense Search: END -->
		
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
		
		<?php else : ?>
			
			<div class="browsing-archive">
    		
        		<div id="header-about">
								
            	<h2><?php echo get_option('bizzthemes_search_nothing_found'); ?></h2>        
				
				</div>
				
	        </div>
		
		<?php endif; ?>
		
		<div class="fix"></div>
	
	    <div class="pagination">
			
            <?php if (function_exists('wp_pagenavi')) { ?><?php wp_pagenavi(); ?><?php } ?>
						
        </div>
					
    </div>		
		
</div>

<?php get_sidebar(); ?>
   	    
<?php get_footer(); ?>
