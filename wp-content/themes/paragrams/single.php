<?php get_header(); ?> 

<?php get_sidebar(); ?> 

            <div id="main-inner">
  
              <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
                <div class="article" id="post-<?php the_ID(); ?>">
                      <h1><?php the_title(); ?></h1>
                      <div class="postmetadata">
                          Posted: <?php the_time(__('F jS, Y')) ?>&nbsp;&#721;&nbsp;<?php printf(__('Filled under: %s'), get_the_category_list(', ')); ?>&nbsp;&#721;&nbsp;
                          <?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments'), '', __('Comments Closed') ); ?><?php edit_post_link(__('Edit this entry'), '&nbsp;&#721;&nbsp;', ''); ?>
                      </div>
                      <?php the_content(); ?>
                      <div class="postmetadata tags">
					  		<?php the_tags(); ?>
                      </div>
                </div>
            <?php endwhile; ?>
            
                <div id="nav">
                    <div id="navleft"><?php previous_post_link('%link', 'Previous article'); ?></div>
                    <div id="navright"><?php next_post_link('%link', 'Next article'); ?></div>
                </div>
                
<?php comments_template(); ?>
            
            <?php else : ?>
                <h1><?php _e("Sorry, but you are looking for something that isn&#8217;t here."); ?></h1>
            <?php endif; ?>
            
            </div>
        

<?php get_footer(); ?>
