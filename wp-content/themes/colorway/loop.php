<ul class="blog_post">
    <!-- Start the Loop. -->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class('animated'); ?>>
                <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
				 <a href="<?php the_permalink(); ?>"><div class='img_thumb_blog'><span></span><?php the_post_thumbnail('post_thumbnail', array('class' => 'postimg')); ?></div></a>
                    <?php
                } else {
                    echo inkthemes_main_image();
                }
                ?>
                <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to ', 'colorway') . the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                <?php
                printf(
                        _x('Posted on %1$s by %2$s in %3$s.', 'Time, Author, Category', 'colorway'), get_the_time(get_option('date_format')), get_the_author(), get_the_category_list(', ')
                );
                ?>
                <?php the_excerpt(); ?>
                <div class="clear"></div>
                <div class="tags">
                    <?php the_tags(__('Post Tagged with ', 'colorway'), ', ', ''); ?>
                </div>
                <div class="clear"></div>                
                <a href="<?php the_permalink() ?>"><?php _e('Continue Reading...', 'colorway'); ?></a>
                <div class="clear"></div>
                <?php comments_popup_link(__('No Comments.', 'colorway'), __('1 Comment.', 'colorway'), __('% Comments.', 'colorway')); ?>                
            </li>
            <!-- End the Loop. -->
            <?php
        endwhile;
    else:
        ?>
        <li>
            <p> <?php _e('Sorry, no posts matched your criteria.', 'colorway'); ?> </p>
        </li>
    <?php endif; ?>
</ul>
