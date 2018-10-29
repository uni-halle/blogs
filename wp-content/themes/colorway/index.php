<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header();
$sidebar = '';
$center = '';
$a = get_option('blog-layout');
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
               
                <div class="blog" id="blogmain">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                            <?php get_template_part('templates/content/content', 'loop'); ?>
                            <?php
                        endwhile;
                    else:
                        ?>
                        <div>
                            <p> <?php get_template_part('templates/content/content', 'none'); ?> </p>
                        </div>
                    <?php endif; ?>
                </div> 
            </div>
        </div>
        <?php if ($sidebar == 'right') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>
        <div class="clear"></div>
        <!--End Content Grid-->
    </div>
</div>
</div>

<!--End Container Div-->
<?php get_footer(); ?>
