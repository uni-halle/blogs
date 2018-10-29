<?php
/**
 * The template for displaying woocommerce pages.
 *
 * This is the template that displays woocommerce pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 */
get_header();
$sidebar = '';
$center = '';
$a = get_option('page-layout');
switch ($a) {
    case 'content-sidebar':
        $sidebar = 'right';
        $content = 'left_d_none';
        break;
    case 'sidebar-content':
        $sidebar = 'left';
        $content = 'right_d_none';
        break;
    case 'content':
        $center = 'col-md-12 col-sm-12';
        break;
    default:
        $sidebar = 'right';
}

?>
        <!--Start Content Grid-->
        <div class="row content ">
            <?php if ($sidebar == 'left') { ?> 
                <div id="left-sidebar" class="col-md-4 col-sm-4 <?php
                if ($content != '') {
                    echo esc_attr($content);
                } else {
                    
                }
                ?>">
                    <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            <?php } ?>

            <div id="content-case" class="<?php
            if ($center != '') {
                echo esc_attr($center);
            } else {
                echo 'col-md-8';
            }
            ?>">
                     <?php
                     if (have_posts()) :
                         while (have_posts()) :
                             the_post();
                             woocommerce_content();
                         endwhile;
                         inkthemes_pagination();

                     else:
                         // If no content, include the "No posts found" template.
//                                          get_template_part( 'content', 'none' );
                         ?>    
                <div>
                                <p> <?php get_template_part( 'templates/content/content', 'none' ); ?> </p>
                    </div>
                <?php
                endif;
                ?>
                <div class="comment_section">
                    <!--Start Comment list-->
                    <?php comments_template('', true); ?>
                    <!--End Comment Form-->
                </div>
            </div>

            <?php if ($sidebar == 'right') { ?>
                <div id="right-sidebar" class="col-md-4 col-sm-4 switch <?php
                if ($content != '') {
                    echo esc_attr($content);
                } else {
                    
                }
                ?>">
                    <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
        <!--End Content Grid-->
    </div>
    </div>
<!--End Container Div-->
<?php get_footer(); ?>
