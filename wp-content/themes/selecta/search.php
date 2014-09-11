<?php get_header(); ?>
	
    <?php get_sidebar(); ?>

	<div id="content">
    	<?php
			if (have_posts()) :
				while (have_posts()) :	the_post(); 			
					setup_postdata($post);				
					// Fetch the PermaLink, Thumbnail and Video Metas
					$get_post_video = get_post_meta($post->ID, "main_video", true);
					$get_thumbnail = get_post_meta($post->ID, "other_media", true);
					$link = get_permalink($post->ID);
					// Set our category
					$category = get_the_category(); 			
					// If we haven't used our custom image input, search for the first image in the post
					$post_image = "";
					if($get_thumbnail == "" && (get_option("ocmx_auto_home_images") && get_option("ocmx_auto_home_images") !== "no")) :
						$post_image =  fetch_post_image($post->ID, "560", "2000");
					endif;
                    
        ?>
                <div class="post">
                    <h4 class="date"><?php echo date('d M Y', strtotime($post-> post_date)); ?></h4>
                    <h2 class="title"><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h2>
                    <div class="container-header-light-normal"><span></span></div>
                    <div class="copy clearfix">
                        <?php if($get_post_video !== "") :
                            $get_post_video = preg_replace("/(width\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 560 $2", $get_post_video);
                            $get_post_video = preg_replace("/(height\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 312 $2", $get_post_video);
                       ?>
                            <div class="media">
                                <?php echo $get_post_video; ?>
                            </div>
                        <?php elseif ($post_image !== "") :  ?>
                            <div class="media">
                                <?php echo $post_image; ?>
                            </div>
                        <?php elseif ($get_thumbnail !== "") : ?>
                            <div class="media">
                                <img src="<?php bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_thumbnail ?>&amp;h=&amp;w=560&amp;zc=1" alt="<?php the_title(); ?>" />
                            </div>
                        <?php endif;
                        if($post->post_excerpt !== "") :
                            the_excerpt();
                        else :
                            the_content();
                        endif; ?>
                        
                        <?php if (comments_open()) : ?> <a href="<?php echo $link;?>#comments" class="post-comments"><?php  echo $post->comment_count; ?> Comments</a><?php endif; ?>
                        <a href="<?php echo $link;?>" class="action-link"><span>Continue reading</span></a>
                    </div>
                    <div class="container-footer-light-normal"><span></span></div>
                </div>
		<?php
				endwhile;		
				/* Pagination */
				ocmx_pagination();		
			else:
		?>
            <div class="post">
				<h4 class="date">This just happened.</h4>
                <h2 class="title"><a href="#">Everyone needs to slow down</a></h2>
                <div class="container-header-light-normal"><span></span></div>
                <div class="copy clearfix">There are no posts which match your criterea.</div>
                <div class="container-footer-light-normal"><span></span></div>
            </div>
		<?php
			endif;
		?>
		
	</div>
    
<?php get_footer(); ?>