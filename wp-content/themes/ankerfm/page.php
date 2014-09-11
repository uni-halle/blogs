<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
		
			<?php if ( get_option( 'woo_breadcrumbs' ) == 'true') { yoast_breadcrumb('<div id="breadcrumb"><p>','</p></div>'); } ?>
            
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div class="post">

                    <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
                    
                    <div class="entry">
                    	<?php the_content(); ?>
                    </div>

                </div><!-- /.post -->
                
                <?php if ('open' == $post->comment_status) : ?>
	                <?php comments_template(); ?>
				<?php endif; ?>
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                </div><!-- /.post -->
            <?php endif; ?>  
        
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>