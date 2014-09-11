<?php get_header(); ?> 


              
              <div class="pagetitle">Search Result for <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e(''); _e('&#8220;'); echo $key; _e('&#8221;'); _e(' &mdash; '); echo $count . ' '; _e('articles'); wp_reset_query(); ?></div>
              
              <ul class="mcol">
              <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
              	<li class="article" id="post-<?php the_ID(); ?>">
                
						<?php
						if ( has_post_thumbnail() ) { ?>
                    	<?php 
                    	$imgsrcparam = array(
						'alt'	=> trim(strip_tags( $post->post_excerpt )),
						'title'	=> trim(strip_tags( $post->post_title )),
						);
                    	$thumbID = get_the_post_thumbnail( $post->ID, 'background', $imgsrcparam ); ?>
                        <div><a href="<?php the_permalink() ?>" class="preview"><?php echo "$thumbID"; ?></a></div>
                    	<?php } ?>

                
                    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                    <div class="postmetadata">
                        Posted: <?php the_time(__('F jS, Y')) ?>&nbsp;&#721;&nbsp;
                        <?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments'), '', __('Comments Closed') ); ?><br />
                        <?php printf(__('Filled under: %s'), get_the_category_list(', ')); ?>
                    </div>
                </li>

            <?php endwhile; ?>
            <?php else : ?>
            <?php endif; ?>
            
                </ul>
        
        
            <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
            <?php endwhile; ?>
            <?php else : ?>
                <h1 id="error"><?php _e("Sorry, but you are looking for something that isn&#8217;t here."); ?></h1>
            <?php endif; ?>
        

            <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
            <?php endwhile; ?>
                <div id="nav">
                    <div id="navleft"><?php next_posts_link(__('Previous page&nbsp;')) ?></div>
                    <div id="navright"><?php previous_posts_link(__('Next page&nbsp;')) ?></div>
                </div>
            <?php else : ?>
            <?php endif; ?>
        
        
<?php get_footer(); ?>
