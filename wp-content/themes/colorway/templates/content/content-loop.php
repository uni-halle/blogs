<article class="blog_post">
    <!-- Start the Loop. -->
    <div id="post-<?php the_ID(); ?>" <?php post_class('animated'); ?>>
        <div class="row"> 

            <header class="entry-header">
                <?php the_title(sprintf('<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
            </header><!-- .entry-header --> 
            <?php 
            $class = '';
            $thumb = has_post_thumbnail();          
                    switch ($thumb) {
                        case true:
                            $class = 'col-md-8';
                            break;
                        case false:
                             $class='col-md-12 col-sm-12';
                            break;
                        default:
                            $class='col-md-12 col-sm-12';
                    }
                        
            ?>

            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { 
                ?>
               
                <div class="col-md-4 col-sm-6">
                    <a href="<?php the_permalink(); ?>"><div class='img_thumb_blog'><span></span><?php the_post_thumbnail('post_thumbnail', array('class' => 'postimg')); ?></div></a>
                </div>
                <?php
            }
            ?>
            <div class="<?php echo esc_attr($class); ?>">
                <div class="blog-content">
                    <ul class="post_meta">
                        <?php if (inkthemes_get_option('inkthemes_author','on')=='on') { ?>
                            <li class="posted_by"><span><?php esc_html_e('By', 'colorway'); ?> </span><?php the_author_posts_link(); ?></li>
                        <?php } ?>
                        <?php if (inkthemes_get_option('inkthemes_categories','on')=='on') { ?>
                            <li class="posted_in"><?php the_category(', '); ?></li>
                        <?php } ?>
                        <?php if (inkthemes_get_option('inkthemes_date','on')=='on') { ?>
                            <li class="post_date"><?php esc_html_e('On ', 'colorway') . the_time('F j, Y'); ?></li>
                        <?php } ?>
                        <?php if (inkthemes_get_option('inkthemes_comments','on')=='on') { ?>
                            <li class="postc_comment"><?php comments_popup_link(__('No Comments.', 'colorway'), __('1 Comment.', 'colorway'), __('% Comments.', 'colorway')); ?></li>
                        <?php } ?>
                    </ul>
                    <?php the_excerpt(); ?>
                    <div class="clear"></div>
                    <div class="tags">
                        <?php the_tags(__('Post Tagged with ', 'colorway'), ', ', ''); ?>
                    </div>
                    <div class="clear"></div>                
            
                </div>               
            </div>
        </div>
        <!-- End the Loop. -->
        <div class="divider"></div>
</article>
