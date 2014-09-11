<?php get_header(); ?>

    <div id="content">
    
        <div id="main">
        
            <div class="box1 clearfix">
                <div class="post clearfix">
            
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <h2><?php the_title(); ?></h2> 
                <p class="txt0"><?php edit_post_link('Edit', '', ''); ?> // <?php the_time('F jS, Y') ?> // <?php the_category(', ') ?></p>
                        
                <?php the_content(); ?>
                
                <?php endwhile; endif; ?>

                </div>

            </div>
                            
        <?php comments_template(); ?>
            
        </div><!-- / #main -->
			
<?php get_sidebar(); ?>
			
	</div><!-- / #content -->

<?php get_footer(); ?>