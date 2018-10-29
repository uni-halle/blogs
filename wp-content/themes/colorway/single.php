<?php
/**
 * The Template for displaying all single posts.
 *
 */
get_header();
$sidebar = '';
$center = '';
$a = get_option('singlepage-layout');
switch ($a) {
    case 'content-sidebar':
        $sidebar = 'right';
        break;
    case 'sidebar-content':
        $sidebar = 'left';
        break;
    case 'content':
        $center = 'col-md-12 col-sm-12';
        break;
    default:
        $sidebar = 'right';
}
?>
    <!--Start Content Grid-->
    <div class="row content">
        <?php if ($sidebar == 'left') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>

        <div class="<?php
        if ($center != '') {
            echo esc_attr($center);
        } else {
            echo 'col-md-8';
        }
        ?>">
            <div class="content-wrap">
                
                <!--Start Blog Post-->
                <div class="blog">
                    <article class="single">
                        <?php
                        if (have_posts()) :
                            while (have_posts()) :
                                the_post();
                                get_template_part('templates/content/content', 'single');
                            endwhile;
                            ?>
                            <nav id="nav-single"> <span class="nav-previous">
                                    <?php previous_post_link('%link', '<span class="meta-nav">&larr;</span> ' . __('Previous Post ', 'colorway')); ?>
                                </span> <span class="nav-next">
                                    <?php next_post_link('%link', __('Next Post ', 'colorway') . '<span class="meta-nav">&rarr;</span>'); ?>
                                </span> </nav>
                            <?php
                        else:
                            // If no content, include the "No posts found" template.
//                                          get_template_part( 'content', 'none' );
                            ?>    <div>
                                <p> <?php get_template_part('templates/content/content', 'none'); ?> </p>
                            </div>
                        <?php
                        endif;
                        ?>
                        <!-- End the Loop. -->          
                    </article>
                </div>
                <div class="hrline"></div>
                <!--End Blog Post-->           

                <div class="clear"></div>
                <!--Start Comment Section-->
                <div class="comment_section">
                    <!--Start Comment list-->
                    <?php comments_template('', true); ?>
                    <!--End Comment Form-->
                </div>
                <!--End comment Section-->
            </div>
        </div>
        <?php if ($sidebar == 'right') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>

        <div class="clearfix"></div>
    </div>
</div>
</div>

<div class="clearfix"></div>
<!--End Container Div-->
<?php get_footer(); ?>
