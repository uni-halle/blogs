<?php get_header(); ?>
<!-- Mainbar -->
<div class="mainbar">
    <div class="span-16">
        <div class="content">
            <?php if (have_posts()) : ?>		
                <?php while (have_posts()) : the_post(); ?>            
                <div class="post <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
                <h2 class="post_header" id="post-<?php the_ID(); ?>"><span><?php the_time('d M')?></span><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="clear"></div>
                <div class="post_meta">Posted by <?php the_author() ?> in <?php the_category(',') ?></div><div class="post_meta"><?php comments_popup_link('No Comments', 'Comment (1)', 'Comments (%)'); ?></div><div class="post_meta"><?php edit_post_link('Edit',''); ?></div>
                <div class="clear"></div>
                <div class="post_content">
                    <?php the_content('Read more...'); ?> 
                    <?php wp_link_pages('before=<div class="post_pages"><b>Pages:</b> <span id="page-links">&after=</span></div>'); ?>
                </div>
                <?php the_tags( '<div class="tags">Tags: ', ', ', '</div> '); ?><div class="clear"></div>
            </div>
            			
        </div>
        <?php comments_template(); ?>
            	<?php endwhile; ?>
                
        <?php else : ?>
        <div class="post">
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        </div>
        <?php endif; ?>   
    </div>
</div>
<!-- End of Mainbar -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>