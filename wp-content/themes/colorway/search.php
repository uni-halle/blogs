<?php /**
 * The template for displaying Search Results pages.
 *
 */ ?>
<?php
get_header();

?>
        <!--Start Content Grid-->
        <div class="row content">
            <div class="col-md-8 col-sm-8">
                <div class="content-wrap">
                   
                    <div class="blog" id="blogmain">
                        <h1><?php printf(/* translators: %s - plugin name. */ esc_html__('Search Results for: %s', 'colorway'), '' . get_search_query() . ''); ?></h1>
                        <div class="blog_list">
                            <!-- Start the Loop. -->
                            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                        <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('post_thumbnail', array('class' => 'postimg')); ?>
                                            </a>
                                            <?php
                                        } else {
                                            
                                        }
                                        ?>
                                        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php esc_attr_e('Permanent Link to ', 'colorway') . the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                        <?php
                                        printf(
                                                /* translators: %s - Time, %s - plugin name, %s - Author, %s - Category */ esc_html_x('Posted on %1$s by %2$s in %3$s.', 'Time, Author, Category', 'colorway'), the_time(get_option('date_format')), get_the_author(), esc_html(get_the_category_list(', '))
                                        );
                                        ?>
                                        <?php the_excerpt(); ?>
                                        <?php comments_popup_link(__('No Comments.', 'colorway'), __('1 Comment.', 'colorway'), __('% Comments.', 'colorway')); ?>
                                        <div>
                                            
                                        </div>
                                        <a href="<?php the_permalink() ?>"><?php esc_html_e('Read Now >>', 'colorway'); ?></a>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- End the Loop. -->
                                    <?php
                                endwhile;
                            else:
                                ?>
                                <div>
                                    <h2>
                                        <?php esc_html_e('Nothing Found', 'colorway'); ?>
                                    </h2>
                                    <p>
                                        <?php esc_html_e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'colorway'); ?>
                                    </p>
                                    <?php get_search_form(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="folio-page-info">
                        <!--<label>Page:</label>-->
                        <?php /* Display navigation to next/previous pages when applicable */ ?>
                        <?php if ($wp_query->max_num_pages > 1) : ?>
                            <?php next_posts_link(__('&larr; Older posts', 'colorway')); ?>
                            <?php previous_posts_link(__('Newer posts &rarr;', 'colorway')); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <!--End Content Grid-->
    </div>
    </div>

<!--End Container Div-->
<?php get_footer(); ?>
