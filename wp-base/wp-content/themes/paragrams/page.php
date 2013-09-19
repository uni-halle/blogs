<?php get_header(); ?> 

<?php get_sidebar(); ?> 

            <div id="main-inner">
  
              <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
                <div class="article">
                      <h1><?php the_title(); ?></h1>
                      <?php the_content(); ?>
                      <?php edit_post_link(__('Edit this page'), '', ''); ?><br />
                </div>
            <?php endwhile; ?>
            <?php else : ?>
                <h1><?php _e("Sorry, but you are looking for something that isn&#8217;t here."); ?></h1>
            <?php endif; ?>
            
            </div>
        

<?php get_footer(); ?>
