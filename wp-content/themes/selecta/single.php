    <?php get_header(); ?>
    
    <?php get_sidebar(); ?>

	<div id="content">
		<?php if (have_posts()) : while (have_posts()) : the_post();    	        
			setup_postdata($post);
			//Fetch the Custom Metas for this post
			$get_thumbnail = get_post_meta($post->ID, "other_media", true);
			$get_post_video = get_post_meta($post->ID, "main_video", true);
			$link = get_permalink($post->ID);
		 ?>	
            <div class="post">
                <div class="container-header-light-normal"><span></span></div>
                <div class="copy clearfix">
	                <?php the_content(); ?>
                    
					<?php if(get_option("ocmx_promote_posts") == "yes") : ?>
                        <h2 class="post-section-title">Promote Post</h2>
                        <div class="promote-post">
                            <ul class="clearfix">
                                <li class="tweet">
                                    <script type="text/javascript">  
                                        tweetmeme_url = '<?php echo $link; ?>';
                                        tweetmeme_source = '<?php echo get_option("ocmx_twitter_id"); ?>';  
                                    </script>  
                                    <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
                                </li>
                                <li class="digg">
                                    <script type="text/javascript">
                                        digg_url = '<?php echo $link; ?>';
                                        digg_title = '<?php echo $post->post_title; ?>';
                                        digg_bodytext = '<?php echo strip_tags($post->post_excerpt); ?>';
                                        digg_media = '<?php echo $post->post_category ?>';
                                        digg_topic = '<?php echo $post->post_category ?>';
                                    </script>
                                    <script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>
                                </li>
                                <li class="moo">
                                    <script type="text/javascript">url_site = '<?php echo $link; ?>';</script>
                                    <script src="http://www.designmoo.com/sites/all/modules/drigg/drigg_external/button.js" type="text/javascript"></script>
                                </li>
                                <li class="bump">
                                    <script type="text/javascript"> url_site='<?php echo $link; ?>';</script>
                                    <script src="http://designbump.com/sites/all/modules/drigg_external/js/button.js" type="text/javascript"></script> 
                                </li>
                            </ul>
                        </div>
                	<?php endif; ?>  
                </div>
                <div class="container-footer-light-normal"><span></span></div>
            </div>
            
			<?php if(get_option("ocmx_post_ad_buysell_ads") == "on") : ?>
                <div class="post">
                    <div class="container-header-light-normal"><span></span></div>
                    <div class="copy clearfix"><?php echo stripslashes(get_option("ocmx_post_ad_buysell_id")); ?></div>
                    <div class="container-footer-light-normal"><span></span></div>
                </div>
            <?php elseif(get_option("ocmx_post_ad_image")) : ?>
                <div class="post">
                    <div class="container-header-light-normal"><span></span></div>
                    <div class="copy post-footer-advert">
                        <a href="<?php echo get_option("ocmx_post_ad_link"); ?>" target="_blank" rel="nofollow">
                            <img src="<?php echo get_option("ocmx_post_ad_image"); ?>" alt="<?php echo get_option("ocmx_post_ad_title"); ?>" />
                        </a>
                    </div>
                    <div class="container-footer-light-normal"><span></span></div>
                </div>
            <?php endif; ?>                  
			<?php comments_template(); ?>
                
        <?php endwhile; else: ?>
            <div class="post">
				<h4 class="date">This just happened.</h4>
                <h2 class="title"><a href="#">Everyone needs to slow down</a></h2>
                <div class="container-header-light-normal"><span></span></div>
                <div class="copy clearfix">There are no posts which match your criterea.</div>
                <div class="container-footer-light-normal"><span></span></div>
            </div>
        <?php endif; ?>    
	</div>
    
    <?php get_footer(); ?>