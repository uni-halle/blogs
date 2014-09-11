<?php get_header(); ?>

<?php if (is_paged()) $is_paged = true; ?>

<?php if ( get_option('bizzthemes_one_column_featposts') ) { ?> 

<?php $imgwidthf = 600;
      $imgheightf = 250; ?> 

<?php } else { ?>

<?php $imgwidthf = 278;
      $imgheightf = 150; ?>

<?php } ?>

<?php if ( get_option('bizzthemes_one_column_posts') ) { ?> 

<?php $imgwidth = 90;
      $imgheight = 75; ?> 

<?php } else { ?>

<?php $imgwidth = 90;
      $imgheight = 75; ?>

<?php } ?>
                	
	<?php if ( get_option('bizzthemes_about_blog') <> "" ) { ?> 
	
	<div id="header-about">
	
	<h2><?php echo stripslashes(get_option('bizzthemes_about_blog')); ?></h2>
	
	</div>
	
	<?php } ?>
	
	    <?php if ( !$is_paged ) { } else { ?>
	
	    <div class="pagination">
			
            <?php if (function_exists('wp_pagenavi')) { ?><?php wp_pagenavi(); ?><?php } ?>
						
        </div>
		
		<div class="fix"></div>
				
		<?php } ?> 
				
	<div class="<?php if ($is_paged || get_option('bizzthemes_one_column_posts')) { echo 'full_posts'; } else { echo 'boxed_posts'; } ?> <?php if ($is_paged || get_option('bizzthemes_one_column_featposts')) { echo 'full_featposts'; } else { echo 'boxed_featposts'; } ?> blog">
	
	<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts("cat=-".$GLOBALS[vid_cat]."&paged=$paged"); ?>
					
	<?php if (have_posts()) : $count = 0; ?>

	<?php while (have_posts()) : the_post(); $postcount++;?>
	        
        <!-- Featured Posts: START -->
					
            <?php if ( $postcount <= get_option('bizzthemes_featured_entries') && !$is_paged ) { ?>
                        
                <div class="featured_post feat_background fl">
                        
                    <?php if ( get_post_meta($post->ID,'image', true) ) { ?>
                
                        <a title="Link to <?php the_title(); ?>" href="<?php the_permalink() ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=<?php echo $imgheightf; ?>&amp;w=<?php echo $imgwidthf; ?>&amp;zc=1&amp;q=80" alt="<?php the_title(); ?>" class="fll" style="margin-top:5px;" /></a>          	
                        						
						<?php if ( get_option('bizzthemes_relative_date') ) { ?>
						
					        <span class="date_bg"><?php relativeDate(get_the_time('YmdHis')) ?></span>  
						
					    <?php } else { ?>
						
					        <span class="date_bg"><?php the_time('d M y'); ?></span>
						
					    <?php } ?>
							
                    <?php } ?> 
                        
                        <div class="featured_content">
						
                            <h2>
							
							<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
							
							<?php if ( get_option( 'bizzthemes_commentcount' )) { ?> 
					
					        <span class="comments-feat">- <?php comments_popup_link('0', '1', '%'); ?></span>
						
					        <?php } ?>
							
							</h2>
							
                        </div>
                    
					<div class="fix"></div>
                        
                </div>
                        
			<?php continue; } ?>
			
			<!-- Split between Featured entries and Rest: START -->
			
			<?php if (( get_option('bizzthemes_featured_entries') + 1) == $postcount  && !$is_paged ) { ?>
			
			    <div class="fix"><!----></div>
						
			    <!-- AdSense Main: START -->
	
	            <?php if (get_option('bizzthemes_main_adsense') <> "") { ?>
		
		            <div class="adsense-468">
		
		                <?php echo stripslashes(get_option('bizzthemes_main_adsense')); ?>
		
		            </div>
							
		        <?php } ?>	
		
                <!-- AdSense Main: END -->
			
            <?php } ?>
			
			<!-- Split between Featured entries and Rest: END -->
						
		<!-- Featured Posts: END -->
								
		<!-- Rest of Entries: START -->
		                                                                                    
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
				
				<?php if ( get_post_meta($post->ID,'image', true) ) { ?>
                
                        <a title="Link to <?php the_title(); ?>" href="<?php the_permalink() ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=<?php echo $imgheight; ?>&amp;w=<?php echo $imgwidth; ?>&amp;zc=1&amp;q=80" alt="<?php the_title(); ?>" class="fll" style="margin-top:5px; margin-right:10px" /></a>          	
                        							
                <?php } ?>
				                
			    <p><?php echo strip_tags(get_the_excerpt(), '<a><strong>'); ?></p>
				
            </div>
                            				
		<!-- Rest of Entries: END -->
		
		<?php if (!get_option('bizzthemes_one_column_posts')) { $count++; if ($count == 2) { $count = 0; ?><div class="fix"></div><?php } } ?>
    
	<?php endwhile; ?>
					
	<?php endif; ?>
	
	<div class="fix"></div>
	
	    <div class="pagination">
			
            <?php if (function_exists('wp_pagenavi')) { ?><?php wp_pagenavi(); ?><?php } ?>
						
        </div>
					
    </div>
                                    
</div>

<?php get_sidebar(); ?>
	    
<?php get_footer(); ?>
