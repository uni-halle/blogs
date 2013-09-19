<?php get_header();?>


<!-- ************************************************************* -->
			<?php if (have_posts()) : while (have_posts()) : the_post();
                        $feedback ='feedback1';
                        //include (TEMPLATEPATH . "/inner_content.php"); ?>
                        
                        
                        <div class="postid" id="post-<?php the_ID(); ?>">
                            <div class="story">
                                <div class="story_date"><span class="dt"><?php the_time('j'); ?></span><?php the_time('S'); ?><br /><span><?php the_time('F'); ?></span></div>
                                <div class="storyTitle">
                                    <h2><?php the_title(); ?></h2>
                                    <p class="storyAuthor">Posted by <span><?php the_author(); ?></span> at <?php the_time('g:i A'); ?>. Placed in <?php the_category(', '); ?> category <?php edit_post_link('Edit','&nbsp;| &nbsp;'); ?></p>
                                </div>
                                <div style="clear:both;"></div>
                                   
                               	<div class="storyContent">
                                    <?php the_content(); ?>
                                    <div style="clear:both;"></div>
                                    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
									<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
                               	</div>
                               
                                <div class="comment_template"> <?php comments_template();?> </div>
                                        
                            </div>
                  </div><!-- close postid--> 
                        
                        
                        
            <?php endwhile; else: ?>
                        <p class="error"><?php _e('Sorry, no posts matched your criteria.'); ?></p>
            <?php endif; ?>
            
<!-- ************************************************************* -->
        </div>
    </div><!-- close innerContent -->
<?php get_sidebar();?>
<?php get_footer(); ?>