<?php get_header(); ?>
<!-- Mainbar -->
<div class="mainbar">
    <div class="mainbar_top">
        <div class="mainbar_bottom">
            <div class="mainbar_inner">
    <?php if (have_posts()) : ?>		
		<?php while (have_posts()) : the_post(); ?>
        
        		<div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
              
                    <h2 class="post_header" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <div class="post_line"></div>
                    <div class="post_content">
                    	<?php the_content('Read more...'); ?> 
                        <?php wp_link_pages('before=<b>Pages:</b><br/><div id="page-links">&after=</div>'); ?>
                    </div>
                    
                </div>  
        

		
		<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>

		<?php endif; ?>
    </div>
        </div>
    </div>
</div>  <!-- End of Mainbar -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>