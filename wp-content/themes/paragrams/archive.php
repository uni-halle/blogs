<?php get_header(); ?> 
                  <?php /* If this is a category archive */ if (is_category()) { ?>
                    <div class="pagetitle"><?php printf(__('Archive for the &#8216;%s&#8217; Category'), single_cat_title('', false)); ?></div>
                  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
                    <div class="pagetitle"><?php printf(__('Posts Tagged &#8216;%s&#8217;'), single_tag_title('', false) ); ?></div>
                  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
                    <div class="pagetitle"><?php printf(_c('Archive for %s|Daily archive page'), get_the_time(__('F jS, Y'))); ?></div>
                  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
                    <div class="pagetitle"><?php printf(_c('Archive for %s|Monthly archive page'), get_the_time(__('F, Y'))); ?></div>
                  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
                    <div class="pagetitle"><?php printf(_c('Archive for %s|Yearly archive page'), get_the_time(__('Y'))); ?></div>
                  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
                    <div class="pagetitle"><?php _e('Author Archive'); ?></div>
                  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
                    <div class="pagetitle"><?php _e('Blog Archives'); ?></div>
                  <?php } ?>

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
