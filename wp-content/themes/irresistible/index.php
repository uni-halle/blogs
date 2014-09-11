<?php get_header(); ?>

	<?php if (get_option('woo_home')) : include (TEMPLATEPATH . '/custom-home.php'); else : ?>

	<div id="content">
		
			<div id="main">
						
                <h3 id="myWritings" class="replace">My Writings. My Thoughts.</h3>

					<?php if (have_posts()) : ?>
															
					<?php while (have_posts()) : the_post(); ?>

                    <div class="box1 clearfix">
                        <div class="post clearfix">
                            <h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                            <p class="txt0"><?php edit_post_link('Edit', '', ''); ?> // <?php the_time('F jS, Y') ?> // <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> // <?php the_category(', ') ?></p>
                        
                        <?php the_content('<span class="continue">Continue Reading</span>') ?>
    
                        </div>
                    </div>
					
					<?php endwhile; ?>
					
					<div class="navigation nav clearfix">
						<div class="fl"><?php next_posts_link('&laquo; Older Entries') ?></div>
						<div class="fr"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
					</div>
					<?php else : ?>
					
					<h2 class='center'>No posts found.</h2>
					
					<?php endif; ?>

	
            </div><!-- / #main -->
		
        <?php get_sidebar(); ?>
        
	</div><!-- / #content -->

	<?php endif; ?>
    
<?php get_footer(); ?>
