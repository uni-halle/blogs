<?php $showrelative = get_option('bizzthemes_relative_date'); ?>

<?php get_header(); ?>

		<?php if (have_posts()) : ?>
	
			<?php while (have_posts()) : the_post(); ?>										

				<div id="post-<?php the_ID(); ?>" class="single-post">
					
					<div id="header-about">
	
	                <h1><?php the_title(); ?></h1>
	
	                </div>
										
					<div class="date-comments">
                    
				    <p class="fl">
						
						<?php if ( $showrelative ) { ?>
						
					    <em><?php relativeDate(get_the_time('YmdHis')) ?></em>
						
					    <?php } else { ?>
						
					    <em><?php the_time('F jS', '', ''); ?>, <?php the_time('Y'); ?></em> &rarr; <em><?php the_time('g:i a'); ?></em>
						
					    <?php } ?>
						
						@ <em><?php the_author_posts_link(); ?></em>
					
					</p>
                    
				    <p class="fr"><span class="comments">
					
					    <?php if ( comments_open() ) : ?>
						  
		                      <a href="#comments"><?php comments_number('0', '1', '%', 'Comments-link', ''.get_option('bizzthemes_comment_off_name').''); ?></a>
		                  
						<?php endif; ?>
					
					</span></p>
                
			        </div>
					
					<div class="clear"></div>
					
					<br/>

					<div class="entry">
					
						<?php if (( get_post_meta($post->ID,'image', true) ) && !( get_option('bizzthemes_hide_singleimg') )) { ?>
                            
                            <a title="<?php the_title(); ?>" href="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" rel="bookmark">
                            	
								<img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=300&amp;w=598&amp;zc=1&amp;q=80" alt="<?php the_title(); ?>" class="fl" style="margin:5px 0 0 0;" />  
                           
						    </a>
							
							<div class="fix"></div><br/>
						   
						<?php } ?>
						
						<?php the_content('<span class="read-on">Read On...</span>'); ?>

                        <?php the_tags('<div class="tags">'.get_option('bizzthemes_general_tags_name').':&nbsp;&nbsp;<em>', ', ', '</em></div>'); ?> 						
										
					</div>
					
					<div class="fix"></div>
																			
				</div>		
				
				<!-- AdSense Comments: START -->
	
	            <?php if (get_option('bizzthemes_comment_adsense') <> "") { ?>
							
					<div class="adsense-468">
		
		                <?php echo stripslashes(get_option('bizzthemes_comment_adsense')); ?>
		
		            </div>
							
		        <?php } ?>	
		
                <!-- AdSense Comments: END -->
		
	            <div class="fix"></div>

				<div id="comments">
				
					<?php comments_template(); ?>
					
				</div>

		<?php endwhile; ?>
		
	    <?php endif; ?>		

</div><!-- Content -->		

<?php get_sidebar(); ?>

<?php get_footer(); ?>