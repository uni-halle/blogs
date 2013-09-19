    <?php get_header(); ?>
    
    <?php get_sidebar(); ?>

	<div id="content">
		<?php if (have_posts()) : while (have_posts()) : the_post();    	        
				setup_postdata($post);
				//Fetch the Custom Metas for this post
				$get_thumbnail = get_post_meta($post->ID, "other_media", true);;
				$get_post_video = get_post_meta($post->ID, "main_video", true);
		 ?>	
            <div class="post">
                <div class="container-header-light-normal"><span></span></div>
                <div class="copy clearfix">
	                <?php the_content(); ?>
                </div>
                <div class="container-footer-light-normal"><span></span></div>
            </div>              
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