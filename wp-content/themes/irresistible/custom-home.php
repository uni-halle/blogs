<?php get_header(); ?>

		<div id="content" class="home">
		
			<div id="main">
			
                <h3 id="myWritings" class="replace">My Writings. My Thoughts.</h3>
				
				<div class="box1 clearfix">
				
					
                    <?php query_posts('showposts='.get_option('woo_home_posts')); ?>															
					<?php if (have_posts()) : ?>															
					<?php while (have_posts()) : the_post(); ?>

                        <div class="post clearfix">
                            <h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3                            ><span class="txt0"><?php edit_post_link('Edit', '', ''); ?> // <?php the_time('F jS, Y') ?> // <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> // <?php the_category(', ') ?></span>
                        
	                        <?php the_excerpt() ?>
    
                        </div>
					
					<?php endwhile; ?>					
					<?php else : ?>
					
					<h3 class='center'>No posts found.</h3>
					
					<?php endif; ?>
					
					<p class="fr"><a href="<?php echo get_option('woo_home_archives'); ?>">View Archives</a></p>
				
				</div>
				
				<?php include ( TEMPLATEPATH . "/includes/video-home.php" ); ?>
			
			</div><!-- / #main -->
			
			<div id="sidebar">
						
                <!-- 
                <div id="portfolio">
                    
                    <h3 id="myPortfolio" class="replace">My Portfolio. Recent Works.</h3>
                
                    <ul class="img-list clearfix">
                    
                        <li><a href="#"><img src="<?php bloginfo('template_url') ?>/images/img-portfolio-1.jpg" alt="" /></a></li>
                        <li><a href="#"><img src="<?php bloginfo('template_url') ?>/images/img-portfolio-2.jpg" alt="" /></a></li>
                        <li><a href="#"><img src="<?php bloginfo('template_url') ?>/images/img-portfolio-3.jpg" alt="" /></a></li>
                        <li><a href="#"><img src="<?php bloginfo('template_url') ?>/images/img-portfolio-4.jpg" alt="" /></a></li>
                    
                    </ul>
                    <div class="clear"></div>					
                
                </div>
				-->
                
                <div id="flickr">
                    
                    <h3 id="myPhotos" class="replace">My photos. Now you know me.</h3>
                
                    <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo get_option('woo_home_flickr_count'); ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo get_option('woo_home_flickr_user'); ?>"></script>        
                    
                    <div class="clear"></div>
					<a href="<?php echo get_option('woo_home_flickr_url'); ?>" class="replace"><p id="browseFlickr"><span class="replace">Browse Flickr</span></p></a>
                
                </div>
				
				<h3 id="myLifestream" class="replace">My lifestream. Stay updated with me.</h3>
				
				<div class="box1 clearfix">	
					
					<?php if (function_exists(lifestream)) lifestream(get_option('woo_home_lifestream')); ?>
					
				</div>               			

			    <?php if ( !get_option('woo_tabs') ) include ( TEMPLATEPATH . "/includes/tabs.php" ); ?>

				<div id="myfavblog" class="fr">			
					
					<h3 id="myFavblog" class="replace">My favblog. Feeds from them.</h3>
					
					<div class="box1">
                        <ul class="list2">
                        
							<!-- You can put whatever you want in this box... this is just an example -->
							<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
                            
                        </ul>
                    </div>
				
				</div>							
							
			</div><!-- / #sidebar -->

        </div><!-- / #content -->
            
		
<?php get_footer(); ?>