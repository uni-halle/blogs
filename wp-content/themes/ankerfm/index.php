<?php get_header(); ?>

    <div id="content" class="col-full">
		<div id="main" class="col-left">
            
			<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts("paged=$paged"); ?>
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <div class="post">

                    <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    
                    <p class="date">
                    	<span class="day"><?php the_time('j'); ?></span>
                    	<span class="month"><?php the_time('M'); ?></span>
                    </p>
                    
                    <?php woo_get_image('image',490,200); ?>
                    
                    <div class="entry">
                    	<?php the_content(); ?>
                    </div>
                    
                    <div class="post-meta">
                    
                    	<ul>
                    		<li class="comments">
                    			<span class="head"><?php _e('Comments', 'woothemes') ?></span>
                    			<span class="body"><?php comments_popup_link(__('0 Comments', 'woothemes'), __('1 Comment', 'woothemes'), __('% Comments', 'woothemes')); ?></span>
                    		</li>
                    		<li class="categories">
                    			<span class="head"><?php _e('Categories', 'woothemes') ?></span>
                    			<span class="body"><?php the_category(', ') ?></span>
                    		</li>
                    		<li class="author">
                    			<span class="head"><?php _e('Author', 'woothemes') ?></span>
                    			<span class="body"><?php the_author_posts_link(); ?></span>
                    		</li>
                    	</ul>
                    	
                    	<div class="fix"></div>
                    
                    </div><!-- /.post-meta -->

                </div><!-- /.post -->
                                                    
			<?php endwhile; else: ?>
				<div class="post">
                	<p><?php _e('Sorry, no posts matched your criteria.', 'woothemes') ?></p>
                </div><!-- /.post -->
            <?php endif; ?>  
        
                <div class="more_entries">
                    <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>                    
                    <div class="fl"><?php next_posts_link(__('&laquo; Older Entries', 'woothemes')) ?></div>
					<div class="fr"><?php previous_posts_link(__('Newer Entries &raquo;', 'woothemes')) ?></div>
                    <br class="fix" />
                    <?php } ?> 
                </div>		
                
		</div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>