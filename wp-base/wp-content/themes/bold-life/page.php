<?php get_header(); ?>
<!-- Mainbar -->
<div class="content">
    	<div class="span-18">
        <?php if (have_posts()) : ?>		
            <?php while (have_posts()) : the_post(); ?> 
            <div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
            	<div class="span-3">
                    <div class="date">
                    <span><?php the_time('d')?></span> <?php the_time('F')?>
                    </div>
                    <?php comments_popup_link('<span>0</span>Comments', '<span>1</span>Comment', '<span>%</span>Comments', 'comment_left'); ?>
                </div>  
                
                <div class="span-15 last">
                    <div class="entry">
                    <div class="entry_inner">
                        <h2 class="post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post_meta">Posted by <?php the_author() ?> in <?php the_category(',') ?></div>
						<?php the_content('Read more...'); ?> 
                        <?php wp_link_pages('before=<div class="post_pages"><b>Pages:</b> <span id="page-links">&after=</span></div>'); ?>
                    
                    <?php the_tags( '<div class="post_tags">Tags: ', ', ', '</div> '); ?><div class="clear"></div>
                    </div>	
                    </div>
                </div>
                <div class="clear"></div>
	        </div>
        <?php comments_template(); ?>
        <?php endwhile; ?>
                
        <?php else : ?>
        <div class="post">
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        </div>
        <?php endif; ?>   
</div>
<!-- End of Mainbar -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>