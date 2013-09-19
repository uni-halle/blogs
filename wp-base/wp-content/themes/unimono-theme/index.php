<?php get_header(); ?>
<!-- Mainbar -->
<div class="mainbar">
    <div class="mainbar_top">
        <div class="mainbar_bottom">
            <div class="mainbar_inner">
			<?php if (have_posts()) : ?>		
                <?php while (have_posts()) : the_post(); ?>            
                <div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
                    <div class="post_date">
                        <div class="post_date_top"><?php the_time('d')?></div>
                        <div class="post_date_bottom"><?php the_time('M') ?></div>                                            
                    </div>
                    <h2 class="post_header" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <div class="post_line"></div>
                    <div class="post_content">
                    	<?php the_content('Read more...'); ?> 
                        <?php wp_link_pages('before=<b>Pages:</b><br/><div id="page-links">&after=</div>'); ?>
                    </div>
                    <div class="post_data">
                    Eingestellt von <?php the_author() ?> in <?php the_category(',') ?> und hat <?php comments_popup_link('Keine Kommentare', '(1) Kommentar', '(%) Kommentare'); ?> <?php edit_post_link('Edit',''); ?>
                    <?php the_tags( '<div class="tags">Tags: ', ', ', '</div> '); ?>
                    </div>  
                </div>
                <?php endwhile; ?>
        <div class="navigation">
            <h5 class="float-left">
                <?php previous_posts_link('&larr; Newer Entries') ?>
            </h5>
            <h5 class="float-right">
                <?php next_posts_link('Older Entries &rarr;') ?>
            </h5>
            <div class="clear"></div>
        </div>
        <?php else : ?> 
        
        <div class="post">
            <h2 class="post_header">Not Found</h2>
            <div class="post_line"></div>
            <div class="post_content">
            <p class="center"><?php _e("Sorry, but you are looking for something that isn't here."); ?></p>
            <p>
                                                   
        </div>
            
        <?php endif; ?>
                
                
            </div>
        </div>
    </div>
</div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>