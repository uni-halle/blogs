<?php get_header(); ?>
    
 <?php if (have_posts()) : ?>
 
	<?php while (have_posts()) : the_post(); ?>
        
		<div class="post">
		
		    <div id="header-about">
    
	            <h2><?php the_title(); ?> <?php edit_post_link('<span class="edit-entry"></span>'); ?></h2>
			
			</div>
			          
            <div class="entry">
				
			    <?php the_content(); ?>
					
            </div>
			
			<div class="last-updated">
						
		        <?php if ( $last_id = get_post_meta($post_ID, '_edit_last', true) ) {
	            
				$last_user = get_userdata($last_id);
	            
				printf(__('Page last updated by %1$s on %2$s at %3$s'), wp_specialchars( $last_user->display_name ), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));} 
	            
				else 
				
				{printf(__('Page last updated on %1$s at %2$s'), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));}
				
				?>
							
		    </div>
                
		</div>
	
	<?php endwhile; else: ?>

			<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>
    	    
<?php get_footer(); ?>
