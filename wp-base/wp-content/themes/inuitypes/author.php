<?php get_header(); ?>
		
		<?php
		
			if(isset($_GET['author_name'])) :
			$curauth = get_userdatabylogin($author_name);
			else :
			$curauth = get_userdata(intval($author));
			endif;
			
		?>

		<?php if (have_posts()) : ?>
		
		<div class="browsing-archive">
		
		    <div id="header-about">
    
	            <h2>&raquo;<?php echo $curauth->nickname; ?>&laquo;</h2>
			
			</div>
			
	    </div>

			<div class="author-archive">
            		                	                	                	
                		<?php
                			// Determine which gravatar to use for the user
							$GLOBALS['defaultgravatar'] = $template_path . '/images/gravatar.png';
                			$email = $curauth->user_email;
                			$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($email). "&default=".urlencode($GLOBALS['defaultgravatar'] )."&size=48";
                		?>
                		
                        <span class="auth_gravatar"><img src="<?php echo $grav_url; ?>" width="48" height="48" alt="" /></span>
                        
						<p><b><?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?></b> &nbsp;|&nbsp; <?php echo $curauth->user_email; ?> &nbsp;|&nbsp; <b><?php the_author_posts(); ?> posts</b></p><br/>
					    					    
						<p><em><?php echo $curauth->description; ?></em><br class="fix" /></p>
                        	                
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